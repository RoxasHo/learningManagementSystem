<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Vote;

class VoteController extends Controller
{
    public function votePost(Request $request, $post_id, $type)
    {
        if (!in_array($type, ['like', 'dislike'])) {
            return response()->json(['error' => 'Invalid type'], 400);
        }

        // Create or update vote
        $vote = Vote::updateOrCreate(
            ['post_id' => $post_id, 'comment_id' => null, 'type' => $type]
        );

        $post = Post::find($post_id);

        return response()->json([
            'likes' => $post->like_count,
            'dislikes' => $post->dislike_count
        ]);
    }

    // Method to handle removing votes
    public function removeVotePost(Request $request, $post_id, $type)
    {
        if (!in_array($type, ['like', 'dislike'])) {
            return response()->json(['error' => 'Invalid type'], 400);
        }

        // Remove the vote
        Vote::where('post_id', $post_id)
            ->where('comment_id', null)
            ->where('type', $type)
            ->delete();

        $post = Post::find($post_id);

        return response()->json([
            'likes' => $post->like_count,
            'dislikes' => $post->dislike_count
        ]);
    }


    public function voteComment(Request $request, $comment_id, $type)
    {
        if (!in_array($type, ['like', 'dislike'])) {
            return response()->json(['error' => 'Invalid type'], 400);
        }

        // Create or update vote
        $vote = Vote::updateOrCreate(
            ['post_id' => null, 'comment_id' => $comment_id, 'type' => $type]
        );

        $comment = Comment::find($comment_id);

        return response()->json([
            'likes' => $comment->like_count,
            'dislikes' => $comment->dislike_count
        ]);
    }

    // Method to handle removing votes from comments
    public function removeVoteComment(Request $request, $comment_id, $type)
    {
        if (!in_array($type, ['like', 'dislike'])) {
            return response()->json(['error' => 'Invalid type'], 400);
        }

        // Remove the vote
        Vote::where('comment_id', $comment_id)
            ->where('post_id', null)
            ->where('type', $type)
            ->delete();

        $comment = Comment::find($comment_id);

        return response()->json([
            'likes' => $comment->like_count,
            'dislikes' => $comment->dislike_count
        ]);
    }

}