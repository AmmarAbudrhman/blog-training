<?php

namespace App\Http\Posts;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;

class ShowController extends Controller
{
    public function __invoke($id)
    {
        $post = Post::with('user')->findOrFail($id);

        return $this->successResponse(new PostResource($post));
    }
}
