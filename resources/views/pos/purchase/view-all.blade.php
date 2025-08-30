@extends('master')
@section('title', '| Purchase Report')

@section('admin')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>


    <div class="row">
        <div class="col-md-12 grid-margin stretch-card filter_box">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title">Purchase Report</h6>
                        @if (Auth::user()->can('purchase.add'))
                            <a href="{{ route('purchase') }}" class="btn btn-rounded-primary btn-sm"><i
                                    data-feather="plus"></i></a>
                        @endif

                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="input-group flatpickr" id="flatpickr-date">
                                <input type="text" class="form-control from-date flatpickr-input start-date"
                                    placeholder="Start date" data-input="" readonly="readonly" name="start_date">
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
                                    placeholder="End date" data-input="" readonly="readonly" name="end_date">
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
                                $suppliers = App\Models\Customer::whereIn('party_type', ['supplier', 'both'])->get();
                        @endphp
                        <div class="col-md-3">
                            <div class="input-group flatpickr" id="flatpickr-date">
                                <select class="js-example-basic-single form-select product_select" data-width="100%"
                                    name="product_id">
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
                                <select style="height: 40px !important"
                                    class="js-example-basic-single form-select select-supplier supplier_id"
                                    data-width="100%" name="supplier_id">
                                    @if ($suppliers->count() > 0)
                                        <option selected disabled>Select Supplier</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    @else
                                        <option selected disabled>Please Add Supplier</option>
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
                            <div class="flex text-md-end btn_group">
                                {{-- <button type="button"
                                    class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0 print-btn">
                                    <i class="btn-icon-prepend" data-feather="printer"></i>
                                    Print
                                </button> --}}
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
                                <h6 class="card-title">Purchase Table</h6>
                                <div>

                                    <button id="copyButton" class="btn btn-primary"><i class="fa-solid fa-copy"></i>
                                        Copy</button>
                                    <button id="excelButton" class="btn btn-success"><i
                                            class="fa-solid fa-file-excel"></i> Excel</button>
                                    <button id="csvButton" class="btn btn-info"><i class="fa-solid fa-file-csv"></i>
                                        CSV</button>
                                    <button id="pdfButton" class="btn btn-danger"><i class="fa-solid fa-file"></i>
                                        PDF</button>
                                    <button id="printButton" class="btn btn-warning"><i class="fa-solid fa-print"></i>
                                        Print</button>

                                    <button type="button" data-bs-toggle="modal" data-bs-target="#purchaseInvoiceModal"
                                        class="btn btn-primary btn-icon-text mb-md-0 gap-1">
                                        <i class="btn-icon-prepend" data-feather="download-cloud"></i>
                                        All Invoice
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                    {{-- ////////////////Modal all Invoice ////////// --}}
                    <div class="modal fade" id="purchaseInvoiceModal" tabindex="-1"
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
                                                @include('pos.purchase.all-purchase-invoice-print')
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" onclick="printModalContent()"
                                        class="btn btn-success">Print/Save PDF</button>
                                    {{-- <button type="button"  class="btn btn-info">Save PDF</button> --}}
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="purchaseTableData" class="table">
                            <thead>
                                <tr>
                                    <th class="id">#</th>
                                    <th>Bill Number</th>
                                    <th>Supplier</th>
                                    <th>Purchase Date</th>
                                    <th>Purchase By</th>
                                    <th>Total</th>
                                    <th>Total Extra Cost</th>
                                    <th>Grand Total</th>
                                    <th>Paid</th>
                                    <th>Due</th>
                                    <th>Payment Status</th>
                                    <th>Order Status</th>
                                    <th class="id">Action</th>
                                </tr>
                            </thead>
                            <tbody id="showData">
                                {{-- @include('pos.purchase.table') --}}
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
        }


        table,
        td,
        th {
            vertical-align: middle !important;
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
            // show error
            function showError(name, message) {
                $(name).css('border-color', 'red');
                $(name).focus();
                $(`${name}_error`).show().text(message);
            }


            $('#copyButton').click(function() {
                $('.buttons-copy').click();
            });
            $('#excelButton').click(function() {
                $('.buttons-excel').click();
            });
            $('#csvButton').click(function() {
                $('.buttons-csv').click();
            });
            $('#pdfButton').click(function() {
                $('.buttons-pdf').click();
            });
            $('#printButton').click(function() {
                $('.buttons-print').click();
            });


            // function showAllPurchaseData() {
            $('#purchaseTableData').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('purchase.view.all') }}',
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'copyHtml5',
                        text: '📋 Copy',
                        className: 'btn btn-primary mb-5 d-none',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 5, 6, 7, 8, 9, 10, 11],
                            modifier: {
                                page: 'all'
                            }
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '📊 Excel',
                        className: 'btn btn-success mb-5 d-none',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 5, 6, 7, 8, 9, 10, 11],
                            modifier: {
                                page: 'all'
                            }
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        text: '📜 CSV',
                        className: 'btn btn-info mb-5 d-none',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 5, 6, 7, 8, 9, 10, 11],
                            modifier: {
                                page: 'all'
                            }
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '📄 PDF',
                        className: 'btn btn-danger mb-5 d-none',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 5, 6, 7, 8, 9, 10, 11],
                            modifier: {
                                page: 'all'
                            }
                        },
                        customize: function(doc) {
                            // Adjust column widths to fit the page
                            doc.content[1].table.widths = Array(doc.content[1].table.body[0]
                                    .length)
                                .fill('*');
                            // Center align headers and body content
                            doc.styles.tableHeader.alignment = 'center';
                            doc.content[1].table.body.forEach(row => {
                                row.forEach(cell => {
                                    cell.alignment = 'center';
                                });
                            });
                        }
                    },
                    {
                        extend: 'print',
                        text: '🖨️ Print',
                        className: 'btn btn-warning mb-5 d-none',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 5, 6, 7, 8, 9, 10, 11],
                            modifier: {
                                search: 'applied',
                                order: 'applied',
                                page: 'all'
                            }
                        },
                        customize: function(win) {
                            // Adjust print layout
                            $(win.document.body).css('font-size', '10pt');
                            $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit')
                                .css('width', '100%');
                            // Center align images in print view
                            $(win.document.body).find('img').css({
                                'display': 'block',
                                'margin': '0 auto'
                            });
                        }
                    }
                ],
                columns: [{
                        data: null,
                        name: 'SL No',
                        render: function(data, type, row, meta) {
                            // console.log(data);
                            let pageInfo = $('#purchaseTableData').DataTable().page.info();
                            return pageInfo.start + meta.row + 1;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'invoice_number',
                        name: 'invoice_number'
                    },
                    {
                        data: 'supplier_name',
                        name: 'supplier_name'
                    },
                    {
                        data: 'purchase_date',
                        name: 'purchase_date'
                    },
                    {
                        data: 'pruchase_by_name',
                        name: 'pruchase_by_name'
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount'
                    },
                    {
                        data: 'total_purchase_cost',
                        name: 'total_purchase_cost'
                    },
                    {
                        data: 'grand_total',
                        name: 'grand_total'
                    },
                    {
                        data: 'paid',
                        name: 'paid'
                    },
                    {
                        data: 'due',
                        name: 'due'
                    },
                    {
                        data: 'payment_status',
                        name: 'payment_status'
                    },
                    {
                        data: 'order_status',
                        name: 'order_status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
            // }
            // showAllPurchaseData();

            // filter
            document.querySelector('#filter').addEventListener('click', function(e) {
                e.preventDefault();
                // alert('ok');
                let startDate = document.querySelector('.start-date').value;
                let endDate = document.querySelector('.end-date').value;

                let product_id = document.querySelector('.product_select').value;
                let supplier_id = document.querySelector('.supplier_id').value;

                // alert(supplier_id);
                $.ajax({
                    url: "{{ route('purchase.filter') }}",
                    method: 'GET',
                    data: {
                        startDate,
                        endDate,
                        product_id,
                        supplier_id,
                    },
                    success: function(res) {
                    //   if ($.fn.DataTable.isDataTable('#example')) {
                    //         $('#example').DataTable().destroy();
                    //     }
                        jQuery('#showData').html(res.purchaseTable);
                        jQuery('#showDataModal').html(res.purchaseInvoice);
                        //  initializeDataTable();
                    }

                });
            });

            // reset
            document.querySelector('#reset').addEventListener('click', function(e) {
                e.preventDefault();
                $('.start-date').val("");
                $('.end-date').val("");
                $('.product_select').val(null).trigger('change');
                $('.supplier_id').val(null).trigger('change');
            });

            // print
            $('.print-btn').click(function() {
                // Remove the id attribute from the table
                $('#dataTableExample').removeAttr('id');
                $('.table-responsive').removeAttr('class');
                // Trigger the print function
                window.print();
                // Restore the id attribute after printing
                // $('#dataTableExample').attr('id', 'dataTableExample');
            });

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
                    url: '/purchase/find/' + id,
                    method: "GET",
                    success: function(res) {
                        // console.log(res);
                        if (res.status == 200) {
                            // console.log(res);
                            $('.amount').val(res.data.due);
                            $('.amount').attr('maxlength', res.data.due);
                        } else {
                            toastr.warning("Data Not Found")
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
                    url: `/purchase/transaction/${id}`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            // console.log(res.purchase);
                            // jQuery('#showData').html(res.purchase);
                            $('#paymentModal').modal('hide');
                            $('.paymentForm')[0].reset();
                            window.location.reload();
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
                                showError('.transaction_account', res.error.payment_method);
                            }
                        }
                    }
                });
            })

            $(document).on('click', '.money_receipt', function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id');
                $.ajax({
                    url: '/purchase/find/' + id,
                    method: "GET",
                    success: function(res) {
                        // console.log(res);
                        if (res.status == 200) {
                            let imageUrl = `/uploads/purchase/${res.data.document}`;
                            // Extract the file extension
                            if (imageUrl) {
                                let fileExtension = imageUrl.split('.').pop().toLowerCase();
                                if (fileExtension !== 'pdf') {
                                    $('.show_doc').html(
                                        `<img src="${imageUrl}" width="100%" height="500px" alt="Image" />`
                                    );
                                } else {
                                    $('.show_doc').html(
                                        `<iframe src="${imageUrl}" width="100%" height="500px"></iframe>`
                                    );
                                }
                            }
                        } else {
                            // Handle other status codes or errors if needed
                        }
                    }
                })
            })
        });


        $(document).on('click', '.delete_invoice', function(e) {
            e.preventDefault();
            let id = $(this).attr('data-id');

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to Delete this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/purchase/destroy/${id}`,
                        type: 'GET',
                        success: function(data) {
                            if (data.status == 200) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Your file has been deleted.",
                                    icon: "success"
                                });
                                window.location.reload();
                            } else {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "warning",
                                    title: "Deleted Unsuccessful!",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        }
                    });
                }
            });
        });


    </script>
@endsection
