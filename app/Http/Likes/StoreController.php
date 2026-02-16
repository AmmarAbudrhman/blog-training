<?php

namespace App\Http\Likes;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
        ]);

        $post = Post::findOrFail($request->post_id);
        $user = $request->user();

        if ($user->likedPosts()->where('post_id', $post->id)->exists()) {
            return $this->errorResponse('Post already liked', 409);
        }

        $user->likedPosts()->attach($post->id);

        return $this->successResponse(null, 'Post liked successfully', 201);
    }
}
