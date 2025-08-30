@extends('master')
@section('title', '| Sale Update')
@section('admin')

    <style>
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
            white-space:nowrap;
            }
            .sale_edit{
            cursor: move;
            }
    </style>


    @php
        $payments = App\Models\Bank::where('branch_id', Auth::user()->branch_id)
            ->latest()
            ->get();

        if ($tax == 1) {
            $taxPercentage = App\Models\Tax::first()->percentage ?? 0;
        } else {
            $taxPercentage = null;
        }
    @endphp
    <div class="row">
        <div class="col-lg-6 grid-margin stretch-card mb-3">
            <div class="card">
                <div class="card-body px-4 py-4">
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="card-title">Invoice Details</h6>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-sm-6 d-flex justify-content-between align-items-center">
                                            <label for="exampleInputUsername2" class="col-form-label">Order Id </label>
                                            <span>:</span>
                                        </div>
                                        <div class="col-sm-6 text-end">
                                            <label for="exampleInputUsername2" class="col-form-label"><b>
                                                </b>{{ $sale->invoice_number ?? 00 }}</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 d-flex justify-content-between align-items-center">
                                            <label for="exampleInputUsername2" class="col-form-label">Customer Name</label>
                                            <span>:</span>
                                        </div>
                                        <div class="col-sm-6 text-end">
                                            <label for="exampleInputUsername2" class="col-form-label"><b>
                                                </b>{{ $sale->customer->name ?? '' }}</label>
                                        </div>
                                        <input type="hidden" id="selectedCustomerId" value="{{ $sale->customer->id }}">
                                    </div>
                                    <div class="row">
                                        <label for="exampleInputMobile" class="col-sm-6 col-form-label">Product Price
                                            :</label>
                                        <div class="col-sm-6 text-end">
                                            <label for="exampleInputUsername2" class="col-form-label"><b>
                                                </b>{{ number_format($sale->total, 2) ?? 0 }}</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="exampleInputUsername2" class="col-sm-6 col-form-label">Discount
                                            :</label>
                                        <div class="col-sm-6 text-end">
                                            <label for="exampleInputUsername2" class="col-form-label"><b>
                                                    {{ number_format($sale->actual_discount, 2) ?? 0 }}</b></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="row">
                                        <label for="exampleInputEmail2" class="col-sm-6 col-form-label">Previous Due
                                            :</label>
                                        <div class="col-sm-6 text-end">
                                            @php
                                                $previousDue = $sale->receivable - $sale->change_amount;
                                                // dd($sale->receivable);
                                            @endphp
                                            <label for="exampleInputUsername2" class="col-form-label"><b>
                                                    {{ number_format($previousDue, 2) ?? 0 }}</b></label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="exampleInputEmail2" class="col-sm-6 col-form-label">Total Receivable
                                            :</label>
                                        <div class="col-sm-6 text-end">
                                            <label for="exampleInputUsername2" class="col-form-label"><b>
                                                    {{ number_format($sale->receivable, 2) ?? 0 }}</b></label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="exampleInputMobile" class="col-sm-6 col-form-label">Total Paid :</label>
                                        <div class="col-sm-6 text-end">
                                            <label for="exampleInputUsername2" class="col-form-label"><b>
                                                    {{ number_format($sale->paid, 2) ?? 0 }}</b></label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="exampleInputMobile" class="col-sm-6 col-form-label">Due :</label>
                                        <div class="col-sm-6 text-end">
                                            <label for="exampleInputUsername2" class="col-form-label"><b>
                                                    {{ $sale->due > 0 ? $sale->due : 0 }}</b></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title">Sale Update</h6>
                    </div>
                    <div class="row">
                        @if ($barcode == 1)
                            <div class="mb-2 col-md-6">
                                <label for="ageSelect" class="form-label">Barcode</label>
                                <div class="input-group">
                                    <div class="input-group-text" id="btnGroupAddon"><i class="fa-solid fa-barcode"></i>
                                    </div>
                                    <input type="text" class="form-control barcode_input" placeholder="Barcode"
                                        aria-label="Input group example" aria-describedby="btnGroupAddon">
                                </div>
                            </div>
                        @endif
                        <div class="mb-3 col-md-6">
                            <label for="ageSelect" class="form-label">Product</label>
                            <select class="js-example-basic-single  form-select product_select" data-width="100%"
                                onclick="errorRemove(this);" onblur="errorRemove(this);">
                                @if ($products->count() > 0)
                                    <option selected disabled>Select Product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}
                                            ({{ $product->stockQuantity->sum('stock_quantity') }}
                                            {{ $product->productUnit->name ?? '' }} Available)
                                        </option>
                                    @endforeach
                                @else
                                    <option selected disabled>Please Add Product</option>
                                @endif
                            </select>
                            <span class="text-danger product_select_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="date" class="form-label">Date</label>
                            <div class="input-group flatpickr" id="flatpickr-date">
                                <input type="text" name="date" class="form-control purchase_date"
                                    placeholder="Select date" data-input value="{{ $sale->sale_date }}">
                                <span class="input-group-text input-group-addon" data-toggle><i
                                        data-feather="calendar"></i></span>
                            </div>
                            <span class="text-danger purchase_date_error"></span>
                        </div>

                        {{-- <div class="mb-3 col-md-6">
                            <label for="password" class="form-label">Customer</label>
                            <div class="d-flex g-3">
                                <select class="js-example-basic-single form-select select-customer" data-width="100%"
                                    onclick="errorRemove(this);" onblur="errorRemove(this);">
                                    <option selected disabled>Select Customer</option>
                                </select>
                                <button class="btn btn-primary ms-2" data-bs-toggle="modal"
                                    data-bs-target="#customerModal">Add</button>
                            </div>
                        </div>
                    </div> --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- table  --}}
        <div class="row">
            <div class="{{ $warranty_status == 1 ? 'col-lg-12' : 'col-lg-8' }} grid-margin stretch-card">
                <div class="card">
                    <div class="card-body px-4 py-2">
                        <div class="mb-3">
                            <h6 class="card-title">Items</h6>
                        </div>

                        <div id="" class="table-responsive">
                            <table class="table" id="sortable">
                                <thead>
                                    <tr class="ui-state-default">
                                         <th>SL</th>
                                        <th>Product</th>
                                        <th>Color</th>
                                        <th>Size</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        @if ($warranty_status == 1)
                                            <th>Warranty</th>
                                        @endif
                                        @if ($sale_hands_on_discount == 1)
                                        <th>Discount</th>
                                        @endif
                                        <th>Sub Total</th>
                                        <th>
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
            <div class="{{ $warranty_status == 1 ? 'col-lg-5' : 'col-lg-4' }} grid-margin stretch-card">
                <div class="card">
                    <div class="card-body px-4 py-2">
                        <div class="row align-items-center mb-2">
                            <div class="col-sm-4">
                                Product Total :
                            </div>
                            <div class="col-sm-8 text-end">
                                <input type="number" class="form-control total border-0 text-end" name="total"
                                    readonly value="0.00" />
                            </div>
                        </div>
                        @if($discount  ===1)
                        <div class="row align-items-center mb-2 ">
                            <div class="col-sm-4">
                                Discount :
                            </div>
                            <div class="col-sm-8 d-flex justify-content-start ">
                                <select name="sale_discount_type" class="px-2 sale_discount_type"
                                >
                                     <option {{ $sale->discount_type === 'fixed' ? 'selected' : '' }} value="fixed">৳</option>
                                     <option {{ $sale->discount_type === 'percentage' ? 'selected' : '' }} value="percentage">%</option>
                                 </select>
                                <input type="number" class="form-control handsOnDiscount text-end" name=""
                                    value="{{$sale->discount }}" />
                            </div>
                        </div>
                        @endif
                        @if ($tax == 1)
                            <div class="row align-items-center mb-2 ">
                                <div class="col-sm-4">
                                    Tax ({{ $taxPercentage }}%) :
                                </div>
                                <div class="col-sm-8 text-end">
                                    <input type="text" class="form-control tax text-end border-0" name=""
                                        value="0.00" readonly />
                                </div>
                            </div>
                        @endif
                        <div class="row align-items-center mb-2 previous_due_field ">
                            <div class="col-sm-4">
                                Sub Total:
                            </div>
                            <div class="col-sm-8 text-end">
                                <input type="number" class="form-control invoice_total border-0 text-end"
                                    name="grand_total" readonly value="0.00" />
                            </div>
                        </div>
                        @if ($invoice_payment === 0)
                            <div class="row align-items-center mb-2">
                                <div class="col-sm-4">
                                    Previous Due :
                                </div>
                                <div class="col-sm-8 text-end">
                                    <input type="number" class="form-control previous_due border-0 text-end"
                                        name="previous_due" readonly value="0.00" />
                                </div>
                            </div>
                            <div class="row align-items-center @if ($invoice_payment === 0) mb-2 @endif">
                                <div class="col-sm-4">
                                    Grand Total :
                                </div>
                                <div class="col-sm-8 text-end">
                                    <input type="number" class="form-control grandTotal border-0 text-end"
                                        name="" readonly value="0.00" />
                                </div>

                            </div>
                        @else
                            <input type="hidden" class="grandTotal" name="" readonly value="0.00" />
                        @endif
                        <div class="row align-items-center mb-2">
                            <div class="col-sm-4">
                                <label for="name" class="form-label">Pay Amount :</label>
                            </div>
                            <div class="col-sm-8 text-end">
                                <input class="form-control total_payable text-end" minlength='0' name="total_payable"
                                    type="number" value="{{ $sale->paid ?? 0 }}">
                                <span class="text-danger total_payable_error"></span>
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-sm-4 due_text ">
                                Total Due :
                            </div>
                            <div class="col-sm-8 text-end">
                                <input type="number" class="form-control total_due border-0 text-end" name=""
                                    readonly value="0.00" />
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-sm-4">
                                <label for="name" class="form-label">Transaction Method <span
                                        class="text-danger">*</span>:</label>
                            </div>
                            <div class="col-sm-8">

                                <select class="form-select payment_method" data-width="100%" onclick="errorRemove(this);"
                                    onblur="errorRemove(this);">
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
                        </div>

                        <div class="my-3">
                            <button class="btn btn-primary payment_btn"><i class="fa-solid fa-money-check-dollar"></i>
                                Update Invoice</button>
                        </div>
                    </div>
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
            $viaProducts = App\Models\Product::latest()->get();
            $suppliers = App\Models\Customer::where('party_type', 'supplier')->get();
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
                                <select class="form-select via_product_name" data-width="100%" name="name"
                                    id="viaProductSelect" onchange="toggleFields('select');">
                                    <option selected disabled>Select Via Product</option>
                                    @foreach ($viaProducts as $viaProduct)
                                        <option value="{{ $viaProduct->id }}">{{ $viaProduct->name ?? '' }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn " onclick="resetViaProductSelect()">Unselect</button>
                                <span class="text-danger via_product_name_error"></span>
                            </div>
                            <script>
                                function toggleFields(trigger) {
                                    const inputField = document.querySelector('.product_name');
                                    const selectField = document.querySelector('.via_product_name');

                                    if (trigger === 'input' && inputField.value.trim() !== '') {
                                        // If input is filled, disable the select field and clear its value
                                        selectField.value = '';
                                        selectField.disabled = true;
                                    } else if (trigger === 'select' && selectField.value !== '') {
                                        // If an option in select is chosen, disable the input field and clear its value
                                        inputField.value = '';
                                        inputField.disabled = true;
                                    } else {
                                        // Enable both fields if both are empty
                                        inputField.disabled = false;
                                        selectField.disabled = false;
                                    }
                                }

                                function resetViaProductSelect() {
                                    const selectField = document.getElementById('viaProductSelect');
                                    selectField.value = ''; // Reset the value to default
                                    toggleFields('select'); // Optionally call the toggle function to handle dependent logic
                                }
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
                            <div class="mb-3 col-md-4">
                                <label for="name" class="form-label">Supplier Name <span
                                        class="text-danger">*</span></label>
                                <select class="form-select via_supplier_name" data-width="100%" name="via_supplier_name"
                                    onchange="errorRemove(this);">
                                    <option selected disabled value="">Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
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
        #printFrame {
            display: none;
            /* Hide the iframe */
        }
    </style>
    <iframe id="printFrame" src="" width="0" height="0"></iframe>

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
        // sell id =
        let sale_id = '{{ $sale->id }}';
        let customerId = '{{$sale->customer->id}}'
        // console.log('customer i',customerId);
        // invoice print type
        let checkPrintType = '{{ $invoice_type }}';
        let priceType = "{{ $sale_price_type }}";
        const previousTotalAmount = "{{ $sale->change_amount }}";

        let taxPercentage = "{{ $taxPercentage }}" ?? 0;
        let drag_and_drop = "{{ $drag_and_drop }}";
        let sale_with_low_price = "{{ $sale_with_low_price }}";
        let sale_without_stock = "{{ $sale_without_stock }}";
        let serialNumber = 1; // Global serial number counter //

        // Function to regenerate serial numbers
        function regenerateSerialNumbers() {
            const rows = document.querySelectorAll("#sortable tbody tr");
            rows.forEach((row, index) => {
                row.querySelector("td:first-child").textContent = index + 1;
            });
        }
        if(drag_and_drop === "1"){
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

    <script src="{{ asset('custom/js/sale-edit.js') }}"></script>
@endsection
