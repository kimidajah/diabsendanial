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
        $request->validate([
            'login_input' => 'required|string',
        ]);

        $loginInput = $request->input('login_input');
        $user = null;

        // Cek apakah input adalah NIDN (hanya angka)
        if (is_numeric($loginInput)) {
            $teacher = Teacher::where('nidn', $loginInput)->first();
            if ($teacher && $teacher->user) {
                $user = $teacher->user;
            }
        }

        // Jika user belum ditemukan (atau input bukan NIDN), cari berdasarkan username atau email
        if (!$user) {
            $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $user = User::where($field, $loginInput)->first();
        }

        // Jika user ditemukan, langsung login tanpa password
        if ($user) {
            Auth::login($user, $request->has('remember'));
            $request->session()->regenerate();
            return $this->redirectUser($user);
        }

        return back()->withErrors([
            'login_input' => 'NIDN / Username tidak terdaftar.',
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
