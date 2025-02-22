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
        return view("forum.create");
    }
    
    
    public function create(Request $request)
    {
        $userId = Auth::id();

        $post= new Post;

        $post->title=$request->title;

        $post->content=$request->input('content');

        $post->tag=$request->tag;

        $post->userID = $userId;

        $post->save();
        
        return redirect()->route('show.main');
    }

    public function show()
    {
        $user = auth()->user();
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
    
        return view("forum.show", compact('posts', 'tagsArray', 'user'));
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

    return view('forum.showtags', compact('tagsArray'));
    }

    public function showByTag(Request $request)
{
    $user = auth()->user();
    $tag = $request->query('tag', '');
    $normalizedTag = trim($tag);

    $posts = Post::where(function ($query) use ($normalizedTag) {
            $query->orWhere('tag', 'LIKE', '%,' . $normalizedTag . ',%')    
                  ->orWhere('tag', 'LIKE', $normalizedTag . ',%')            
                  ->orWhere('tag', 'LIKE', '%,' . $normalizedTag)            
                  ->orWhere('tag', 'LIKE', $normalizedTag)                  
                  ->orWhere('tag', 'LIKE', '%,' . $normalizedTag . ' %')    // In the middle with a space after
                  ->orWhere('tag', 'LIKE', '%, ' . $normalizedTag . ',%')    // After a comma
                  ->orWhere('tag', 'LIKE', '%, ' . $normalizedTag);          // After a comma with no trailing comma
        })
        ->where('is_deleted', false)
        ->orderBy('created_at', 'desc')
        ->paginate(5)
        ->appends(['tag' => $tag]); // Append the tag parameter for pagination links

    return view('forum.showByTag', compact('posts', 'tag', 'user'));
}

    

    public function searchTags(Request $request)
    {
        $search = $request->input('search', '');
       
        $tagsArray = Post::pluck('tag')
            ->map(function ($tagString) {
                return explode(',', $tagString);
            })
            ->flatten()
            ->unique()
            ->sort()
            ->values()
            ->toArray();
           
        $searchedTags = collect($tagsArray);
        if ($search) {
            $searchedTags = $searchedTags->filter(function ($tag) use ($search) {
                return strtolower($tag) === strtolower($search);
            });
        }
    
        return view('forum.showtags', compact('tagsArray', 'searchedTags'));
    }
    
    
    public function searchPosts(Request $request)
{
    $user = auth()->user();
    $search = $request->input('search', '');
    
    if (empty($search)) {
        $posts = Post::orderBy('created_at', 'desc')->paginate(5)
        ->where('is_deleted', false);
    } else {
        $posts = Post::where(function($query) use ($search) {
            $query->where('title', 'like', "%{$search}%")
                ->where('is_deleted', false)
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('tag', 'like', "%{$search}%");
        })
        ->where('is_deleted', false)
        ->orderBy('created_at', 'desc')
        ->paginate(5)
        ->appends(['search' => $search]);
    }
    
    $tagsArray = Post::pluck('tag')
        ->map(function ($tagString) {
            return explode(',', $tagString);
        })
        ->flatten()
        ->unique()
        ->sort()
        ->values()
        ->toArray();
    
    return view('forum.showSearchPost', compact('posts', 'tagsArray', 'search', 'user'));
}

        

public function showPost($post_id)
{
    $user = auth()->user(); 
    $posts = Post::with('likes', 'dislikes')
    ->where('is_deleted', false)
    ->get();
    $post = Post::findOrFail($post_id); 

    $comment = Comment::where('post_id', $post_id)
    ->where('is_visible', true) 
    ->get();

    return view('forum.showPost', compact('post', 'user', 'comment'));
}

public function storeComment(Request $request, $post_id)
{
    $request->validate([
        'content' => 'required|string',
    ]);

    $post = Post::findOrFail($post_id);

    $comment = new Comment;
    $comment->content = $request->input('content');
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

    $post = Post::findOrFail($post_id);
    $parentComment = Comment::where('comment_id', $parent_comment_id)->firstOrFail();

    $reply = new Comment;
    $reply->content = $request->input('content');
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

        if ($user && $tag) {
            FollowedTags::where('userID', $user->id)
                ->where('tag', $tag)
                ->delete();
        }

        return redirect()->back();
    }

    public function index(Request $request)
    {
        $user = $request->user(); 
    
        if ($request->ajax() && $request->query('tab') === 'following') {
            $followedTags = FollowedTags::where('userID', $user->id)->pluck('tag');
            if ($followedTags->isEmpty()) {
                return view('partials.no-followed-topics'); 
            }
    
            $followedPosts = Post::where(function ($query) use ($followedTags) {
                foreach ($followedTags as $tag) {
                    $normalizedTag = trim($tag);
                    $query->orWhere('tag', 'LIKE', '%,' . $normalizedTag . ',%')    
                          ->orWhere('tag', 'LIKE', $normalizedTag . ',%')           
                          ->orWhere('tag', 'LIKE', '%,' . $normalizedTag)            
                          ->orWhere('tag', 'LIKE', $normalizedTag)                  
                          ->orWhere('tag', 'LIKE', '%,' . $normalizedTag . ' %')    
                          ->orWhere('tag', 'LIKE', '%, ' . $normalizedTag . ',%')    
                          ->orWhere('tag', 'LIKE', '%, ' . $normalizedTag);          
                }
            })
            ->where('is_deleted', false)
            ->get();
    
            return view('partials.following-posts', compact('followedPosts'));
        }
    
        $posts = Post::where('is_deleted', false)->get(); 
        $tagsArray = []; 
    
        return view('show', compact('posts', 'tagsArray', 'user'));
    }
    
    public function viewMyPosts()
{
    $user = Auth::user(); 
    $posts = Post::where('userID', $user->id) 
                 ->where('is_deleted', false)
                 ->orderBy('created_at', 'desc')
                 ->paginate(5); 

    return view('forum.myPosts', compact('posts', 'user'));
}


public function destroy($post_id)
{
    $post = Post::findOrFail($post_id);
    $post->is_deleted = true;
    $post->save();

    return redirect()->route('view.mypost')->with('success', 'Post deleted successfully.');
}


public function destroyComment($comment_id)
{
    $comment = Comment::findOrFail($comment_id);
    $comment->is_visible = false; 
    $comment->save();

    return redirect()->back()->with('success', 'Comment deleted successfully.');
}

}