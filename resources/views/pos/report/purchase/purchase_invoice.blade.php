@extends('master')
@section('title', '| Purchase Invoice')
@section('admin')
    <div class="row" bis_skin_checked="1">
        <div class="col-md-2">

        </div>
        <div class="col-md-8" bis_skin_checked="1">
            <div class="row justify-content-center" bis_skin_checked="1">
                <div class="col-md-7 card card-body" bis_skin_checked="1">
                    <div id="print-area" bis_skin_checked="1">
                        <div class="invoice-header">
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="logo-area">
                                        @if (!empty($invoice_logo_type))
                                            @if ($invoice_logo_type == 'Name')
                                                <h4>{{ $siteTitle }}</h4>
                                            @elseif($invoice_logo_type == 'Logo')
                                                @if (!empty($logo))
                                                    <img height="50" width="150" src="{{ url($logo) }}"
                                                        alt="logo">
                                                @else
                                                    <h4>{{ $siteTitle }}</h4>
                                                @endif
                                            @elseif($invoice_logo_type == 'Both')
                                                @if (!empty($logo))
                                                    <img height="50" width="150" src="{{ url($logo) }}"
                                                        alt="logo">
                                                @endif
                                                <h4>{{ $siteTitle }}</h4>
                                            @endif
                                        @else
                                            <h4>EIL POS Software</h4>
                                        @endif
                                    </div>

                                </div>
                                <div class="col-md-5">
                                    <address class="text-right">
                                        <p>
                                            Address : {{ $address }}<br>
                                            Phone : <strong>{{ $phone }}</strong><br>
                                            Email : <strong>{{ $email }}</strong>
                                        </p>
                                    </address>
                                </div>
                            </div>
                        </div>

                        <div class="bill-date border p-1">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="bill-no">
                                        Invoice No: <strong>#{{ $purchase->id }} </strong>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="date text-right">
                                        Date: <strong>{{ $purchase->purchase_date }} </strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="name border  p-1">
                            Supplier Name :
                            <strong>{{ $purchase->supplier->name }} </strong>
                        </div>
                        <div class="address border p-1" bis_skin_checked="1">
                            Address : <span>{{ $purchase->supplier->address }}</span>
                        </div>
                        <div class="mobile-no border p-1" bis_skin_checked="1">
                            Mobile : <span>{{ $purchase->supplier->phone }}</span>
                        </div>

                        <table class="table table-bordered table-plist my-3 order-details border">
                            <tbody>
                                <tr class="bg-primary">
                                    <th>#</th>
                                    <th>Details</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Net.A</th>
                                </tr>
                                @forelse ($purchase->purchaseItem as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->unit_price }} Tk</td>
                                        <td>{{ $item->total_price }} Tk</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">Data Not Found</td>
                                    </tr>
                                @endforelse
                                <tr>
                                    <td colspan="4" class="text-end">Grand Total : </td>
                                    <td>
                                        <strong>{{ $purchase->total_amount }}</strong>Tk
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="4" class="text-end">Paid : </td>
                                    <td>
                                        <strong>{{ $purchase->paid }}</strong>Tk
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="4" class="text-end"> Due : </td>
                                    <td>
                                        <strong>{{ $purchase->due }}
                                        </strong>Tk
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        {{-- <p class="note">Note: </p> --}}

                    </div>
                    <button class="btn btn-secondary btn-block print-btn">
                        <i class="fa fa-print"></i>
                        Print
                    </button>
                    <div class="row mt-4 footer-purches" bis_skin_checked="1">
                        <div class="col-6" bis_skin_checked="1">
                            <a href="{{ route('purchase') }}" class="btn btn-primary btn-block">
                                <i class="fa fa-reply"></i>
                                New Purchase
                            </a>
                        </div>

                        <div class="col-6" bis_skin_checked="1">
                            <a href="{{ route('purchase.view') }}" class="btn btn-primary btn-block">
                                <i class="fa fa-reply"></i>
                                Purchase List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">

        </div>
    </div>
    <script>
        $('.print-btn').click(function() {
            // Remove the id attribute from the table
            $('#dataTableExample').removeAttr('id');
            $('.table-responsive').removeAttr('class');
            // Trigger the print function
            window.print();

        });
    </script>
    <style>
        @media print {

            nav,
            button,
            .footer,
            .footer-purches {
                display: none !important;
            }

            .page-content {
                margin-top: 0 !important;
                padding-top: 0 !important;
            }

            .btn_group,
            .buttona {
                display: none !important;
            }
        }
    </style>

@endsection
