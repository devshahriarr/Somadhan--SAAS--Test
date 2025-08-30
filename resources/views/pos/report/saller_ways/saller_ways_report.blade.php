@extends('master')
@section('title', '| Saller Ways Report ')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Saller Ways Report</li>
        </ol>
    </nav>

    <div class="row ">

            @foreach ($salesByPerson as $person)
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <h6 class="card-title mb-2">{{$person->saleBy->name }}</h6>
                            <div class="dropdown mb-2">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="mb-2">
                                    ৳ {{$person->total_amount}}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

    </div>
    <div id="purchase-filter-table">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title text-info">Saller Ways Report</h6>
                        <div id="" class="table-responsive">
                            <table id="lowStockVariationDataTable" class="table">
                                <thead>
                                    <tr>
                                        <th>SN#</th>
                                        <th>Date</th>
                                        <th>Saller Name</th>
                                        <th>Invoice</th>
                                        <th>Amount</th>

                                    </tr>
                                </thead>
                                <tbody class="showData">
                                    @if ($Sales->count() > 0)
                                        @foreach ($Sales as $key => $sale)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    {{ $sale->sale_date ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $sale->saleBy->name ?? '' }}
                                                </td>

                                                <td>
                                                    {{ $sale->invoice_number ?? 0 }}
                                                </td>
                                                <td>
                                                    {{ $sale->receivable ?? 0 }}
                                                </td>

                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="12">
                                                <div class="text-center text-warning mb-2">Data Not Found</div>
                                            </td>
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

    {{-- <script>
        $(document).ready(function() {
            document.querySelector('#purchesfilter').addEventListener('click', function(e) {
                e.preventDefault();
                let startDatePurches = document.querySelector('.start-date-purches').value;
                let endDatePurches = document.querySelector('.end-date-purches').value;
                //  alert(endDatePurches);
                let filterProduct = document.querySelector('.filter_product_name').value;
                let branchId = document.querySelector('.filter_branch').value;
                // alert(filterProduct);
                $.ajax({
                    url: "{{ route('damage.product.filter.view') }}",
                    method: 'GET',
                    data: {
                        startDatePurches,
                        endDatePurches,
                        filterProduct,
                        branchId
                    },
                    success: function(res) {
                        jQuery('#purchase-filter-table').html(res);
                    }
                });
            });
        });
    </script> --}}
@endsection
