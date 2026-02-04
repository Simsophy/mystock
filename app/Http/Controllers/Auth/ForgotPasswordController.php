<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | sending digit codes for password reset functionality.
    |
    */

    /**
     * Show the form to request a password reset code.
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Send a password reset code via email.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email not found']);
        }

        // Generate a 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Delete old codes for this email
        DB::table('password_reset_codes')->where('email', $request->email)->delete();

        // Store the code
        DB::table('password_reset_codes')->insert([
            'email' => $request->email,
            'code' => $code,
            'created_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addMinutes(15), // Code valid for 15 minutes
        ]);

        // Send the code via email
        try {
            Mail::raw("Your password reset code is: {$code}\n\nThis code will expire in 15 minutes.", function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Password Reset Code');
            });

            return back()->with('status', 'A password reset code has been sent to your email!');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to send email. Please try again.']);
        }
    }
}
