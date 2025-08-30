@extends('master')
@section('title', '| Service View')
@section('admin')

    <div class="row">

        <div class="col-md-12 grid-margin stretch-card">
            <div class="card ">
                <div class="card-body">
                    <div class="col-md-12 grid-margin stretch-card d-flex  mb-0 justify-content-between">
                        <div>
                            <h4 class="mb-2">View Service Sale</h4>
                        </div>
                        <div class="">
                            <h4 class="text-right"><a href="{{ route('service.sale') }}" class="btn"
                                    style="background: #5660D9">Add Service Sale</a></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-2">Service Sale list</h4>
                    <div id="" class="table-responsive">
                        <table id="example" class="table">
                            <thead>
                                <tr>
                                    <th>Invoice</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Grand Total</th>
                                    <th>Paid</th>
                                    <th>Due</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="showData">
                                @if ($serviceSales->count() > 0)
                                    @foreach ($serviceSales as $key => $serviceSale)
                                        <tr>

                                            <td>
                                                <a href="{{ route('service.sale.invoice', $serviceSale->id) }}">
                                                    #{{ $serviceSale->invoice_number ?? '' }}
                                                </a>
                                            </td>
                                            <td>{{ $serviceSale->customer->name ?? '' }}</td>
                                            <td>{{ $serviceSale->date ?? '' }}</td>
                                            <td>{{ $serviceSale->grand_total ?? '' }}</td>
                                            <td>{{ $serviceSale->paid ?? '' }}</td>
                                            <td>{{ $serviceSale->due ?? '' }}</td>
                                            <td> <a href="{{ route('service.sale.ledger',  $serviceSale->id) }}" class=" btn btn-secondary text-white" >  View</a>

                                            </td>

                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="12">
                                            <div class="text-center text-warning mb-2">Data Not Found</div>
                                            <div class="text-center">
                                                <a href="{{ route('service.sale') }}" class="btn btn-primary">Add Service
                                                    Sale<i data-feather="plus"></i></a>
                                            </div>
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

@endsection
