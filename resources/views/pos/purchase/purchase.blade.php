@extends('master')
@section('title', '| Add Purchase')
@section('admin')
    <style>
        .input-small {
            width: 100px;
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

        .addVariant.active>div {
            background: #0d6efd;
            color: white;
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

        .pruchase_bars {
            cursor: move;
        }
    </style>
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Purchase Products</li>
        </ol>
    </nav>
    {{-- ///////////Supplier Modal ////// --}}
    <!-- Modal -->
    <div class="modal fade" id="supplierModal" tabindex="-1" aria-labelledby="eexampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eexampleModalScrollableTitle">Add Supplier Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="supplierForm row">
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Supplier Name <span
                                    class="text-danger">*</span></label>
                            <input id="defaultconfig" class="form-control supplier_name" maxlength="255" name="name"
                                type="text" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger supplier_name_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Email</label>
                            <input id="defaultconfig" class="form-control email" maxlength="39" name="email"
                                type="email">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Phone Nnumber <span
                                    class="text-danger">*</span></label>
                            <input id="defaultconfig" class="form-control phone" maxlength="39" name="phone"
                                type="tel" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger phone_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Address</label>
                            <input id="defaultconfig" class="form-control address" maxlength="39" name="address"
                                type="text">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Supplier Due (সাপ্লায়ার আপানার
                                থেকে পাবে)</label>
                            <input id="defaultconfig" class="form-control opening_payable" maxlength="39"
                                name="opening_payable" type="number">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Opening Receivable (সাপ্লায়ার থেকে আপনি
                                পাবেন)</label>
                            <input id="defaultconfig" class="form-control opening_receivable" maxlength="39"
                                name="opening_receivable" type="number">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_supplier">Save</button>
                </div>
                </form>
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
    <form id="purchaseForm" class="" enctype="multipart/form-data">
        {{-- form  --}}
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="card-title"> Purchase Products</h6>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="ageSelect" class="form-label">Supplier <span
                                        class="text-danger">*</span></label>
                                <div class="d-flex g-2">
                                    <select class="js-example-basic-single form-select select-supplier supplier_id"
                                        data-width="100%" onclick="errorRemove(this);" name="supplier_id">
                                    </select>
                                    <span class="text-danger supplier_id_error"></span>
                                    <a class="btn btn-primary ms-2" data-bs-toggle="modal"
                                        data-bs-target="#supplierModal">Add</a>
                                </div>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="password" class="form-label">Purchase Date</label>
                                <div class="input-group flatpickr me-2 mb-2 mb-md-0" id="dashboardDate">
                                    <span class="input-group-text input-group-addon bg-transparent border-primary"
                                        data-toggle><i data-feather="calendar" class="text-primary"></i></span>
                                    <input type="text" name="date"
                                        class="form-control bg-transparent border-primary purchase_date"
                                        placeholder="Select date" data-input>
                                </div>
                                <span class="text-danger purchase_date_error"></span>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="ageSelect" class="form-label">Products <span
                                        class="text-danger">*</span></label>
                                @php

                                @endphp
                                <select class="js-example-basic-single form-select product_select" data-width="100%"
                                    onclick="errorRemove(this);">
                                    @if ($products->count() > 0)
                                        <option selected disabled>Select Products</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name ?? '' }}
                                                ({{ $product->stock_quantity_sum_stock_quantity ?? 0 }}
                                                {{ $product->unit->name ?? '' }})
                                                @if (in_array(Auth::user()->role, ['superadmin', 'admin']))
                                                    | Cost Price: {{ $product->defaultVariations->cost_price ?? 0 }}
                                                @endif
                                            </option>
                                        @endforeach
                                    @else
                                        <option selected disabled>
                                            Please Add Product</option>
                                    @endif
                                </select>
                                <span class="text-danger product_select_error"></span>
                            </div>
                            @if ($barcode == 1)
                                <div class="mb-2 col-md-6">
                                    <label for="ageSelect" class="form-label">Barcode</label>
                                    <div class="input-group">
                                        <div class="input-group-text" id="btnGroupAddon"><i
                                                class="fa-solid fa-barcode"></i>
                                        </div>
                                        <input type="text" class="form-control purchase_barcode_input"
                                            placeholder="Barcode" aria-label="Input group example"
                                            aria-describedby="btnGroupAddon">
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="formFile">Invoice File/Picture upload</label>
                                <input class="form-control document_file" name="document" type="file" id="formFile"
                                    onclick="errorRemove(this);">
                                <span class="text-danger document_file_error"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="formFile">Invoice Number</label>
                                <input class="form-control" name="invoice" type="text">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- ///////////Supplier Modal ////// --}}

        {{-- table  --}}
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="card-title">Purchase Table</h6>
                        </div>

                        <div id="" class="table-responsive">
                            <table class="table" id="sortable">
                                <thead>
                                    <tr class="ui-state-default">
                                        <th>#SL</th>
                                        <th>Product</th>
                                        @if ($color_view == 1)
                                            <th>Color</th>
                                        @endif
                                        @if ($size_view == 1)
                                            <th>Size</th>
                                        @endif
                                        <th>Rate</th>
                                        {{-- <th>Qty</th> --}}

                                        <th>Qty</th>
                                        @if ($purchase_individual_product_discount === 1)
                                            <th>Discount</th>
                                        @endif
                                        <th>Sub Total</th>
                                        @if ($manufacture_date === 1)
                                            <th>Manufacture Date</th>
                                        @endif
                                        @if ($expiry_date === 1)
                                            <th>Expiry Date</th>
                                        @endif
                                        <th>
                                            <i class="fa-solid fa-trash-can"></i>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="showData">
                                </tbody>

                            </table>
                            <div class="row">
                                <div class="col-md-7">

                                </div>
                                <div class="col-md-5">
                                    <table>
                                        <tfoot>
                                            <tr>
                                                <td></td>
                                                <td></td>

                                                @if ($color_view == 1)
                                                    <td></td>
                                                @endif
                                                @if ($size_view == 1)
                                                    <td></td>
                                                @endif
                                                <td></td>
                                                <td></td>
                                                @if ($purchase_individual_product_discount === 1)
                                                    <td></td>
                                                @endif
                                                <td></td>
                                                @if ($manufacture_date === 1)
                                                    <td></td>
                                                @endif
                                                @if ($expiry_date === 1)
                                                    <td></td>
                                                @endif
                                                <td>
                                                    <div class="row align-items-center">
                                                        <div class="col-md-4">
                                                            Total :
                                                        </div>
                                                        <div class="col-md-8">
                                                            <input type="number" class="form-control total border-0 "
                                                                name="total" readonly value="0.00" />
                                                        </div>
                                                    </div>
                                                    @if ($purchase_hands_on_discount === 1)
                                                        <div class="row align-items-center">
                                                            <div class="col-md-4">
                                                                Discount :
                                                            </div>
                                                            <div class="col-md-8 d-flex justify-content-start">
                                                                <select name="discount_type" class="px-2 discount_type"
                                                                    onchange="calculateTotal();">
                                                                    <option value="fixed">৳</option>
                                                                    <option value="percentage">%</option>
                                                                </select>
                                                                <input type="number" class="form-control discount_amount"
                                                                    name="discount_amount" onkeyup="calculateTotal();"
                                                                    value="0.00" />
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="row align-items-center">
                                                        <div class="col-md-4">
                                                            Carrying Cost :
                                                        </div>
                                                        <div class="col-md-8">
                                                            <input type="number" class="form-control carrying_cost"
                                                                name="carrying_cost" onkeyup="calculateTotal();"
                                                                value="0.00" />
                                                        </div>
                                                    </div>

                                                    <div class="row align-items-center">
                                                        <div class="col-md-4">
                                                            Sub Total :
                                                        </div>
                                                        <div class="col-md-8">
                                                            <input type="number"
                                                                class="form-control grand_total border-0 "
                                                                name="sub_total" readonly value="0.00" />
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="my-3">
                                <a class="btn btn-primary payment_btn"><i class="fa-solid fa-money-check-dollar"></i>
                                    Payment
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- payement modal  --}}
            <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="exampleModalScrollableTitle"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalScrollableTitle">Payment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="btn-close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="" class="table-responsive mb-3">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Paying Items :</th>
                                            <th>
                                                <span class="paying_items">0</span>
                                            </th>
                                            <th>Sub Total :</th>
                                            <th>
                                                <input type="number" name="subTotal"
                                                    class="subTotal form-control border-0 " readonly value="00">
                                            </th>
                                        </tr>
                                        <tr>
                                            @if ($invoice_payment === 0)
                                                <th>Previous Due:</th>
                                                <th>
                                                    (<span class="previous_due">00</span>TK)
                                                </th>
                                            @endif
                                            <th>Grand Total:</th>
                                            <th>
                                                <input type="number" name="grand_total"
                                                    class="grandTotal form-control border-0 " readonly value="00">
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            {{-- <form id="signupForm" class="supplierForm row"> --}}
                            <div class="supplierForm row">
                                <div class="mb-3 col-md-6">
                                    <label for="name" class="form-label">Transaction Method <span
                                            class="text-danger">*</span></label>
                                    @php
                                        $payments = App\Models\Bank::get();
                                    @endphp
                                    <select class="form-select payment_method" data-width="100%"
                                        onclick="errorRemove(this);" onblur="errorRemove(this);" name="payment_method">
                                        @if ($payments->count() > 0)
                                            @foreach ($payments as $payemnt)
                                                <option value="{{ $payemnt->id }}">
                                                    {{ $payemnt->name }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option selected disabled>Please Add Transaction</option>
                                        @endif
                                    </select>
                                    <span class="text-danger payment_method_error"></span>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="name" class="form-label">Pay Amount <span
                                            class="text-danger">*</span></label>
                                    <div class="d-flex align-items-center">
                                        <input class="form-control total_payable border-end-0 rounded-0"
                                            name="total_payable" type="number" onkeyup="payFunc();"
                                            onclick="errorRemove(this);" onblur="errorRemove(this);" step="0.01">
                                        <span class="text-danger total_payable_error"></span>
                                        <button class="btn btn-info border-start-0 rounded-0 paid_btn">Paid</button>
                                    </div>

                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="name" class="form-label">Due</label>
                                    <input name="note" class="form-control final_due" id="" placeholder=""
                                        readonly></input>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="name" class="form-label">Note</label>
                                    <input name="note" class="form-control note" id=""
                                        placeholder="Enter Note (Optional)" rows="3"></input>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-cart-shopping"></i>
                                    Purchase
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </form>


    <script>
        let checkPurchaseEdit = '{{ $purchase_price_edit }}';
        let invoicePayment = '{{ $invoice_payment }}';
        let manufacture_dates = '{{ $manufacture_date }}';
        let expiry_dates = '{{ $expiry_date }}';
        let color_view = "{{ $color_view }}";
        let size_view = "{{ $size_view }}";
        let drag_and_drop = "{{ $drag_and_drop }}";
        let purchase_individual_product_discount = "{{ $purchase_individual_product_discount }}";
        let purchase_hands_on_discount = "{{ $purchase_hands_on_discount }}";
        let serialNumber = 1; // Global serial number counter

        // Function to regenerate serial numbers
        function regenerateSerialNumbers() {
            const rows = document.querySelectorAll("#sortable tbody tr");
            rows.forEach((row, index) => {
                row.querySelector("td:first-child").textContent = index + 1;
            });
        }
        if (drag_and_drop === "1") {
            $("#sortable tbody").sortable({
                cursor: "move",
                placeholder: "sortable-placeholder",
                helper: function(e, tr) {
                    var $originals = tr.children();
                    var $helper = tr.clone();
                    $helper.children().each(function(index) {
                        $(this).width($originals.eq(index).width());
                    });
                    return $helper;
                },
                update: function(event, ui) {
                    // Regenerate serial numbers after drag-and-drop
                    regenerateSerialNumbers();
                }
            }).disableSelection();
        }
    </script>
    <script src="{{ asset('custom/js/purchase.js') }}"></script>
@endsection
