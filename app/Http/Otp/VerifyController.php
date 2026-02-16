<?php

namespace App\Http\Otp;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class VerifyController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422, $validator->errors());
        }

        $otpRecord = Otp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$otpRecord) {
            return $this->errorResponse('Invalid OTP', 400);
        }

        if (Carbon::now()->greaterThan($otpRecord->expires_at)) {
            return $this->errorResponse('OTP has expired', 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            $otpRecord->delete();
        }

        return $this->successResponse(null, 'Email verified successfully');
    }
}
