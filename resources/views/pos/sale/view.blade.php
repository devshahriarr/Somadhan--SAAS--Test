@extends('master')
@section('title', '| Sale History')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Sale Manage</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12   grid-margin stretch-card filter_box">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="input-group flatpickr" id="flatpickr-date">
                                <input type="text" class="form-control from-date flatpickr-input start-date"
                                    placeholder="Start date" data-input="" readonly="readonly">
                                <span class="input-group-text input-group-addon" data-toggle=""><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-calendar">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2">
                                        </rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group flatpickr" id="flatpickr-date">
                                <input type="text" class="form-control from-date flatpickr-input end-date"
                                    placeholder="End date" data-input="" readonly="readonly">
                                <span class="input-group-text input-group-addon" data-toggle=""><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-calendar">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2">
                                        </rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg></span>
                            </div>
                        </div>
                        @php
                            $products = App\Models\Product::all();
                            $customers = App\Models\Customer::whereIn('party_type', ['customer', 'both'])->get();
                        @endphp
                        <div class="col-md-3">
                            <div class="input-group flatpickr" id="flatpickr-date">
                                <select class="js-example-basic-single form-select product_select" data-width="100%">
                                    @if ($products->count() > 0)
                                        <option selected disabled>Select Product</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    @else
                                        <option selected disabled>Please Add Product</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group flatpickr" id="flatpickr-date">
                                <select class="js-example-basic-single form-select select-supplier customer_id"
                                    data-width="100%" name="">
                                    @if ($customers->count() > 0)
                                        <option selected disabled>Select Customer</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    @else
                                        <option selected disabled>Please Add Customer</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="justify-content-left">
                                <button class="btn btn-sm bg-info text-dark mr-2" id="filter">Filter</button>
                                <button class="btn btn-sm bg-primary text-dark" id="reset">Reset</button>
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="flex text-md-end ">
                                {{-- <button type="button"
                                    class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0 print-btn">
                                    <i class="btn-icon-prepend" data-feather="printer"></i>
                                    Print
                                </button> --}}
                                {{-- <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
                                    <i class="btn-icon-prepend" data-feather="download-cloud"></i>
                                   All Invoice
                                </button> - --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-title">Sales Table</h6>
                                <button type="button" data-bs-toggle="modal" data-bs-target="#invoiceModal"
                                    class="btn btn-primary btn-icon-text mb-md-0 d-flex align-items-center gap-1">
                                    <i class="btn-icon-prepend" data-feather="download-cloud"></i>
                                    All Invoice
                                </button>
                            </div>
                        </div>
                    </div>
                    {{-- //////////////// Modal all Invoice ////////// --}}
                    <div class="modal fade" id="invoiceModal" tabindex="-1"
                        aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalScrollableTitle">Preview</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                                    <div class="table-responsive">
                                        <table class="table ">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Invoice</th>
                                                    <th>Details</th>
                                                    {{-- <th>Total</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody id="showDataModal">
                                                @include('pos.sale.all-invoice-print')

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" onclick="printModalContent()"
                                        class="btn btn-success">Print/Save PDF</button>
                                    <button type="button" class="btn btn-info">Save PDF</button>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="table-responsive">
                        <table id="saleAndPurchaseTableData" class="table">
                            <thead>
                                <tr>
                                    <th class="id">#</th>
                                    <th>Invoice <br>Number</th>
                                    <th>Customer</th>
                                    {{-- <th>Items</th> --}}
                                    <th>Quantity</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Discount</th>
                                    <th>Previous Due</th>
                                    <th>Receivable</th>
                                    <th>Paid</th>
                                    <th>Product <br> Returned</th>
                                    <th>Due/Return</th>
                                    <th>Purchase <br> Cost</th>
                                    <th>Profit</th>
                                    <th>Status</th>
                                    <th class="id">Action</th>
                                </tr>
                            </thead>
                            <tbody id="showData">
                                @include('pos.sale.table')
                            </tbody>
                        </table>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="paymentForm row">
                        <div class="mb-3 col-md-12">
                            <label for="name" class="form-label">Payment Date<span
                                    class="text-danger">*</span></label>
                            <div class="input-group flatpickr" id="flatpickr-date">
                                <input type="text" class="form-control from-date flatpickr-input payment_date"
                                    placeholder="Payment Date" data-input="" readonly="readonly" name="payment_date">
                                <span class="input-group-text input-group-addon" data-toggle=""><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2">
                                        </rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg></span>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Transaction Account<span
                                    class="text-danger">*</span></label>
                            @php
                                $payments = App\Models\Bank::all();
                            @endphp
                            <select class="form-select transaction_account" data-width="100%" name="transaction_account"
                                onclick="errorRemove(this);" onblur="errorRemove(this);">
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
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Amount<span class="text-danger">*</span></label>
                            <input id="defaultconfig" class="form-control amount" maxlength="39" name="amount"
                                type="number" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger amount_error"></span>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="name" class="form-label">Note</label>
                            <textarea name="note" class="form-control note" id="" placeholder="Enter Note (Optional)"
                                rows="3"></textarea>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_payment">Payment</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .page-content {
                margin-top: 0 !important;
                padding-top: 0 !important;
                width: 100% !important;
            }

            button,
            a,
            .filter_box,
            nav,
            .footer,
            .id,
            .dataTables_filter,
            .dataTables_length,
            .dataTables_info {
                display: none !important;
            }

            table {
                padding-right: 50px !important;
            }

            /* table th {
                                                                                            font-size: 5px;
                                                                                        } */

            #saleTableData td {
                word-wrap: break-word;
                white-space: normal;
                /* Ensures the content can wrap */
            }

            #saleTableData td ul {
                padding-left: 0;
                /* Remove default padding */
                list-style-type: none;
                /* Remove list style */
                word-wrap: break-word;
                display: grid !important;
                grid-column: 1;
            }

            #saleTableData td ul li {
                word-wrap: break-word;
                padding-bottom: 5px;
                /* Adds some spacing between list items */
            }

            /* If the column still looks squeezed, adjust its width */
            #saleTableData td:nth-child(4) {
                width: 10%;
                /* Adjust this value as per your table design */
            }
        }
    </style>

    <script>
        // error remove
        function errorRemove(element) {
            if (element.value != '') {
                $(element).siblings('span').hide();
                $(element).css('border-color', 'green');
            }
        }

        $(document).ready(function() {

            // $('#saleAndPurchaseTableData').DataTable({
            //     processing: true,
            //     serverSide: true,
            //     ajax: '{{ route('sale.view') }}', // আপনার route
            //     columns: [{
            //             data: 'id',
            //             name: 'id'
            //         },
            //         {
            //             data: 'invoice_number',
            //             name: 'invoice_number'
            //         },
            //         {
            //             data: 'customer_name',
            //             name: 'customer_name'
            //         },
            //         {
            //             data: 'quantity',
            //             name: 'quantity'
            //         },
            //         {
            //             data: 'sale_date',
            //             name: 'sale_date'
            //         },
            //         {
            //             data: 'total',
            //             name: 'total'
            //         },
            //         {
            //             data: 'actual_discount',
            //             name: 'actual_discount'
            //         },
            //         {
            //             data: 'previous_due',
            //             name: 'previous_due'
            //         },
            //         {
            //             data: 'receivable',
            //             name: 'receivable'
            //         },
            //         {
            //             data: 'paid',
            //             name: 'paid'
            //         },
            //         {
            //             data: 'product_returned',
            //             name: 'product_returned'
            //         },
            //         {
            //             data: 'due',
            //             name: 'due'
            //         },
            //         {
            //             data: 'purchase_cost',
            //             name: 'purchase_cost'
            //         },
            //         {
            //             data: 'profit',
            //             name: 'profit'
            //         },
            //         {
            //             data: 'status',
            //             name: 'status'
            //         },
            //         {
            //             data: 'action',
            //             name: 'action',
            //             orderable: false,
            //             searchable: false
            //         }
            //     ]
            // });

            // show error
            function showError(name, message) {
                $(name).css('border-color', 'red');
                $(name).focus();
                $(`${name}_error`).show().text(message);
            }


            // add payment
            $(document).on('click', '.add_payment', function(e) {
                e.preventDefault();
                // alert('ok');
                let id = $(this).attr('data-id');
                // console.log(`Purchase#${id}`);
                var currentDate = new Date().toISOString().split('T')[0];
                $('.payment_date').val(currentDate);
                $('.save_payment').val(id);


                $.ajax({
                    url: '/sale/find/' + id,
                    method: "GET",
                    success: function(res) {
                        // console.log(res);
                        if (res.status == 200) {
                            // console.log(res);
                            $('.amount').val(res.data.due);
                        }
                    }
                })
            });

            // save payment
            $(document).on('click', '.save_payment', function(e) {
                e.preventDefault();
                let id = $(this).val();
                // alert(id);
                let formData = new FormData($('.paymentForm')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: `/sale/transaction/${id}`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            // console.log(res.purchase);
                            // jQuery('#showData').html(res);
                            $('#paymentModal').modal('hide');
                            $('.paymentForm')[0].reset();
                            window.location.href = '{{ route('sale.view') }}'
                            toastr.success(res.message);
                        } else {
                            // console.log(res.error);
                            if (res.error.paid) {
                                showError('.amount', res.error.paid);
                            }
                            if (res.error.amount) {
                                showError('.amount', res.error.amount);
                            }
                            if (res.error.payment_method) {
                                showError('.transaction_account', res.error
                                    .payment_method);
                            }
                        }
                    }
                });
            })




            // filter
            document.querySelector('#filter').addEventListener('click', function(e) {
                e.preventDefault();
                // alert('ok');
                let startDate = document.querySelector('.start-date').value;
                let endDate = document.querySelector('.end-date').value;

                let product_id = document.querySelector('.product_select').value;
                let customer_id = document.querySelector('.customer_id').value;

                // alert(supplier_id);
                $.ajax({
                    url: "{{ route('sale.filter') }}",
                    method: 'GET',
                    data: {
                        startDate,
                        endDate,
                        product_id,
                        customer_id,
                    },
                    success: function(res) {
                        jQuery('#showData').html(res.salesTable);
                        jQuery('#showDataModal').html(res.saleInvoiceTable);
                    }
                });
            });

            // reset
            document.querySelector('#reset').addEventListener('click', function(e) {
                e.preventDefault();
                $('.start-date').val("");
                $('.end-date').val("");
                $('.product_select').val('Select Product').trigger('change');
                $('.customer_id').val('Select Customer').trigger('change');
            });

            // print
            document.querySelector('.print-btn').addEventListener('click', function(e) {
                e.preventDefault();
                $('#dataTableExample').removeAttr('id');
                $('.table-responsive').removeAttr('class');
                // Trigger the print function
                window.print();
            });





        });


        function printModalContent() {

            let printContents = document.getElementById('showDataModal').innerHTML; // Get the modal content
            let originalContents = document.body.innerHTML; // Save the current body content

            // Replace the body content with the modal content and open the print dialog
            document.body.innerHTML = '<html><head><title>Print Preview</title></head><body>' + printContents +
                '</body></html>';


            window.print(); // Trigger print dialog
            // $('#invoiceModal').modal('close')
            window.location.reload();
            // Restore the original body content after printing
            document.body.innerHTML = originalContents;
        }
    </script>


@endsection
