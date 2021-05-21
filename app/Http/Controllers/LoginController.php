<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('login', ['title' => 'Login']);
    }

    public function login()
    {
        $authenticated = auth()->attempt(request(['email', 'password']));

        if ($authenticated) {
            return redirect()->to('/contacts')->with('alert', 'Successfully logged in!');
        } else {
            return back()->withErrors([
                'message' => 'The email or password is incorrect, please try again'
            ]);
        }

    }

    public function logout() {
        Auth::logout();
        return redirect('/login')->with('alert', 'Successfully logged out!');
    }

}
