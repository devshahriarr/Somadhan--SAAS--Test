@extends('master')

@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Courier Details Edit</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8 mx-auto grid-margin stretch-card">
            <div class="card shadow-sm rounded-4">
                <div class="card-header bg-primary text-white fw-semibold fs-5 rounded-top">
                    Courier Details Edit
                </div>
                <div class="card-body">
                    <form id="couriermanage">
                        @csrf
                        @php
                            $normalizedName = strtolower(str_replace(' ', '', $courier_manage->courier_name));
                        @endphp

                         <input type="text" name="id" value="{{$courier_manage->id }}" hidden>
                        <div class="mb-3">
                            <label for="courier_name" class="form-label">Courier Name</label>
                            <input type="text" class="form-control" id="courier_name" name="courier_name" placeholder="e.g. Sundarban Courier" value="{{ $courier_manage->courier_name }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="base_url" class="form-label">Base URL</label>
                            <input type="url" class="form-control" id="base_url" name="base_url" placeholder="https://api.courier.com" value="{{ $courier_manage->base_url }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="contact_number" class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" id="contact_number" name="contact_number" placeholder="01XXXXXXXXX" value="{{ $courier_manage->contact_number }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="current_balance" class="form-label">Current Balance</label>
                            <input type="number" class="form-control" id="current_balance" name="current_balance" step="0.01" placeholder="e.g. 1500.00" value="{{ $courier_manage->current_balance }}" required>
                        </div>

                        @if($normalizedName === 'steadfast')
                            <div class="mb-3">
                                <label for="api_key" class="form-label">API Key</label>
                                <input type="text" class="form-control" id="api_key" name="api_key" placeholder="Enter SteadFast API Key" value="{{ $courier_other_info->api_key ??''}}" required>
                            </div>

                            <div class="mb-3">
                                <label for="secret_key" class="form-label">Secret Key</label>
                                <input type="text" class="form-control" id="secret_key" name="secret_key" value="{{ $courier_other_info->secret_key??'' }}" placeholder="Enter SteadFast Secret Key">
                            </div>
                        @endif

                        @if($normalizedName === 'redx')
                            <div class="mb-3">
                                <label for="api_access_token" class="form-label">API Access Token</label>
                                <input type="text" class="form-control" id="api_access_token" name="api_access_token" placeholder="Enter REDX API Access Token" value="{{ $courier_other_info->api_access_token ??''}}" required>
                            </div>
                        @endif

                        @if($normalizedName === 'paperfly')
                            <div class="mb-3">
                                <label for="user_name" class="form-label">User Name</label>
                                <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Enter Paperfly User Name" value="{{ $courier_other_info->user_name ??''}}" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="text" class="form-control" id="password" name="password" placeholder="Enter Paperfly Password" value="{{ $courier_other_info->password ??''}}" required>
                            </div>

                            <div class="mb-3">
                                <label for="paperfly_key" class="form-label">PaperFly Key</label>
                                <input type="text" class="form-control" id="paperfly_key" name="paperfly_key" placeholder="Enter PaperFly Key" value="{{ $courier_other_info->paperfly_key ??''}}" required>
                            </div>
                        @endif

                        <div class="text-end">
                            <a class="btn btn-primary px-4 btn_save">Update</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- jQuery CDN --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- JavaScript Validation and AJAX --}}
    <script>
        $(document).on('click', '.btn_save', function(e) {
            e.preventDefault();

            $('.text-danger').remove();  // Remove previous error messages
            $('.form-control').removeClass('is-invalid');  // Remove invalid classes

            var courier_name = $('#courier_name').val().trim();
            var base_url = $('#base_url').val().trim();
            // var contact_number = $('#contact_number').val().trim();
            // var current_balance = $('#current_balance').val().trim();
            var api_key = $('#api_key').val()?.trim() || '';
            var secret_key = $('#secret_key').val()?.trim() || '';
            var api_access_token = $('#api_access_token').val()?.trim() || '';
            var user_name = $('#user_name').val()?.trim() || '';
            var password = $('#password').val()?.trim() || '';
            var paperfly_key = $('#paperfly_key').val()?.trim() || '';

            let normalizedName = courier_name.toLowerCase().replace(/\s+/g, '');
            let isValid = true;

            // Basic Validation
            if (courier_name === '') {
                $('#courier_name').after('<span class="text-danger">Please enter a courier name.</span>').addClass('is-invalid');
                isValid = false;
            }

            if (base_url === '') {
                $('#base_url').after('<span class="text-danger">Please enter a Base URL.</span>').addClass('is-invalid');
                isValid = false;
            }

            // if (contact_number === '') {
            //     $('#contact_number').after('<span class="text-danger">Please enter a contact number.</span>').addClass('is-invalid');
            //     isValid = false;
            // }

            // if (current_balance === '') {
            //     $('#current_balance').after('<span class="text-danger">Please enter a current balance.</span>').addClass('is-invalid');
            //     isValid = false;
            // }

            // Conditional Validation
            if (normalizedName === 'steadfast') {
                if (api_key === '') {
                    $('#api_key').after('<span class="text-danger">API Key is required.</span>').addClass('is-invalid');
                    isValid = false;
                }
                if (secret_key === '') {
                    $('#secret_key').after('<span class="text-danger">Secret Key is required.</span>').addClass('is-invalid');
                    isValid = false;
                }
            }

            if (normalizedName === 'redx') {
                if (api_access_token === '') {
                    $('#api_access_token').after('<span class="text-danger">API Access Token is required.</span>').addClass('is-invalid');
                    isValid = false;
                }
            }

            if (normalizedName === 'paperfly') {
                if (user_name === '') {
                    $('#user_name').after('<span class="text-danger">User Name is required.</span>').addClass('is-invalid');
                    isValid = false;
                }
                if (password === '') {
                    $('#password').after('<span class="text-danger">Password is required.</span>').addClass('is-invalid');
                    isValid = false;
                }
                if (paperfly_key === '') {
                    $('#paperfly_key').after('<span class="text-danger">Paperfly Key is required.</span>').addClass('is-invalid');
                    isValid = false;
                }
            }

            // Submit if valid
            if (isValid) {
                var formData = new FormData($('#couriermanage')[0]);
                $.ajax({
                    url: "{{ route('couriers.manage.other.info.update') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status == 200) {
                            $('#couriermanage')[0].reset();
                            toastr.success("Courier Updated Successfully");
                        }
                    },
                    error: function(xhr) {
                        toastr.error("Something went wrong.");
                    }
                });
            }
        });
    </script>
@endsection
