@extends('master')
@section('title', '| Edit Purchase')
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
    </style>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> --}}

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Update Purchase</li>
        </ol>
    </nav>

    <!-- Varient Modal -->
    <div class="modal fade" id="varientModal" tabindex="-1" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
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
    {{-- form  --}}
    <form id="purchaseForm" class="row" enctype="multipart/form-data">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="card-title">Update Purchase</h6>
        </div>

        <div class="row">
            <div class="col-lg-7 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="password" class="form-label">Purchase Date</label>

                                <div class="input-group flatpickr" id="flatpickr-date">
                                    <input type="text" name="date" class="form-control purchase_date"
                                        placeholder="Select date" data-input value="{{ $purchase->purchase_date }}">
                                    <span class="input-group-text input-group-addon" data-toggle><i
                                            data-feather="calendar"></i></span>
                                </div>

                                <span class="text-danger purchase_date_error"></span>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="ageSelect" class="form-label">Products <span
                                        class="text-danger">*</span></label>
                                <select class="js-example-basic-single form-select product_select" data-width="100%"
                                    onclick="errorRemove(this);">
                                    @if ($products->count() > 0)
                                        <option selected disabled>Select Products</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name ?? '' }}
                                                ({{ $product->stock_quantity_sum_stock_quantity ?? 0 }}
                                                {{ $product->unit->name ?? '' }})
                                                @if (in_array(Auth::user()->role, ['superadmin', 'admin']))
                                                    | Cost Price: {{ $product->defaultVariations->cost_price ?? 'N/A' }} |
                                                    B2B Price: {{ $product->defaultVariations->b2b_price ?? 'N/A' }} | B2C
                                                    Price: {{ $product->defaultVariations->b2c_price ?? 'N/A' }}
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
                                        <div class="input-group-text" id="btnGroupAddon"><i class="fa-solid fa-barcode"></i>
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
                                    onclick="errorRemove(this);" onblur="errorRemove(this);"
                                    value="{{ $purchase->document }}">
                                <span class="text-danger document_file_error"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="formFile">Invoice Number</label>
                                <input class="form-control" name="invoice" type="text" value="{{ $purchase->invoice }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Invoice Details</h6>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-sm-6 d-flex justify-content-between align-items-center">
                                        <label for="exampleInputUsername2" class="col-form-label">Invoice Number</label>
                                        <span>:</span>
                                    </div>
                                    <div class="col-sm-6 text-end">
                                        <label for="exampleInputUsername2" class="col-form-label"><b>
                                            </b>{{ $purchase->invoice ?? 00 }}</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 d-flex justify-content-between align-items-center">
                                        <label for="exampleInputUsername2" class="col-form-label">Supplier Name</label>
                                        <span>:</span>
                                    </div>
                                    <div class="col-sm-6 text-end">
                                        <label for="exampleInputUsername2" class="col-form-label"><b>
                                            </b>{{ $purchase->supplier->name ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 d-flex justify-content-between align-items-center">
                                        <label for="exampleInputUsername2" class="col-form-label">Invoice Total</label>
                                        <span>:</span>
                                    </div>
                                    <div class="col-sm-6 text-end">
                                        <label for="exampleInputUsername2" class="col-form-label"><b>
                                            </b>{{ number_format($purchase->total_amount, 2) ?? 0 }}</label>
                                    </div>
                                </div>
                                @if ($purchase->discount_amount)
                                    @if ($purchase->discount_type === 'fixed')
                                        <div class="row">
                                            <div class="col-sm-6 d-flex justify-content-between align-items-center">
                                                <label for="exampleInputUsername2" class="col-form-label">Discount</label>
                                                <span>:</span>
                                            </div>
                                            <div class="col-sm-6 text-end">
                                                <label for="exampleInputUsername2" class="col-form-label">
                                                    {{ number_format($purchase->discount_amount, 2) ?? 0 }}</label>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row">
                                            <div class="col-sm-6 d-flex justify-content-between align-items-center">
                                                <label for="exampleInputUsername2" class="col-form-label">Discount
                                                    ({{ $purchase->discount_amount }} %)</label>
                                                <span>:</span>
                                            </div>

                                            <div class="col-sm-6 text-end">
                                                <label for="exampleInputUsername2" class="col-form-label">
                                                    {{ number_format(($purchase->total_amount * $purchase->discount_amount) / 100, 2) ?? 0 }}</label>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-sm-6 d-flex justify-content-between align-items-center">
                                        <label for="exampleInputUsername2" class="col-form-label">Total Purchase
                                            Cost</label>
                                        <span>:</span>
                                    </div>
                                    <div class="col-sm-6 text-end">
                                        <label for="exampleInputUsername2" class="col-form-label"><b>
                                            </b> {{ number_format($purchase->total_purchase_cost, 2) ?? 0 }}</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 d-flex justify-content-between align-items-center">
                                        <label for="exampleInputUsername2" class="col-form-label">Total Receivable</label>
                                        <span>:</span>
                                    </div>
                                    <div class="col-sm-6 text-end">
                                        <label for="exampleInputUsername2" class="col-form-label"><b>
                                            </b> {{ number_format($purchase->grand_total, 2) ?? 0 }}</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 d-flex justify-content-between align-items-center">
                                        <label for="exampleInputUsername2" class="col-form-label">Total Paid</label>
                                        <span>:</span>
                                    </div>
                                    <div class="col-sm-6 text-end">
                                        <label for="exampleInputUsername2" class="col-form-label"><b>
                                            </b>{{ number_format($purchase->paid, 2) ?? 0 }}</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 d-flex justify-content-between align-items-center">
                                        <label for="exampleInputUsername2" class="col-form-label"> Due</label>
                                        <span>:</span>
                                    </div>
                                    <div class="col-sm-6 text-end">
                                        <label for="exampleInputUsername2" class="col-form-label"><b>
                                            </b>{{ number_format($purchase->due, 2) ?? 0 }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- table  --}}
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="card-title">Purchase Table</h6>
                        </div>

                        <div id="" class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#SL</th>
                                        <th>Product</th>
                                        @if ($color_view == 1)
                                            <th>Color</th>
                                        @endif
                                        @if ($size_view == 1)
                                            <th>Size</th>
                                        @endif
                                        <th>Rate</th>
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
                                                        name="carrying_cost" value="0.00" />
                                                </div>
                                            </div>
                                            <div class="row align-items-center">
                                                <div class="col-md-4">
                                                    Sub Total :
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="number" class="form-control grand_total border-0 "
                                                        name="sub_total" readonly value="0.00" />
                                                </div>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="my-3">
                            <button class="btn btn-primary payment_btn" data-bs-toggle="modal"
                                data-bs-target="#paymentModal"><i class="fa-solid fa-money-check-dollar"></i>
                                Payment</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


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
                                            <input type="number" name="subTotal" class="subTotal form-control border-0 "
                                                readonly value="00">
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
                                <select class="form-select payment_method" data-width="100%" onclick="errorRemove(this);"
                                    onblur="errorRemove(this);" name="payment_method">
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
                                    <input class="form-control total_payable border-end-0 rounded-0" name="total_payable"
                                        type="number" onkeyup="payFunc();" onclick="errorRemove(this);"
                                        onblur="errorRemove(this);" step="0.01">
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
        let purchase_individual_product_discount = "{{ $purchase_individual_product_discount }}";
        let purchase_hands_on_discount = "{{ $purchase_hands_on_discount }}";
        // let purchaseItems = @json($purchase->purchaseItem);
        // let purchaseItems = {!! json_encode($purchase->purchaseItem) !!};
        let purchase_id = "{{ $purchase->id }}";

        // error remove
        function errorRemove(element) {
            if (element.value != '') {
                $(element).siblings('span').hide();
                $(element).css('border-color', 'green');
            }
        }

        function calculateTotal() {
            let total = 0;

            // Subtotal calculation for each item
            $('.quantity').each(function() {
                const variantId = $(this).attr('variant-id');
                const qty = parseFloat($(this).val()) || 1;
                const costPrice = parseFloat($('.cost_price' + variantId).val()) || 0;
                const discount = purchase_individual_product_discount === "1" ?
                    parseFloat($('.variant_discount' + variantId).val()) || 0 :
                    0;

                const subtotal = (qty * costPrice) - discount;
                $('.variant_subtotal' + variantId).val(subtotal.toFixed(2));
                total += subtotal;
            });

            $('.total').val(total.toFixed(2));

            // Hands-on discount calculation
            const discountType = purchase_hands_on_discount === "1" ? $('.discount_type').val() : null;
            const handsOnDiscount = purchase_hands_on_discount === "1" ?
                parseFloat($('.discount_amount').val()) || 0 :
                0;
            const carryingCost = parseFloat($('.carrying_cost').val()) || 0;

            let grandTotal = total;
            if (discountType === 'fixed') {
                grandTotal -= handsOnDiscount;
            } else if (handsOnDiscount > 0) {
                if (handsOnDiscount > 100) {
                    toastr.warning('Please select a number between 1 and 100');
                    $('.discount_amount').val(0);
                } else {
                    grandTotal -= (total * handsOnDiscount) / 100;
                }
            }

            $('.grand_total').val((grandTotal + carryingCost).toFixed(2));
        }

        // payFunc
        function payFunc() {
            let pay = parseFloat($('.total_payable').val()) || 0;
            let grandTotal = parseFloat($('.grandTotal').val()) || 0;
            let due = (grandTotal - pay).toFixed(2);
            if (due > 0) {
                $('.final_due').val(due);
            } else {
                $('.final_due').val(0);
            }
        }
        $(document).ready(function() {
            // show error
            function showError(name, message) {
                $(name).css('border-color', 'red');
                $(name).focus();
                $(`${name}_error`).show().text(message);
            }

            let selectedSupplier = '{{ $selectedSupplier->id }}'

            //Supplier Data find
            function fetchSupplierDetails(supplierId) {
                $.ajax({
                    url: `/purchase/supplier/${supplierId}`,
                    method: 'GET',
                    success: function(res) {
                        const supplier = res.supplier;
                        // console.log(supplier);
                        if (supplier.wallet_balance > 0) {
                            $('.previous_due').text(supplier.wallet_balance);
                        } else {
                            $('.previous_due').text(0);
                        }
                    }
                });
            } //
            fetchSupplierDetails(selectedSupplier);

            // total quantity
            let totalQuantity = 0;

            // Function to update total quantity
            function updateTotalQuantity() {
                totalQuantity = 0;
                $('.quantity').each(function() {
                    let quantity = parseFloat($(this).val());
                    if (!isNaN(quantity)) {
                        totalQuantity += quantity;
                    }
                });
                // console.log(totalQuantity);
            }

            // Function to update SL numbers
            function updateSLNumbers() {
                $('.showData > tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
            }

            // select product
            $('.product_select').change(function() {
                let id = $(this).val();
                // alert("ok");

                if ($(`.data_row${id}`).length === 0 && id) {
                    $.ajax({
                        url: '/product/find/' + id,
                        type: 'GET',
                        dataType: 'JSON',
                        success: function(res) {
                            if (res.status == 200) {
                                const product = res.data;
                                const unit = res.unit;
                                if (product.variations.length > 1) {
                                    $('#varientModal').modal('show');
                                    showVariants(product.variations);
                                    updateSLNumbers();
                                } else {
                                    addVariants(product.variations[0], 1);
                                    updateSLNumbers();
                                    calculateTotal();
                                }
                                updateSLNumbers();
                                calculateTotal();
                                updateTotalQuantity();
                            } else {
                                toastr.warning(res.message);
                            }
                        }
                    })
                }
            })

            // Purchase add with barcode
            $('.purchase_barcode_input').change(function() {
                let barcode = $(this).val();
                $.ajax({
                    url: '/variant/barcode/find/' + barcode,
                    type: 'GET',
                    dataType: 'JSON',
                    success: function(res) {
                        if (res.status == 200) {
                            addVariants(res.variant, 1);
                            updateSLNumbers();
                            calculateTotal();
                            $('.purchase_barcode_input').val('');
                        } else {
                            toastr.warning(res.error);
                            $('.purchase_barcode_input').val('');
                        }
                    }
                });
            });


            function showSelectedVariants() {
                $.ajax({
                    url: '/purchase/find/{id}' + purchase_id,
                    type: 'GET',
                    dataType: 'JSON',
                    success: function(res) {
                        console.log(res);
                        if (res.status == 200) {
                            addVariants(res.variant, 1);
                            updateSLNumbers();
                            calculateTotal();
                            $('.purchase_barcode_input').val('');
                        } else {
                            toastr.warning(res.error);
                            $('.purchase_barcode_input').val('');
                        }
                    }
                });
            }
            showSelectedVariants();
            // console.log("Manufacture Date:", manufacture_dates);
            // console.log("Expiry Date:", expiry_dates);

            function addVariants(variant, quantity) {
                const quantity1 = quantity || 1;
                const existingRows = $(`.data_row${variant.id}`);
                if (existingRows.length > 0) {
                    // If the row exists, update the quantity
                    const quantityInput = existingRows.find('.quantity');
                    const currentQuantity = parseInt(quantityInput.val());
                    quantityInput.val(currentQuantity + 1);
                    return;
                }
                // console.log(variant);
                $('.showData').append(
                    `<tr class="data_row${variant.id}">
                    <td>

                    </td>
                    <td>
                        <input type="text" class="form-control border-0 product_name${variant.id}"  name="product_name[]" readonly value="${variant?.product?.name ?? ""}" style="width:60px;margin:0;padding:0;font-size:12px" />
                    </td>
                    <td  ${color_view === '0' ? "hidden" : ""
                    }>
                        <input type="text" class="color${variant.id} form-control border-0"  name="color[]" readonly value="${variant?.color_name?.name ?? ""}" style="width:60px;margin:0;padding:0;font-size:12px" />
                    </td>
                    <td ${size_view === '0' ? "hidden" : ""
                    }>
                        <input type="text" class="form-control border-0 size${variant.id}"  name="size[]" readonly value="${variant?.variation_size?.size ?? ""}" style="width:60px;margin:0;padding:0;font-size:12px" />
                    </td>
                    <td>
                        <input type="hidden" class="variant_id" name="variant_id[]" readonly value="${variant.id ?? 0}" />
                    <input type="number" class="form-control cost_price${variant.id} ${checkPurchaseEdit == 0 ? 'border-0' : ''}" ${checkPurchaseEdit == 0 ? 'readonly' : ''}  name="cost_price[]" onkeyup="calculateTotal();"  value="${variant.cost_price ?? 0}" style="width:60px;margin:0;padding:3px;font-size:12px"/>
                    </td>
                    <td class="text-start">
                    <div class="d-flex align-items-center justify-content-center">
                        <input type="number" variant-id="${variant.id}" class="form-control input-small me-3 quantity" onkeyup="calculateTotal();" name="quantity[]"  value="${quantity1}"  style="width:60px;margin:0;padding:3px;font-size:12px" /> <span>${variant?.product?.product_unit?.name ?? "pc"}</span>
                        <div class="text-danger validation-message" style="display: none;">Please enter a quantity of at least 1.</div>
                        </div>
                    </td>
                    ${purchase_individual_product_discount === "1" ? ` <td>
                                                                                                                                                                        <input type="number" class="form-control input-small me-3 variant_discount${variant.id}"  name="variant_discount[]"  onkeyup="calculateTotal();" value="" style="width:60px;margin:0;padding:3px;font-size:12px" />
                                                                                                                                                                    </td>`: ""
                    }
                    <td>
                        <input type="number" class="form-control border-0 variant_subtotal${variant.id}"  name="total_price[]" readonly value="${variant?.cost_price ?? 0}" />
                    </td>

                    ${manufacture_dates == 1 ? `<td>
                                                                                                                                                                        <input type="date" class="form-control" name="manufacture_date[]" style="width:60px;margin:0;padding:0;font-size:12px" />
                                                                                                                                                                    </td>` : ''}
                    ${expiry_dates == 1 ? `<td>
                                                                                                                                                                        <input type="date" class="form-control" name="expiry_date[]" style="width:60px;margin:0;padding:0;font-size:12px" />
                                                                                                                                                                    </td>` : ''}
                    <td>
                        <a href="#" class="btn btn-danger btn-icon purchase_delete" data-id=${variant.id} style="width:60px;margin:0;padding:0;font-size:12px" >
                            <i class="fa-solid fa-trash-can"></i>
                        </a>
                    </td>
                </tr>`
                );

            }

            // show variants
            function showVariants(variants) {
                // console.log(variants);
                $('.show_variants').empty();
                variants.forEach(variant => {
                    // console.log(variant);
                    const imageUrl = variant?.image ? `/uploads/products/${variant.image}` :
                        '/dummy/image.jpg';
                    // console.log(imageUrl);
                    $('.show_variants').append(`
                <div class="col-md-4 p-2 text-center addVariant cursor-pointer" data-active="false" data-id="${variant.id}">
                    <div class="border rounded overflow-hidden pb-2">
                        <img src="${imageUrl}" class="img-fluid"
                            style="object-fit: cover; height: 120px; width: 100%;" alt="variant image">
                        <div class="mt-2">
                            <p class="">${variant?.cost_price ?? 0}</p>
                            <p class="">${variant?.variation_size?.size ?? ""}</p>
                            <p class="">${variant?.color_name.name ?? ""}</p>
                        </div>
                    </div>
                </div>
            `);
                });
            }

            // Handle variant selection
            $(document).on('click', '.addVariant', function() {
                // Toggle the 'active' class and data-active attribute for the clicked variant
                $(this).toggleClass('active');
                const isActive = $(this).hasClass('active');
                $(this).attr('data-active', isActive ? 'true' : 'false');
            });

            // Add variant selection
            $(document).on('click', '.add_all_variants', function() {
                // Find all active variants
                const activeVariants = $('.addVariant[data-active="true"]');

                // Check if any variant is selected
                if (activeVariants.length === 0) {
                    alert('Please select at least one variant.');
                    return;
                }

                // Loop through each active variant and fetch details
                activeVariants.each(function() {
                    const variantId = $(this).attr(
                        'data-id'); // Get the data-id of the active variant
                    fetchVariantDetails(variantId);
                    updateSLNumbers(); // Call fetchVariantDetails for each active variant
                });

                // Hide the modal after processing
                $('#varientModal').modal('hide');
            });


            // Fetch variant details via AJAX
            function fetchVariantDetails(variantId, isProduct) {
                $.ajax({
                    url: `/variant/find/${variantId}`, // Create this endpoint in your backend
                    type: 'GET',
                    data: {
                        isProduct: isProduct
                    },
                    dataType: 'JSON',
                    success: function(res) {
                        const variant = res.variant;
                        // console.log(variant);
                        // const promotion = res.promotion;
                        addVariants(variant, 1);
                        updateSLNumbers();
                        calculateTotal();
                    },
                    error: function(error) {
                        console.error('Error fetching variant details:', error);
                    }
                });
            }



            // purchase Delete
            $(document).on('click', '.purchase_delete', function(e) {
                // alert('ok');
                let id = $(this).attr('data-id');
                let dataRow = $('.data_row' + id);
                dataRow.remove();
                // Recalculate grand total
                calculateTotal();
                updateSLNumbers();
                updateTotalQuantity();
            });

            // payment button click event
            $('.payment_btn').click(function(e) {
                e.preventDefault();
                // let supplier = $('.select-supplier').val();
                // if (supplier) {
                $('#paymentModal').modal('show');

                updateTotalQuantity();
                let customer_due = parseFloat($('.previous_due').text()) || 0;
                let subtotal = parseFloat($('.grand_total').val()) || 0;
                $('.subTotal').val(subtotal.toFixed(2));
                let grandTotal = customer_due + subtotal;
                $('.grandTotal').val(grandTotal.toFixed(2));
                $('.paying_items').text(totalQuantity);
                var isValid = true;
                //Quantity Message
                $('.quantity').each(function() {
                    var quantity = $(this).val();
                    if (!quantity || quantity < 1) {
                        isValid = false;
                        return false;
                    }
                });
                if (!isValid) {
                    e.preventDefault();
                    // alert('Please enter a quantity of at least 1 for all products.');
                    toastr.error('Please enter a quantity of at least 1.)');
                    $('#paymentModal').modal('hide');
                }
                // } else {
                //     toastr.warning('Please Select Supplier');
                // }
            })

            // paid amount
            $('.paid_btn').click(function(e) {
                e.preventDefault();
                // alert('ok');
                let grandTotal = $('.grandTotal').val();
                $('.total_payable').val(grandTotal);
                payFunc();
            })


            $('#purchaseForm').submit(function(event) {
                event.preventDefault();
                // showSpinner();
                $('#paymentModal').modal('hide');
                let formData = new FormData($('#purchaseForm')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '/purchase/store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            $('#paymentModal').modal('hide');
                            toastr.success(res.message);
                            let id = res.purchaseId;
                            hideSpinner();
                            window.location.href = '/purchase/invoice/' + id;

                        } else if (res.status == 400) {
                            hideSpinner();
                            toastr.warning(res.message);
                            showError('.payment_method',
                                'please Select Another Payment Method');
                        } else {
                            hideSpinner();
                            toastr.warning("something went wrong");
                            if (res.error.payment_method || res.error.total_payable) {
                                if (res.error.total_payable) {
                                    showError('.total_payable', res.error.total_payable);
                                }
                                if (res.error.payment_method) {
                                    showError('.payment_method', res.error.payment_method);
                                }
                            } else {
                                $('#paymentModal').modal('hide');
                                if (res.error.supplier_id) {
                                    showError('.supplier_id', res.error.supplier_id);
                                }
                                if (res.error.purchase_date) {
                                    showError('.purchase_date', res.error.purchase_date);
                                }
                                if (res.error.document) {
                                    showError('.document_file', res.error.document);
                                }
                            }
                        }
                    }
                });
            });
        });
    </script>
    {{-- <script src="{{ asset('custom/js/purchase-edit.js') }}"></script> --}}
@endsection
