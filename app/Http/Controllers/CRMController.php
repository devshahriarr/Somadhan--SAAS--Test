<?php

namespace App\Http\Controllers;

use App\Jobs\SendBulkEmails;
// use Validator;
use App\Mail\BulkMail;
use App\Models\Customer;
use App\Models\Sms;
use App\Models\SmsCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class CRMController extends Controller
{
    public function smsToCustomerPage()
    {
        return view('pos.crm.sms-marketing');
    }

    public function smsToCustomer(Request $request)
    {
        // Assuming $request->number and $request->sms are provided correctly
        $url = 'http://bulksmsbd.net/api/smsapimany';
        $api_key = '0yRu5BkB8tK927YQBA8u';
        $senderid = '8809617615171';
        $numbers = explode(',', $request->number);
        $messages = [];
        $sms = $request->sms;
        foreach ($numbers as $number) {
            $messages[] = [
                'to' => $number,
                'message' => $sms,
            ];
        }

        // Construct the full data payload
        $data = [
            'api_key' => $api_key,
            'senderid' => $senderid,
            'messages' => $messages, // This should be an array of messages
        ];

        // JSON encode the entire data array
        $jsonData = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData); // Send JSON data
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']); // Set appropriate content type
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:'.curl_error($ch);
        }
        curl_close($ch);

        // Store SMS details in the database
        foreach ($numbers as $number) {
            $customer = Customer::where('phone', $number)->first();
            if ($customer) {
                // Create SMS record
                try {
                    Sms::create([
                        'customer_id' => $customer->id,
                        'purpose' => $request->purpose,
                        'number' => $number,
                        'message' => $sms,
                        // You may want to store additional information like API response or status
                    ]);
                } catch (\Exception $e) {
                    // Optionally, you can also add a flash message to inform the user about the error
                    return back()->with('error', $e->getMessage());
                }
            }
        }

        // Handle response and return
        if (isset($error_message)) {
            return back()->with('error', $error_message);
        } else {
            return back()->with('message', 'SMS Submitted Successfully');
        }
    }

    public function smsCategoryStore(Request $request)
    {
        // dd($request->all());
    }

    public function emailToCustomerPage()
    {
        return view('pos.crm.email.compose');
    }

    public function emailToCustomerSend(Request $request)
    {

        $content = $request->message;
        $mails = $request->mails;
        $subject = $request->subject;
        // dd($mails);
        foreach ($mails as $mail) {
            Mail::to($mail)->queue(new BulkMail($content, $subject));
        }

        // SendBulkEmails::dispatch($mails,$subject,$content);

        $notification = [
            'message' => 'Email successfully sent',
            'alert-type' => 'info',
        ];

        return back()->with($notification);
    }

    public function storeSmsCat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);
        if ($validator->passes()) {
            $smsCat = new SmsCategory;
            $smsCat->name = $request->name;
            $smsCat->save();

            return response()->json([
                'status' => 200,
                'data' => $smsCat,
                'message' => 'Successfully saved',
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'error' => $validator->messages(),
            ]);
        }
    }

    public function viewSmsCat()
    {
        $smsCat = SmsCategory::get();

        return response()->json([
            'status' => 200,
            'data' => $smsCat,
        ]);
    }

    public function updateSmsCat(Request $request, $id)
    {
        // dd($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);
        if ($validator->passes()) {
            $smsCat = SmsCategory::findOrFail($id);
            $smsCat->name = $request->name;
            $smsCat->save();

            return response()->json([
                'status' => 200,
                'message' => 'Successfully Updated',
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'error' => $validator->messages(),
            ]);
        }
    } //

    public function deleteSmsCat($id)
    {
        $smsCat = SmsCategory::findOrFail($id);
        $smsCat->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Successfully Deleted',
        ]);
    }

    public function CustomerlistView()
    {
        if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin') {
            $customers = Customer::where('party_type', 'customer')->withSum('salesCustomer as total_sales', 'change_amount')
                ->withSum('salesCustomer as total_due', 'due')->get();
        } else {
            $customers = Customer::where('party_type', 'customer')->where('branch_id', Auth::user()->branch_id)
                ->withSum('salesCustomer as total_due', 'due')
                ->latest()->get();
        }

        return view('pos.crm.customize_customer.customize_customer', compact('customers'));
    }

    //
    public function CustomerlistFilterView(Request $request)
    {
        if (is_numeric($request->filterCustomer)) {
            $monthsToSubtract = intval($request->filterCustomer);
            $oneMonthAgo = Carbon::now()->subMonths($monthsToSubtract);
        } else {
            // Default case
            $oneMonthAgo = Carbon::now(); // Default to current time
        }

        // Query কাস্টমারদের মোট সেলসহ
        $customerQuery = Customer::where('party_type', 'customer')->withSum('sales as total_sales', 'change_amount')
            ->withSum('salesCustomer as total_due', 'due');

        // "Did not purchase" ফিল্টার চেক করা
        if ($request->filterCustomer != 'Did not purchase') {
            $customerQuery->whereDoesntHave('sales', function ($query1) use ($oneMonthAgo) {
                $query1->where('sale_date', '>', $oneMonthAgo);
            });
        }

        // তারিখের রেঞ্জ চেক করা
        if ($request->startDate && $request->endDate) {
            $startDate = Carbon::parse($request->startDate)->startOfDay();
            $endDate = Carbon::parse($request->endDate)->endOfDay();

            $customerQuery->whereHas('sales', function ($query1) use ($startDate, $endDate) {
                $query1->whereBetween('sale_date', [$startDate, $endDate]);
            });
        }

        if ($request->topSale == 'top_sale') {
            $customers = $customerQuery->orderByDesc('total_sales')->get();
        } elseif ($request->topSale == 'top_due') {
            $customers = $customerQuery->orderByDesc('total_due')->get();
        } else {

            $customers = $customerQuery->get();
        }

        return view('pos.crm.customize_customer.customize_customer-table', compact('customers'))->render();
    }
}
