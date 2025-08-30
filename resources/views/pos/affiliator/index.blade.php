@extends('master')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Affiliator List</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">Affiliator List</h6>
                        <a class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#affiliatorModal">Add New</a>
                    </div>


                    <div class="table-responsive">
                        <table id="example" class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Affiliator Name</th>
                                    <th>Phone</th>
                                    <th>Commission Type</th>
                                    <th>Commission Rate</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody id="table_data">





                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="affiliatorModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalTitle">Add New Affiliator</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="affiliatorForm">
                        <div class="row g-3">
                            <!-- Name Input -->
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-bold">Name</label>
                                <input type="text" class="form-control rounded-pill" name="name"  placeholder="Enter Name">
                            </div>

                            <!-- Phone Input -->
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-bold">Phone</label>
                                <input type="text" class="form-control rounded-pill"  name="phone" placeholder="Enter Phone">
                            </div>

                            <!-- Commission Type Selection -->
                            <div class="col-md-6">
                                <label for="commission_type" class="form-label fw-bold">Commission Type</label>
                                <select class="form-select rounded-pill"  name="commission_type">
                                    <option value="fixed">Fixed</option>
                                    <option value="percentage">Percentage</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="commission_type" class="form-label fw-bold">Commission State</label>
                                <select class="form-select rounded-pill"  name="commission_state">
                                    <option value="">Select Type Of Commission State</option>
                                    <option value="against_sale_amount">Sale Wise Commission</option>
                                    <option value="against_profit_amount">Profit Wise Commission</option>
                                </select>
                            </div>
                            <!-- Commission Rate Input -->
                            <div class="col-md-6">
                                <label for="commission_rate" class="form-label fw-bold">Commission Rate</label>
                                <input type="number" class="form-control rounded-pill"  placeholder="Enter Rate" name="commission_rate">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-end mt-4">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                            <a  class="btn btn-primary rounded-pill px-4 affliatorSave">Submit</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--Edit Modal -->
    <div class="modal fade" id="affiliatorEditModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalTitle">Edit Affiliator</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="affiliatorEditForm">
                        <div class="row g-3">
                            <!-- Name Input -->
                            <input type="hidden" name="id" id="affiliator_id">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-bold">Name</label>
                                <input type="text" class="form-control rounded-pill" name="name" id="name" >
                            </div>

                            <!-- Phone Input -->
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-bold">Phone</label>
                                <input type="text" class="form-control rounded-pill" id="phone" name="phone" >
                            </div>

                            <!-- Commission Type Selection -->
                            <div class="col-md-6">
                                <label for="commission_type" class="form-label fw-bold">Commission Type</label>
                                <select class="form-select rounded-pill" id="commission_type" name="commission_type">
                                    <option value="fixed">Fixed</option>
                                    <option value="percentage">Percentage</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="commission_type" class="form-label fw-bold">Commission State</label>
                                <select class="form-select rounded-pill"  name="commission_state" id="commission_state">
                                    <option value="">Select Type Of Commission State</option>
                                    <option value="against_sale_amount">Sale Wise Commission</option>
                                    <option value="against_profit_amount">Profit Wise Commission</option>
                                </select>
                            </div>

                            <!-- Commission Rate Input -->
                            <div class="col-md-6">
                                <label for="commission_rate" class="form-label fw-bold">Commission Rate</label>
                                <input type="number" class="form-control rounded-pill" id="commission_rate"  name="commission_rate">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-end mt-4">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                            <a  class="btn btn-primary rounded-pill px-4 affliatorEdit">Update</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

   <!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>

    $(document).on('click', '.affliatorSave', function () {
    var formdata = new FormData($('#affiliatorForm')[0]);

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $.ajax({
        url: "{{ route('affiliator.store') }}",
        type: "POST",
        data: formdata,
        contentType: false,
        processData: false,
        success: function (data) {
            if (data.status == 200) {
                $('#affiliatorModal').modal('hide');
                $('#affiliatorForm')[0].reset();
                toastr.success("Affiliator Added Successfully");
                loadData();
            }
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                var errors = xhr.responseJSON.errors;
                $('.error-message').remove();

                $.each(errors, function (key, value) {
                    $('#' + key).after('<span class="text-danger error-message">' + value[0] + '</span>');
                });

                toastr.error("Please fix the errors and try again.");
            } else {
                toastr.error("Something went wrong. Please try again.");
            }
        }
    });
});


function loadData(){
    $.ajax({
      url:"{{ route('affiliator.view') }}",
      type:"GET",
       success:function(data){
          if(data.status == 200){
            let affiliator = data.affiliator;
              $('#table_data').empty();
            affiliator.forEach(function(affiliator,index) {
               $('#table_data').append(`

              <tr>
                <td>${index+1}</td>
                <td>${affiliator.name}</td>
                <td>${affiliator.phone}</td>
                <td>${affiliator.commission_type}</td>
                <td>${affiliator.commission_rate}</td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#affiliatorEditModal" id="edit" data-id="${affiliator.id}">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="javascript:void(0)" data-id="${affiliator.id}" id="destroy">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </li>
                        </ul>
                    </div>
                </td>
              </tr>
               `)
            });
          }

       },
    });
}



$(document).on('click', '#edit', function () {
    var id = $(this).data('id');
    $.ajax({
        url: "{{ url('/affiliator/edit') }}/" + id,
        type: "GET",
        success: function (data) {
           if (data.status == 200) {

              let affiliator = data.affiliator;
              $('#affiliator_id').val(affiliator.id);
              $('#name').val(affiliator.name);
              $('#phone').val(affiliator.phone);
              $('#commission_type').val(affiliator.commission_type);
              $('#commission_rate').val( affiliator.commission_rate);
              $('#commission_state').val(affiliator.commission_state);
           }
        }
    })
});

$(document).on('click','.affliatorEdit',function(){
    var formdata = new FormData($('#affiliatorEditForm')[0]);
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $.ajax({
        url: "{{ route('affiliator.update') }}",
        type: "POST",
        data: formdata,
        contentType: false,
        processData: false,
        success: function (data) {
          if(data.status== 200){
            $('#affiliatorEditModal').modal('hide');
            $('#affiliatorEditForm')[0].reset();
            toastr.success("Affiliator Updated Successfully");
            loadData();
          }
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                var errors = xhr.responseJSON.errors;
                $('.error-message').remove();

                $.each(errors, function (key, value) {
                    $('#' + key).after('<span class="text-danger error-message">' + value[0] + '</span>');
                });

                toastr.error("Please fix the errors and try again.");
            } else {
                toastr.error("Something went wrong. Please try again.");
            }
        }
    })

})


$(document).on('click', '#destroy', function () {
    var id = $(this).data('id');

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $.ajax({
        url: "{{ route('affiliator.delete') }}",
        type: "POST",
        data: {id:id},
        success: function (data) {
            if(data.status == 200){

                toastr.success("Affiliator Deleted Successfully");
                loadData();
            }
        }
    })
});





 loadData();


    flatpickr("#start_date", {
        enableTime: false,
        dateFormat: "Y-m-d",
        placeholder: "Start Date"
    });

    flatpickr("#end_date", {
        enableTime: false,
        dateFormat: "Y-m-d",
        placeholder: "Start Date"
    });
</script>


@endsection
