<?php

namespace App\Http\Otp;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\User;

class StoreController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422, $validator->errors());
        }

        $email = $request->email;
        $otpCode = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(10);

        Otp::updateOrCreate(
            ['email' => $email],
            ['otp' => $otpCode, 'expires_at' => $expiresAt]
        );

        Mail::to($email)->send(new OtpMail($otpCode));

        return $this->successResponse(null, 'OTP sent successfully');
    }
}
