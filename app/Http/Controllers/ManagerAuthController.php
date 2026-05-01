<?php

namespace App\Http\Controllers;

use App\Models\ManagerDesignerUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerAuthController extends Controller
{
    public function loginForm()
    {
        return view('manager_auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = ManagerDesignerUser::where('email', $request->email)
            ->where('role_type', 'manager')
            ->first();

        if ($user && (int) $user->status !== 1) {
            return back()->withInput($request->only('email'))->with('error', 'User is not active. Please contact admin.');
        }

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'role_type' => 'manager',
        ];

        if (Auth::guard('manager')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('manager.dashboard');
        }

        return back()->with('error', 'Invalid manager login credentials.');
    }
    public function dashboard()
    {
        return view('manager_auth.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('manager')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('manager.login');
    }
}
