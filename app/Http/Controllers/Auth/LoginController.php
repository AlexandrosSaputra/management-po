<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        if ($request->filled('id_tkn') || $request->filled('id_token') || $request->filled('token')) {
            return response()->json([
                'message' => 'Token-based login is deprecated. Please use username/email and password.',
            ], Response::HTTP_GONE);
        }

        $validated = $request->validate([
            'login' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
            'remember' => ['sometimes', 'boolean'],
        ]);

        $login = $validated['login'];
        $field = Str::contains($login, '@') ? 'email' : 'username';

        $user = User::query()
            ->select(['id', 'status'])
            ->where($field, $login)
            ->first();

        if ($user && $user->status === false) {
            abort(Response::HTTP_FORBIDDEN, 'Akun dinonaktifkan.');
        }

        if (Auth::attempt([$field => $login, 'password' => $validated['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        throw ValidationException::withMessages([
            'login' => 'Kredensial tidak valid.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
