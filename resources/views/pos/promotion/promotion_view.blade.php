@extends('master')
@section('title','| Promottion List')
@section('admin')

<div class="row">
    @if(Auth::user()->can('promotion.add'))
<div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
    <div class="">
        <h4 class="text-right"><a href="{{route('promotion.add')}}" class="btn btn-primary">Add New Promotion</a></h4>
    </div>
</div>
@endif
<div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                <h6 class="card-title text-info">View Promotion List</h6>

                    <div  class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Promotion Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Discount Type</th>
                                    <th>Discount Value</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="showData">
                            @if ($promotions->count() > 0)
                            @foreach ($promotions as $key => $promotion)
                                <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $promotion->promotion_name ?? '-'}}</td>
                                <td>{{ $promotion->start_date ?? '-'}}</td>
                                <td>{{ $promotion->end_date ?? '-'}}</td>
                                <td>{{ $promotion->discount_type ?? '-'}}</td>
                                <td>@if ($promotion->discount_type == 'percentage')
                                    {{ $promotion->discount_value ?? ''}} <span>%</span>
                                    @else
                                    {{ $promotion->discount_value ?? ''}} <span>Tk</span>
                                    @endif

                                </td>
                                <td>{{ $promotion->description ?? ''}}</td>
                                <td>
                                    @if(Auth::user()->can('promotion.edit'))
                                    <a href="{{route('promotion.edit',$promotion->id)}}" class="btn btn-sm btn-primary btn-icon">
                                        <i data-feather="edit"></i>
                                    </a>
                                    @endif
                                    @if(Auth::user()->can('promotion.delete'))
                                    <a href="{{route('promotion.delete',$promotion->id)}}" id="delete" class="btn btn-sm btn-danger btn-icon">
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
                                    @if(Auth::user()->can('promotion.add'))
                                    <a href="{{route('promotion.add')}}" class="btn btn-primary">Add Promotion<i
                                            data-feather="plus"></i></a>
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


