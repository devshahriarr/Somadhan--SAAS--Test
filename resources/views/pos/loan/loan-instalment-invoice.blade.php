@extends('master')
@section('title', '| loan Repayments Invoice')
@section('admin')
    @php
        $branch = App\Models\Branch::findOrFail($loanRepayments->branch_id);
        $loan = App\Models\LoanManagement\Loan::findOrFail($loanRepayments->loan_id );
    @endphp
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-none">
                <div class="card-body ">
                    <div class="container-fluid d-flex justify-content-between">
                        <div class="col-lg-3 ps-0">
                            <a href="#" class="noble-ui-logo logo-light d-block mt-3">EIL<span>POS</span></a>
                            <p class="mt-1 mb-1 show_branch_name"><b>{{ $branch->name ?? '' }}</b></p>
                            <p class="show_branch_address">{{ $branch->address ?? 'accordion ' }}</p>
                            <p class="show_branch_email">{{ $branch->email ?? '' }}</p>
                            <p class="show_branch_phone">{{ $branch->phone ?? '' }}</p>

                        </div>
                        <div class="col-lg-3 mt-4 pe-0 text-end">

                            <p>Invoice No: {{ 'INV-' . now()->year . '-' . str_pad($loanRepayments->id, 5, '0', STR_PAD_LEFT) }}</p>
                            <p class="show_supplier_address fw-normal mt-1"><span class="">Repayment Schedule : </span> <b>{{ $loan->repayment_schedule ?? '' }}</b> </p>
                            <p class="show_supplier_email fw-normal  mt-1"> <span class="">Total Loan :</span> <b>{{ $loan->loan_balance ?? '' }} ৳</b></p>
                            <h6 class="mb-0 mt-4 text-end fw-normal  mt-1"><span class="text-muted show_purchase_date">Invoice
                                    Date :</span> {{ $loanRepayments->created_at ? $loanRepayments->created_at->format('d M Y') : '' }}
                                </h6>
                        </div>
                    </div>
                    <div class="container-fluid mt-5 d-flex justify-content-center w-100">
                        <div class="table-responsive w-100">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Loan Name</th>
                                        <th>Payments Date</th>
                                        <th class="text-end">Bank Account name</th>
                                        <th class="text-end">Principal Paid</th>
                                        <th class="text-end">Interest Paid</th>
                                        <th class="text-end">Total Paid</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-end">
                                        <td class="text-start">{{ $loan->loan_name ?? '' }}</td>
                                        <td class="text-start">{{ $loanRepayments->repayment_date ?? '' }}</td>
                                        <td>{{ $loanRepayments->bankAccounts->name ?? ''}}</td>
                                        <td>{{ $loanRepayments->principal_paid ?? ''}}</td>
                                        <td>{{ $loanRepayments->interest_paid ?? ''}}</td>
                                        <td>{{ $loanRepayments->total_paid ?? ''}}</td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="container-fluid mt-5 w-100">
                        <div class="row">
                            {{-- <div class="col-md-6 ms-auto">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>Sub Total</td>
                                                <td class="text-end">৳ {{ number_format($loanRepayments->total_amount, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Previous Due</td>
                                                <td class="text-end">৳
                                                    {{ number_format(max(0, $loanRepayments->grand_total - $loanRepayments->total_amount), 2) }}
                                                </td>
                                            </tr>

                                            @if ($loanRepayments->carrying_cost > 0)
                                                <tr>
                                                    <td>Carrying Cost</td>
                                                    <td class="text-end">৳ {{ number_format($loanRepayments->carrying_cost, 2) }}
                                                    </td>
                                                </tr>
                                            @endif

                                            <tr>
                                                <td class="text-bold-800">Grand Total</td>
                                                <td class="text-bold-800 text-end">৳
                                                    {{ number_format($loanRepayments->grand_total + $loanRepayments->carrying_cost, 2) }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Payment Made</td>
                                                <td
                                                    class="text-end {{ $loanRepayments->grand_total <= $purchase->paid ? 'text-success' : 'text-danger' }}">
                                                    {{ $purchase->grand_total <= $purchase->paid ? '৳' : '(-) ৳' }}
                                                    {{ number_format($purchase->paid + ($purchase->carrying_cost > 0 ? $purchase->carrying_cost : 0), 2) }}
                                                </td>
                                            </tr>

                                            @if ($purchase->due != 0)
                                                <tr class="bg-dark">
                                                    <td class="text-bold-800">Balance Due</td>
                                                    <td class="text-bold-800 text-end">৳
                                                        {{ number_format($purchase->due, 2) }}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    <div class="container-fluid w-100 btn_group">
                        {{-- <a href="javascript:;" class="btn btn-primary float-end mt-4 ms-2"><i data-feather="send"
                                class="me-3 icon-md"></i>Send Invoice</a> --}}
                        <a href="javascript:;" class="btn btn-outline-primary float-end mt-4" onclick="window.print();"><i
                                data-feather="printer" class="me-2 icon-md"></i>Print</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="w-100 mx-auto btn_group">
                <a href="{{ url()->previous() }}" class="btn btn-primary  mt-4 ms-2"><i
                        class="fa-solid fa-arrow-rotate-left me-2"></i>Back</a>

            </div>
        </div>
    </div>

    <style>
        @media print {

            nav,
            .footer {
                display: none !important;
            }

            .page-content {
                margin-top: 0 !important;
                padding-top: 0 !important;
            }

            .btn_group {
                display: none !important;
            }
        }
    </style>
@endsection
