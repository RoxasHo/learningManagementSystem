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
                ['userID' => $user->id, 'post_id' => $postId, 'comment_id' => null], 
                ['type' => 'like']
            );
        }
    
        $likesCount = Vote::where('post_id', $postId)
            ->whereNull('comment_id') 
            ->where('type', 'like')
            ->count();

        $dislikesCount = Vote::where('post_id', $postId)
            ->whereNull('comment_id') 
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
                ['userID' => $user->id, 'post_id' => $postId, 'comment_id' => null],
                ['type' => 'dislike']
            );
        }
    
        $likesCount = Vote::where('post_id', $postId)
            ->whereNull('comment_id')
            ->where('type', 'like')
            ->count();

        $dislikesCount = Vote::where('post_id', $postId)
            ->whereNull('comment_id') 
            ->where('type', 'dislike')
            ->count();

        $userVote = $vote->type ?? null;
    
        return response()->json(['likesCount' => $likesCount, 'dislikesCount' => $dislikesCount, 'userVote' => $userVote]);
    }

 
 
  public function likeComment(Request $request)
  {
      $user = auth()->user();
      $commentId = $request->input('comment_id');
      $postId = $request->input('post_id'); 
  
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
      $postId = $request->input('post_id'); 
  
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