@extends('master')
@section('title', '| Customer List')
@section('admin')

    <div class="row">
        @if (Auth::user()->can('customer.add'))
            <div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
                <div class="">
                    <h4 class="text-right"><a href="{{ route('customer.add') }}" class="btn btn-info">Add New Customer</a></h4>
                </div>
            </div>
        @endif
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-info">View Customer List</h6>

                    <div id="" class="table-responsive">
                        <table id="example" class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Branch</th>
                                    <th>Due</th>
                                    <th>Wallet Balance</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="showData">
                                @if ($customers->count() > 0)
                                    @foreach ($customers as $key => $customer)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <a href="{{ route('customer.profile', $customer->id) }}">
                                                    {{ $customer->name ?? '' }}
                                                </a>
                                            </td>
                                            <td>{{ $customer->phone ?? '' }}</td>
                                            <td>{{ $customer['branch']['name'] ?? '' }}</td>
                                            <td>
                                                {{-- @dd($customer->wallet_balance) --}}
                                                @if ($customer->wallet_balance > 0)
                                                    <span class="text-danger">৳
                                                        {{ $customer->wallet_balance ?? 0 }}</span>
                                                @else
                                                    <span>৳ 0</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($customer->wallet_balance > 0)
                                                    <span>
                                                        {{ $customer->wallet_balance ?? 0 }}
                                                        <br>
                                                        আপনি কাস্টমার <br> থেকে পাবেন।
                                                    </span>
                                                @elseif ($customer->wallet_balance < 0)
                                                    <span>
                                                        {{ -$customer->wallet_balance ?? 0 }}
                                                        <br>
                                                        আপনার থেকে <br> কাস্টমার পাবেন।
                                                    </span>
                                                @else
                                                    <span>
                                                        {{ $customer->wallet_balance ?? 0 }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (Auth::user()->can('customer.edit'))
                                                    <a href="{{ route('customer.edit', $customer->id) }}"
                                                        class="btn btn-sm btn-primary btn-icon">
                                                        <i data-feather="edit"></i>
                                                    </a>
                                                @endif
                                                @if (Auth::user()->can('customer.delete'))
                                                    <a href="{{ route('customer.delete', $customer->id) }}" id="delete"
                                                        class="btn btn-sm btn-danger btn-icon">
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
                                            @if (Auth::user()->can('customer.add'))
                                            <div class="text-center">
                                                <a href="{{ route('customer.add') }}" class="btn btn-primary">Add
                                                    Customer<i data-feather="plus"></i></a>
                                            </div>
                                            @endif
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
