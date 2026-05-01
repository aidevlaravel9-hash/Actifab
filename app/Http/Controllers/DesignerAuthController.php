<?php

namespace App\Http\Controllers;

use App\Models\ManagerDesignerUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DesignerAuthController extends Controller
{
    public function loginForm()
    {
        return view('designer_auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = ManagerDesignerUser::where('email', $request->email)
            ->where('role_type', 'designer')
            ->first();

        if ($user && (int) $user->status !== 1) {
            return back()->withInput($request->only('email'))->with('error', 'User is not active. Please contact admin.');
        }

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'role_type' => 'designer',
        ];

        if (Auth::guard('designer')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('designer.dashboard');
        }

        return back()->with('error', 'Invalid designer login credentials.');
    }

    public function dashboard()
    {
        return view('designer.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('designer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('designer.login');
    }
}
