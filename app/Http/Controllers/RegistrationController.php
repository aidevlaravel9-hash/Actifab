<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;


class RegistrationController extends Controller
{
    public function create()
    {
        return view('RegistrationAuth.registration');
    }

    public function store(Request $request)
    {

        $request->validate([
            'company_name'   => 'required',
            'contact_person' => 'required',
            'email' => 'required|email|unique:registration,email',
            'mobile'         => 'required',
            'password'       => 'required|confirmed',
        ]);

        // Generate OTP
        $otp = rand(100000, 999999);

        // Check if email already exists
        $Registration = Registration::where('email', $request->email)->first();

        if ($Registration) {

            // If user exists but NOT verified
            if ($Registration->is_verified == 0) {

                // Update OTP & expiry
                $Registration->update([
                    'otp' => $otp,
                    'otp_expires_at' => now()->addMinutes(10),
                ]);
            } else {
                // Already verified user
                return back()->with('error', 'Email already exists');
            }
        } else {

            // New Registration
            $Registration = Registration::create([
                'company_name'   => $request->company_name,
                'contact_person' => $request->contact_person,
                'email'          => $request->email,
                'mobile'         => $request->mobile,
                'address'        => $request->address,
                'password'       => Hash::make($request->password),
                'otp'            => $otp,
                'otp_expires_at' => now()->addMinutes(10),
                'is_verified'    => 0,
            ]);
        }

        // Send OTP Email
        $sendEmailDetails = DB::table('sendemaildetails')->where('id', 9)->first();

        $msg = [
            'FromMail' => $sendEmailDetails->strFromMail,
            'Title'    => $sendEmailDetails->strTitle,
            'ToEmail'  => $Registration->email,
            'Subject'  => $sendEmailDetails->strSubject,
        ];

        $data = [
            "contactname" => $Registration->contact_person,
            "otp"         => $otp
        ];

        Mail::send('emails.SendOtpmail', ['data' => $data], function ($message) use ($msg) {
            $message->from($msg['FromMail'], $msg['Title']);
            $message->to($msg['ToEmail'])->subject($msg['Subject']);
        });

        // Redirect to OTP verification screen
        return redirect()->route('verify.otp', ['email' => $Registration->email]);
    }

    public function showVerifyOtp(Request $request)
    {
        return view('RegistrationAuth.verifyotp', [
            'email' => $request->email
        ]);
    }

    public function verifyOtp(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|digits:6'
        ]);

        $Registration = Registration::where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();


        if (!$Registration) {
            session()->flash('error', 'Invalid OTP');
            return redirect()->back();
        }

        if (now()->gt($Registration->otp_expires_at)) {
            session()->flash('error', 'OTP expired');
            return redirect()->back();
        }

        $Registration->update([
            'is_verified'    => 1,
            'otp'            => null,
            'otp_expires_at' => null,
        ]);
        return redirect()->route('loginuser')->with('success', 'Account verified. Please login.');
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $registration = Registration::where('email', $request->email)
            ->where('is_verified', 0)
            ->first();

        // Generic response (don’t reveal existence)
        if (!$registration) {
            return back()->with('error', 'Unable to resend OTP. Please try again.');
        }

        // Generate new OTP
        $otp = rand(100000, 999999);

        $registration->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Email template details
        $sendEmailDetails = DB::table('sendemaildetails')
            ->where('id', 9)
            ->first();

        $msg = [
            'FromMail' => $sendEmailDetails->strFromMail,
            'Title'    => $sendEmailDetails->strTitle,
            'ToEmail'  => $registration->email,
            'Subject'  => 'Resend OTP Verification',
        ];

        $data = [
            'contactname' => $registration->contact_person,
            'otp'  => $otp,
        ];

        Mail::send('emails.SendOtpmail', ['data' => $data], function ($message) use ($msg) {
            $message->from($msg['FromMail'], $msg['Title']);
            $message->to($msg['ToEmail'])->subject($msg['Subject']);
        });

        return back()->with('success', 'OTP has been resent successfully.');
    }

    public function loginForm(Request $request)
    {

        return view('RegistrationAuth.login');
    }

    public function showChangePassword(Request $request)
    {

        return view('RegistrationAuth.change-password');
    }

    public function dashboard(Request $request)
    {

        return view('RegistrationAuth.dashboard');
    }

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required'
    //     ]);

    //     $credentials = [
    //         'email' => $request->email,
    //         'password' => $request->password,
    //         'is_verified' => 1
    //     ];

    //     // 🔴 logout admin if logged in
    //     Auth::guard('web')->logout();

    //     if (Auth::guard('user')->attempt($credentials)) {

    //         $request->session()->regenerate();

    //         return redirect()->route('dashboard');
    //     }

    //     return back()->with('error', 'Invalid login credentials');
    // }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'is_verified' => 1
        ];

        if (Auth::guard('user')->attempt($credentials)) {

            $request->session()->regenerate();

            return redirect()->route('dashboard');
        }

        return back()->with('error', 'Invalid login credentials');
    }

    public function logout(Request $request)
    {
        Auth::guard('user')->logout();   // logout user guard

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('loginuser');
    }

    public function getUserProfile()
    {
        $user = Auth::guard('user')->user();

        return view('userprofile', compact('user'));
    }

    public function editProfile()
    {
        $user = Auth::guard('user')->user();

        return view('UserEditprofile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::guard('user')->user();

        $request->validate([
            'company_name'   => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email'          => 'required|email|unique:registration,email,' . $user->id,
            'mobile'         => 'required',
            'Address'        => 'nullable|string|max:500',
        ]);

        $user->update([
            'company_name'   => $request->company_name,
            'contact_person' => $request->contact_person,
            'email'          => $request->email,
            'mobile'         => $request->mobile,
            'Address'        => $request->Address,
        ]);

        return redirect()->route('user.detail')
            ->with('success', 'Profile updated successfully.');
    }


    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password'      => 'required',
            'new_password'          => 'required|min:6',
            'new_confirm_password'  => 'required|same:new_password',
        ]);

        $user = Auth::guard('user')->user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.'
            ]);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password),
            'is_password_changed' => 1,
        ]);

        return redirect()->route('user.detail')
            ->with('success', 'Password changed successfully.');
    }

    // public function changePassword(Request $request)
    // {
    //     $request->validate([
    //         'password' => 'required|min:6|confirmed',
    //     ]);

    //     $user = Registration::findOrFail(session('user_id'));

    //     $user->update([
    //         'password' => Hash::make($request->password),
    //         'is_password_changed' => 1,
    //     ]);

    //     return redirect()->route('dashboard')
    //         ->with('success', 'Password changed successfully.');
    // }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = Registration::where('email', $request->email)
            ->where('is_password_changed', 0)
            ->firstOrFail();

        $user->update([
            'password' => Hash::make($request->password),
            'is_password_changed' => 1,
        ]);

        return redirect()->route('login')
            ->with('success', 'Password updated successfully.');
    }

    public function forgotForm()
    {
        return view('RegistrationAuth.forgot-password');
    }


    public function sendNewPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = Registration::where('email', $request->email)
            ->where('is_verified', 1)
            ->first();

        // ❌ If email not found
        if (!$user) {
            return back()->with('error', 'Invalid email address.');
        }

        // Generate 6 digit numeric password
        $newPassword = rand(100000, 999999);

        // Update password (hashed)
        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        // Send email
        Mail::send('emails.new-password', [
            'name' => $user->contact_person,
            'password' => $newPassword
        ], function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Your New Password');
        });

        return back()->with('success', 'New password has been sent to your email.');
    }





    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required'
    //     ]);

    //     $user = Registration::where('email', $request->email)
    //         ->where('is_verified', 1)
    //         ->first();


    //     if (!$user || !Hash::check($request->password, $user->password)) {
    //         return back()->with('error', 'Invalid login credentials');
    //     }

    //     // manual login (if not using default auth)
    //     session(['user_id' => $user->id]);

    //     // if ($user->is_password_changed == 0) {
    //     //     return redirect()->route('password.change', encrypt($user->email));
    //     // }

    //     return redirect()->route('dashboard');
    // }
}
