@extends('master')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Courier Manage</li>
        </ol>
    </nav>

    <div class="row">


       <div class="row mb-4">
    <!-- Filter Form -->
    <div class="col-12 mb-3">
        <div class="d-flex justify-content-end">
            <form  id="filterForm" class="bg-light p-3 rounded shadow-sm">
                <div class="d-flex align-items-center">
                    <label for="filter_type" class="me-2 mb-0 fw-semibold text-secondary">Filter By:</label>
                    <select name="filter_type" class="form-select form-select-sm w-auto filter_type">
                        <option value="today">Today</option>
                        <option value="month">This Month</option>
                        <option value="year">This Year</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Order Cards -->
    <div class="col-md-3 mt-1">
        <div class="card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-title mb-1">Total Orders</h6>
                    <h3 class="fw-bold" id="total-orders">{{$today_total_order ?? 0}}</h3>
                </div>
                <i class="fas fa-box fa-3x text-primary"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3 mt-1">
        <div class="card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-title mb-1">New Orders</h6>
                    <h3 class="fw-bold" id="new-orders">{{$new_order ?? 0}}</h3>
                </div>
                <i class="fas fa-clock fa-3x text-warning"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3 mt-1">
        <div class="card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-title mb-1">Shipment Orders</h6>
                    <h3 class="fw-bold" id="shipment-orders">{{$today_processing_order ?? 0}}</h3>
                </div>
                <i class="fas fa-truck-moving fa-3x text-info"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3 mt-1">
        <div class="card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-title mb-1">Completed Orders</h6>
                    <h3 class="fw-bold" id="completed-orders">{{$today_completed_order ?? 0}}</h3>
                </div>
                <i class="fas fa-check-circle fa-3x text-success"></i>
            </div>
        </div>
    </div>
</div>





        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">Courier List</h6>
                        <a class="btn btn-primary mb-2" href="{{ route('courier.add') }}">Add New</a>
                    </div>








                    <div class="table-responsive">
                        <table id="example" class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Courier Name</th>
                                    <th>Contact Number</th>
                                    <th>Base URL</th>
                                    <th> Balance In Courier</th>
                                    <th>Total Order</th>
                                    <th>Total Courier Shipment</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody id="table_data">

                            @foreach ($courier_manage as $courier_manage)


                          @php
                            $total_order = App\Models\CouerierOrder::where('courier_id',$courier_manage->id)->count();
                            $total_courier_shipment = App\Models\CouerierOrder::where('courier_id',$courier_manage->id)->where('status','processing')->count();
                            $balance=0;
                         if (strtolower(str_replace(' ', '', $courier_manage->courier_name)) == "steadfast") {
                                $courierdetails = App\Models\CourierAdd::where('courier_id',$courier_manage->id)->first();

                                $app_key = $courierdetails->api_key;
                                $app_secret = $courierdetails->secret_key;

                                $balance_url = "https://portal.packzy.com/api/v1/get_balance";
                                $header = [
                                    'Api-Key' => $app_key,
                                    'Secret-Key' => $app_secret,
                                    'Content-Type' => 'application/json'
                                ];

                                $balanceResponse = Http::withHeaders($header)->get($balance_url)->json();
                                $balance = $balanceResponse['current_balance'] ?? 0;

                            }
                          @endphp




                            <tr>
                                <td>{{$loop->iteration}}</td>
                               <td>
                                    <a href="{{ route('courier.wise.order', $courier_manage->id) }}">
                                        {{ $courier_manage->courier_name ?? '' }}
                                    </a>
                                </td>

                                <td>{{ $courier_manage->contact_number??'' }}</td>
                                <td>{{ $courier_manage->base_url??'' }}</td>
                                <td>{{ $balance??'' }}</td>
                                <td>{{ $total_order??'' }}</td>
                                <td>{{ $total_courier_shipment??'' }}</td>
                                <td>
                                    <a href="{{ route('courier_manage.add.information.edit',$courier_manage->id) }}" class="btn btn-primary btn-icon unit_edit" >
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="" class="btn btn-danger btn-icon unit_delete" >
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>


                            </tr>

                            @endforeach



                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>




<script>
    $(document).on('change', '.filter_type', function() {
        var filter_type = $(this).val();

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            url: "{{ route('courier.order.filter') }}",
            type: "POST",
            data: { filter_type: filter_type },
            success: function(data) {
                  console.log(data);
                if(data.status===200){
                   $('#total-orders').text(data.total_order);
                   $('#shipment-orders').text(data.shipment_order);
                   $('#new-orders').text(data.pending);
                   $('#completed-orders').text(data.today_completed_order);

                }
            }

        })

    });
</script>







@endsection
