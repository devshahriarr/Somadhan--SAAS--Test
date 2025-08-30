@extends('master')
@section('title')
    | B2B Transfer Transaction
@endsection
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">
                Transaction Ledger
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
                                                <td>Invoice No</td>
                                                <td>{{$b2bTransfer->invoice ?? 'N/A'}}</td>
                                                <td>Amount</td>
                                                <td>{{$b2bTransfer->amount ?? 0}}</td>
                                                <td>Created By</td>
                                                <td>{{$b2bTransfer->createdBy->name ?? 'N/A'}}</td>
                                            </tr>
                                            <tr>
                                                <td>From</td>
                                                <td>{{$b2bTransfer->fromBank->name ?? 'N/A'}}</td>
                                                <td>To</td>
                                                <td>{{$b2bTransfer->toBank->name ?? 'N/A'}}</td>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="my-3 text-center">
                        Transaction History
                    </h4>
                    <div class="container-fluid w-100">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Date/Time</th>
                                                <th>Created By</th>
                                                <th>Purpose</th>
                                                <th>Debit</th>
                                                <th>Credit</th>


                                            </tr>
                                        </thead>
                                        <tbody>

                                            @if (count($accoutTransactions))
                                                @foreach ($accoutTransactions as $accoutTransactions)
                                                    <tr>
                                                        <td>{{ $accoutTransactions->created_at->timezone('Asia/Dhaka')->format('d-m-Y h:i a') }}</td>
                                                       <td>{{ $accoutTransactions['user']['name'] ?? '' }}</td>
                                                        <td>{{ $accoutTransactions->purpose ?? 'N/A' }}</td>
                                                        <td>{{ $accoutTransactions->debit ?? 'N/A' }}</td>
                                                        <td>{{ $accoutTransactions->credit ?? 'N/A' }}</td>

                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td>No Data Found</td>
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
<script>
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
    <style>
        #printFrame {
            display: none;
            /* Hide the iframe */
        }

        .table> :not(caption)>*>* {
            padding: 0px 10px !important;
        }

        thead,
        tbody,
        tfoot,
        tr,
        td,
        th {
            vertical-align: middle;
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
        #variationTable .form-control {
                width: 100%;
                box-sizing: border-box;
            }

            #variationTable td {
                padding: 5px;
            }

            #variationTable tr {
                display: flex;
                flex-wrap: wrap;
            }

            #variationTable tr td {
                flex: 1 1 30%;
                margin: 5px;
            }

            @media (max-width: 1024px) {
                #variationTable tr td {
                    flex: 1 1 45%;
                }
            }

            @media (max-width: 767px) {
                #variationTable tr td {
                    flex: 1 1 100%;
                }
            }

@endsection

