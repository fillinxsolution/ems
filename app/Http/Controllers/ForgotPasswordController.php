<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class ForgotPasswordController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();
        Password::deleteToken($user);
        $token = Password::createToken($user);

        if ($user) {
            $link = route('password.reset', [$token, 'email' => $request->email]);
            $arr = array('{$link}' => $link);
            $emailfrom = env('MAIL_FROM_ADDRESS') ?? 'support@fillinxsolutions.com';
            $to = $request->email;
            $subject = 'Reset Password';

            $maildata = [
                'email' => $request->email,
                'emailbody' => env('FRONT_APP_URL').'update_password?token=' .$token.'&email=' .$request->email,
            ];

            Mail::send('email.forgotPassword', $maildata, function ($message) use ($emailfrom, $to, $subject) {
                $message->from($emailfrom, 'Fillinx Solution');
                $message->to($to);
                $message->subject($subject);
            });
            return $this->sendResponse('' ,200, ['Email Sent Successfully'], true);
        } else {
            return $this->sendResponse('' ,401, ['Email not found'], true);
        }
    }

    public function update(Request $request){

        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );
        return $this->sendResponse($status, 200, ['Password Update Successfully'], true);
    }
}
