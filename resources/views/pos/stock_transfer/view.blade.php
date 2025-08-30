@extends('master')
@section('title', '| Return View')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Stock Transfer</li>
        </ol>
    </nav>
    <div class="row">
        @if (Auth::user()->can('stock.transfer.add'))
        <div class="d-flex justify-content-end mb-2">
            <a href="{{route('stock.transfer')}}" class="btn btn-primary">Add Stock Transfer</a>
        </div>
        @endif
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <h6 class="card-title">Stock Transfer List</h6>

                    <div class="table-responsive">
                        <table id="example" class="table">
                            <thead>
                                <tr>
                                    <th class="id">#</th>
                                    <th>Product Name </th>
                                    <th>Transfer Date</th>
                                    <th>Quantity</th>
                                    <th>From Warehouse</th>
                                    <th>To Warehouse</th>
                                    <th>From Rack</th>
                                    <th>To Rack</th>
                                    <th>From Branch</th>
                                    <th>To Branch</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody id="showData">
                              @foreach ($stockTransfers as $index => $stockTransfer)
                                <tr>
                                    <td class="id">{{ $index + 1 }}</td>
                                    <td>{{$stockTransfer->product->name ?? ''}} | {{$stockTransfer->variation->colorName->name ?? ''}} | {{$stockTransfer->variation->variationSize->size ?? ''}}</td>
                                    <td>{{$stockTransfer->transfer_date}}</td>
                                    <td>{{$stockTransfer->quantity ?? ''}}</td>
                                    <td>{{$stockTransfer->fromWarehouse->warehouse_name ?? 'N/A'}}</td>
                                    <td>{{$stockTransfer->toWarehouse->warehouse_name ?? 'N/A'}} </td>
                                    <td>{{$stockTransfer->fromRack->rack_name ?? 'N/A'}}</td>
                                    <td>{{$stockTransfer->toRack->rack_name ?? 'N/A'}}</td>
                                    <td>{{$stockTransfer->fromBranch->name ?? 'N/A'}}</td>
                                    <td>{{$stockTransfer->toBranch->name ?? 'N/A'}}</td>
                                    <td class=" btn-sm badge   bg-success text-white">{{$stockTransfer->status ?? 'N/A'}}</td>
                                </tr>
                              @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endsection
