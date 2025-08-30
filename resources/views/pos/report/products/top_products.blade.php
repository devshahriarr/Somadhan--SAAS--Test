@extends('master')
@section('title', '| Top Products Report')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Top Products</li>
        </ol>
    </nav>

    <div class="row">

        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Top Products Table</h6>
                    <div class="table-responsive">
                        <table id="example" class="table">
                            <thead>
                                <tr>
                                    {{-- <th class="id">#</th>
                                    <th>Image</th>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Purchased</th>
                                    <th>Sold</th>
                                    <th>Damaged</th>
                                    <th>Returned</th>
                                    <th>Available Stock</th>
                                    <th>Sell Value</th>
                                    <th>Profit</th> --}}
                                    <th class="id">#</th>
                                    <th>Image</th>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Purchased</th>
                                    <th>Sold</th>
                                    <th>Damaged</th>
                                    <th>Cost Price</th>
                                    <th>Sell Price</th>
                                    <th>Available Stock</th>
                                    <th>Sell Value</th>
                                    <th>Stock Value</th>
                                    <th>Profit</th>
                                     @if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin')
                                    <th>Branch Name</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody id="showData">
                                @if ($products->count() > 0)
                                @foreach ($products as $index => $data)
                                    {{-- @dd($data->damage); --}}
                                    <tr>
                                        <td class="id">{{ $index + 1 }}</td>
                                        <td>
                                            @if ($data->image > 0)
                                                <img src="{{ asset('uploads/product/' . $data->image) }}" alt="product Image">
                                            @else
                                                <img src="{{ asset('dummy/image.jpg') }}" alt="product Image">
                                            @endif

                                        </td>
                                        <td>
                                            <a href="{{ route('product.ledger', $data->id) }}">
                                                {{ $data->name ?? '' }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $data->category->name ?? '' }}
                                        </td>
                                        {{-- purchase --}}
                                        @php

                                            $branchId = Auth::user()->branch_id;
                                            $totalPurchase = App\Models\PurchaseItem::whereHas('Purchas', function ($query) use ($branchId) {
                                                $query->where('branch_id', $branchId);
                                            })->where('product_id', $data->id)
                                            ->sum('quantity');
                                            $saleItems = App\Models\SaleItem::where('product_id', $data->id)->get();
                                            $totalSalePrice = $saleItems->sum('sub_total');
                                            $totalsaleQuantity = $saleItems->sum('qty');
                                            $totalCost = $data->cost * $totalsaleQuantity;
                                            $totalProfit = $totalSalePrice - $totalCost;
                                            $totalDamage = $data->damage->sum('qty');
                                          @endphp
                                        <td>
                                            {{ $totalPurchase  ?? ''}} {{ $data->unit->name  ?? ''}}
                                        </td>

                                        {{-- sold  --}}
                                        <td>
                                            <span class="text-danger">

                                            </span>
                                        </td>

                                        {{-- damage  --}}
                                        <td>
                                            {{ $totalDamage ?? 0 }} {{ $data->unit->name  ?? ''}}
                                        </td>
                                        <td>{{ $data->cost ?? 0 }}</td>
                                        <td>
                                            ৳ {{ $data->price ?? 0 }}
                                        </td>
                                        <td>
                                            @if ($data->stock_quantity_sum <= 10)
                                                <span class="text-danger">
                                                    {{ $data->stock_quantity_sum ?? 0 }} {{ $data->unit->name }}
                                                </span>
                                            @else
                                                {{ $data->stock_quantity_sum ?? 0 }} {{ $data->unit->name }}
                                            @endif
                                        </td>
                                        <td>
                                            ৳ {{ $totalSalePrice ?? 0 }}
                                        </td>
                                        <td>
                                            <span>৳</span> {{ $data->total_stock_value ?? 0}}
                                        </td>
                                        <td>
                                            ৳ {{ $totalProfit ?? 0 }}
                                        </td>
                                        @if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin')
                                        @foreach ($data->stockQuantity as $item)
                                       <td>{{$item->branch->name ?? ''}}</td>
                                        @endforeach
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9">No Data Found</td>
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
