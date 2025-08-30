@extends('master')
@section('title', '| Damage List')
@section('admin')

    <div class="row">

        @if(Auth::user()->can('damage.add'))
        <div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
            <div class="">
                <h4 class="text-right"><a href="{{ route('damage') }}" class="btn btn-info">Add Damage</a></h4>
            </div>
        </div>
        @endif
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-info">View Damage History</h6>

                    <div id="" class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Name</th>
                                    <th>Size</th>
                                    <th>Color</th>
                                    <th>Quantity</th>
                                    <th>Branch Name</th>
                                    <th>Date</th>
                                    <th>Note</th>
                                    {{-- <th>Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @if ($damages->count() > 0)
                                    @foreach ($damages as $key => $damage)
                                        {{-- @dd($damage->branch->name); --}}
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $damage->product->name ?? '' }}</td>
                                            <td>{{ $damage->product->defaultVariations->variationSize->size ?? '' }}</td>
                                            <td>{{ $damage->product->defaultVariations->colorName->name ?? '' }}</td>
                                            <td>{{ $damage->qty ?? '' }}</td>
                                            <td>{{ $damage->branch->name ?? '' }}</td>
                                            <td>{{ $damage->date ?? '' }}</td>
                                            <td>{{ $damage->note ?? '' }}</td>
                                            {{-- <td> --}}
                                                {{-- @if(Auth::user()->can('damage.edit'))
                                                <a href="{{ route('damage.edit', $damage->id) }}"
                                                    class="btn btn-sm btn-primary btn-icon">
                                                    <i data-feather="edit"></i>
                                                </a>
                                                @endif --}}
                                                {{-- @if(Auth::user()->can('damage.delete')) --}}
                                                {{-- <a href="{{route('damage.delete',$damage->id)}}" id="delete" class="btn btn-sm btn-danger btn-icon"> --}}
                                                {{-- <i data-feather="trash-2"></i> --}}
                                                {{-- <a href="{{ route('damage.destroy', [$damage->id, $damage->product_id]) }}" id="delete"
                                                    class="btn btn-sm btn-danger btn-icon">
                                                    <i data-feather="trash-2"></i>
                                                </a>
                                                @endif --}}
                                            {{-- </td> --}}
                                        </tr>
                                    @endforeach
                                    {{-- @else
                                    <tr>
                                        <td colspan="7">
                                            <div class="text-center text-warning mb-2">Data Not Found</div>
                                            <div class="text-center">
                                                <a href="{{ route('damage') }}" class="btn btn-primary">Add damage<i
                                                        data-feather="plus"></i></a>
                                            </div>
                                        </td>
                                    </tr> --}}
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
