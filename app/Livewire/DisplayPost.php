<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Post;
use App\Models\PostOption;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DisplayPost extends Component
{
    public $post;
    public $totalCount;
    public $comment;

    public function mount($slug)
    {
        $this->post = Post::where('slug', $slug)->firstOrFail();
        $this->totalCount = $this->post->options->sum('counts');
    }

    public function updateVote($optionId)
    {
        $postId = $this->post->id;
        $userId = Auth::id();

        // Check if user has already voted
        $existingVote = DB::table('track_votes')
            ->where('post_id', $postId)
            ->where('user_id', $userId)
            ->first();

        if ($existingVote) {
            // If same option is selected, do nothing
            if ($existingVote->post_option_id == $optionId) {
                DB::table('track_votes')
                    ->where('post_id', $postId)
                    ->where('user_id', $userId)
                    ->delete();
                optional(PostOption::find($existingVote->post_option_id))->decrement('counts');

                return;
            }

            // Update the vote option
            DB::table('track_votes')
                ->where('post_id', $postId)
                ->where('user_id', $userId)
                ->update(['post_option_id' => $optionId, 'updated_at' => now()]);

            // Decrease count of previous option
            optional(PostOption::find($existingVote->post_option_id))->decrement('counts');

            // Increase count of new option
            optional(PostOption::find($optionId))->increment('counts');

            // Update total count
            $this->totalCount = $this->post->options->sum('counts');

            return;
        }

        // If user hasn't voted before, insert a new vote
        if ($option = PostOption::find($optionId)) {
            $option->increment('counts');

            DB::table('track_votes')->insert([
                'user_id' => $userId,
                'post_id' => $postId,
                'post_option_id' => $optionId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->totalCount = $this->post->options->sum('counts');
        }
    }

    public function saveComment()
    {
        $this->validate([
            'comment' => 'required',
        ]);
        $userId = Auth::id();
        $postId = $this->post->id;

        Comment::create([
            'user_id' => $userId,
            'post_id' => $postId,
            'comment' => $this->comment,
        ]);
        $this->comment = "";
    }

    public function likeComment($commentId)
    {
        // Check if the user has interacted with the comment
        $userInteracted = DB::table('track_comments')
            ->where(['comment_id' => $commentId, 'user_id' => auth()->id()])
            ->first();

        if ($userInteracted) {
            // If the user has already liked, remove the like
            if ($userInteracted->liked == 1) {
                // Decrement the like count
                DB::table('comments')->where('id', $commentId)->decrement('likes');
                // Delete the like record
                DB::table('track_comments')->where('id', $userInteracted->id)->delete();
            }
            // If the user had disliked before, switch to like
            elseif ($userInteracted->liked == 0) {
                // Decrement the dislike count
                DB::table('comments')->where('id', $userInteracted->comment_id)->decrement('dislikes');
                // Update the like status to 1 (like)
                DB::table('comments')->where('id', $commentId)->update(['liked' => 1]);
                // Update the user's interaction
                DB::table('track_comments')->where('id', $userInteracted->id)->update(['liked' => 1]);
            }
        } else {
            // Insert a new like record
            DB::table('track_comments')->insert([
                'user_id' => auth()->id(),
                'comment_id' => $commentId,
                'liked' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Increment the likes count for the comment
            DB::table('comments')->where('id', $commentId)->increment('likes');
        }
    }

    public function dislikeComment($commentId)
    {
        // Check if the user has interacted with the comment
        $userInteracted = DB::table('track_comments')
            ->where(['comment_id' => $commentId, 'user_id' => auth()->id()])
            ->first();

        if ($userInteracted) {
            // If the user has already disliked, remove the dislike
            if ($userInteracted->liked == 0) {
                // Decrement the dislike count
                DB::table('comments')->where('id', $commentId)->decrement('dislikes');
                // Delete the dislike record
                DB::table('track_comments')->where('id', $userInteracted->id)->delete();
            }
            // If the user had liked before, switch to dislike
            elseif ($userInteracted->liked == 1) {
                // Decrement the like count
                DB::table('comments')->where('id', $userInteracted->comment_id)->decrement('likes');
                // Update the like status to 0 (dislike)
                DB::table('comments')->where('id', $commentId)->update(['liked' => 0]);
                // Update the user's interaction
                DB::table('track_comments')->where('id', $userInteracted->id)->update(['liked' => 0]);
            }
        } else {
            // Insert a new dislike record
            DB::table('track_comments')->insert([
                'user_id' => auth()->id(),
                'comment_id' => $commentId,
                'liked' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Increment the dislikes count for the comment
            DB::table('comments')->where('id', $commentId)->increment('dislikes');
        }
    }

    public function render()
    {
        return view('livewire.display-post');
    }
}
