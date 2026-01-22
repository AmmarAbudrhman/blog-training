<?php

namespace App\Http\Posts;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ImageHelper;
use App\Http\Resources\PostResource;

class UpdateController extends Controller
{
    public function __invoke(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        if ((Auth::guard('api')->id() ?? Auth::id()) !== $post->user_id) {
            return $this->errorResponse('Unauthorized', 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'body' => 'nullable|string',
            'image' => ImageHelper::getValidationRules(false),
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', 422, $validator->errors());
        }

        if ($request->hasFile('image')) {
            // delete old image if exists
            ImageHelper::delete($post->image);
            $post->image = ImageHelper::upload($request->file('image'), 'posts');
        }

        $post->fill($request->only(['title', 'body']));
        $post->save();

        return $this->successResponse(new PostResource($post), 'Post updated');
    }
}
