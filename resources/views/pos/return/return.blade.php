@extends('master')
@section('admin')
    @php
        $branch = App\Models\Branch::findOrFail($sale->branch_id);
        $customer = App\Models\Customer::findOrFail($sale->customer_id);
        $sale_items = App\Models\SaleItem::where('sale_id', $sale->id)->get();
    @endphp
    <div class="row ">
        <div class="col-lg-4 grid-margin stretch-card mb-3">
            <div class="card">
                <div class="card-body px-4 py-2">
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="card-title">Basic Details</h6>
                            <div class="row my-0 py-0">
                                <label for="exampleInputUsername2" class="col-6 col-form-label">Order Id :</label>
                                <div class="col-6 text-end">
                                    <label for="exampleInputUsername2" class="col-form-label"><b>
                                        </b>{{ $sale->invoice_number ?? 00 }}</label>
                                </div>
                            </div>
                            <div class="row">
                                <label for="exampleInputUsername2" class="col-6 col-form-label">Customer Name :</label>
                                <div class="col-6 text-end">
                                    <label for="exampleInputUsername2" class="col-form-label"><b>
                                        </b>{{ $sale->customer->name ?? '' }}</label>
                                </div>
                            </div>
                            <div class="row">
                                <label for="exampleInputMobile" class="col-6 col-form-label">Product Price :</label>
                                <div class="col-6 text-end">
                                    <label for="exampleInputUsername2" class="col-form-label"><b>
                                        </b>{{ number_format($sale->total, 2) ?? 0 }}</label>
                                </div>
                            </div>
                            <div class="row">
                                <label for="exampleInputUsername2" class="col-6 col-form-label">Discount :</label>
                                <div class="col-6 text-end">
                                    <label for="exampleInputUsername2" class="col-form-label"><b>
                                            {{ number_format($sale->actual_discount, 2) ?? 0 }}</b></label>
                                </div>
                            </div>
                            <div class="row">
                                <label for="exampleInputEmail2" class="col-6 col-form-label">Previous Due :</label>
                                <div class="col-6 text-end">
                                    @php
                                        $total = $sale->total - $sale->actual_discount;
                                        $previousDue = $sale->receivable - $total;
                                    @endphp
                                    <label for="exampleInputUsername2" class="col-form-label"><b>
                                            {{ number_format($previousDue, 2) ?? 0 }}</b></label>
                                </div>
                            </div>
                            <div class="row">
                                <label for="exampleInputEmail2" class="col-6 col-form-label">Total Receivable :</label>
                                <div class="col-6 text-end">
                                    <label for="exampleInputUsername2" class="col-form-label"><b>
                                            {{ number_format($sale->receivable, 2) ?? 0 }}</b></label>
                                </div>
                            </div>
                            <div class="row">
                                <label for="exampleInputMobile" class="col-6 col-form-label">Total Paid :</label>
                                <div class="col-6 text-end">
                                    <label for="exampleInputUsername2" class="col-form-label"><b>
                                            {{ number_format($sale->paid, 2) ?? 0 }}</b></label>
                                </div>
                            </div>
                            <div class="row">
                                <label for="exampleInputMobile" class="col-6 col-form-label">Due :</label>
                                <div class="col-6 text-end">
                                    <label for="exampleInputUsername2" class="col-form-label"><b>
                                            {{ $sale->due > 0 ? $sale->due : 0 }}</b></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 grid-margin stretch-card mb-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-info">Add Return Product</h6>
                    @csrf
                    <div class="row">
                        <!-- Col -->
                        <div class="mb-3 col-md-6">
                            <label for="" class="form-label">Product <span class="text-danger">*</span></label>
                            <div class="d-flex g-3">
                                <select class="js-example-basic-single form-select product_select" data-width="100%">
                                    @if ($sale_items->count() > 0)
                                        <option selected disabled>Select Returned Product</option>
                                        @foreach ($sale_items as $product)
                                            <option value="{{ $product->id }}"
                                                {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                                {{ $product->product->name }} / {{ $product->variant->color ?? '' }} /
                                                {{ $product->variant->variationSize->size ?? '' }}
                                                ({{ $product->qty }} {{ $product->productUnit->name ?? 'pc' }})
                                            </option>
                                        @endforeach
                                    @else
                                        <option selected disabled>Please Add Product</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3 form-valid-groups">
                                <label class="form-label">Date<span class="text-danger">*</span></label>
                                <div class="input-group flatpickr me-2 mb-2 mb-md-0" id="dashboardDate">
                                    <span class="input-group-text input-group-addon bg-transparent border-primary"
                                        data-toggle><i data-feather="calendar" class="text-primary"></i></span>
                                    <input type="text" name="date"
                                        class="form-control bg-transparent border-primary return_date" value=""
                                        placeholder="Select date" data-input>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            @php
                                $payments = App\Models\Bank::where('branch_id', Auth::user()->branch_id)->get();
                            @endphp
                            <label for="" class="form-label">Payment Method <span
                                    class="text-danger">*</span></label>
                            <div class="d-flex g-3">
                                <select class="js-example-basic-single payment_method" data-width="100%">
                                    @if ($payments->count() > 0)
                                        @foreach ($payments as $payemnt)
                                            <option value="{{ $payemnt->id }}">
                                                {{ $payemnt->name }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option selected disabled>Please Add Payment Method</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        @if ($invoice_payment === 0)
                            <div class="col-sm-6">
                                <label class="form-label">Adjus Due</label>
                                <select class="form-select adjust_due">
                                    <option selected disabled>Please Select Adjust Due</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                        @endif
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Return Reason</label>
                                <textarea name="note" class="form-control return_purpose" value="{{ old('note') }}"
                                    placeholder="Write About Return" rows="4" cols="50"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-1 grid-margin stretch-card">
            <div class="card">
                <div class="card-body px-4 py-2">
                    <div class="mb-3">
                        <h6 class="card-title">Items</h6>
                    </div>

                    <div id="" class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Color</th>
                                    <th>Size</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="showData">
                            </tbody>
                        </table>
                    </div>


                    <div class="col-md-6">
                        <div id="" class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>Product Total</td>
                                        <td class="text-end">
                                            <span class="all_product_total"></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="my-3">
                        <button class="btn btn-primary return_btn">
                            Return <i class="fa-solid fa-arrow-turn-down"
                                style="transform: rotate(90deg); font-size: 10px;"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // show Product function
            function showAddProduct(sale_items) {
                let old_subtotal = sale_items.sub_total;
                let old_quantity = sale_items.qty;
                let max_return_price = parseFloat(old_subtotal / old_quantity);

                // console.log(sale_items);

                $('.showData').append(
                    `<tr class="data_row${sale_items.id}" sale-item-id="${sale_items.id}" product-id="${sale_items?.product?.id}" variant-id="${sale_items?.variant?.id}">
                        <td style="width:60px;margin:0;padding:15px;font-size:12px" >
                            ${sale_items?.product?.name ?? ""}
                        </td>
                        <td  style="width:60px;margin:0;padding:15px;font-size:12px">
                            ${sale_items?.variant?.color_name?.name ?? ""}
                        </td>
                        <td style="width:60px;margin:0;padding:15px;font-size:12px">
                            ${sale_items?.variant?.variation_size?.size ?? ""}
                        </td>
                        <td>
                            <input type="number" product-id="${sale_items.id}" class="form-control return_price product_price${sale_items.id}" old_price="${max_return_price ?? 0}" id="product_price" name="return_price[]" value="${max_return_price ?? 0}" style="width:60px;margin:0;padding:3px;font-size:12px"/>
                        </td>
                        <td>
                            <input type="number" product-id="${sale_items.id}" maxLength="${sale_items.qty}" old_quantity="${sale_items.qty ?? 0}" class="form-control quantity productQuantity${sale_items.id}" name="quantity[]" value="1" style="width:60px;margin:3px;padding:0;font-size:12px" />
                        </td>
                        <td><input type="number" class="form-control subTotal border-0 productTotal${sale_items.id}" old_subtotal="${sale_items.sub_total ?? 0}" name="total_price[]" id="productTotal" readonly value="${max_return_price ?? 0}" style="width:70px;margin:0;padding:3px;font-size:12px"/></td>

                        <td style="padding-top: 20px;">
                            <a href="#" class="btn btn-sm btn-danger btn-icon purchase_delete" style="font-size: 8px; height: 25px; width: 25px;" data-id=${sale_items.id}>
                                <i class="fa-solid fa-trash-can" style="font-size: 0.8rem; margin-top: 2px;"></i>
                            </a>
                        </td>
                    </tr>`
                );

            }

            function calculateMaxReturnQuantity(productId, quantity) {
                let max_quantity = $(`.productQuantity${productId}`).attr('maxLength');
                let unitPrice = parseFloat($(`.product_price${productId}`).val());

                if (quantity > max_quantity) {
                    toastr.warning('Quantity should not exceed ' + max_quantity);
                    $(`.productQuantity${productId}`).val(max_quantity);
                    let subTotal = unitPrice * max_quantity;
                    $(`.productTotal${productId}`).val(subTotal);
                    calculateProductTotal();
                } else if (quantity < 1) {
                    toastr.warning('Quantity should not less than 1');
                    $(`.productQuantity${productId}`).val(1);
                    let subTotal = unitPrice * 1;
                    $(`.productTotal${productId}`).val(subTotal);
                    calculateProductTotal();
                } else {
                    let subTotal = unitPrice * quantity;
                    $(`.productTotal${productId}`).val(subTotal);
                    calculateProductTotal();
                }
            }

            // Function to calculate the subtotal for each product
            $(document).on('keyup', '.quantity', function() {
                let productId = $(this).attr('product-id');
                let quantity = parseInt($(this).val());
                calculateMaxReturnQuantity(productId, quantity);

            });

            $(document).on('click', '.quantity', function() {
                let productId = $(this).attr('product-id');
                let quantity = parseInt($(this).val());
                calculateMaxReturnQuantity(productId, quantity);
            });

            function calculateReturnPrice(productId, return_price) {

                let quantity = parseInt($(`.productQuantity${productId}`).val());
                let max_return_price = $(`.product_price${productId}`).attr('old_price');

                if (max_return_price < return_price) {
                    toastr.warning('Return Price should not exceed selling price. Your Selling Price is' +
                        max_return_price);
                    $(`.product_price${productId}`).val(max_return_price);

                    let subTotal = max_return_price * quantity;
                    $(`.productTotal${productId}`).val(subTotal);
                    calculateProductTotal();
                } else {
                    let subTotal = return_price * quantity;
                    $(`.productTotal${productId}`).val(subTotal);
                    calculateProductTotal();
                }
            }

            $(document).on('keyup', '.return_price', function() {
                // alert('Return price');
                let productId = $(this).attr('product-id');
                let return_price = parseFloat($(this).val());
                calculateReturnPrice(productId, return_price);
            });

            $(document).on('click', '.return_price', function() {
                let productId = $(this).attr('product-id');
                let return_price = parseFloat($(this).val());
                calculateReturnPrice(productId, return_price);
            });

            // Select product
            $(document).on('change', '.product_select', function() {
                let id = $(this).val();
                if ($(`.data_row${id}`).length === 0 && id) {
                    $.ajax({
                        url: '/return/find/' + id,
                        type: 'GET',
                        dataType: 'JSON',
                        success: function(res) {
                            showAddProduct(res.sale_items);
                            calculateProductTotal();
                        }
                    });
                }
            });

            // Purchase delete
            $(document).on('click', '.purchase_delete', function(e) {
                let id = $(this).attr('data-id');
                let dataRow = $('.data_row' + id);
                dataRow.remove();
                calculateProductTotal();
            });

            // Function to calculate the grand total from all products
            function calculateProductTotal() {
                let allProductTotal = document.querySelectorAll('#productTotal');
                let allTotal = 0;
                allProductTotal.forEach(product => {
                    let productValue = parseFloat(product.value);
                    if (!isNaN(productValue)) {
                        allTotal += productValue;
                    }
                });

                $('.all_product_total').text(allTotal.toFixed(2));
            }
            calculateProductTotal();

            function returnInvoice() {

                let sale_id = '{{ $sale->id }}';
                let customer_id = '{{ $customer->id }}';
                let return_date = $('.return_date').val();
                let formattedReturnDate = moment(return_date, 'DD-MMM-YYYY').format('YYYY-MM-DD HH:mm:ss');
                let adjustDue = $('.adjust_due').val();
                let paymentMethod = $('.payment_method').val();
                // console.log(adjustDue);

                let refund_amount = parseFloat($('.all_product_total').text());
                let note = $('.return_purpose').val();

                let sale_items = [];

                $('tr[class^="data_row"]').each(function() {
                    let row = $(this);
                    // Get values from the current row's elements

                    let sale_item_id = parseInt($(this).attr('sale-item-id'));
                    // let product_id = parseInt($(this).attr('product-id'));
                    // let variant_id = parseInt($(this).attr('variant-id'));
                    let quantity = row.find('input[name="quantity[]"]').val();
                    let return_price = row.find('input[name="return_price[]"]').val();

                    let total_price = row.find('input[name="total_price[]"]').val();
                    // console.log(productDiscount);

                    let product = {
                        sale_item_id,
                        // product_id,
                        // variant_id,
                        quantity,
                        return_price,
                        total_price
                    };

                    // Push the object into the products array
                    sale_items.push(product);
                });
                // console.log(products);

                let allData = {
                    sale_id,
                    customer_id,
                    formattedReturnDate,
                    refund_amount,
                    note,
                    sale_items,
                    adjustDue,
                    paymentMethod
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '/return/store',
                    type: 'POST',
                    data: allData,
                    success: function(res) {
                        if (res.status == 200) {
                            toastr.success(res.message);
                            window.location.href = '/return/products/list';
                        } else {
                            toastr.warning("Something Went Wrong");
                        }
                    }
                });
            }

            // order btn
            $('.return_btn').click(function(e) {
                e.preventDefault();
                returnInvoice();
            })
        })
    </script>
@endsection
