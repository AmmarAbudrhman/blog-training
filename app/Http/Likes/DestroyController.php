<?php

namespace App\Http\Likes;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DestroyController extends Controller
{
    public function __invoke(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);
        $user = $request->user();

        if (!$user->likedPosts()->where('post_id', $post->id)->exists()) {
            return $this->errorResponse('Post not liked yet', 404);
        }

        $user->likedPosts()->detach($post->id);

        return $this->successResponse(null, 'Post unliked successfully');
    }
}
