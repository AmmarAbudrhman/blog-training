<?php

namespace Tests\Feature;

use App\Mail\OtpMail;
use App\Models\Otp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OtpApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_send_otp()
    {
        Mail::fake();
        // Create user so email exists
        $user = User::factory()->create();

        $response = $this->postJson('/api/otp/send', [
            'email' => $user->email,
        ]);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('otps', ['email' => $user->email]);

        Mail::assertSent(OtpMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_can_verify_otp()
    {
        $user = User::factory()->create(['email_verified_at' => null]);
        $otp = '123456';
        
        // Manually create OTP record
        Otp::create([
            'email' => $user->email,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        $response = $this->postJson('/api/otp/verify', [
            'email' => $user->email,
            'otp' => $otp,
        ]);

        $response->assertStatus(200);

        $this->assertNotNull($user->fresh()->email_verified_at);
        $this->assertDatabaseMissing('otps', ['email' => $user->email]); // Should be deleted
    }

    public function test_cannot_verify_invalid_otp()
    {
        $user = User::factory()->create();
        Otp::create([
            'email' => $user->email,
            'otp' => '123456',
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        $response = $this->postJson('/api/otp/verify', [
            'email' => $user->email,
            'otp' => '000000',
        ]);

        $response->assertStatus(400); // Or whatever error code you used
    }
}
