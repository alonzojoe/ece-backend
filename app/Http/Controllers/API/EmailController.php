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



        $recipients = $request->input('recipients');
        $data = $request->input('data');

        // return response()->json(['recepients' => $recipients, 'data' => $data], 200);

        foreach ($recipients as $recipient) {
            Mail::to($recipient['email'])->send(new CustomEmailNotification($recipient, $data));
        }

        return response()->json(['message' => 'Emails sent successfully!'], 200);
    }
}
