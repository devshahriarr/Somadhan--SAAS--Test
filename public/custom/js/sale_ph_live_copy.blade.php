@extends('master')
@section('title', '| Sale')
@section('admin')
    <style>
        .row {
            --bs-gutter-x: 0.5rem;
        }

        .main-wrapper .page-wrapper .page-content {
            flex-grow: 1;
            padding: 0px;
            margin-top: 61px;
        }
        #product_search {
            border: 1px solid #0d6efd;
            border-radius: 5px;
            padding: 10px;
            width: 100%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        #product_search:focus {
            border-color: #0056b3;
            outline: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .product_search_result {
            position: absolute;
            background-color: #060C17;
            width: 45%;
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #0056b3;
            border-top: 10;
            margin-top: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .product_search_result table {
            width: 100%;
            /* margin: 0; */
            border-collapse: collapse;
        }

        .product_search_result table tbody tr {
            border-bottom: 1px solid #0056b3;
            padding: 10px;

        }

        .product_search_result table tbody tr:hover {
            background-color: #0056b3;
            cursor: pointer;

        }

        .product_search_result table tbody tr td {
            padding: 8px 12px;
        }

        .product_search_result {
            display: none;
        }

        .product_search_result.active {
            display: block;
        }

        /* Chrome, Safari, Edge, Opera */
        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

        /* ///drag & drop// */
        .responsive-table {
            overflow: auto;
        }

        table {
            width: 100%;
            border-spacing: 0;
            border-collapse: collapse;
            white-space: nowrap;
        }

        .fa-barss {
            cursor: move;
        }

        .highlight {
            background-color: #0d7dfd!important;
            color: #bd2323;
            font-size: 16px;
        }

        .table-head-style {
            font-size: 10px !important;
            margin: 0 !important;
            padding: 4px 0 !important;
            text-align: center !important;
        }

        /* //Canvas// */


        /* Offcanvas Styling */
        .offcanvas {
            position: fixed;
            top: 0;
            right: -250px;
            /* Hidden off-screen */
            width: 250px;
            height: 100%;
            background-color: #f8f8f8;
            /* White background */
            box-shadow: -4px 0 6px rgba(0, 0, 0, 0.1);
            /* Shadow for depth */
            transition: right 0.3s ease;
            /* Smooth slide */
            z-index: 1000;
            border-left: 1px solid #e0e0e0;
            /* Subtle border */
        }

        /* Offcanvas Header */
        .offcanvas-header {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Offcanvas Body */
        .offcanvas-body {
            padding: 15px;
        }

        /* Show Offcanvas and Blur Main Content */
        #offcanvas-toggle:checked~.main-wrapper .page-wrapper .page-content {
            filter: blur(5px);
            /* Blur main content */
        }

        #offcanvas-toggle:checked~.offcanvas {
            right: 0;
            /* Slide in */
        }

        /* Toggle Button */
        .offcanvas-toggle-btn {
            display: inline-block;
            padding: 8px 15px;
            background-color: #0d6efd;
            /* Matches your primary button */
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Subtle shadow */
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .offcanvas-toggle-btn:hover {
            background-color: #0056b3;
            /* Darker on hover */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
            /* Stronger shadow on hover */
        }

        /* Close Button */
        .offcanvas-close-btn {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            padding: 5px 10px;
            color: #333;
            transition: color 0.3s ease;
        }

        .offcanvas-close-btn:hover {
            color: #0d6efd;
            /* Matches primary color on hover */
        }

        /* Add transition to existing .page-content for smooth blur */
        .main-wrapper .page-wrapper .page-content {
            transition: filter 0.3s ease;
        }

    </style>


    @php
        $payments = App\Models\Bank::where('branch_id', Auth::user()->branch_id)
            ->latest()
            ->get();

        if ($tax === 1) {
            $taxPercentage = App\Models\Tax::first()->percentage ?? null;
        } else {
            $taxPercentage = null;
        }

        $affiliateProgram = App\Models\PosSetting::where('affliate_program', 1)->first();
    @endphp
<input type="checkbox" id="offcanvas-toggle" style="display: none;">
<label for="offcanvas-toggle" style="position: fixed; font-size:20px; top: 65px; right: 20px; z-index: 1000; cursor: pointer;">
    <i class="fas fa-cog text-primary"></i>
</label>



    <div class="{{ $invoice_payment == 1 ? 'col-lg-12' : 'col-lg-12' }} stretch-card mb-1">
        <div class="card">
            <div class="card-body px-4 py-2">
                <div class="row">
                    @if ($barcode == 1)
                        <div class="mb-2 col-md-12">
                            <label for="ageSelect" class="form-label">Barcode</label>
                            <div class="input-group">
                                <div class="input-group-text" id="btnGroupAddon"><i class="fa-solid fa-barcode"></i>
                                </div>
                                <input type="text" class="form-control barcode_input" placeholder="Barcode"
                                    aria-label="Input group example" aria-describedby="btnGroupAddon" style="height:30px">
                            </div>
                        </div>
                    @endif

                    <div class="mb-2 col-md-6">
                        <label for="date" class="form-label">Date</label>
                        <div class="input-group flatpickr me-2 mb-2 mb-md-0" id="dashboardDate">
                            <span class="input-group-text input-group-addon bg-transparent border-primary"
                                style="height:30px" data-toggle><i data-feather="calendar" class="text-primary"></i></span>
                            <input type="text" name="date"
                                class="form-control bg-transparent border-primary purchase_date" placeholder="Select date"
                                data-input style="height:30px">
                        </div>
                        <span class="text-danger purchase_date_error"></span>
                    </div>

                    @if ($affiliateProgram)
                        <div class="mb-1 col-md-6">
                            <label class="form-label">Affiliators</label>
                            @php
                                $affiliators = \App\Models\Affiliator::where('branch_id', Auth::user()->branch_id)
                                    ->whereNull('user_id')
                                    ->get();

                            @endphp
                            <div class="d-flex g-3">
                                <select class="js-example-basic-multiple form-select affiliator_select" style="height:30px"
                                    multiple="multiple" data-width="100%" onchange="errorRemove(this);">
                                    @foreach ($affiliators as $affiliator)
                                        <option value="{{ $affiliator->id }}">{{ $affiliator->name }}</option>
                                    @endforeach
                                </select>

                                <span class="text-danger product_select_error"></span>
                            </div>
                        </div>
                    @endif
                    @if ($auto_genarate_invoice === 0)
                        <div class="mb-1 col-md-6">
                            <label class="form-label">Generate Invoice Number</label>
                            <input type="text" class="generate_invoice form form-control" style="height:30px">
                            <span class="text-danger generate_invoice_error"></span>
                        </div>
                    @else
                        <input type="hidden" class="generate_invoice form form-control">
                    @endif

                    @if ($barcode == 1)
                        @if ($auto_genarate_invoice == 1 && $affliate_program == 0)
                            <div class="mb-1 col-md-6">
                            @elseif ($auto_genarate_invoice == 1 && $affliate_program == 1)
                                <div class="mb-1 col-md-6">
                                @else
                                <div class="mb-1 col-md-12">
                        @endif
                        <label for="password" class="form-label">Customer</label>
                        <div class="d-flex g-3">
                            <select class="js-example-basic-single form-select select-customer" data-width="100%"
                                onchange="errorRemove(this);" style="height:30px">

                            </select>
                            <span class="text-danger select-customer_error"></span>
                            <button class="btn btn-primary ms-2" data-bs-toggle="modal"
                                data-bs-target="#customerModal">Add</button>

                        </div>
                </div>

                {{-- <div class="mb-1 col-md-12">
                        <label class="form-label">Product</label>
                        <div class="d-flex g-3">
                            <select class="js-example-basic-single form-select product_select view_product"
                                data-width="100%" onchange="errorRemove(this);" >
                            </select>

                            <span class="text-danger product_select_error"></span>
                                    @if ($via_sale == 1)
                                        <button class="btn btn-primary ms-2 w-25" data-bs-toggle="modal"
                                            data-bs-target="#viaSellModal">Via Sell</button>
                                    @endif
                        </div>
                    </div> --}}
            @else
                @if ($auto_genarate_invoice == 1 && $affliate_program == 0)
                    <div class="mb-1 col-md-6">
                    @elseif ($auto_genarate_invoice == 0 && $affliate_program == 1)
                        <div class="mb-1 col-md-6">
                        @else
                            <div class="mb-1 col-md-12">
                @endif
                <label for="password" class="form-label">Customer</label>
                <div class="d-flex g-3">
                    <select class="js-example-basic-single form-select select-customer" data-width="100%"
                        onchange="errorRemove(this);" style="height:30px">

                    </select>
                    <span class="text-danger select-customer_error"></span>
                    <button class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#customerModal">Add</button>

                </div>


                {{-- //////////////////Canvas///////////////////////// --}}

                {{-- <div class="mb-1 col-md-12">
                    <label class="form-label">Product</label>
                    <div class="d-flex g-3">
                        <select class="js-example-basic-single form-select product_select view_product" data-width="100%"
                            onchange="errorRemove(this);">
                        </select>
                        <span class="text-danger product_select_error"></span>
                        @if ($via_sale == 1)
                            <button class="btn btn-primary ms-2 w-25" data-bs-toggle="modal"
                                data-bs-target="#viaSellModal">Via Sell</button>
                        @endif
                    </div>
                </div> --}}
                @endif
         @if ($barcode == 1)
        @else
        </div>
         @endif
        </div>
    </div>
      @if ($invoice_payment == 1)
        <div class="col-lg-12  stretch-card">
            <div class="card  m-0 p-0 ">
                <div class="card-body  my-0 py-0  ">

                    <div class="row show_customer_details">

                        <div class="col-lg-6 d-flex   align-items-center">
                            <label for="exampleInputUsername2" class="col-form-label">Previous Due</label>
                            <span> : </span>
                            <label for="exampleInputUsername2" class="col-form-label px-4"><b>
                                </b> 00</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    </div>



    </div>

    {{--
    @dd(session('success'))
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif --}}
    {{-- table  --}}

     <div class="row mb-5">
        <div class="col-lg-12 mb-1  stretch-card">
            <div class="card">
                <div class="card-body px-4 py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-title">Items</h6>
                        <th> <button type="button" class="btn btn-primary btn-sm" id="addRow">+</button></th>
                    </div>

                    <div id="">
                        <table class="table salesTable " id="sortable">

                            <thead>
                                <tr class="ui-state-default">
                                    <th>SL</th>
                                    <th class="table-head-style">Product</th>
                                    @if ($color_view == 1)
                                        <th class="table-head-style">Color</th>
                                    @endif
                                    @if ($size_view == 1)
                                        <th class="table-head-style">Size</th>
                                    @endif
                                    <th class="table-head-style">Price</th>
                                    <th class="table-head-style">Qty</th>
                                    @if ($warranty_status == 1)
                                        <th class="table-head-style">Warranty</th>
                                    @endif
                                    @if ($sale_hands_on_discount == 1)
                                        <th class="table-head-style">Discount</th>
                                    @endif
                                    <th class="table-head-style">Sub Total</th>
                                    <th class="table-head-style">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="showData">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="invoice-footer-container mt-5" style="font-size: 12px">
    <div class="card fixed-footer px-2 py-1">
        <div class="card-body px-2 py-2">
            <div class="d-flex flex-nowrap align-items-center justify-content-start gap-3 overflow-auto">
                <!-- Product Total -->
                <div class="d-flex align-items-center nowrap-item">
                    <span class="me-2">Total:</span>
                    <input type="number" class="form-control total border-0 text-end" style="width: 70px;" name="total" readonly value="0.00" />
                </div>

                @if ($discount === 1)
                <!-- Discount -->
                <div class="d-flex align-items-center nowrap-item">
                    <span class="me-2">Discount:</span>
                    <select name="sale_discount_type" class="form-select form-select-sm sale_discount_type" style="width: 65px;">
                        <option value="fixed">৳</option>
                        <option value="percentage">%</option>
                    </select>
                    <input type="number" class="form-control form-control-sm handsOnDiscount text-end" style="width: 60px;" name="" value="0" />
                </div>
                @endif

                @if ($tax == 1)
                <!-- Tax -->
                <div class="d-flex align-items-center nowrap-item">
                    <span class="me-2">Tax ({{ $taxPercentage }}%):</span>
                    <input type="text" class="form-control tax border-0 text-end" style="width: 70px;" name="" value="0.00" readonly />
                </div>
                @endif

                <!-- Sub Total -->
                <div class="d-flex align-items-center nowrap-item">
                    <span class="me-2">Sub Total:</span>
                    <input type="number" class="form-control invoice_total border-0 text-end" style="width: 70px;" name="grand_total" readonly value="0.00" />
                </div>

                @if ($invoice_payment === 0)
                <!-- Previous Due -->
                <div class="d-flex align-items-center nowrap-item">
                    <span class="me-2">Due:</span>
                    <input type="number" class="form-control previous_due border-0 text-end" style="width: 70px;" name="previous_due" readonly value="0.00" />
                </div>

                <!-- Grand Total -->
                <div class="d-flex align-items-center nowrap-item">
                    <span class="me-2">Grand Total:</span>
                    <input type="number" class="form-control grandTotal border-0 text-end" style="width: 70px;" name="" readonly value="0.00" />
                </div>
                @else
                <input type="hidden" class="grandTotal" name="" readonly value="0.00" />
                @endif

                <!-- Pay Amount -->
                <div class="d-flex align-items-center nowrap-item">
                    <span class="me-2">Pay:</span>
                    <input class="form-control form-control-sm total_payable text-end" style="width: 70px; border:1px solid #1761D1" minlength="0" name="total_payable" type="number" value="" />
                </div>
                 <div class="d-flex align-items-center nowrap-item">
                        <span class="me-2 due_text ">
                            Total Due :
                        </span>
                            <input type="number" class="form-control total_due border-0 text-end"  style="width: 70px;" name=""
                                readonly value="0.00" />
                    </div>
                <!-- Transaction Method -->
                <div class="d-flex align-items-center nowrap-item">
                    <span class="me-2">Method:</span>
                    <select class="form-select form-select-sm payment_method" style="width: 120px;" data-width="100%">
                        @if ($payments->count() > 0)
                            @foreach ($payments as $payemnt)
                                <option value="{{ $payemnt->id }}">{{ $payemnt->name }}</option>
                            @endforeach
                        @else
                            <option selected disabled>Add Method</option>
                        @endif
                    </select>
                </div>

                <!-- Buttons -->
                <div class="d-flex align-items-center gap-2 nowrap-item">
                    <button class="btn btn-sm btn-primary payment_btn"><i class="fa-solid fa-money-check-dollar"></i> Pay</button>
                    <button class="btn btn-sm btn-secondary draft_invoice"><i class="fa-solid fa-file-lines"></i> Draft</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .page-content{
        position: relative!important;
        min-height: 80vh;

    }
.invoice-footer-container {
    position: absolute;
    bottom: 0;
    left: 0; /* Adjust to match your sidebar width */
    background: white;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
    width: 100%;
}

.fixed-footer {
    width: 100%;
    margin: 0;
}

.nowrap-item {
    flex: 0 0 auto;
}

.btn-sm {
    padding: 0.3rem 0.6rem;
    font-size: 0.85rem;
}

/* Custom scrollbar for better visibility */
.d-flex.flex-nowrap::-webkit-scrollbar {
    height: 4px;
}

.d-flex.flex-nowrap::-webkit-scrollbar-thumb {
    background: #ddd;
    border-radius: 10px;
}

@media (max-width: 992px) {
    .invoice-footer-container {
        left: 0;
    }
}
</style>

    {{-- ////Canvas///// --}}
    @php
        $mode = App\models\PosSetting::all()->first();
    @endphp
    <div class="offcanvas">
        <label for="offcanvas-toggle" class="offcanvas-close-btn">Close</label>

        <form id="myValidForm" action="{{ route('sale.settings.update') }}" method="post"
            class="p-3 bg-white shadow-md rounded-lg space-y-6">
            @csrf
            <input type="hidden" name="setting_id" value="1">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <h4 class="text-primary">Sale Settings</h4>
                <hr>
                 <div class="col-sm-6 d-none">
                <div  class="mb-3 form-valid-groups">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox"
                            {{ $mode->sale_page == 1 ? 'checked' : '' }}
                            name="sale_page" role="switch" id="flexSwitchCheckDefault12">
                        <label class="form-check-label" for="flexSwitchCheckDefault12">Sale Page
                            </label>
                    </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <input class="form-check-input rounded-full" type="checkbox"
                        {{ $mode->make_invoice_print == 1 ? 'checked' : '' }} name="make_invoice_print"
                        id="flexSwitchInvoicePrint">
                    <label class="form-check-label" for="flexSwitchInvoicePrint">Make Invoice Print</label>
                </div>

                <div class="flex items-center space-x-2">
                    <input class="form-check-input rounded-full" type="checkbox"
                        {{ $mode->auto_genarate_invoice == 1 ? 'checked' : '' }} name="auto_genarate_invoice"
                        id="flexSwitchAutoInvoice">
                    <label class="form-check-label" for="flexSwitchAutoInvoice">Update Manual Invoice Number</label>
                </div>

                @if (Auth::user()->can('discount.promotion'))
                    <div class="flex items-center space-x-2">
                        <input class="form-check-input rounded-full" type="checkbox"
                            {{ $mode->discount == 1 ? 'checked' : '' }} name="discount" id="flexSwitchDiscount">
                        <label class="form-check-label" for="flexSwitchDiscount">Discount/Promotion</label>
                    </div>
                @endif

                <div class="flex items-center space-x-2">
                    <input class="form-check-input rounded-full" type="checkbox"
                        {{ $mode->sale_hands_on_discount == 1 ? 'checked' : '' }} name="sale_hands_on_discount"
                        id="flexSwitchHandsDiscount">
                    <label class="form-check-label" for="flexSwitchHandsDiscount">Sale Hands on Discount</label>
                </div>

                @if (Auth::user()->can('tax'))
                    <div class="flex items-center space-x-2">
                        <input class="form-check-input rounded-full" type="checkbox"
                            {{ $mode->tax == 1 ? 'checked' : '' }} name="tax" id="flexSwitchTax">
                        <label class="form-check-label" for="flexSwitchTax">Tax</label>
                    </div>
                @endif

                <div class="flex items-center space-x-2">
                    <input class="form-check-input rounded-full" type="checkbox"
                        {{ $mode->sale_with_low_price == 1 ? 'checked' : '' }} name="sale_with_low_price"
                        id="flexSwitchLowPrice">
                    <label class="form-check-label" for="flexSwitchLowPrice">Sale with Low Price</label>
                </div>

                <div class="flex items-center space-x-2">
                    <input class="form-check-input rounded-full" type="checkbox"
                        {{ $mode->sale_commission == 1 ? 'checked' : '' }} name="sale_commission"
                        id="flexSwitchCommission">
                    <label class="form-check-label" for="flexSwitchCommission">Sale Commission</label>
                </div>

                @if (Auth::user()->can('barcode'))
                    <div class="flex items-center space-x-2">
                        <input class="form-check-input rounded-full" type="checkbox"
                            {{ $mode->barcode == 1 ? 'checked' : '' }} name="barcode" id="flexSwitchBarcode">
                        <label class="form-check-label" for="flexSwitchBarcode">Barcode</label>
                    </div>
                @endif
                @if (Auth::user()->can('sale.price.edit'))
                    <div class="flex items-center space-x-2">
                        <input class="form-check-input rounded-full" type="checkbox"
                            {{ $mode->selling_price_edit == 1 ? 'checked' : '' }} name="selling_price_edit"
                            id="flexSwitchEditPrice">
                        <label class="form-check-label" for="flexSwitchEditPrice">Selling Price Edit</label>
                    </div>
                @endif

                @if (Auth::user()->can('sale.price.update'))
                    <div class="flex items-center space-x-2">
                        <input class="form-check-input rounded-full" type="checkbox"
                            {{ $mode->selling_price_update == 1 ? 'checked' : '' }} name="selling_price_update"
                            id="flexSwitchUpdatePrice">
                        <label class="form-check-label" for="flexSwitchUpdatePrice">Update Price from Sale</label>
                    </div>
                @endif

                @if (Auth::user()->can('warranty.satus'))
                    <div class="flex items-center space-x-2">
                        <input class="form-check-input rounded-full" type="checkbox"
                            {{ $mode->warranty == 1 ? 'checked' : '' }} name="warranty" id="flexSwitchWarranty">
                        <label class="form-check-label" for="flexSwitchWarranty">Warranty Status</label>
                    </div>
                @endif

                <div class="flex items-center space-x-2">
                    <input class="form-check-input rounded-full" type="checkbox"
                        {{ $mode->sale_without_stock == 1 ? 'checked' : '' }} name="sale_without_stock"
                        id="flexSwitchWithoutStock">
                    <label class="form-check-label" for="flexSwitchWithoutStock">Sale Without Stock</label>
                </div>

                @if (Auth::user()->can('sale.price.type'))
                    <div class="space-y-2">
                        <label class="form-label font-medium">Sale Price Type</label>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <input type="radio" class="form-check-input"
                                    {{ $mode->sale_price_type == 'b2b_price' ? 'checked' : '' }} id="sale_price_type_b2b"
                                    name="sale_price_type" value="b2b_price"
                                    {{ !empty($allData->id) && $allData->sale_price_type == 'b2b_price' ? 'checked' : '' }}>
                                <label for="sale_price_type_b2b" class="form-check-label">B2B Price</label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="radio" class="form-check-input" id="sale_price_type_b2c"
                                    {{ $mode->sale_price_type == 'b2c_price' ? 'checked' : '' }} name="sale_price_type"
                                    value="b2c_price"
                                    {{ !empty($allData->id) && $allData->sale_price_type == 'b2c_price' ? 'checked' : '' }}>
                                <label for="sale_price_type_b2c" class="form-check-label">B2C Price</label>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex items-center space-x-2">
                    <input class="form-check-input rounded-full" type="checkbox"
                        {{ $mode->party_ways_rate_kit == 1 ? 'checked' : '' }} name="party_ways_rate_kit"
                        id="flexSwitchRateKit">
                    <label class="form-check-label" for="flexSwitchRateKit">Party Wise Rate Kit</label>
                </div>
            </div>

            <div class="text-right mt-2">
                <button type="submit" class="btn btn-sm btn-primary px-4 py-2 rounded-lg">Save Changes</button>
            </div>
        </form>

    </div>

    <style>
        #printFrame {
            display: none;
            /* Hide the iframe */
        }
    </style>
    <iframe id="printFrame" src="" width="0" height="0"></iframe>
    <!-- customer Modal -->
    <div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="exampleModalScrollableTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Add Customer Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form class="customerForm row">
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Customer Name <span
                                    class="text-danger">*</span></label>
                            <input id="defaultconfig" class="form-control customer_name" maxlength="255" name="name"
                                type="text" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger customer_name_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Phone Number <span
                                    class="text-danger">*</span></label>
                            <input id="defaultconfig" class="form-control phone" maxlength="39" name="phone"
                                type="tel" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger phone_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Email</label>
                            <input id="defaultconfig" class="form-control email" maxlength="39" name="email"
                                type="email" onkeyup="errorRemove(this);">
                            <span class="text-danger email_error"></span>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Address</label>
                            <input id="defaultconfig" class="form-control address" maxlength="39" name="address"
                                type="text" onkeyup="errorRemove(this);">
                            <span class="text-danger address_error"></span>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Previous Due (আপনি কাস্টমার থেকে পাবেন)</label>
                                <input type="number" class="form-control" name="opening_receivable" placeholder="0.00"
                                    onkeyup="errorRemove(this);">
                                <span class="text-danger opening_receivable_error"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Oppenning Balance (আপনার থেকে কাস্টমার পাবেন)</label>
                                <input type="number" class="form-control" name="opening_payable" placeholder="0.00"
                                    onkeyup="errorRemove(this);">
                                <span class="text-danger opening_payable_error"></span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_new_customer">Save</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Varient Modal -->
    <div class="modal fade" id="varientModal" tabindex="-1" aria-labelledby="exampleModalScrollableTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Select Variants</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="show_variants row gx-2">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary add_all_variants">Add Variants</button>
                </div>
            </div>
        </div>
    </div>

    @if ($via_sale == 1)
        @php
            $viaProducts = App\Models\Variation::latest()->get();
            $suppliers = App\Models\Customer::where('party_type', 'supplier')->get();
            $colors = App\Models\Color::latest()->get();
            $sizes = App\Models\Psize::latest()->get();
            $categories = App\Models\Category::latest()->get();
        @endphp
        <!-- Via Sell -->
        <div class="modal fade" id="viaSellModal" tabindex="-1" aria-labelledby="exampleModalScrollableTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalScrollableTitle">Add Via Sell Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="btn-close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="viaSellForm row">
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">Product Name <span
                                        class="text-danger">*</span></label>
                                <input id="defaultconfig" class="form-control product_name" maxlength="255"
                                    name="name" type="text" onkeyup="toggleFields('input');"
                                    onkeydown="errorRemove(this);" onblur="toggleFields('input');">
                                <span class="text-danger product_name_error"></span>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="via_product_name" class="form-label">
                                    Via Product Select <span class="text-danger">*</span>
                                </label>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="w-75">
                                        <select class="js-choice form-select via_product_name " data-width="100%"
                                            name="name" id="viaProductSelect" onchange="toggleFields('select');">
                                            <option selected disabled>Select Via Product</option>
                                            @foreach ($viaProducts as $viaProduct)
                                                <option value="{{ $viaProduct->id }}">
                                                    {{ $viaProduct->product->name ?? '' }}
                                                    /
                                                    ({{ $viaProduct->variationSize->size ?? ($viaProduct->colorName->name ?? '') }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <button type="button" class="btn "
                                            onclick="resetViaProductSelect()">Unselect</button>
                                    </div>
                                </div>
                                <span class="text-danger via_product_name_error"></span>
                            </div>
                            <script>
                                const selectField = document.querySelector('.via_product_name');

                                function toggleFields(trigger) {
                                    const inputField = document.querySelector('.product_name');

                                    const sizeArea = document.querySelector('.via_size_area');
                                    const colorArea = document.querySelector('.via_color_area');
                                    const categoryArea = document.querySelector('.via_category_area');
                                    const sellPriceField = document.querySelector('.sell_price');
                                    const costPriceField = document.querySelector('.cost_price');

                                    if (trigger === 'input' && inputField.value.trim() !== '') {
                                        // If input is filled, disable the select field and clear its value
                                        selectField.value = '';
                                        selectField.disabled = true;
                                        sizeArea.classList.remove('d-none');
                                        colorArea.classList.remove('d-none');
                                        categoryArea.classList.remove('d-none');
                                        costPriceField.value = "";
                                        sellPriceField.value = "";
                                    } else if (trigger === 'select' && selectField.value !== '') {
                                        // If an option in select is chosen, disable the input field and clear its value
                                        inputField.value = '';
                                        inputField.disabled = true;
                                        sizeArea.classList.add('d-none');
                                        colorArea.classList.add('d-none');
                                        categoryArea.classList.add('d-none');
                                    } else {
                                        // Enable both fields if both are empty
                                        inputField.disabled = false;
                                        selectField.disabled = false;
                                        sizeArea.classList.remove('d-none');
                                        colorArea.classList.remove('d-none');
                                        categoryArea.classList.remove('d-none');
                                    }
                                }

                                function resetViaProductSelect() {
                                    const selectField = document.getElementById('viaProductSelect');
                                    selectField.value = ''; // Reset the value to default
                                    toggleFields('select'); // Optionally call the toggle function to handle dependent logic
                                }



                                selectField.addEventListener('change', function() {
                                    const id = this.value;
                                    $.ajax({
                                        url: "/variant/find/" + id,
                                        type: "GET",
                                        dataType: "JSON",
                                        success: function(res) {
                                            // console.log(res);
                                            let priceType = "{{ $sale_price_type }}";
                                            const sellPriceField = document.querySelector('.sell_price');
                                            const costPriceField = document.querySelector('.cost_price');
                                            const variant = res?.variant;


                                            const salePrice = priceType === "b2c_price" ? variant?.b2c_price : variant
                                                ?.b2b_price;

                                            costPriceField.value = variant?.cost_price;
                                            sellPriceField.value = salePrice;
                                        },
                                    });
                                });
                                // selectField.onchange = function() {
                                //     alert("ok");
                                // };
                            </script>
                            <div class="mb-3 col-md-4">
                                <label for="name" class="form-label">Sell Price <span
                                        class="text-danger">*</span></label>
                                <input id="defaultconfig" class="form-control sell_price" maxlength="39" name="price"
                                    type="number" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                                <span class="text-danger sell_price_error"></span>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="name" class="form-label">Cost Price <span
                                        class="text-danger">*</span></label>
                                <input id="defaultconfig" class="form-control cost_price" maxlength="39" name="cost"
                                    type="number" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                                <span class="text-danger cost_price_error"></span>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label for="name" class="form-label">Quantity <span
                                        class="text-danger">*</span></label>
                                <input id="defaultconfig" class="form-control via_quantity" maxlength="39"
                                    name="stock" type="number" onkeyup="errorRemove(this);"
                                    onblur="errorRemove(this);">
                                <span class="text-danger via_quantity_error"></span>
                            </div>
                            <div class="mb-3 col-md-4 via_category_area d-none">
                                <label for="name" class="form-label">Category <span
                                        class="text-danger">*</span></label>
                                <select class="js-choice form-select via_category" data-width="100%" name="category_id"
                                    onchange="errorRemove(this);">
                                    <option selected disabled value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name ?? '' }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger via_category_error"></span>
                            </div>
                            <div class="mb-3 col-md-4 via_size_area d-none">
                                <label for="name" class="form-label">Size <span class="text-danger">*</span></label>
                                <select class="js-choice form-select via_size" data-width="100%" name="size"
                                    onchange="errorRemove(this);">
                                    <option selected disabled value="">Select Size</option>
                                    @foreach ($sizes as $size)
                                        <option value="{{ $size->id }}">{{ $size->size ?? '' }}</option>
                                    @endforeach
                                </select>

                                <span class="text-danger via_size_error"></span>
                            </div>
                            <div class="mb-3 col-md-4 via_color_area d-none">
                                <label for="name" class="form-label">Color <span class="text-danger">*</span></label>
                                <select class="js-choice form-select via_color" data-width="100%" name="color"
                                    onchange="errorRemove(this);">
                                    <option selected disabled value="">Select Color</option>
                                    @foreach ($colors as $color)
                                        <option value="{{ $color->id }}">{{ $color->name ?? '' }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger via_color_error"></span>
                            </div>

                            {{-- <div class="col-md-4 extra"></div> --}}
                            <div class="mb-3 col-md-4">
                                <label for="name" class="form-label">Supplier Name <span
                                        class="text-danger">*</span></label>
                                <select class="js-choice form-select via_supplier_name" data-width="100%"
                                    name="via_supplier_name" onchange="errorRemove(this);">
                                    <option selected disabled value="">Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name ?? '' }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger via_supplier_name_error"></span>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="name" class="form-label">Total</label>
                                <input id="defaultconfig" class="form-control via_product_total" maxlength="39"
                                    name="via_product_total" type="number" readonly>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="name" class="form-label">Total Pay</label>
                                <input id="defaultconfig" class="form-control via_total_pay" name="via_total_pay"
                                    type="number" readonly>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="name" class="form-label">Paid</label>
                                <input id="defaultconfig" class="form-control via_paid" name="via_paid" type="number">
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="name" class="form-label">Payement Method <span
                                        class="text-danger">*</span></label>
                                <select class="form-select transaction_account" data-width="100%"
                                    name="transaction_account" onclick="errorRemove(this);" onblur="errorRemove(this);">
                                    @if ($payments->count() > 0)
                                        {{-- <option selected disabled>Select Transaction</option> --}}
                                        @foreach ($payments as $payment)
                                            <option value="{{ $payment->id }}">{{ $payment->name }}</option>
                                        @endforeach
                                    @else
                                        <option selected disabled>Please Add Payment</option>
                                    @endif
                                </select>
                                <span class="text-danger transaction_account_error"></span>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="name" class="form-label">Due</label>
                                <input id="defaultconfig" class="form-control via_due" name="via_due" type="number"
                                    readonly>
                                <input type="hidden" class="invoice_number" name="invoice_number" />
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary save_via_product">Save</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <style>
        .addVariant.active>div {
            background: #0d6efd;
            color: white;
        }
          .salesTable tbody.showData td {
        padding: 5px 8px;
        vertical-align: middle;
        }
    </style>

    <script>
        // sell edit check
        let checkSellEdit = '{{ $selling_price_edit }}';
        // discount edit check
        let discountCheck = '{{ $discount }}';
        let sale_hands_on_discount = '{{ $sale_hands_on_discount }}';

        // check warranty status
        let checkWarranty = '{{ $warranty_status }}';
        // check invoice payment system
        let invoicePayment = '{{ $invoice_payment }}';
        // check invoice payment system
        let checkTax = '{{ $tax }}';
        // check price type
        let priceType = "{{ $sale_price_type }}";
        // checkInvoice type
        let checkPrintType = '{{ $invoice_type }}';
        let checkalertStock = '{{ $low_stock_alert }}';
        let taxPercentage = "{{ $taxPercentage }}" ?? 0;
        let color_view = "{{ $color_view }}";
        let size_view = "{{ $size_view }}";
        let party_ways_rate_kit = "{{ $party_ways_rate_kit }}";
        let drag_and_drop = "{{ $drag_and_drop }}";
        let sale_with_low_price = "{{ $sale_with_low_price }}";
        let sale_without_stock = "{{ $sale_without_stock }}";
        let make_invoice_print = "{{ $make_invoice_print }}";


        // generate Invoice number
        function generateInvoice() {
            let invoice_number = '{{ rand(123456, 99999) }}';
            $('.generate_invoice').val(invoice_number);
            $('.invoice_number').val(invoice_number);
        }
        generateInvoice();

   function regenerateSerialNumbers() {
        $(".salesTable tbody.showData tr:visible").each(function (index) {
            $(this).find(".serial-number").text(index + 1); // Set serial number starting from 1
        });
    }
    if (drag_and_drop === "1") {
        $("#sortable tbody").sortable({
            cursor: "move",
            placeholder: "sortable-placeholder",
            helper: function (e, tr) {
                var $originals = tr.children();
                var $helper = tr.clone();
                $helper.children().each(function (index) {
                    $(this).width($originals.eq(index).width());
                });
                return $helper;
            },
            update: function (event, ui) {
                regenerateSerialNumbers();
            }
        }).disableSelection();
    }
        ////////////////////////
        //////Role Permission Access////
        window.authUserCanCostPrice = @json(auth()->user()->can('search.sale.cost_price'));
        window.authUserB2BPrice = @json(auth()->user()->can('search.sale.b2b_price'));
        window.authUserB2CPrice = @json(auth()->user()->can('search.sale.b2c_price'));
        window.authUserColor = @json(auth()->user()->can('search.sale.color'));
        window.authUserSize = @json(auth()->user()->can('search.sale.size'));
    </script>





    <script src="{{ asset('custom/js/sale-pharmacy.js') }}"></script>

@endsection
