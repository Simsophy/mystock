<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\Models\User;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | using digit codes sent via email.
    |
    */

    /**
     * Display the password reset view for the given token.
     */
    public function showResetForm()
    {
        return view('auth.passwords.verify-code');
    }

    /**
     * Verify the reset code.
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|digits:6',
        ]);

        $resetCode = DB::table('password_reset_codes')
            ->where('email', $request->email)
            ->where('code', $request->code)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$resetCode) {
            return back()->withErrors(['code' => 'Invalid or expired code']);
        }

        return view('auth.passwords.reset', [
            'email' => $request->email,
            'code' => $request->code,
        ]);
    }

    /**
     * Reset the user password.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|digits:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Verify the code again
        $resetCode = DB::table('password_reset_codes')
            ->where('email', $request->email)
            ->where('code', $request->code)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$resetCode) {
            return back()->withErrors(['code' => 'Invalid or expired code']);
        }

        // Find user and update password
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User not found']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the used code
        DB::table('password_reset_codes')
            ->where('email', $request->email)
            ->where('code', $request->code)
            ->delete();

        return redirect('/login')->with('status', 'Password reset successfully! Please login with your new password.');
    }
}
