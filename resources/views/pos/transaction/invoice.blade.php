@extends('master')
@section('title','| Transaction Invoice')
@section('admin')
<div class="row" bis_skin_checked="1">
    <div class="col-md-2">

    </div>
    <div class="col-md-8" bis_skin_checked="1">
<div class="row justify-content-center" bis_skin_checked="1">
    <div class="col-md-8 card card-body" bis_skin_checked="1">
        <div id="print-area" bis_skin_checked="1">
            <div class="invoice-header" bis_skin_checked="1">
                <div class="logo-area" bis_skin_checked="1">

                    <h1 class="title">EIL POS Bangladesh</h1>

                </div>
                <address>
                    F/5
                    <br>
                    Phone : <strong>{{ $phone ?? '-' }}</strong>
                    <br>
                    Email : <strong>{{ $email ?? '-' }}</strong>
                    <br>
                    Address : <strong>{{$address ?? 'Banasree' }}</strong>
                </address>
            </div>
            <table class="table payment-invoice-header mt-2">
                <tbody>
                    <tr>
                        <td colspan="4" style="border-top: 0">
                            <h3 style="text-align: center; font-weight:bold;">Payment Invoice</h3>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:15%;">Payment No :</td>
                        <td style="width: 35%;">{{$transaction->id}}</td>
                        <td style="width:15%;">Date :</td>
                        <td>{{ \Carbon\Carbon::parse($transaction->date)->format('d F Y') ?? ''}}</td>
                    </tr>
                    <tr>
                        <td>Name :</td>
                        <td colspan="3"> {{$transaction['supplier']['name'] ?? $transaction['customer']['name'] ?? $transaction['investor']['name'] ?? ''}}</td>
                    </tr>
                    <tr>
                        <td>Address :</td>
                        <td colspan="3">{{$transaction['supplier']['address'] ?? $transaction['customer']['address'] ?? $transaction['investor']['address'] ??''}}</td>
                    </tr>
                    <tr>
                        <td>Mobile :</td>
                        <td colspan="3">{{$transaction['supplier']['phone'] ?? $transaction['customer']['phone'] ?? $transaction['investor']['phone'] ??''}} </td>
                    </tr>
                    <tr>
                        <td>Account Type :</td>
                        <td>
                            {{-- {{$transaction->customer_id ?? $transaction->supplier_id}} --}}
                            @if(isset($transaction->customer_id))
                            <span>Customer</span>
                         @elseif(isset($transaction->supplier_id))
                            <span>Supplier</span>
                          @endif
                        </td>
                        <td>Account:</td>
                     <td>{{$transaction['bank']['name'] ?? ''}}</td>
                    </tr>
                    <tr>
                        <td>Transaction Type :</td>
                        <td colspan="3" style="text-transform: capitalize">
                            @if($transaction->payment_type == 'pay')
                            <span>Cash Payment</span>
                         @elseif($transaction->payment_type == 'receive')
                            <span>Cash Received</span>
                          @endif
                            </td>
                    </tr>
                    <tr>
                        <td>Note :</td>
                        <td colspan="3">@php
                            $note = $transaction->note;
                            $noteChunks = str_split($note, 70);
                            echo implode("<br>", $noteChunks);
                            @endphp</td>
                    </tr>
                </tbody>
            </table>
                <table class="table table-bordered table-plist my-3">
                    <tbody><tr class="bg-primary">
                        <th>Date</th>
                        {{-- <th>Previous Due</th> --}}
                        <th>Paid</th>
                        @if($transaction->others_id != null)
                        @else
                            <th>Total Due</th>
                        @endif

                    </tr>
                    </tbody><tbody>
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($transaction->date)->format('d F Y') }}</td>
                            {{-- <td>
                                @if($transaction->credit != null && $transaction['customer']['wallet_balance'])
                                    {{ $transaction->credit - $transaction['customer']['wallet_balance'] }}
                                @elseif($transaction->debit != null && $transaction['supplier']['wallet_balance'])
                                    {{$transaction->debit - $transaction['supplier']['wallet_balance'] }}
                                @endif
                            </td> --}}
                            <td>@if( $transaction->credit != null)
                                {{ $transaction->credit }}
                                @elseif( $transaction->debit != null)
                                {{ $transaction->debit }}
                                @endif
                            </td>
                                @if($transaction->others_id != null)
                                @else
                            <td>
                                @if(isset($transaction['customer']['wallet_balance']))
                                    {{ $transaction['customer']['wallet_balance'] }}
                                @elseif(isset($transaction['supplier']['wallet_balance']))
                                    {{  $transaction['supplier']['wallet_balance'] }}
                                @endif
                            </td>
                            @endif
                        </tr>
                    </tbody>
                </table>
        </div>
        <button class="btn btn-secondary btn-block" onclick="window.print();">
            <i class="fa fa-print"></i>
            Print
        </button>
        {{-- <a href="{{route('transaction.add')}}" class="btn btn-primary buttona btn-block">
            <i class="fa fa-reply"></i>
            Back
        </a> --}}
    </div>
</div>
</div>
<div class="col-md-2">

</div>
</div>
<style>
    @media print {

        nav ,button,
        .footer {
            display: none !important;
        }

        .page-content {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }

        .btn_group,.buttona {
            display: none !important;
        }
    }
</style>
@endsection
