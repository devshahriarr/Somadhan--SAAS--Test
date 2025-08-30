<?php

namespace App\Http\Controllers;

use App\Jobs\SendCustomerEmailJob;
use App\Mail\CustomerSendEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

class CustomeMailControler extends Controller
{
    public function CustomerSendEmail(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'message' => 'required',
            'recipients' => 'required',
        ]);
        $data = [
            'subject' => $request->subject,
            'message' => $request->message,
        ];
        $recipients = $request->recipients;
        // $ccRecipients = $request->input('cc_recipients');

        foreach ($recipients as $recipient) {
            Mail::to($recipient)->send(new CustomerSendEmail($data));
        }
        // dd($recipients);
        // foreach ($recipients as $recipient) {
        //     // Queue::push(new SendCustomerEmailJob($recipient, $data));
        //      dispatch(new SendCustomerEmailJob($recipient, $data));
        //     // Mail::to($recipient)->queue(new CustomerSendEmail($data));
        // }

        $notification = [
            'message' => 'Email Send Successfully',
            'alert-type' => 'info',
        ];

        return redirect()->route('crm.email.To.Customer.Page')->with($notification);
    }
}
