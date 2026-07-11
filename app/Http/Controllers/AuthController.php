<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectUser(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login_input' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = $credentials['login_input'];
        $password = $credentials['password'];

        // Cek apakah input adalah NIDN (hanya angka)
        if (is_numeric($loginInput)) {
            $teacher = Teacher::where('nidn', $loginInput)->first();
            if ($teacher && $teacher->user) {
                $user = $teacher->user;
                if (Hash::check($password, $user->password)) {
                    Auth::login($user, $request->has('remember'));
                    $request->session()->regenerate();
                    return $this->redirectUser($user);
                }
            }
        } else {
            // Cek apakah input adalah email atau username
            $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $user = User::where($field, $loginInput)->first();

            if ($user && Hash::check($password, $user->password)) {
                Auth::login($user, $request->has('remember'));
                $request->session()->regenerate();
                return $this->redirectUser($user);
            }
        }

        return back()->withErrors([
            'login_input' => 'NIDN / Email / Username atau Password salah.',
        ])->onlyInput('login_input');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    protected function redirectUser(User $user)
    {
        if ($user->isWakasek()) {
            return redirect()->intended('/wakasek/dashboard');
        } elseif ($user->isTu()) {
            return redirect()->intended('/tu/scanner');
        } elseif ($user->isGuru()) {
            return redirect()->intended('/guru/dashboard');
        }

        return redirect('/');
    }
}
