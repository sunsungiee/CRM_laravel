<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view("profile.main", [
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->merge([
            'phone' => preg_replace('/[^0-9]/', '', $request->phone)
        ]);

        $data = request()->validate([
            'surname' => 'required|string',
            'name' => 'required|string',
            'post' => 'required|string',
            'email' => 'required|string',
            'phone' => 'required|string',
            'password' => 'string'
        ]);

        $user->update($data);

        return redirect()->route("profile.main");
    }
}
