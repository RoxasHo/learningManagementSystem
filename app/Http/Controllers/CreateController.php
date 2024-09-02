<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use response;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

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

        $post->save();
        
        return redirect()->back();
    }

    public function show()
    {
        $tagsArray = Post::select('tag')
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
    
        $posts = Post::orderBy('created_at', 'desc')->paginate(5);
    
        return view("show", compact('posts', 'tagsArray'));
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
        $posts = Post::orderBy('created_at', 'desc')->paginate(5);
    } else {
        // Search for posts where the title, content, or tag matches the search term
        $posts = Post::where(function($query) use ($search) {
            $query->where('title', 'like', "%{$search}%")
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
    $post = Post::findOrFail($post_id); 
    return view('showPost', compact('post'));
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
    $reply->save();

    return back()->with('success', 'Reply posted successfully!');
}




}