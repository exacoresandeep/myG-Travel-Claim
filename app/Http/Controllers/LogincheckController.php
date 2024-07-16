<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Redirect;
use Session;
use Hash;
use Auth;

class LogincheckController extends Controller
{
/*****************************************
   Date        : 01/03/2024
   Description :  Login for Submission
******************************************/
    public function login(Request $req)
    {
        $validatedData = $req->validate([
          'username' => 'required',
          'password'=>'required',
        ], 
        [
          'username.required' => 'Please enter the name.',
          'password.required' => 'Please enter the password.',
        ]);
        $credentials = $req->only('username', 'password');
        $check_exist=User::where('emp_id',$req->username)->exists();
        $user = User::where('emp_id', $credentials['username'])->first();
        if($check_exist!=true)
        {
            return Redirect::back()->withErrors(['msg' => 'Incorrect username.']);
        }
        else if ($check_exist==true && !Hash::check($credentials['password'], $user->password)) {
            return Redirect::back()->withErrors(['msg' => 'Incorrect password.']);
        }
        else if (Auth::attempt(['emp_id' => $credentials['username'], 'password' => $credentials['password']])) 
        {
            return redirect()->route('home');
        }
        else
        {
            return Redirect::back()->withErrors(['msg' => 'Invalid credentials. Please try again.']);
        }
              
    }
    
}
