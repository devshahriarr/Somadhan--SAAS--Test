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
                        <td style="width: 35%;">{{$investors->id ?? '-'}}</td>
                        <td style="width:15%;">Date :</td>
                        <td>{{ \Carbon\Carbon::parse($investors->created_at)->format('d F Y') ?? '-'}}</td>
                    </tr>
                    <tr>
                        <td>Name :</td>
                        <td colspan="3"> {{$investors->name ?? '-'}}</td>
                    </tr>
                    <tr>
                        <td>Type :</td>
                        <td colspan="3">{{ $investors->type ?? '-'}}</td>
                    </tr>
                    <tr>
                        <td>Mobile :</td>
                        <td colspan="3">{{ $investors->phone ?? '-'}} </td>
                    </tr>

                </tbody>
            </table>
                <table class="table table-bordered table-plist my-3">
                    <tbody><tr class="bg-primary">
                        <th>Date</th>
                        {{-- <th>Previous Due</th> --}}
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Balance</th>
                    </tr>
                    </tbody>
                    <tbody>
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($investors->created_at)->format('d F Y') }}</td>
                            <td>
                                {{ $investors->debit ?? '-'}}
                            <td>
                                {{ $investors->credit ?? '-'}}
                            </td>
                            <td>
                                {{ $investors->wallet_balance ?? '-'}}
                            </td>
                        </tr>
                    </tbody>
                </table>
        </div>
        <button class="btn btn-secondary btn-block" onclick="window.print();">
            <i class="fa fa-print"></i>
            Print
        </button>
        <a href="{{route('transaction.add')}}" class="btn btn-primary buttona btn-block">
            <i class="fa fa-reply"></i>
            Back
        </a>
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
