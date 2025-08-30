@extends('master')
@section('title')
    | {{ $loan->loan_name }}
@endsection
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">
                Loan Ledger
            </li>
        </ol>
    </nav>

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
                                <a href="#" class="noble-ui-logo logo-light d-block mt-3">EIL<span>Accounts</span></a>
                            @endif
                            <hr>
                            <p class="show_branch_address">{{ $branch->address ?? '' }}</p>
                            <p class="show_branch_email">{{ $branch->email ?? '' }}</p>
                            <p class="show_branch_phone">{{ $branch->phone ?? '' }}</p>
                        </div>
                        <div>
                            @if ($loan->loan_balance > 0)
                                <button type="button"
                                    class="btn btn-outline-primary btn-icon-text float-left add_money_modal"
                                    id="payment-btn" data-bs-toggle="modal" data-bs-target="#duePayment">
                                    <i class="btn-icon-prepend" data-feather="credit-card"></i>
                                    Payment
                                </button>
                            @endif
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
                                                <td>
                                                    <b><i>Loan Name</i></b>
                                                </td>
                                                <td>
                                                    {{ $loan->loan_name ?? '' }}
                                                </td>
                                                <td><b><i>Bank Account name</i></b></td>
                                                <td>{{ $loan->bankAccounts->account_name ?? '' }}</td>
                                                <td>
                                                    <b><i>Loan Principal</i></b>
                                                </td>
                                                <td>{{ number_format($loan->loan_principal, 2) ?? 0 }}</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b><i>Total Loan Amount</i></b>
                                                </td>
                                                <td>{{ number_format($loan->loan_balance, 2) ?? 00 }}</td>
                                                <td>
                                                    <b><i>Interest Rate</i></b>
                                                </td>
                                                <td>{{ number_format($loan->interest_rate) ?? 0 }} %</td>
                                                <td>
                                                    <b><i>Repayment Schedule</i></b>
                                                </td>
                                                <td class="text-capitalize">{{ $loan->repayment_schedule ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b><i>Loan Duration</i></b>
                                                </td>
                                                <td>{{ $loan->loan_duration ?? 0 }} Years</td>
                                                <td>
                                                    <b><i>Start Date</i></b>
                                                </td>
                                                <td>{{ $loan->start_date ?? '' }}</td>
                                                <td>
                                                    <b><i>End Date</i></b>
                                                </td>
                                                <td>{{ $loan->end_date ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b><i>Installments Amount</i></b>
                                                </td>
                                                @php
                                                    // $repayment_amount = 0;
                                                    $total_duration = 0;
                                                    if ($loan->repayment_schedule == 'daily') {
                                                        $total_duration = $loan->loan_duration * 365;
                                                    } elseif ($loan->repayment_schedule == 'weekly') {
                                                        $total_duration = $loan->loan_duration * 52;
                                                    } elseif ($loan->repayment_schedule == 'monthly') {
                                                        $total_duration = $loan->loan_duration * 12;
                                                    } else {
                                                        $total_duration = $loan->loan_duration;
                                                    }
                                                    $repayment_amount = $loan->loan_balance / $total_duration;
                                                @endphp

                                                <td>{{ number_format($repayment_amount, 2) ?? 0 }}</td>
                                                <td>
                                                    <b><i>Total Installments</i></b>
                                                </td>
                                                <td>{{ $total_duration ?? 0 }}</td>
                                                <td>
                                                    <b><i>Paid Intallment</i></b>
                                                </td>
                                                <td>{{ $loan_repayments->count() ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b><i>Remaining Installments</i></b>
                                                </td>
                                                <td>{{ $total_duration - $loan_repayments->count() ?? 0 }}</td>
                                                <td>
                                                    <b><i>Total Paid Amount</i></b>
                                                </td>
                                                <td>{{ number_format($repayment_amount * $loan_repayments->count(), 2) ?? 0 }}
                                                </td>
                                                <td>
                                                    <b><i>Remaining Loan Amount</i></b>
                                                </td>
                                                <td>{{ number_format($loan->loan_balance - $repayment_amount * $loan_repayments->count(), 2) ?? 0 }}
                                                </td>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Invoice No.</th>
                                                <th>Instalment No.</th>
                                                <th>Date</th>
                                                <th>Payment Method</th>
                                                <th>Principal Paid</th>
                                                <th>Interest Paid</th>
                                                <th>Total Paid</th>
                                                <th class="action">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @if ($loan_repayments->count() > 0)
                                                @php
                                                    function ordinal($number)
                                                    {
                                                        $suffixes = ['th', 'st', 'nd', 'rd', 'th'];
                                                        $remainder = $number % 100;
                                                        return $number .
                                                            ($suffixes[($remainder - 20) % 10] ??
                                                                ($suffixes[$remainder] ?? $suffixes[0]));
                                                    }
                                                @endphp
                                                @foreach ($loan_repayments as $index => $data)
                                                    <tr>
                                                        <td><a
                                                                href="{{ route('loan.instalment.invoice', $data->id) }}">{{ 'INV-' . now()->year . '-' . str_pad($data->id, 5, '0', STR_PAD_LEFT) }}</a>
                                                        </td>
                                                        <td>{{ ordinal($index + 1) }} Installment</td>
                                                        <td>{{ $data->repayment_date ?? '' }}</td>
                                                        <td>
                                                            {{ $data->bankAccounts->name ?? '' }}
                                                        </td>
                                                        <td>
                                                            {{ $data->principal_paid ?? 0 }}
                                                        </td>
                                                        <td>
                                                            {{ $data->interest_paid ?? 0 }}
                                                        </td>
                                                        <td>
                                                            {{ $data->total_paid ?? 0 }}
                                                        </td>
                                                        <td class="action"><a href="#"
                                                                class="btn-sm btn-outline-primary  float-end printLoan"
                                                                data-id="{{ $data->id }}" type="loan">
                                                                <i data-feather="printer" class="me-2 icon-md"></i>
                                                            </a></td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="6" class="text-center">No Data Found</td>
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
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Loan Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="addPaymentForm" class="addPaymentForm row" method="POST">
                        <input type="hidden" name="data_id" id="data_id" value="{{ $loan->id }}">
                        <input type="hidden" name="payment_balance" id="payment_balance" value="{{ $repayment_amount }}">
                        <div class="col-md-12">
                            <label for="name" class="form-label">Installment Amount : <span id="due-amount">
                                    {{ number_format($repayment_amount, 2) }}</span> ৳ </label> <br>
                            {{-- <label for="remaining" class="form-label">Remaining Due:
                                <span class="text-danger" id="remaining-due">
                                    {{$repayment_amount }} </span>৳
                            </label> --}}
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="name" class="form-label">Payment Account<span
                                    class="text-danger">*</span></label>
                            <select class="form-control payment_account_id" name="payment_account_id"
                                onchange="errorRemove(this);">
                                <option value="">Select Payment Account</option>
                                @foreach ($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger payment_account_id_error"></span>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="name" class="form-label">Repayment Date<span
                                    class="text-danger">*</span></label>
                            <div class="input-group flatpickr me-2 mb-2 mb-md-0" id="dashboardDate">
                                <span class="input-group-text input-group-addon bg-transparent border-primary"
                                    data-toggle><i data-feather="calendar" class="text-primary"></i></span>
                                <input type="text" name="repayment_date"
                                    class="form-control bg-transparent border-primary repayment_date"
                                    placeholder="Select date" data-input>
                            </div>
                            <span class="text-danger repayment_date_error"></span>
                        </div>

                        {{-- <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Balance Amount <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control add_amount payment_balance" name="payment_balance"
                                onkeyup="errorRemove(this);">
                            <span class="text-danger payment_balance_error"></span>
                        </div> --}}

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a type="button" class="btn btn-primary" id="add_payment">Payment</a>
                </div>

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
            .action,
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
                if (tag == 'select') {
                    $(element).css('border-color', 'green');
                    $(element).siblings('span').hide();
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
            let paymentBalance = parseFloat($('#payment_balance').val());
            // console.log('form:',formData,'balance:',paymentBalance);
            // CSRF Token setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // // AJAX request
            $.ajax({
                url: '/loan-repayments/store',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    // console.log(res);
                    if (res.status == 200) {
                        // Hide the correct modal
                        $('#duePayment').modal('hide');
                        // Reset the form
                        $('.addPaymentForm')[0].reset();
                        toastr.success(res.message);
                        window.location.reload();
                    } else if (res.status == 400) {
                        toastr.warning(res.message);
                    } else {
                        // console.log(res.error);
                        if (res.message) {
                            toastr.error(res.error);
                        }
                        if (res.error.data_id) {
                            toastr.error(res.error.data_id);
                        }
                        if (res.error.account_type) {
                            showError('.account_type', res.error.account_type);
                        }
                        if (res.error.account_type) {
                            showError('.account_type', res.error.account_type);
                        }
                        if (res.error.payment_account_id) {
                            showError('.payment_account_id', res.error.payment_account_id);
                        }
                        if (res.error.repayment_date) {
                            showError('.repayment_date', res.error.repayment_date);
                        }
                        if (res.error.payment_balance) {
                            toastr.error(res.error.payment_balance);
                        }
                    }
                },
                error: function(err) {
                    toastr.error('An error occurred, Something Went Wrong.');
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


        // document.addEventListener("DOMContentLoaded", function() {
        //     modalShowHide('duePayment');
        // });

        $('.printLoan').click(function(e) {
            e.preventDefault();
            let id = $(this).attr('data-id');
            let type = $(this).attr('type');
            var printFrame = $('#printFrame')[0];

            let printLoanContentUrl = '/loan/instalment/invoice' + id;
            console.log(printLoanContentUrl);
            $('#printFrame').attr('src', printLoanContentUrl);
            printFrame.onload = function() {
                printFrame.contentWindow.focus();
                printFrame.contentWindow.print();
            };
        })


    </script>
@endsection
