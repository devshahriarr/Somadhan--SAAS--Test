@extends('master')
@section('title', '| Supplier Ledger Report')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Supplier Ledger</li>
        </ol>
    </nav>

    <div class="row">
        
        <div class="col-md-12   grid-margin stretch-card filter_box">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        @php
                            if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
                                $suppliers = App\Models\Customer::where('party_type', 'supplier')->get();
                            } else {
                                $suppliers = App\Models\Customer::where('branch_id', Auth::user()->branch_id)->get();
                            }
                        @endphp
                        <div class="col-md-4">
                            <div class="input-group flatpickr" id="flatpickr-date">
                                <select class="js-example-basic-single form-select select-supplier" data-width="100%"
                                    name="">
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
                        <div class="col-md-4">
                            <div class="input-group flatpickr" id="flatpickr-date">
                                <input type="text" class="form-control from-date flatpickr-input start-date"
                                    placeholder="Start date" data-input="" readonly="readonly">
                                <span class="input-group-text input-group-addon" data-toggle="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2">
                                        </rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group flatpickr" id="flatpickr-date">
                                <input type="text" class="form-control from-date flatpickr-input end-date"
                                    placeholder="End date" data-input="" readonly="readonly">
                                <span class="input-group-text input-group-addon" data-toggle="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2">
                                        </rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="justify-content-left">
                                <button class="btn btn-sm bg-info text-dark mr-2" id="filter">Filter</button>
                                <button onclick="window.location.reload();" class="btn btn-sm bg-primary text-dark"
                                    id="reset">Reset</button>
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="flex text-md-end ">
                                <button type="button"
                                    class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0 print-btn">
                                    <i class="btn-icon-prepend" data-feather="printer"></i>
                                    Print
                                </button>
                                {{-- <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
                                    <i class="btn-icon-prepend" data-feather="download-cloud"></i>
                                    Download Report
                                </button> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @php
            $branch = App\Models\Branch::findOrFail(Auth::user()->branch_id);
        @endphp
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-none">
                    <div class="card-body ">
                        <div class="container-fluid d-flex justify-content-between">
                            <div class="col-lg-3 ps-0">
                                <a href="#" class="noble-ui-logo logo-light d-block mt-3">EIL<span>POS</span></a>
                                <h5 class="mt-2">{{ $branch->name ?? '' }}</h5>
                                <hr>
                            </div>
                            <div class="col-lg-3 pe-0 text-end">
                                <p class="show_branch_address">{{ $branch->address ?? '' }}</p>
                                <p class="show_branch_email">{{ $branch->email ?? '' }}</p>
                                <p class="show_branch_phone">{{ $branch->phone ?? '' }}</p>
                            </div>
                        </div>

                    </div>
                    <div class="card-body show_ledger">
                        {{-- @include('pos.report.supplier.show_ledger') --}}
                    </div>
                </div>
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
        }
    </style>
    <script>
        $(document).ready(function() {
            function supplierInfo(supplier, transactions) {
                let totalDebit = 0;
                let totalCredit = 0;
                let totalBalance = 0;

                transactions.forEach(transaction => {
                    totalDebit += parseFloat(transaction.debit ?? 0);
                    totalCredit += parseFloat(transaction.credit ?? 0);
                    totalBalance += parseFloat(transaction.balance ?? 0);
                });

                // console.log(totalDebit.toFixed(2));

                $('.show_ledger').html(`
                <div class="container-fluid mt-2 d-flex justify-content-center w-100">
                    <div class="table-responsive w-100">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td>Account Of</td>
                                    <td>${supplier.name ?? '' }</td>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td>${supplier.address ?? '' }</td>
                                </tr>
                                <tr>
                                    <td>Contact No.</td>
                                    <td>${supplier.phone ?? '' }</td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <h4 class="my-3 text-center">Supplier Ledger</h4>
                <div class="container-fluid w-100">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Particulars</th>
                                            <th>Debit</th>
                                            <th>Credit</th>
                                            <th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${transactions.map((transaction) => `
                                                                                                                                        <tr>
                                                                                                                                            <td>${transaction.date ?? ''}</td>
                                                                                                                                            <td>${transaction.particulars ?? ''}</td>
                                                                                                                                            <td>৳ ${transaction.debit ?? ''}</td>
                                                                                                                                            <td>৳ ${transaction.credit ?? ''}</td>
                                                                                                                                            <td>৳ ${transaction.balance ?? 0}</td>
                                                                                                                                        </tr>
                                                                                                                                    `).join('')}
                                    </tbody>
                                    <tfoot>
                                            <tr>
                                                <td></td>
                                                <td>Total</td>
                                                <td>৳ ${totalDebit.toFixed(2)}</td>
                                                <td>৳ ${totalCredit.toFixed(2)}</td>
                                                <td>৳ ${totalBalance.toFixed(2)}</td>
                                            </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                `);

            }

            // transactioninfo
            $('#filter').click(function(e) {
                e.preventDefault();
                // alert('ok');
                let startDate = document.querySelector('.start-date').value;
                let endDate = document.querySelector('.end-date').value;
                // alert(endDate)
                let supplierId = document.querySelector('.select-supplier').value;
                // alert(supplierId);

                // // alert(supplier_id);
                $.ajax({
                    url: "{{ route('supplier.ledger.filter') }}",
                    method: 'GET',
                    data: {
                        startDate,
                        endDate,
                        supplierId
                    },
                    success: function(res) {

                        // $(".show_ledger").html(res);
                        const supplier = res.supplier;
                        const transactions = res.transactions;
                        supplierInfo(supplier, transactions);
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
        })
    </script>
@endsection
