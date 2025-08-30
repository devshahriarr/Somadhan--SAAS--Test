<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\PosSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PosSettingsController extends Controller
{
    // pos settings add function
    public function PosSettingsAdd(Request $request)
    {
        $allData = PosSetting::whereId(1)->first();

        return view('pos.pos_settings.add_pos_settings', compact('allData'));
    } //

    // mode swtich function
    public function switch_mode(Request $request)
    {
        if ($request->has('dark_mode')) {
            $mdVal = 2;
        } else {
            $mdVal = 1;
        }
        //  dd($mdVal);
        $PosSetting = PosSetting::all()->first();
        $PosSetting->dark_mode = $mdVal;
        $PosSetting->update();

        return back();
    } //

    // upodate settings function
    public function PosSettingsStore(Request $request)
    {
        $request->validate([
            'logo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'company' => 'required',
        ]);

        $settingId = $request->input('setting_id');
        $values = $request->only(['company', 'email', 'facebook', 'header_text', 'footer_text', 'phone', 'address']);

        $values['dark_mode'] = $request->has('dark_mode') ? 2 : 1;

        if ($request->hasFile('logo')) {
            $imageName = rand().'.'.$request->logo->extension();
            $request->logo->move(public_path('uploads/pos_setting/'), $imageName);
            $values['logo'] = 'uploads/pos_setting/'.$imageName;
        }

        PosSetting::updateOrCreate(['id' => $settingId], $values);

        $company = Company::updateOrCreate(
            ['name' => $request->input('company')],
            [
                'slug' => Str::slug($request->input('company')),
                'logo' => $values['logo'] ?? null,
                'status' => 1,
            ]
        );

        return redirect()->back()->with(['message' => 'Settings updated successfully!', 'alert-type' => 'info']);
    }

    // pos settings invoice 1
    public function PosSettingsInvoice()
    {
        return view('pos.pos_settings.invoice1');
    }

    // pos settings invoice 2
    public function PosSettingsInvoice2()
    {
        return view('pos.pos_settings.invoice2');
    }

    // pos settings invoice 3
    public function PosSettingsInvoice3()
    {
        return view('pos.pos_settings.invoice3');
    }

    // pos settings invoice 4
    public function PosSettingsInvoice4()
    {
        return view('pos.pos_settings.invoice4');
    }

    public function invoiceSettings()
    {
        $allData = PosSetting::whereId(1)->first();

        return view('pos.pos_settings.invoice-settings', compact('allData'));
    }

    public function invoiceSettingsStore(Request $request)
    {
        $settingId = $request->input('setting_id');
        $values = $request->only(['invoice_logo_type', 'invoice_type', 'barcode_type']);

        PosSetting::where('id', $settingId)->update($values);

        return redirect()->back()->with(['message' => 'Print Settings updated successfully!', 'alert-type' => 'info']);
    }

    public function saleSettings()
    {
        $allData = PosSetting::whereId(1)->first();

        return view('pos.pos_settings.sale-settings', compact('allData'));
    }

    public function saleSettingsUpdate(Request $request)
    {
        $checkboxFields = ['auto_genarate_invoice', 'discount', 'tax', 'sale_with_low_price', 'sale_commission', 'barcode', 'via_sale', 'selling_price_edit', 'selling_price_update', 'warranty', 'party_ways_rate_kit', 'sale_without_stock', 'sale_hands_on_discount', 'sale_page', 'make_invoice_print'];
        $values = $request->only(['sale_price_type']);

        $settingId = $request->input('setting_id');
        foreach ($checkboxFields as $field) {
            $values[$field] = $request->has($field) ? 1 : 0;
        }

        PosSetting::where('id', $settingId)->update($values);

        return redirect()->back()->with(['message' => 'Sale Settings updated successfully!', 'alert-type' => 'info']);
    }

    public function purchaseSettings()
    {
        $allData = PosSetting::whereId(1)->first();

        return view('pos.pos_settings.purchase', compact('allData'));
    }

    public function purchaseSettingsUpdate(Request $request)
    {
        $settingId = $request->input('setting_id');
        $values = [
            'purchase_price_edit' => $request->has('purchase_price_edit') ? 1 : 0,
            'purchase_price_update' => $request->has('purchase_price_update') ? 1 : 0,
            'purchase_individual_product_discount' => $request->has('purchase_individual_product_discount') ? 1 : 0,
            'purchase_hands_on_discount' => $request->has('purchase_hands_on_discount') ? 1 : 0,
            'purchase_page' => $request->has('purchase_page') ? 1 : 0,
        ];

        PosSetting::where('id', $settingId)->update($values);

        return redirect()->back()->with(['message' => 'Purchase Settings updated successfully!', 'alert-type' => 'info']);
    }

    public function productStockSettings()
    {
        $allData = PosSetting::whereId(1)->first();

        return view('pos.pos_settings.product-stock', compact('allData'));
    }

    public function productStockSettingsUpdate(Request $request)
    {
        $settingId = $request->input('setting_id');
        $checkboxFields = ['manufacture_date', 'expiry_date', 'bulk_update',  'low_stock_alert', 'product_set_low_stock', 'color_view', 'size_view', 'multiple_category'];
        $values = $request->only(['low_stock']);

        foreach ($checkboxFields as $field) {
            $values[$field] = $request->has($field) ? 1 : 0;
        }

        PosSetting::where('id', $settingId)->update($values);

        return redirect()->back()->with(['message' => 'Product & Update Settings updated successfully!', 'alert-type' => 'info']);
    }

    public function systemSettings()
    {
        $allData = PosSetting::whereId(1)->first();

        return view('pos.pos_settings.system', compact('allData'));
    }

    public function systemSettingsUpdate(Request $request)
    {
        $settingId = $request->input('setting_id');
        $checkboxFields = ['sms_manage', 'invoice_payment', 'link_invoice_payment',  'due_reminder', 'affliate_program', 'sale_commission', 'courier_management', 'drag_and_drop', 'elastic_search'];
        foreach ($checkboxFields as $field) {
            $values[$field] = $request->has($field) ? 1 : 0;
        }

        PosSetting::where('id', $settingId)->update($values);

        return redirect()->back()->with(['message' => 'System Settings updated successfully!', 'alert-type' => 'info']);
    }

    public function smsSettings()
    {
        $allData = PosSetting::whereId(1)->first();

        return view('pos.pos_settings.sms', compact('allData'));
    }

    public function smsSettingsUpdate(Request $request)
    {
        $settingId = $request->input('setting_id');
        $checkboxFields = ['sale_sms', 'transaction_sms', 'profile_payment_sms',  'link_invoice_payment_sms'];
        foreach ($checkboxFields as $field) {
            $values[$field] = $request->has($field) ? 1 : 0;
        }

        PosSetting::where('id', $settingId)->update($values);

        return redirect()->back()->with(['message' => 'SMS Settings updated successfully!', 'alert-type' => 'info']);
    }
}
