<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($data, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended('welcome');
        }

        return back()->withErrors([
            'email' => 'Неверные учетные данные.',
        ]);
    }

    // Показать форму регистрации
    public function showRegisterForm()
    {
        return view('auth.reg');
    }

    // Обработка регистрации
    public function register(Request $request)
    {
        $data = $request->validate([
            'surname' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'post' => 'required|string|max:255',
            'phone' => 'required|string|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $data['phone'] = preg_replace('/\D/', '', $data['phone']);
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        Auth::login($user);

        return redirect()->route("welcome");
    }

    // Выход
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route("welcome");
    }
}
