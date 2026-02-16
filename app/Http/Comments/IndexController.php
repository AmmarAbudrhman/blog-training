<?php

namespace App\Http\Comments;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);
        $comments = $post->comments()->with('user')->paginate(config('comments.pagination_limit', 10));
        
        return $this->successResponse(CommentResource::collection($comments));
    }
}
