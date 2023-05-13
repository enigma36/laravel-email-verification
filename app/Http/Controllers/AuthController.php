<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerificationMail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register()
    {
        return view('register');
    }

    public function registerPost(Request $request)
    {

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->email_verification_code = Str::random(40);
        $user->save();
        Mail::to($request->email)->send(new EmailVerificationMail($user));
        return back()->with('success', 'Register successfully. Please Verify Email.');

    }

    public function login()
    {
        return view('login');
    }

    public function loginPost(Request $request)
    {
        $credetials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credetials)) {
            return redirect('/home')->with('success', 'Login Success');
        }

        return back()->with('error', 'Error Email or Password');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }

    public function verify_email($verification_code){
        $user= User::where('email_verification_code',$verification_code)->first();
        if($user){
            if($user->email_verified_at){
                return redirect()->route('register')->with('error', 'Email already verified');
            }
            else{
                $user->update([
                    'email_verified_at'=>\Carbon\Carbon::now()
                ]);
                return redirect()->route('register')->with('success','Email Verified');

            }
        }else{
            return redirect()->route('register')->with('error','Invalid URL');
        }

    }

}
