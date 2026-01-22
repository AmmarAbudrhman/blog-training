<?php

namespace App\Http\Posts;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ImageHelper;
use App\Http\Resources\PostResource;

class StoreController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'body' => 'nullable|string',
            'image' => ImageHelper::getValidationRules(false),
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', 422, $validator->errors());
        }

        $data = $request->only(['title', 'body']);
        $data['user_id'] = Auth::guard('api')->id() ?? Auth::id();

        if ($request->hasFile('image')) {
            $data['image'] = ImageHelper::upload($request->file('image'), 'posts');
        }

        $post = Post::create($data);

        return $this->successResponse(new PostResource($post), 'Post created', 201);
    }
}
