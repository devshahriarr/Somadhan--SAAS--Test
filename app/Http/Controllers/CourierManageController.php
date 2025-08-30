<?php

namespace App\Http\Controllers;

use App\Models\CouerierOrder;
use App\Models\CourierAdd;
use App\Models\CourierManage;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CourierManageController extends Controller
{
    public function courierAdd()
    {

        return view('pos.CourierManage.courierAdd');
    }

    public function courierManage(Request $request)
    {
        //  dd($request->all());
        try {
            $courier_name = strtolower(str_replace(' ', '', $request->courier_name));
            $courier_manage = CourierManage::where('courier_name', $courier_name)->first();
            if ($courier_manage) {
                return response()->json(['status' => 400, 'message' => 'Courier Name Already Exist']);
            } else {
                $courier_manage = new CourierManage;
                $courier_manage->courier_name = $request->courier_name;
                $courier_manage->contact_number = $request->contact_number;
                $courier_manage->base_url = $request->base_url;
                $courier_manage->current_balance = $request->current_balance;
                $courier_manage->save();

                return response()->json(['status' => 200, 'message' => 'Courier Manage Added Successfully']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function courierManageView()
    {
        $courier_manage = CourierManage::all();

        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            $today_total_order = CouerierOrder::whereDate('created_at', Carbon::today())->count();

            $new_order = CouerierOrder::with('sale')
                ->where('status', 'pending')
                ->whereDate('created_at', Carbon::today())
                ->count();

            $today_processing_order = CouerierOrder::with('sale')
                ->where('status', 'processing')
                ->whereDate('created_at', Carbon::today())
                ->count();

            $today_completed_order = CouerierOrder::with('sale')
                ->where('status', 'completed')
                ->whereDate('created_at', Carbon::today())
                ->count();

            return view('pos.CourierManage.CourierManage', compact(
                'courier_manage',
                'today_total_order',
                'new_order',
                'today_processing_order',
                'today_completed_order'
            ));
        } else {
            $today_total_order = CouerierOrder::where('branch_id', Auth::user()->branch_id)
                ->whereDate('created_at', Carbon::today())
                ->count();

            $new_order = CouerierOrder::with('sale')
                ->where('branch_id', Auth::user()->branch_id)
                ->where('status', 'pending')
                ->whereDate('created_at', Carbon::today())
                ->count();

            $today_processing_order = CouerierOrder::with('sale')
                ->where('branch_id', Auth::user()->branch_id)
                ->where('status', 'processing')
                ->whereDate('created_at', Carbon::today())
                ->count();

            $today_completed_order = CouerierOrder::with('sale')
                ->where('branch_id', Auth::user()->branch_id)
                ->where('status', 'completed')
                ->whereDate('created_at', Carbon::today())
                ->count();

            return view('pos.CourierManage.CourierManage', compact(
                'courier_manage',
                'today_total_order',
                'new_order',
                'today_processing_order',
                'today_completed_order'
            ));
        }
        // return view('pos.CourierManage.CourierManage',compact('courier_manage'));
    }

    public function courierManageinfoEdit($id)
    {
        $courier_manage = CourierManage::find($id);
        $courier_other_info = CourierAdd::where('courier_id', $id)->first();

        return view('pos.CourierManage.courierinfoedit', compact('courier_manage', 'courier_other_info'));
    }

    public function courierManageinfoUpdate(Request $request)
    {
        try {

            if ($request->courier_name) {

                $courier_manage = CourierManage::find($request->id);

                if (! $courier_manage) {
                    return response()->json(['status' => 404, 'message' => 'Courier Manage not found!']);
                }

                $courier_manage->courier_name = $request->courier_name;
                $courier_manage->contact_number = $request->contact_number;
                $courier_manage->base_url = $request->base_url;
                $courier_manage->current_balance = $request->current_balance;
                $courier_manage->save();

                if ($courier_manage->id) {
                    $courier_add = CourierAdd::where('courier_id', $courier_manage->id)->first();
                    if (! $courier_add) {
                        $courier_add = new CourierAdd;
                    }
                    $courier_add->courier_id = $courier_manage->id;
                    $courier_add->api_key = $request->api_key;
                    $courier_add->secret_key = $request->secret_key;
                    $courier_add->api_access_token = $request->api_access_token;
                    $courier_add->user_name = $request->user_name;
                    $courier_add->password = $request->password;
                    $courier_add->paperfly_key = $request->paperfly_key;
                    $courier_add->save();
                }

                return response()->json(['status' => 200, 'message' => 'Courier Manage Updated Successfully']);
            }

            return response()->json(['status' => 400, 'message' => 'Courier name is required.']);

        } catch (\Exception $e) {

            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function courierPendingOrder()
    {

        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {

            $base_url = 'https://bdapi.vercel.app/api/v.1/district';
            $district = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->get($base_url);

            $district = $district->json();
            $district = $district['data'];

            $courier_manage = CouerierOrder::with('sale')->where('status', 'pending')->get();

            $couriers = CourierManage::all();
            //   dd($courier);
            $courier_total_order = CouerierOrder::count();
            $new_order = CouerierOrder::with('sale')->where('status', 'pending')->count();

            //  dd($courier_total_order,$new_order);
            return view('pos.CourierManage.courierPendingOrder', compact('courier_manage', 'couriers', 'district', 'courier_total_order', 'new_order'));
        } else {

            $base_url = 'https://bdapi.vercel.app/api/v.1/district';
            $district = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->get($base_url);

            $district = $district->json();
            $district = $district['data'];
            $courier_manage = CouerierOrder::where('branch_id', Auth::user()->branch_id)->with('sale')->where('status', 'pending')->get();
            $courier = CourierManage::all();

            $courier_total_order = CouerierOrder::where('branch_id', Auth::user()->branch_id)->count();
            $new_order = CouerierOrder::where('branch_id', Auth::user()->branch_id)->with('sale')->where('status', 'pending')->count();

            return view('pos.CourierManage.courierPendingOrder', compact('courier_manage', 'courier', 'district', 'courier_total_order', 'new_order'));
        }

    }

    public function getAreaByDistrict(Request $request)
    {
        try {
            $district_name = $request->district_name;

            $redx_courier_manage = CourierManage::whereRaw(
                "LOWER(REPLACE(courier_name, ' ', '')) = ?",
                ['redx']
            )->first();

            $redx_info = CourierAdd::where('courier_id', $redx_courier_manage->id)->first();
            $api_access_token = $redx_info->api_access_token;

            $base_url = 'https://openapi.redx.com.bd/v1.0.0-beta/areas?district_name='.urlencode($district_name);

            $response = Http::withHeaders([
                'API-ACCESS-TOKEN' => $api_access_token,
                'Content-Type' => 'application/json',
            ])->get($base_url);

            $area = $response->json();

            return response()->json([
                'status' => 200,
                'message' => 'Area List',
                'area' => $area['areas'] ?? [],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Something Went Wrong',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function courierOrderAssign(Request $request)
    {
        try {
            // dd($request->all());
            $getCourier = CourierAdd::where('courier_id', $request->courier_id)->first();
            //  dd($getCourier);
            $courier_manage = CourierManage::where('id', $request->courier_id)->first();
            $courierName = $courierName = strtolower(str_replace(' ', '', $courier_manage->courier_name));

            if ($courierName == 'steadfast') {
                $base_url = $courier_manage->base_url;
                $api_key = $getCourier->api_key;
                $secret_key = $getCourier->secret_key;
                $invoice = $request->invoice;
                $recipient_name = $request->recipient_name;
                $recipient_phone = $request->recipient_phone;
                $cod_amount = $request->cod_amount;
                $recipient_address = $request->customer_address;
                $note = $request->note;

                $header = [
                    'Api-Key' => $api_key,
                    'Secret-Key' => $secret_key,
                    'Content-Type' => 'application/json',

                ];

                $data = [
                    'invoice' => $invoice,
                    'recipient_name' => $recipient_name,
                    'recipient_phone' => $recipient_phone,
                    'cod_amount' => $cod_amount,
                    'recipient_address' => $recipient_address,
                    'note' => $note,
                ];

                $response = Http::withHeaders($header)->post($base_url, $data);
                $response = $response->json();

                if ($response['status'] === 200) {
                    $saleOrder = CouerierOrder::where('sale_id', $request->sale_id)->where('status', 'pending')->latest()->first();

                    $saleOrder->status = 'processing';
                    $saleOrder->courier_id = $request->courier_id;
                    $saleOrder->tracking_number = $response['consignment']['tracking_code'];
                    $saleOrder->courier_status = $response['consignment']['delivery_status'];
                    $saleOrder->save();

                    return response()->json([
                        'status' => 200,
                        'message' => 'Courier Order Assign Successfully',
                        'data' => $response,
                    ]);
                }
            }
            // steadfast end
            // paperfly start
            if ($courierName == 'paperfly') {
                // dd($request->all());
                $base_url = $courier_manage->base_url;

                $user_name = $getCourier->user_name;

                $password = $getCourier->password;

                $paperfly_key = $getCourier->paperfly_key;

                $orderData = [
                    'merOrderRef' => $request->invoice,
                    'pickMerchantName' => '',
                    'pickMerchantAddress' => '',
                    'pickMerchantThana' => '',
                    'pickMerchantDistrict' => '',
                    'pickupMerchantPhone' => '',
                    'productSizeWeight' => $request->productSizeWeight,
                    // "productBrief"=>$request->productBrief,
                    'packagePrice' => $request->packagePrice,
                    'max_weight' => $request->maxWeight,
                    'deliveryOption' => $request->deliveryOption,
                    'custname' => $request->recipient_name,
                    'custaddress' => $request->customer_address,
                    'customerThana' => $request->customerThana,
                    'customerDistrict' => $request->district_name,
                    'custPhone' => $request->recipient_phone,
                ];

                // API Request with Basic Authentication
                $response = Http::withBasicAuth($user_name, $password)
                    ->withHeaders([
                        'paperflykey' => $paperfly_key,
                        'Accept' => 'application/json',
                    ])
                    ->post($base_url, $orderData);
                $responseData = $response->json();
                // dd($responseData);
                // dd($responseData['success']['tracking_number']);
                if ($responseData['response_code'] === 200) {
                    $saleOrder = CouerierOrder::where('sale_id', $request->sale_id)->where('status', 'pending')->latest()->first();
                    $saleOrder->status = 'processing';
                    $saleOrder->tracking_number = $responseData['success']['tracking_number'];

                    $saleOrder->courier_id = $request->courier_id;
                    $saleOrder->save();

                    return response()->json([
                        'status' => 200,
                        'message' => 'Courier Order Assign Successfully',
                        'data' => $responseData,
                    ]);
                }

            }

            // paperfly end
            if ($courierName == 'redx') {

                $base_url = $courier_manage->base_url;
                $api_access_token = $getCourier->api_access_token;
                $customer_name = $request->recipient_name;
                $customer_phone = $request->recipient_phone;
                $delivery_area = $request->delivery_area;
                $delivery_area_id = $request->area_id;
                $customer_address = $request->customer_address;
                $cash_collection_amount = $request->cash_collection_amount;
                $parcel_weight = $request->parcel_weight;
                $value = $request->value;
                $merchant_invoice_id = $request->invoice;

                $header = [
                    'API-ACCESS-TOKEN' => $api_access_token,
                    'Content-Type' => 'application/json',
                ];
                $data = [
                    'customer_name' => $customer_name,
                    'customer_phone' => $customer_phone,
                    'delivery_area' => $delivery_area,
                    'delivery_area_id' => $delivery_area_id,
                    'customer_address' => $customer_address,
                    'cash_collection_amount' => $cash_collection_amount,
                    'parcel_weight' => $parcel_weight,
                    'value' => $value,
                    'merchant_invoice_id' => $merchant_invoice_id,
                ];
                $response = Http::withHeaders($header)->post($base_url, $data);

                $response = $response->json();
                //   dd($response);
                if ($response) {
                    $saleOrder = CouerierOrder::where('sale_id', $request->sale_id)->where('status', 'pending')->latest()->first();
                    $saleOrder->status = 'processing';
                    $saleOrder->courier_id = $request->courier_id;
                    $saleOrder->tracking_number = $response['tracking_id'];
                    // $saleOrder->courier_status=$response['consignment']['delivery_status'];
                    $saleOrder->save();

                    return response()->json([
                        'status' => 200,
                        'message' => 'Courier Order Assign Successfully',
                        'data' => $response,
                    ]);
                }

            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Something Went Wrong',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function courierOrderCancel(Request $request)
    {
        try {
            $courierOrder = CouerierOrder::where('id', $request->order_id)->first();

            $courierOrder->status = 'cancelled';
            $courierOrder->save();

            return response()->json([
                'status' => 200,
                'message' => 'Courier Order Cancel Successfully',
                'data' => $courierOrder,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Something Went Wrong',
                'error' => $e->getMessage(),
            ]);
        }

    }

    public function ProcessingOrder()
    {
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {

            $courier_manage = CouerierOrder::with('sale')->where('status', 'processing')->get();

            $couriers = CourierManage::all();
            $courier_total_order = CouerierOrder::count();
            $new_order = CouerierOrder::with('sale')->where('status', 'pending')->count();

            return view('pos.CourierManage.CourierProcessingOrder', compact('courier_manage', 'courier_total_order', 'new_order', 'couriers'));
        } else {

            $courier_manage = CouerierOrder::where('branch_id', Auth::user()->branch_id)->with('sale')->where('status', 'processing')->get();
            $courier_total_order = CouerierOrder::where('branch_id', Auth::user()->branch_id)->count();
            $new_order = CouerierOrder::where('branch_id', Auth::user()->branch_id)->with('sale')->where('status', 'pending')->count();
            $couriers = CourierManage::all();

            return view('pos.CourierManage.CourierProcessingOrder', compact('courier_manage', 'courier_total_order', 'new_order', 'couriers'));
        }
    }

    public function ProcessingToComplete(Request $request)
    {
        try {
            $courierOrder = CouerierOrder::where('id', $request->order_id)->first();
            $courierOrder->status = 'completed';
            $courierOrder->save();
            $courierOrder->sale->paid += $courierOrder->sale->due;
            $courierOrder->sale->due = 0;
            // $courierOrder->sale->receivable += $courierOrder->sale->due;

            $courierOrder->sale->save();

            return response()->json([
                'status' => 200,
                'message' => 'Courier Order Complete Successfully',
                'data' => $courierOrder,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Something Went Wrong',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function courierOrderComplete()
    {
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {

            $courier_manage = CouerierOrder::with('sale')->where('status', 'completed')->get();

            $courier = CourierManage::all();
            $courier_total_order = CouerierOrder::count();
            $new_order = CouerierOrder::with('sale')->where('status', 'pending')->count();

            return view('pos.CourierManage.CourierCompleteOrder', compact('courier_manage', 'courier_total_order', 'new_order'));
        } else {

            $courier_manage = CouerierOrder::where('branch_id', Auth::user()->branch_id)->with('sale')->where('status', 'completed')->get();
            $courier_total_order = CouerierOrder::where('branch_id', Auth::user()->branch_id)->count();
            $new_order = CouerierOrder::where('branch_id', Auth::user()->branch_id)->with('sale')->where('status', 'pending')->count();

            return view('pos.CourierManage.CourierCompleteOrder', compact('courier_manage'));
        }
    }

    public function cancelOrder()
    {
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {

            $courier_manage = CouerierOrder::with('sale')->where('status', 'cancelled')->get();

            $courier = CourierManage::all();

            $courier_total_order = CouerierOrder::count();
            $new_order = CouerierOrder::with('sale')->where('status', 'pending')->count();

            return view('pos.CourierManage.CourierCancelOrder', compact('courier_manage', 'courier_total_order', 'new_order'));
        } else {

            $courier_manage = CouerierOrder::where('branch_id', Auth::user()->branch_id)->with('sale')->where('status', 'cancelled')->get();

            $courier_total_order = CouerierOrder::where('branch_id', Auth::user()->branch_id)->count();
            $new_order = CouerierOrder::where('branch_id', Auth::user()->branch_id)->with('sale')->where('status', 'pending')->count();

            return view('pos.CourierManage.CourierCancelOrder', compact('courier_manage'));
        }
    }

    public function courierOrderView($id)
    {
        $courier = CourierManage::where('id', $id)->first();
        $courierdetails = CourierAdd::where('courier_id', $courier->id)->first();

        $today = Carbon::today();
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            $totalToday = CouerierOrder::where('courier_id', $courier->id)
                ->whereDate('created_at', $today)
                ->count();

            $pendingToday = CouerierOrder::where('courier_id', $courier->id)
                ->whereDate('created_at', $today)
                ->where('status', 'pending')
                ->count();
        } else {
            $totalToday = CouerierOrder::where('branch_id', Auth::user()->branch_id)
                ->where('courier_id', $courier->id)
                ->whereDate('created_at', $today)
                ->count();
            $pendingToday = CouerierOrder::where('branch_id', Auth::user()->branch_id)->where('courier_id', $courier->id)
                ->whereDate('created_at', $today)
                ->where('status', 'pending')
                ->count();
        }

        // === REDX COURIER ===
        if (strtolower(str_replace(' ', '', $courier->courier_name)) == 'redx') {

            if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {

                $order = CouerierOrder::with('sale')->where('courier_id', $courier->id)->get();

            } else {
                $order = CouerierOrder::where('branch_id', Auth::user()->branch_id)->with('sale')->where('courier_id', $courier->id)->get();
            }

            $api_access_token = $courierdetails->api_access_token;

            foreach ($order as $orderItem) {
                $base_url = 'https://openapi.redx.com.bd/v1.0.0-beta/parcel/track/'.$orderItem->tracking_number;
                $header = [
                    'API-ACCESS-TOKEN' => $api_access_token,
                    'Content-Type' => 'application/json',
                ];

                $response = Http::withHeaders($header)->get($base_url);
                $response = $response->json();

                if (isset($response['tracking'][0]['message_bn'])) {
                    $orderItem->courier_status = $response['tracking'][0]['message_bn'];
                    $orderItem->save();
                }
            }

            if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {

                $order = CouerierOrder::with('sale')->where('courier_id', $courier->id)->get();

            } else {
                $order = CouerierOrder::where('branch_id', Auth::user()->branch_id)->with('sale')->where('courier_id', $courier->id)->get();
            }

            $couriers = CourierManage::all();
            $courier_name = $courier->courier_name;

            return view('pos.CourierManage.steadfastorder', compact(
                'order', 'courier', 'couriers', 'courier_name', 'totalToday', 'pendingToday'
            ));
        }

        // === PAPERFLY COURIER ===
        if (strtolower(str_replace(' ', '', $courier->courier_name)) == 'paperfly') {
            if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {

                $order = CouerierOrder::with('sale')->where('courier_id', $courier->id)->get();

            } else {
                $order = CouerierOrder::where('branch_id', Auth::user()->branch_id)->with('sale')->where('courier_id', $courier->id)->get();
            }
            $user_name = $courierdetails->user_name;
            $password = $courierdetails->password;
            $paperfly_key = $courierdetails->paperfly_key;
            $base_url = 'https://api.paperfly.com.bd/API-Order-Tracking';

            foreach ($order as $orderItem) {
                $orderData = [
                    'ReferenceNumber' => $orderItem->sale->invoice_number,
                ];

                $response = Http::withBasicAuth($user_name, $password)
                    ->withHeaders([
                        'paperflykey' => $paperfly_key,
                        'Accept' => 'application/json',
                    ])
                    ->post($base_url, $orderData);

                $responseData = $response->json();

                if (
                    isset($responseData['success']['trackingStatus'][0]['inTransit']) &&
                    $responseData['success']['trackingStatus'][0]['inTransit'] !== ''
                ) {
                    $orderItem->courier_status = $responseData['success']['trackingStatus'][0]['inTransit'];
                    $orderItem->save();
                }
            }

            if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {

                $order = CouerierOrder::with('sale')->where('courier_id', $courier->id)->get();

            } else {
                $order = CouerierOrder::where('branch_id', Auth::user()->branch_id)->with('sale')->where('courier_id', $courier->id)->get();
            }

            $couriers = CourierManage::all();
            $courier_name = $courier->courier_name;

            return view('pos.CourierManage.steadfastorder', compact(
                'order', 'courier', 'couriers', 'courier_name', 'totalToday', 'pendingToday'
            ));
        }

        // === STEADFAST COURIER ===
        if (strtolower(str_replace(' ', '', $courier->courier_name)) == 'steadfast') {
            if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {

                $order = CouerierOrder::with('sale')->where('courier_id', $courier->id)->get();

            } else {
                $order = CouerierOrder::where('branch_id', Auth::user()->branch_id)->with('sale')->where('courier_id', $courier->id)->get();
            }
            $app_key = $courierdetails->api_key;
            $app_secret = $courierdetails->secret_key;

            $balance_url = 'https://portal.packzy.com/api/v1/get_balance';
            $header = [
                'Api-Key' => $app_key,
                'Secret-Key' => $app_secret,
                'Content-Type' => 'application/json',
            ];

            $balanceResponse = Http::withHeaders($header)->get($balance_url)->json();
            $balance = $balanceResponse['current_balance'] ?? 0;

            foreach ($order as $orderItem) {
                $track_url = 'https://portal.packzy.com/api/v1/status_by_trackingcode/'.$orderItem->tracking_number;
                $response = Http::withHeaders($header)->get($track_url)->json();

                if (isset($response['delivery_status'])) {
                    $orderItem->courier_status = $response['delivery_status'];
                    $orderItem->save();
                }
            }

            if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {

                $order = CouerierOrder::with('sale')->where('courier_id', $courier->id)->get();

            } else {
                $order = CouerierOrder::where('branch_id', Auth::user()->branch_id)->with('sale')->where('courier_id', $courier->id)->get();
            }

            $couriers = CourierManage::all();
            $courier_name = $courier->courier_name;

            return view('pos.CourierManage.steadfastorder', compact(
                'order', 'courier', 'balance', 'couriers', 'courier_name', 'totalToday', 'pendingToday'
            ));
        }
    }

    public function courierOrderFilter(Request $request)
    {
        $filter_type = $request->filter_type;
        if ($filter_type == 'today') {
            if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
                $total_order = CouerierOrder::whereDate('created_at', Carbon::today())->count();
                $pending = CouerierOrder::whereDate('created_at', Carbon::today())->where('status', 'pending')->count();
                $shipment_order = CouerierOrder::whereDate('created_at', Carbon::today())->where('status', 'processing')->count();
                $today_completed_order = CouerierOrder::whereDate('created_at', Carbon::today())->where('status', 'completed')->count();
            } else {
                $total_order = CouerierOrder::whereDate('created_at', Carbon::today())->where('branch_id', Auth::user()->branch_id)->count();
                $pending = CouerierOrder::whereDate('created_at', Carbon::today())->where('status', 'pending')->where('branch_id', Auth::user()->branch_id)->count();
                $shipment_order = CouerierOrder::whereDate('created_at', Carbon::today())->where('status', 'processing')->where('branch_id', Auth::user()->branch_id)->count();
                $today_completed_order = CouerierOrder::whereDate('created_at', Carbon::today())->where('status', 'completed')->where('branch_id', Auth::user()->branch_id)->count();
            }
        } elseif ($filter_type == 'month') {
            if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
                $total_order = CouerierOrder::whereMonth('created_at', Carbon::now()->month)->count();
                $pending = CouerierOrder::whereMonth('created_at', Carbon::now()->month)->where('status', 'pending')->count();
                $shipment_order = CouerierOrder::whereMonth('created_at', Carbon::now()->month)->where('status', 'processing')->count();
                $today_completed_order = CouerierOrder::whereMonth('created_at', Carbon::now()->month)->where('status', 'completed')->count();
            } else {
                $total_order = CouerierOrder::whereMonth('created_at', Carbon::now()->month)->where('branch_id', Auth::user()->branch_id)->count();
                $pending = CouerierOrder::whereMonth('created_at', Carbon::now()->month)->where('status', 'pending')->where('branch_id', Auth::user()->branch_id)->count();
                $shipment_order = CouerierOrder::whereMonth('created_at', Carbon::now()->month)->where('status', 'processing')->where('branch_id', Auth::user()->branch_id)->count();
                $today_completed_order = CouerierOrder::whereMonth('created_at', Carbon::now()->month)->where('status', 'completed')->where('branch_id', Auth::user()->branch_id)->count();
            }
        } elseif ($filter_type == 'year') {
            if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
                $total_order = CouerierOrder::whereYear('created_at', Carbon::now()->year)->count();
                $pending = CouerierOrder::whereYear('created_at', Carbon::now()->year)->where('status', 'pending')->count();
                $shipment_order = CouerierOrder::whereYear('created_at', Carbon::now()->year)->where('status', 'processing')->count();
                $today_completed_order = CouerierOrder::whereYear('created_at', Carbon::now()->year)->where('status', 'completed')->count();
            } else {
                $total_order = CouerierOrder::whereYear('created_at', Carbon::now()->year)->where('branch_id', Auth::user()->branch_id)->count();
                $pending = CouerierOrder::whereYear('created_at', Carbon::now()->year)->where('status', 'pending')->where('branch_id', Auth::user()->branch_id)->count();
                $shipment_order = CouerierOrder::whereYear('created_at', Carbon::now()->year)->where('status', 'processing')->where('branch_id', Auth::user()->branch_id)->count();
                $today_completed_order = CouerierOrder::whereYear('created_at', Carbon::now()->year)->where('status', 'completed')->where('branch_id', Auth::user()->branch_id)->count();
            }
        }

        return response()->json([
            'status' => 200,
            'total_order' => $total_order,
            'pending' => $pending,
            'shipment_order' => $shipment_order,
            'today_completed_order' => $today_completed_order,
        ]);
    }

    public function courierWiseFilterOrder(Request $request)
    {

        $courier_id = $request->courier_id;
        $filter_type = $request->filter_type;

        if ($filter_type == 'today') {
            if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
                $total_order = CouerierOrder::whereDate('created_at', Carbon::today())->where('courier_id', $courier_id)->count();
                $pending = CouerierOrder::whereDate('created_at', Carbon::today())->where('status', 'pending')->where('courier_id', $courier_id)->count();
                $shipment_order = CouerierOrder::whereDate('created_at', Carbon::today())->where('status', 'processing')->where('courier_id', $courier_id)->count();
                $today_completed_order = CouerierOrder::whereDate('created_at', Carbon::today())->where('status', 'completed')->where('courier_id', $courier_id)->count();
            } else {
                $total_order = CouerierOrder::whereDate('created_at', Carbon::today())->where('branch_id', Auth::user()->branch_id)->where('courier_id', $courier_id)->count();

                $pending = CouerierOrder::whereDate('created_at', Carbon::today())->where('status', 'pending')->where('branch_id', Auth::user()->branch_id)->where('courier_id', $courier_id)->count();
                $shipment_order = CouerierOrder::whereDate('created_at', Carbon::today())->where('status', 'processing')->where('branch_id', Auth::user()->branch_id)->where('courier_id', $courier_id)->count();
                $today_completed_order = CouerierOrder::whereDate('created_at', Carbon::today())->where('status', 'completed')->where('branch_id', Auth::user()->branch_id)->where('courier_id', $courier_id)->count();
            }
        } elseif ($filter_type == 'month') {
            if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
                $total_order = CouerierOrder::whereMonth('created_at', Carbon::now()->month)->where('courier_id', $courier_id)->count();
                $pending = CouerierOrder::whereMonth('created_at', Carbon::now()->month)->where('status', 'pending')->where('courier_id', $courier_id)->count();
                $shipment_order = CouerierOrder::whereMonth('created_at', Carbon::now()->month)->where('status', 'processing')->where('courier_id', $courier_id)->count();
                $today_completed_order = CouerierOrder::whereMonth('created_at', Carbon::now()->month)->where('status', 'completed')->where('courier_id', $courier_id)->count();
            } else {
                $total_order = CouerierOrder::whereMonth('created_at', Carbon::now()->month)->where('branch_id', Auth::user()->branch_id)->where('courier_id', $courier_id)->count();
                $pending = CouerierOrder::whereMonth('created_at', Carbon::now()->month)->where('status', 'pending')->where('branch_id', Auth::user()->branch_id)->where('courier_id', $courier_id)->count();
                $shipment_order = CouerierOrder::whereMonth('created_at', Carbon::now()->month)->where('status', 'processing')->where('branch_id', Auth::user()->branch_id)->where('courier_id', $courier_id)->count();
                $today_completed_order = CouerierOrder::whereMonth('created_at', Carbon::now()->month)->where('status', 'completed')->where('branch_id', Auth::user()->branch_id)->where('courier_id', $courier_id)->count();
            }
        } elseif ($filter_type == 'year') {
            if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
                $total_order = CouerierOrder::whereYear('created_at', Carbon::now()->year)->where('courier_id', $courier_id)->count();
                $pending = CouerierOrder::whereYear('created_at', Carbon::now()->year)->where('status', 'pending')->where('courier_id', $courier_id)->count();
                $shipment_order = CouerierOrder::whereYear('created_at', Carbon::now()->year)->where('status', 'processing')->where('courier_id', $courier_id)->count();
                $today_completed_order = CouerierOrder::whereYear('created_at', Carbon::now()->year)->where('status', 'completed')->where('courier_id', $courier_id)->count();
            } else {
                $total_order = CouerierOrder::whereYear('created_at', Carbon::now()->year)->where('branch_id', Auth::user()->branch_id)->where('courier_id', $courier_id)->count();
                $pending = CouerierOrder::whereYear('created_at', Carbon::now()->year)->where('status', 'pending')->where('branch_id', Auth::user()->branch_id)->where('courier_id', $courier_id)->count();
                $shipment_order = CouerierOrder::whereYear('created_at', Carbon::now()->year)->where('status','processing')->where('branch_id',Auth::user()->branch_id)->where('courier_id',$courier_id)->count();
                $today_completed_order = CouerierOrder::whereYear('created_at', Carbon::now()->year)->where('status','completed')->where('branch_id',Auth::user()->branch_id)->where('courier_id',$courier_id)->count();
            }
        }

        return response()->json([
            'status' => 200,
            'total_order' => $total_order,
            'pending' => $pending,
            'shipment_order' => $shipment_order,
            'today_completed_order' => $today_completed_order,
        ]);

    }
}
