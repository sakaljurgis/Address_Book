<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Models\User as User;

class RegistrationController extends Controller
{
    public function showForm()
    {
        return view('register', ['title' => 'Register']);
    }
    public function process()
    {
        $result = $this->validate(request(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        $user = User::create([
            'name' => trim($result['name']),
            'email' => strtolower($result['email']),
            'password' => bcrypt($result['password']),
        ]);

        auth()->login($user);

        return redirect()->to('/contacts');
    }
}
