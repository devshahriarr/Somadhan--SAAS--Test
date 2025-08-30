@extends('master')
@section('title')
    | {{ $investor->name }}
@endsection
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">
                Investor Profile
            </li>
        </ol>
    </nav>
    {{-- @dd($transactions) --}}

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card border-0 shadow-none">
                <div class="card-body">
                    <div class="container-fluid d-flex justify-content-between">
                        <div class="col-lg-3 ps-0">
                            @if (!empty($invoice_logo_type))
                                @if ($invoice_logo_type == 'Name')
                                    <a href="#" class="noble-ui-logo logo-light d-block mt-3">{{ $siteTitle }}</a>
                                @elseif($invoice_logo_type == 'Logo')
                                    @if (!empty($logo))
                                        <img class="margin_left_m_14" height="100" width="200"
                                            src="{{ url($logo) }}" alt="logo">
                                    @else
                                        <p class="mt-1 mb-1 show_branch_name"><b>{{ $siteTitle }}</b></p>
                                    @endif
                                @elseif($invoice_logo_type == 'Both')
                                    @if (!empty($logo))
                                        <img class="margin_left_m_14" height="90" width="150"
                                            src="{{ url($logo) }}" alt="logo">
                                    @endif
                                    <p class="mt-1 mb-1 show_branch_name"><b>{{ $siteTitle }}</b></p>
                                @endif
                            @else
                                <a href="#" class="noble-ui-logo logo-light d-block mt-3">EIL<span>Electro</span></a>
                            @endif
                            <hr>
                            <p class="show_branch_address">{{ $branch->address ?? '' }}</p>
                            <p class="show_branch_email">{{ $branch->email ?? '' }}</p>
                            <p class="show_branch_phone">{{ $branch->phone ?? '' }}</p>
                        </div>
                        <div>

                            <button type="button"
                                class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0 print-btn">
                                <i class="btn-icon-prepend" data-feather="printer"></i>
                                Print
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body show_ledger">
                    <div class="container-fluid w-100">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td>Account Of</td>
                                                <td>{{ $investor->name ?? '' }}</td>
                                                <td>Contact No.</td>
                                                <td>{{ $investor->phone ?? '' }}</td>
                                                <td>Type</td>
                                                <td>{{ $investor->type ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Debit</td>
                                                <td>{{ $investor->debit ?? '' }}</td>
                                                <td>Credit</td>
                                                <td>{{ $investor->credit ?? '' }}</td>
                                                <td>Balance</td>
                                                <td>{{ $investor->balance ?? '' }}</td>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="my-3 text-center">
                        Investor Ledger
                    </h4>
                    <div class="container-fluid w-100">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th class="id">Invoice</th>
                                                <th>Particulars</th>
                                                <th>Total</th>
                                                <th>Debit</th>
                                                <th>Credit</th>
                                                <th>Balance</th>
                                                <th class="id">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @if ($transactions->count() > 0)
                                                @php
                                                    $totalDebit = 0;
                                                    $totalCredit = 0;
                                                    $totalBalance = 0;
                                                @endphp

                                                @foreach ($transactions as $transaction)
                                                    {{-- @dd($transaction->balance) --}}
                                                    <tr>
                                                        <td>{{ \Carbon\Carbon::parse($transaction->date)->format('F j, Y') ?? '' }}
                                                        </td>
                                                        <td class="id">
                                                            <a
                                                                href="{{ route('transaction.invoice.receipt', $transaction->id) }}">
                                                                #{{ rand(000000, 999999) }}
                                                            </a>
                                                        </td>
                                                        <td>
                                                            {{ $transaction->particulars ?? '' }}
                                                        </td>
                                                        <td>
                                                            @if ($transaction->credit)
                                                                {{ number_format($transaction->credit, 2) ?? 0 }}
                                                            @else
                                                                {{ number_format($transaction->debit, 2) ?? 0 }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ number_format($transaction->debit, 2) ?? 0 }}
                                                        </td>
                                                        <td>
                                                            {{ number_format($transaction->credit, 2) ?? 0 }}
                                                        </td>
                                                        <td>
                                                            {{ number_format($transaction->balance, 2) ?? 0 }}
                                                        </td>
                                                        <td class="id">
                                                            <a href="#"
                                                                class="btn-sm btn-outline-primary float-end print"
                                                                data-id="{{ $transaction->id }}" type="transaction">
                                                                <i data-feather="printer" class="me-2 icon-md"></i>
                                                            </a>
                                                        </td>
                                                    </tr>

                                                    @php
                                                        $totalBalance += $transaction->balance ?? 0;
                                                        $totalDebit += $transaction->debit ?? 0;
                                                        $totalCredit += $transaction->credit ?? 0;
                                                    @endphp
                                                @endforeach
                                                <tr>
                                                    <td class="id"></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>Total</td>
                                                    <td> {{ number_format($totalDebit, 2) }}</td>
                                                    <td> {{ number_format($totalCredit, 2) }}</td>
                                                    <td>
                                                        {{ number_format($totalBalance, 2) }} </td>
                                                    <td></td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="7" class="text-center">No Data Found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <iframe id="printFrame" src="" width="0" height="0"></iframe>


    <!-- Modal add Payment -->
    <div class="modal fade" id="duePayment" tabindex="-1" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Due Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="addPaymentForm" class="addPaymentForm row" method="POST">
                        <input type="hidden" name="data_id" id="data_id" value="{{ $investor->id }}">
                        <input type="hidden" name="isCustomer" value="">
                        <input type="hidden" name="due_amount" value="{{ $investor->balance }}">
                        <div>
                            <label for="name" class="form-label">Due Amount : <span id="due-amount">
                                    {{ number_format($investor->balance, 2) }}</span> ৳ </label> <br>
                            <label for="remaining" class="form-label">Remaining Due:
                                <span class="text-danger" id="remaining-due">
                                    {{ number_format($investor->balance, 2) }} </span>৳
                            </label>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Balance Amount <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control add_amount payment_balance" name="payment_balance"
                                onkeyup="dueShow()" onkeydown="errorRemove(this);">
                            <span class="text-danger payment_balance_error"></span>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Transaction Account <span
                                    class="text-danger">*</span></label>
                            <select class="form-control account" name="account" id=""
                                onchange="errorRemove(this);">
                                @foreach ($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger account_error"></span>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a type="button" class="btn btn-primary" id="add_payment">Payment</a>
                </div>
                </form>
            </div>
        </div>
    </div>
    <style>
        #printFrame {
            display: none;
            /* Hide the iframe */
        }

        .table> :not(caption)>*>* {
            padding: 0px 10px !important;
        }

        .margin_left_m_14 {
            margin-left: -14px;
        }

        .w_40 {
            width: 250px !important;
            text-wrap: wrap;
        }

        @media print {
            .page-content {
                margin-top: 0 !important;
                padding-top: 0 !important;
                min-height: 740px !important;
                background-color: #ffffff !important;
            }

            .grid-margin,
            .card,
            .card-body,
            table {
                background-color: #ffffff !important;
                color: #000 !important;
            }

            .footer_invoice p {
                font-size: 12px !important;
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
        }
    </style>

    <script>
        // Error Remove Function 
        function errorRemove(element) {
            tag = element.tagName.toLowerCase();
            if (element.value != '') {
                // console.log('ok');
                if (tag == 'select') {
                    $(element).closest('.mb-3').find('.text-danger').hide();
                } else {
                    $(element).siblings('span').hide();
                    $(element).css('border-color', 'green');
                }
            }
        }

        // Show Error Function 
        function showError(payment_balance, message) {
            $(payment_balance).css('border-color', 'red');
            $(payment_balance).focus();
            $(`${payment_balance}_error`).show().text(message);
        }

        // due Show 
        function dueShow() {
            let dueAmountText = document.getElementById('due-amount').innerText.trim();
            let dueAmount = parseFloat(dueAmountText.replace(/[^\d.-]/g, ''));

            let paymentBalanceText = document.querySelector('.payment_balance').value.trim();
            let paymentBalance = parseFloat(paymentBalanceText)

            let remainingDue = dueAmount - (paymentBalance || 0);
            document.getElementById('remaining-due').innerText = remainingDue.toFixed(2) ?? 0 + ' ৳';

        }


        const savePayment = document.getElementById('add_payment');
        savePayment.addEventListener('click', function(e) {
            // console.log('Working on payment')
            e.preventDefault();

            let formData = new FormData($('.addPaymentForm')[0]);
            // CSRF Token setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // AJAX request
            $.ajax({
                url: '/due/invoice/payment/transaction',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    console.log(res);
                    if (res.status == 200) {
                        // Hide the correct modal
                        $('#duePayment').modal('hide');
                        // Reset the form
                        $('.addPaymentForm')[0].reset();
                        toastr.success(res.message);
                        window.location.reload();
                    } else if (res.status == 400) {
                        showError('.account', res.message);
                    } else {
                        // console.log(res);
                        if (res.error.payment_balance) {
                            showError('.payment_balance', res.error.payment_balance);
                        }
                        if (res.error.account) {
                            showError('.account', res.error.account);
                        }
                    }
                },
                error: function(err) {
                    toastr.error('An error occurred, Empty Feild Required.');
                }
            });
        });


        // print
        document.querySelector('.print-btn').addEventListener('click', function(e) {
            e.preventDefault();
            $('#dataTableExample').removeAttr('id');
            $('.table-responsive').removeAttr('class');
            // Trigger the print function
            window.print();
        });


        $('.print').click(function(e) {
            e.preventDefault();
            let id = $(this).attr('data-id');
            let type = $(this).attr('type');
            var printFrame = $('#printFrame')[0];



            if (type == 'sale') {
                var printContentUrl = '/sale/invoice/' + id;
            } else if (type == 'return') {
                var printContentUrl = '/return/products/invoice/' + id;
            } else if (type == 'purchase') {
                var printContentUrl = '/purchase/invoice/' + id;
            } else {
                var printContentUrl = '/transaction/invoice/receipt/' + id;
            }

            $('#printFrame').attr('src', printContentUrl);
            printFrame.onload = function() {
                printFrame.contentWindow.focus();
                printFrame.contentWindow.print();
            };
        })
    </script>
@endsection
