@extends('master')
@section('title','| Edit Expense')
@section('admin')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
            <div class="">

                <h4 class="text-right"><a href="{{ route('expense.view') }}" class="btn btn-info">View All Expense</a></h4>
            </div>
        </div>
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-info">Edit Expanse</h6>
                    <form id="myValidForm" action="{{ route('expense.update', $expense->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <label class="form-label">Purpose<span class="text-danger">*</span></label>
                                    <input type="text" name="purpose" value="{{ $expense->purpose }}"
                                        class="form-control field_required" placeholder="Enter purpose">
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <label class="form-label">Amount<span class="text-danger">*</span></label>
                                    <input type="number" name="amount" value="{{ $expense->amount }}" class="form-control"
                                        placeholder="Enter Amount">
                                </div>
                            </div>
                            <div class="col-sm-6 form-valid-group">


                                <div class="mb-3" bis_skin_checked="1">
                                    <label for="ageSelect" class="form-label">Select Expense Category <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select expense_category_name is-valid js-example-basic-single"
                                        name="expense_category_id" aria-invalid="false">
                                        <option selected="" disabled="">Select Expense Category </option>
                                        @foreach ($expenseCategory as $expanses)
                                            <option value="{{ $expanses->id }}"
                                                {{ $expanses->id == $expense->expense_category_id ? 'selected' : '' }}>
                                                {{ $expanses->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger related_sign_error"></span>
                                </div>



                            </div><!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <label class="form-label">Splender<span class="text-danger">*</span></label>
                                    <input type="text" name="spender" value="{{ $expense->spender }}"
                                        class="form-control" placeholder="Enter Amount">
                                </div>
                            </div><!-- Col -->

                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <label class="form-label">Date<span class="text-danger">*</span></label>

                                        <div class="input-group flatpickr" id="flatpickr-date">
                                            <input type="text"name="expense_date" value="{{ $expense->expense_date }}"
                                                class="form-control @error('expense_date') is-invalid @enderror flatpickr-input"
                                                data-input="" readonly="readonly"
                                                placeholder="Select Expense Date">
                                            <span class="input-group-text input-group-addon"
                                                data-toggle=""><svg xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-calendar">
                                                    <rect x="3" y="4" width="18" height="18"
                                                        rx="2" ry="2">
                                                    </rect>
                                                    <line x1="16" y1="2" x2="16"
                                                        y2="6"></line>
                                                    <line x1="8" y1="2" x2="8"
                                                        y2="6"></line>
                                                    <line x1="3" y1="10" x2="21"
                                                        y2="10"></line>
                                                </svg></span>
                                        </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3" bis_skin_checked="1">
                                    <label for="ageSelect" class="form-label">Select Bank Acoount</label>
                                    <select class="form-select bank_id is-valid js-example-basic-single"
                                        name="bank_account_id" aria-invalid="false">
                                        <option selected="" disabled="" value="">Select Bank</option>
                                        @foreach ($bank as $banks)
                                            <option value="{{ $banks->id }}"
                                                {{ $banks->id == $expense->bank_account_id ? 'selected' : '' }}>
                                                {{ $banks->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger related_sign_error"></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">Expense Image</h6>
                                        <div style="height:150px;position:relative">
                                            <button type="button" class="btn btn-info edit_upload_img" id="abc"
                                                style="position: absolute;top:50%;left:50%;transform:translate(-50%,-50%)">Browse</button>
                                            <img class="img-fluid showEditImage"
                                            src="{{ $expense->image ? asset('uploads/expense/' . $expense->image) : asset('dummy/image.jpg') }}"
                                                style="height:100%; object-fit:cover">
                                        </div>
                                        <input hidden type="file" class="edit_image" name="image" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <label class="form-label">Note<span class="text-danger">*</span></label>
                                    <textarea name="note" class="form-control" id="" cols="10" rows="5">{{ $expense->note }}</textarea>
                                </div>
                            </div>
                        </div><!-- Row -->
                        <br>
                        <div>
                            <input type="submit" class="btn btn-primary submit" value="Update">
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
                    purpose: {
                        required: true,
                    },
                    amount: {
                        required: true,
                    },
                    expense_category_id: {
                        required: true,
                    },
                    spender: {
                        required: true,
                    },
                    // bank_account_id:{
                    //     required : true,
                    // },
                    // note:{
                    //     required : true,
                    // },
                    expense_date: {
                        required: true,
                    },
                },
                messages: {
                    purpose: {
                        required: 'Please Enter Purpose',
                    },
                    amount: {
                        required: 'Please Enter Amount',
                    },
                    expense_category_id: {
                        required: 'Please select expense category name',
                    },
                    spender: {
                        required: 'Please Enter  spender',
                    },
                    // bank_account_id: {
                    //     required : 'Please Select Bank Name',
                    // },
                    // note: {
                    //     required : 'Please Enter Note',
                    // },
                    expense_date: {
                        required: 'Please Select Date',
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


$(document).ready(function(){
    const edit_upload_img = document.querySelector('#abc');
            const edit_image = document.querySelector('.edit_image');
            edit_upload_img.addEventListener('click', function(e) {
                e.preventDefault();
                edit_image.click();

                edit_image.addEventListener('change', function(e) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        document.querySelector('.showEditImage').src = e.target.result;
                    }
                    reader.readAsDataURL(this.files[0]);
                });
            });
});
    </script>
@endsection
