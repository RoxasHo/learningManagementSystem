<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use response;
use App\Models\Post;
use App\Models\Comment;
use App\Models\FollowedTags;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;

class CreateController extends Controller
{
    public function upload(Request $request)
    {
       if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;

            $request->file('upload')->move(public_path('media'), $fileName);

            $url = asset('media/' . $fileName);
            return response()->json(['fileName' => $fileName, 'uploaded'=> 1, 'url' => $url]);
        }
    }

    public function displayCreate()
    {   
        return view("create");
    }
    
    
    public function create(Request $request)
    {
        $post= new Post;

        $post->title=$request->title;

        $post->content=$request->content;

        $post->tag=$request->tag;

        $post->userID = Auth::id();

        $post->save();
        
        return redirect()->route('show.main');
    }

    public function show()
    {
        $user = auth()->user(); // Get the authenticated user
        $posts = Post::with('likes', 'dislikes')
        ->where('is_deleted', false)
        ->get();

        $tagsArray = Post::select('tag')
                        ->where('is_deleted', false)
                         ->get()
                         ->pluck('tag')
                         ->flatMap(function ($tagString) {
                             return array_map('trim', explode(',', $tagString));
                         })
                         ->filter()
                         ->countBy()
                         ->sortDesc()
                         ->take(10)
                         ->keys()
                         ->toArray();
    
        $posts = Post::orderBy('created_at', 'desc')
        ->where('is_deleted', false)
        ->paginate(5);
    
        return view("show", compact('posts', 'tagsArray', 'user'));
    }

    public function showtags()
    {
        $tags = Post::select('tag', DB::raw('count(*) as count'))
        ->groupBy('tag')
        ->orderByDesc('count')
        ->pluck('tag');

    $tagsArray = [];
    foreach ($tags as $tagString) {
        $tagsArray = array_merge($tagsArray, array_map('trim', explode(',', $tagString)));
    }

    $tagsArray = array_unique($tagsArray);

    return view('showtags', compact('tagsArray'));
    }

    public function showByTag(Request $request)
    {
        $tag = $request->query('tag', '');
        
        $posts = Post::whereRaw("FIND_IN_SET(?, tag)", [$tag])
        ->where('is_deleted', false)
            ->orderBy('created_at', 'desc')
            ->paginate(5)
            ->appends(['tag' => $tag]); // Append the tag parameter
        
        return view('showByTag', compact('posts', 'tag'));
    }
    
    

    public function searchTags(Request $request)
    {
        $search = $request->input('search', '');
    
        // Fetch all tags from the posts table
        $tagsArray = Post::pluck('tag')
            ->map(function ($tagString) {
                return explode(',', $tagString);
            })
            ->flatten()
            ->unique()
            ->sort()
            ->values()
            ->toArray();
    
        // Filter tags based on exact search input
        $searchedTags = collect($tagsArray);
        if ($search) {
            $searchedTags = $searchedTags->filter(function ($tag) use ($search) {
                return strtolower($tag) === strtolower($search);
            });
        }
    
        // Pass the $searchedTags and $tagsArray to the view
        return view('showtags', compact('tagsArray', 'searchedTags'));
    }
    
    
    public function searchPosts(Request $request)
{
    $search = $request->input('search', '');
    
    // If no search term is provided, fetch all posts
    if (empty($search)) {
        $posts = Post::orderBy('created_at', 'desc')->paginate(5)
        ->where('is_deleted', false);
    } else {
        // Search for posts where the title, content, or tag matches the search term
        $posts = Post::where(function($query) use ($search) {
            $query->where('title', 'like', "%{$search}%")
                ->where('is_deleted', false)
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('tag', 'like', "%{$search}%");
        })
        ->orderBy('created_at', 'desc')
        ->paginate(5)
        ->appends(['search' => $search]); // Append the search parameter
    }
    
    // Fetch all tags for displaying in the view
    $tagsArray = Post::pluck('tag')
        ->map(function ($tagString) {
            return explode(',', $tagString);
        })
        ->flatten()
        ->unique()
        ->sort()
        ->values()
        ->toArray();
    
    return view('showSearchPost', compact('posts', 'tagsArray', 'search'));
}

        

public function showPost($post_id)
{
    $student = Auth::user()->student;
    $user = auth()->user(); // Get the authenticated user
    $posts = Post::with('likes', 'dislikes')
    ->where('is_deleted', false)
    ->get();
    $post = Post::findOrFail($post_id); 

    $comment = Comment::where('post_id', $post_id)
    ->where('is_visible', true) 
    ->get();

    return view('showPost', compact('post', 'user', 'student', 'comment'));
}

public function storeComment(Request $request, $post_id)
{
    $request->validate([
        'content' => 'required|string',
    ]);

    $post = Post::findOrFail($post_id);

    $comment = new Comment;
    $comment->content = $request->content;
    $comment->post_id = $post_id;
    $comment->userID = Auth::id();
    $comment->save();

    return back()->with('success', 'Comment posted successfully!');
}

public function replyToComment(Request $request, $post_id, $parent_comment_id)
{
    $request->validate([
        'content' => 'required|string',
    ]);

    // Find the post and parent comment
    $post = Post::findOrFail($post_id);
    $parentComment = Comment::where('comment_id', $parent_comment_id)->firstOrFail();

    // Create the reply
    $reply = new Comment;
    $reply->content = $request->content;
    $reply->post_id = $post_id;
    $reply->parent_comment_id = $parent_comment_id;  
    $reply->userID = Auth::id();
    $reply->save();

    return back()->with('success', 'Reply posted successfully!');
}

public function follow(Request $request)
    {
        $user = auth()->user();
        $tag = $request->input('tag');

        // Ensure the user is authenticated and tag is valid
        if ($user && $tag) {
            FollowedTags::updateOrCreate(
                ['userID' => $user->id, 'tag' => $tag]
            );
        }

        return redirect()->back();
    }

    public function unfollow(Request $request)
    {
        $user = auth()->user();
        $tag = $request->input('tag');

        // Ensure the user is authenticated and tag is valid
        if ($user && $tag) {
            FollowedTags::where('userID', $user->id)
                ->where('tag', $tag)
                ->delete();
        }

        return redirect()->back();
    }

    public function index(Request $request)
    {
        $user = $request->user(); // Get the currently authenticated user

        // Check if it's an AJAX request and for the "Following" tab
        if ($request->ajax() && $request->query('tab') === 'following') {
            // Fetch the tags followed by the user
            $followedTags = FollowedTags::where('userID', $user->id)->pluck('tag');
    
            // If there are no followed tags, return a 'no followed posts' partial view
            if ($followedTags->isEmpty()) {
                return view('partials.no-followed-topics'); // A view for when there are no followed tags
            }
    
            // Fetch posts that match the followed tags
            $followedPosts = Post::whereIn('tag', $followedTags)
            ->where('is_deleted', false)
            ->paginate(10);
    
            // Return only the partial view with the followed posts
            return view('partials.following-posts', compact('followedPosts'));
        }
    
        // Full page request: handle recent posts
        $posts = Post::paginate(10);
        $tagsArray = []; // Populate this array as needed
    
        // Return the full view with header, layout, etc.
        return view('show', compact('posts', 'tagsArray', 'user'));
    }
    
    public function viewMyPosts()
{
    $user = Auth::user(); // Get the logged-in user
    $posts = Post::where('userID', $user->id) // Get only posts by the current user
                 ->where('is_deleted', false)
                 ->orderBy('created_at', 'desc') // Order posts by creation date (newest first)
                 ->paginate(5); 

    return view('myPosts', compact('posts', 'user'));
}

// PostController.php
public function destroy($post_id)
{
    // Find the post by its ID
    $post = Post::findOrFail($post_id);

    // Mark the post as deleted by setting the is_deleted flag to true
    $post->is_deleted = true;
    $post->save();

    // Redirect back with success message
    return redirect()->route('view.mypost')->with('success', 'Post deleted successfully.');
}


public function destroyComment($comment_id)
{
    $comment = Comment::findOrFail($comment_id);

    // Mark the comment as invisible
    $comment->is_visible = false; 
    $comment->save();

    return redirect()->back()->with('success', 'Comment deleted successfully.');
}

public function destroyReply($comment_id)
{
    $reply = Comment::findOrFail($comment_id);

    if ($reply->parent_comment_id !== null) {
        // Mark the reply as invisible
        $reply->is_visible = false; 
        $reply->save();
    }

    return redirect()->back()->with('success', 'Reply deleted successfully.');
}

    


 

}