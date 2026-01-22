<?php

namespace App\Http\Posts;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ImageHelper;

class DestroyController extends Controller
{
    public function __invoke($id)
    {
        $post = Post::findOrFail($id);

        if ((Auth::guard('api')->id() ?? Auth::id()) !== $post->user_id) {
            return $this->errorResponse('Unauthorized', 403);
        }

        ImageHelper::delete($post->image);
        $post->delete();

        return $this->successResponse(null, 'Post deleted');
    }
}
