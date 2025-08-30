<?php

namespace App\Providers;

use App\Models\PosSetting;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $settings = PosSetting::first();
                $siteTitle = $settings ? $settings->company : 'EIL POS || Eclipse Intellitech Limited POS Software';
                $logo = $settings->logo;
                $facebook = $settings->facebook;
                $address = $settings->address;
                $header = $settings->header_text;
                $phone = $settings->phone;
                $email = $settings->email;
                $invoice_logo_type = $settings->invoice_logo_type;
                $invoice_type = $settings->invoice_type;
                $barcode_type = $settings->barcode_type;
                $barcode = $settings->barcode;
                $discount = $settings->discount;
                $tax = $settings->tax;
                $selling_price_edit = $settings->selling_price_edit;
                $purchase_price_edit = $settings->purchase_price_edit;
                $warranty_status = $settings->warranty;
                $invoice_payment = $settings->invoice_payment;
                $via_sale = $settings->via_sale;
                $sale_price_type = $settings->sale_price_type;
                $link_invoice_payment = $settings->link_invoice_payment;
                $manufacture_date = $settings->manufacture_date;
                $expiry_date = $settings->expiry_date;
                $selling_price_update = $settings->selling_price_update;
                $bulk_update = $settings->bulk_update;
                $low_stock_alert = $settings->low_stock_alert;
                $auto_genarate_invoice = $settings->auto_genarate_invoice;
                $product_set_low_stock = $settings->product_set_low_stock;
                $color_view = $settings->color_view;
                $size_view = $settings->size_view;
                $purchase_hands_on_discount = $settings->purchase_hands_on_discount;
                $purchase_individual_product_discount = $settings->purchase_individual_product_discount;
                $party_ways_rate_kit = $settings->party_ways_rate_kit;
                $drag_and_drop = $settings->drag_and_drop;
                $affliate_program = $settings->affliate_program;
                $sale_with_low_price = $settings->sale_with_low_price;
                $sale_without_stock = $settings->sale_without_stock;
                $sale_hands_on_discount = $settings->sale_hands_on_discount;
                $courier_management = $settings->courier_management;
                $sale_page = $settings->sale_page;
                $purchase_page = $settings->purchase_page;
                $make_invoice_print = $settings->make_invoice_print;
                $multiple_category = $settings->multiple_category;
                $elastic_search = $settings->elastic_search;
                $view->with([
                    'siteTitle' => $siteTitle,
                    'logo' => $logo,
                    'header' => $header,
                    'address' => $address,
                    'facebook' => $facebook,
                    'phone' => $phone,
                    'email' => $email,
                    'invoice_logo_type' => $invoice_logo_type,
                    'invoice_type' => $invoice_type,
                    'barcode_type' => $barcode_type,
                    'barcode' => $barcode,
                    'discount' => $discount,
                    'tax' => $tax,
                    'selling_price_edit' => $selling_price_edit,
                    'purchase_price_edit' => $purchase_price_edit,
                    'via_sale' => $via_sale,
                    'warranty_status' => $warranty_status,
                    'invoice_payment' => $invoice_payment,
                    'sale_price_type' => $sale_price_type,
                    'link_invoice_payment' => $link_invoice_payment,
                    'manufacture_date' => $manufacture_date,
                    'expiry_date' => $expiry_date,
                    'selling_price_update' => $selling_price_update,
                    'bulk_update' => $bulk_update,
                    'auto_genarate_invoice' => $auto_genarate_invoice,
                    'low_stock_alert' => $low_stock_alert,
                    'product_set_low_stock' => $product_set_low_stock,
                    'size_view' => $size_view,
                    'color_view' => $color_view,
                    'purchase_hands_on_discount' => $purchase_hands_on_discount,
                    'purchase_individual_product_discount' => $purchase_individual_product_discount,
                    'party_ways_rate_kit' => $party_ways_rate_kit,
                    'drag_and_drop' => $drag_and_drop,
                    'affliate_program' => $affliate_program,
                    'sale_with_low_price' => $sale_with_low_price,
                    'sale_without_stock' => $sale_without_stock,
                    'sale_hands_on_discount' => $sale_hands_on_discount,
                    'courier_management' => $courier_management,
                    'sale_page' => $sale_page,
                    'purchase_page' => $purchase_page,
                    'make_invoice_print' => $make_invoice_print,
                    'multiple_category' => $multiple_category,
                    'elastic_search' => $elastic_search,
                ]);
            }
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
