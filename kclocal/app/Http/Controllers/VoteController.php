<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Vote;

class VoteController extends Controller
{
    public function like(Request $request)
    {
        $user = auth()->user();
        $postId = $request->input('post_id');
    
        if ($user && $postId) {
            $vote = Vote::updateOrCreate(
                ['userID' => $user->id, 'post_id' => $postId, 'comment_id' => null], // Ensure this is for a post only
                ['type' => 'like']
            );
        }
    
        $likesCount = Vote::where('post_id', $postId)
            ->whereNull('comment_id') // Only count votes for the post, not comments
            ->where('type', 'like')
            ->count();

        $dislikesCount = Vote::where('post_id', $postId)
            ->whereNull('comment_id') // Only count votes for the post
            ->where('type', 'dislike')
            ->count();

        $userVote = $vote->type ?? null;
    
        return response()->json(['likesCount' => $likesCount, 'dislikesCount' => $dislikesCount, 'userVote' => $userVote]);
    }

    public function dislike(Request $request)
    {
        $user = auth()->user();
        $postId = $request->input('post_id');
    
        if ($user && $postId) {
            $vote = Vote::updateOrCreate(
                ['userID' => $user->id, 'post_id' => $postId, 'comment_id' => null], // Ensure this is for a post only
                ['type' => 'dislike']
            );
        }
    
        $likesCount = Vote::where('post_id', $postId)
            ->whereNull('comment_id') // Only count votes for the post
            ->where('type', 'like')
            ->count();

        $dislikesCount = Vote::where('post_id', $postId)
            ->whereNull('comment_id') // Only count votes for the post
            ->where('type', 'dislike')
            ->count();

        $userVote = $vote->type ?? null;
    
        return response()->json(['likesCount' => $likesCount, 'dislikesCount' => $dislikesCount, 'userVote' => $userVote]);
    }

 
  // In your VoteController
  public function likeComment(Request $request)
  {
      $user = auth()->user();
      $commentId = $request->input('comment_id');
      $postId = $request->input('post_id'); // Assuming you pass post_id from the frontend
  
      if ($user && $commentId && $postId) {
          $vote = Vote::updateOrCreate(
              ['userID' => $user->id, 'comment_id' => $commentId],
              ['type' => 'like', 'post_id' => $postId]
          );
      }
  
      $likesCount = Vote::where('comment_id', $commentId)->where('type', 'like')->count();
      $dislikesCount = Vote::where('comment_id', $commentId)->where('type', 'dislike')->count();
      $userVote = $vote->type ?? null;
  
      return response()->json(['likesCount' => $likesCount, 'dislikesCount' => $dislikesCount, 'userVote' => $userVote]);
  }
  
  public function dislikeComment(Request $request)
  {
      $user = auth()->user();
      $commentId = $request->input('comment_id');
      $postId = $request->input('post_id'); // Assuming you pass post_id from the frontend
  
      if ($user && $commentId && $postId) {
          $vote = Vote::updateOrCreate(
              ['userID' => $user->id, 'comment_id' => $commentId],
              ['type' => 'dislike', 'post_id' => $postId]
          );
      }
  
      $likesCount = Vote::where('comment_id', $commentId)->where('type', 'like')->count();
      $dislikesCount = Vote::where('comment_id', $commentId)->where('type', 'dislike')->count();
      $userVote = $vote->type ?? null;
  
      return response()->json(['likesCount' => $likesCount, 'dislikesCount' => $dislikesCount, 'userVote' => $userVote]);
  }

}