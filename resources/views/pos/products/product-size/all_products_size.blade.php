@extends('master')
@section('title','| Product Size list')
@section('admin')

<div class="row">

<div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
    <div class="">
        @if(Auth::user()->can('products-size.add'))
        <h4 class="text-right"><a href="{{route('product.size.add')}}" class="btn btn-info">Add New Product Size</a></h4>
        @endif
    </div>
</div>
<div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                <h6 class="card-title text-info">View P.Size List</h6>

                    <div id="" class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Category Name</th>
                                    <th>Product Size</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="showData">
                            @if ($productSize->count() > 0)
                            @foreach ($productSize as $key => $pSize)
                                <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $pSize['category']['name']}}</td>
                                <td>{{ $pSize->size ?? ''}}</td>
                                <td>
                                    @if(Auth::user()->can('products-size.edit'))
                                    <a href="{{route('product.size.edit',$pSize->id)}}" class="btn btn-sm btn-primary btn-icon">
                                        <i data-feather="edit"></i>
                                    </a>
                                    @endif
                                    @if(Auth::user()->can('products-size.delete'))
                                    <a href="{{route('product.size.delete',$pSize->id)}}" id="delete" class="btn btn-sm btn-danger btn-icon">
                                        <i data-feather="trash-2"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">
                                <div class="text-center text-warning mb-2">Data Not Found</div>
                                <div class="text-center">
                                    <a href="{{route('product.size.add')}}" class="btn btn-primary">Add Product Size<i
                                            data-feather="plus"></i></a>
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


