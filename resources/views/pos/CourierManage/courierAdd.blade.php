@extends('master')

@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Courier Set</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8 mx-auto grid-margin stretch-card">
            <div class="card shadow-sm rounded-4">
                <div class="card-header bg-primary text-white fw-semibold fs-5 rounded-top">
                    Courier Basic Information
                </div>
                <div class="card-body">
                    <form  id="couriermanage">
                        @csrf
                        <div class="mb-3">
                            <label for="courier_name" class="form-label">Courier Name</label>
                            <input type="text" class="form-control" id="courier_name" name="courier_name" placeholder="e.g. Sundarban Courier" >
                        </div>

                        <div class="mb-3">
                            <label for="base_url" class="form-label">Base URL</label>
                            <input type="url" class="form-control" id="base_url" name="base_url" placeholder="https://api.courier.com" >
                        </div>

                        <div class="mb-3">
                            <label for="contact_number" class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" id="contact_number" name="contact_number" placeholder="01XXXXXXXXX" >
                        </div>

                        <div class="mb-3">
                            <label for="current_balance" class="form-label">Current Balance</label>
                            <input type="number" class="form-control" id="current_balance" name="current_balance" step="0.01" placeholder="e.g. 1500.00" >
                        </div>

                        <div class="text-end">
                            <a  class="btn btn-primary px-4 btn_save">Save</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).on('click', '.btn_save', function(e) {
     // Clear previous error messages and remove the 'is-invalid' class
     $('.text-danger').remove();  // Remove all previous error messages
    $('.form-control').removeClass('is-invalid');  // Remove 'is-invalid' class

    var courier_name = $('input[name="courier_name"]').val().trim();
    var base_url = $('input[name="base_url"]').val().trim();
    // var contact_number = $('input[name="contact_number"]').val().trim();
    // var current_balance = $('input[name="current_balance"]').val().trim();

    let isValid = true;

    // Validate Courier Name
    if (courier_name === '') {
        $('input[name="courier_name"]').after('<span class="text-danger">Please enter a courier name.</span>');
        $('input[name="courier_name"]').addClass('is-invalid'); // Optional: To add visual invalid class
        isValid = false;
    }

    // Validate Base URL
    if (base_url === '') {
        $('input[name="base_url"]').after('<span class="text-danger">Please enter a Base URL.</span>');
        $('input[name="base_url"]').addClass('is-invalid'); // Optional: To add visual invalid class
        isValid = false;
    }

    // Validate Contact Number
    // if (contact_number === '') {
    //     $('input[name="contact_number"]').after('<span class="text-danger">Please enter a contact number.</span>');
    //     $('input[name="contact_number"]').addClass('is-invalid'); // Optional: To add visual invalid class
    //     isValid = false;
    // }


    // if (current_balance === '') {
    //     $('input[name="current_balance"]').after('<span class="text-danger">Please enter a current balance.</span>');
    //     $('input[name="current_balance"]').addClass('is-invalid'); // Optional: To add visual invalid class
    //     isValid = false;
    // }

     var formData = new FormData($('#couriermanage')[0]);
   if(isValid){
    $.ajax({
        url: "{{ route('couriers.manage.store') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success:function(response){
            if(response.status == 200){
                $('#couriermanage')[0].reset();
                toastr.success("Courier Added Successfully");
            }

            if(response.status == 400){
                toastr.error("Courier Already Exists");
            }
        }
        });
   }

});
</script>

@endsection

