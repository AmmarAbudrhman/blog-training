<?php

namespace App\Http;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ImageHelper;
use App\Http\Resources\UserResource;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = Auth::guard('api')->user() ?? Auth::user();

        return $this->successResponse(new UserResource($user));
    }

    public function update(Request $request)
    {
        $user = Auth::guard('api')->user() ?? Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:6',
            'avatar' => ImageHelper::getValidationRules(false),
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', 422, $validator->errors());
        }

        if ($request->hasFile('avatar')) {
            ImageHelper::delete($user->avatar);
            $user->avatar = ImageHelper::upload($request->file('avatar'), 'avatars');
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->fill($request->only(['name', 'email']));
        $user->save();

        return $this->successResponse(new UserResource($user), 'Profile updated');
    }
}
