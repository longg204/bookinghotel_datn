<?php

namespace App\Http\Controllers;

use App\Mail\WellcomeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendMailController extends Controller
{
    //
    public function sendmail()
    {
//        $user = User::find(2);
//        $mailable = new WellcomeMail($user);
//        Mail::to('culai5500@gmail.com')->send($mailable);
//        return true;
    }

    public function verify_email(Request $re1)
    {
        $user = session('user');
        return view('mail.sendmail', compact(['user']));
    }
}
