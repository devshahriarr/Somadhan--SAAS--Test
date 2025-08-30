@extends('master')
@section('title', '| Settings Page')
@section('admin')
    @php
        $mode = App\models\PosSetting::all()->first();
    @endphp
    <h2 style="margin: 20px">Settings</h2>
    <div class="row">
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-info">Company Details</h6>
                    <form id="myValidForm" action="{{ route('pos.settings.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="setting_id"value="1">
                        <div class="row">
                            <!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <!-- Label for the file input -->
                                    <label class="form-label" for="myDropify">Upload New Logo</label>
                                    <!-- File input -->
                                    <input type="file" name="logo" id="myDropify" class="form-control field_required">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <!-- Label for the image -->
                                    <label class="form-label">Current Logo</label>
                                    <!-- Image -->
                                    <img src="{{ $allData->logo ? asset($allData->logo) : asset('dummy/image.jpg') }}"
                                        height="auto" width="250" alt="logo">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <label class="form-label">Company</label>
                                    {{-- value="{{$allData->company}}"  --}}
                                    <input type="text" name="company" class="form-control"
                                        placeholder="Enter company Name"
                                        value="{{ !empty($allData->id) ? $allData->company : '' }}">

                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <label class="form-label">Email Address
                                        {{-- <span class="text-danger">*</span> --}}
                                    </label>
                                    <input type="email" name="email"
                                        value="{{ !empty($allData->id) ? $allData->email : '' }}"class="form-control"
                                        placeholder="Enter email Address">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <label class="form-label">Facebook
                                        {{-- <span class="text-danger">*</span> --}}
                                    </label>
                                    <input type="text" name="facebook"
                                        value="{{ !empty($allData->id) ? $allData->facebook : '' }}" class="form-control"
                                        placeholder="Enter facebook url">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <label class="form-label">Header Text
                                        {{-- <span class="text-danger">*</span> --}}
                                    </label>
                                    <input type="text" name="header_text"
                                        value="{{ !empty($allData->id) ? $allData->header_text : '' }}" class="form-control"
                                        placeholder="Enter Header text">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <label class="form-label">Footer Text
                                        {{-- <span class="text-danger">*</span> --}}
                                    </label>
                                    <input type="text" name="footer_text"
                                        value="{{ !empty($allData->id) ? $allData->footer_text : '' }}" class="form-control"
                                        placeholder="Enter footer text">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <label class="form-label">Phone</label>

                                    <input type="number" name="phone" class="form-control"
                                        placeholder="Enter Phone Number"value="{{ !empty($allData->id) ? $allData->phone : '' }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <label class="form-label">Address</label>
                                    <input type="text" class="form-control" placeholder="Enter Address" name="address"
                                        value="{{ !empty($allData->id) ? $allData->address : '' }}">
                                </div>
                            </div>
                            @if (Auth::user()->can('dark.mode'))
                                <h6 class="card-title text-info">Others Settings</h6><br><br>
                                <div class="col-sm-6">
                                    <div class="mb-3 form-valid-groups">
                                        <div class="form-check form-switch">
                                            <input class=" form-check-input" type="checkbox"
                                                {{ $mode->dark_mode == 2 ? 'checked' : '' }} name="dark_mode" role="switch"
                                                id="flexSwitchCheckDefault">
                                            <label class="form-check-label" for="flexSwitchCheckDefault">Dark Mode</label>
                                        </div>
                                        {{-- {{ $allData->dark_mode == 2 ?  'checked' : '' }} --}}
                                    </div>
                                </div>
                            @endif
                        </div>
                        <!-- Row -->
                        <div class="mt-2">
                            <input type="submit" class="btn btn-primary submit" value="Save Changes">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#myValidForm').validate({
                rules: {
                    company: {
                        required: true,
                    },
                },
                messages: {
                    company: {
                        required: 'Company Name is Required',
                    },
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-valid-groups').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                    $(element).addClass('is-valid');
                },
            });
        });
    </script>
@endsection
