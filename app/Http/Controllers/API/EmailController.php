<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomEmailNotification;

class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {

        $request->validate([
            'emails' => 'required|array',
            'emails.*' => 'email',
            'subject' => 'required|string',
            'content' => 'required|string',
        ]);


        $emails = $request->input('emails');
        $subject = $request->input('subject');
        $content = $request->input('content');


        foreach ($emails as $email) {
            Mail::to($email)->send(new CustomEmailNotification($subject, $content));
        }

        return response()->json(['message' => 'Emails sent successfully!'], 200);
    }
}
