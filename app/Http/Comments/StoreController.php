<?php

namespace App\Http\Comments;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comment;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422, $validator->errors());
        }

        $comment = Comment::create([
            'user_id' => $request->user()->id,
            'post_id' => $request->input('post_id'),
            'content' => $request->input('content'),
        ]);

        return $this->successResponse(new CommentResource($comment), 'Comment created successfully', 201);
    }
}
