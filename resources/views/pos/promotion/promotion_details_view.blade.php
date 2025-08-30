@extends('master')
@section('title','| Promotional Details List')
@section('admin')

    <div class="row">
        @if(Auth::user()->can('promotion-details.add'))
        <div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
            <div class="">
                <h4 class="text-right"><a href="{{ route('promotion.details.add') }}" class="btn btn-primary">Add Promotion
                        Details</a></h4>
            </div>
        </div>
        @endif
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-info">View Promotion Details</h6>

                    <div class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Promotion Name</th>
                                    <th>Promotion Type</th>
                                    <th>logic</th>
                                    <th>Additional Condition</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="showData">
                                @if ($promotion_details->count() > 0)
                                    @foreach ($promotion_details as $key => $promotions_details)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            {{-- @dd($promotions_details['promotion']); --}}
                                            <td>{{ $promotions_details['promotion']['promotion_name'] ?? '' }}</td>
                                            <td>{{ $promotions_details->promotion_type ?? '' }}</td>
                                            <td>

                                                @if ($promotions_details->logic == 'all')
                                                    {{ $promotions_details->logic ?? '' }}
                                                @elseif ($promotions_details->promotion_type == 'products')
                                                    @php
                                                        $ids = explode(',', $promotions_details->logic);
                                                        // dd($ids);
                                                        $products = App\Models\Product::whereIn('id', $ids)->get();
                                                        // dd($products);
                                                    @endphp
                                                    <ul>
                                                        @foreach ($products as $product)
                                                            <li>{{ $product->name }}</li>
                                                        @endforeach
                                                    </ul>
                                                @elseif ($promotions_details->promotion_type == 'customers')
                                                    @php
                                                        $ids = explode(',', $promotions_details->logic);
                                                        // dd($ids);
                                                        $customers = App\Models\Customer::whereIn('id', $ids)->get();
                                                        // dd($products);
                                                    @endphp
                                                    <ul>
                                                        @foreach ($customers as $customer)
                                                            <li>{{ $customer->name }}</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    @php
                                                        $ids = explode(',', $promotions_details->logic);
                                                        // dd($ids);
                                                        $branchs = App\Models\Branch::whereIn('id', $ids)->get();
                                                        // dd($products);
                                                    @endphp
                                                    <ul>
                                                        @foreach ($branchs as $branch)
                                                            <li>{{ $branch->name }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </td>
                                            <td>{{ $promotions_details->additional_conditions ?? '' }}</td>
                                            <td>
                                                @if(Auth::user()->can('promotion-details.edit'))
                                                <a href="{{ route('promotion.details.edit', $promotions_details->id) }}"
                                                    class="btn btn-sm btn-primary btn-icon">
                                                    <i data-feather="edit"></i>
                                                </a>
                                                @endif
                                                @if(Auth::user()->can('promotion-details.delete'))
                                                <a href="{{ route('promotion.details.delete', $promotions_details->id) }}"
                                                    id="delete" class="btn btn-sm btn-danger btn-icon">
                                                    <i data-feather="trash-2"></i>
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="12">
                                            <div class="text-center text-warning mb-2">Data Not Found</div>
                                            <div class="text-center">
                                                @if(Auth::user()->can('promotion-details.add'))
                                                <a href="{{ route('promotion.details.add') }}" class="btn btn-primary">Add
                                                    Promotion Details<i data-feather="plus"></i></a>
                                                 @endif
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
