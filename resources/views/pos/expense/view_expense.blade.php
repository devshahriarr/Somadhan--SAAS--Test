@extends('master')
@section('title','| Expense')
@section('admin')
    <div class="row">
        <style>

    .nav.nav-tabs .nav-item .nav-link.active {
        background-color: #edf1f5;
        color: #6571FF!important;
    }
        </style>
    {{-- ///////////tab//////////// --}}
    <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home"
            aria-selected="true">Add Expense</a>
    </li>
    <li class="nav-item">
        {{-- <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#" role="tab" aria-controls="contact" aria-selected="false"></a> --}}
        <a class="nav-link " id="expense-tab" data-bs-toggle="tab" href="#expense " role="tab"
            aria-controls="profile" aria-selected="false" >Expense Report</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab"
            aria-controls="profile" aria-selected="false">View Expense Category</a>
    </li>
    </ul>
    <div class="tab-content border border-top-0 p-3 active" id="myTabContent">
    <div class="tab-pane show active " id="home" role="tabpanel" aria-labelledby="home-tab">
        {{-- ///Expense --}}
        <div class="row">

            <div class="col-md-12 grid-margin stretch-card">
                <!--------Add Expense-------->
                <div class="row">
                    <div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">

                    </div>
                    <div class="col-md-12 stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title text-info">Add Expanse</h6>
                                <form id="myValidForm" action="{{ route('expense.store') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <!-- Col -->
                                        <div class="col-sm-6">
                                            <div class="mb-3 form-valid-groups">
                                                <label class="form-label">Purpose<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="purpose"
                                                    class="form-control field_required  @error('purpose') is-invalid @enderror"
                                                    placeholder="Enter purpose" value="{{ old('purpose') }}">
                                            </div>
                                            @error('purpose')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div><!-- Col -->
                                        <div class="col-sm-6">
                                            <div class="mb-3 form-valid-groups">
                                                <label class="form-label">Amount<span
                                                        class="text-danger">*</span></label>
                                                <input type="number" name="amount"
                                                    class="form-control @error('amount') is-invalid @enderror"
                                                    placeholder="Enter Amount" value="{{ old('amount') }}">
                                            </div>
                                            @error('amount')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-valid-group">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="mb-3" bis_skin_checked="1">
                                                        <label for="ageSelect" class="form-label">Select Expense
                                                            Category <span class="text-danger">*</span></label>
                                                        <select
                                                            class="form-select expense_category_name is-valid js-example-basic-single @error('expense_category_id') is-invalid @enderror"
                                                            name="expense_category_id" aria-invalid="false">
                                                            <option selected="" disabled="">Select Expense
                                                                Category </option>
                                                            @foreach ($expenseCategory as $expanse)
                                                                <option value="{{ $expanse->id }}">
                                                                    {{ $expanse->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('expense_category_id')
                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 float-end">
                                                    <div>
                                                        <label for="ageSelect" class="form-label">Add Expense
                                                            Category </label>
                                                        <a href="" class="btn btn-sm bg-info text-dark"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#exampleModalLongScollable"><i
                                                                data-feather="plus"></i>
                                                            Expense Category</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- Col -->
                                        <div class="col-sm-6">
                                            <div class="mb-3 form-valid-groups">
                                                <label class="form-label">Splender<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="spender"
                                                    class="form-control @error('spender') is-invalid @enderror"
                                                    value="{{ old('spender') }}" placeholder="Enter Splender">
                                            </div>
                                            @error('spender')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div><!-- Col -->

                                        <div class="col-sm-6">
                                            <div class="mb-3 form-valid-groups">
                                                <label class="form-label">Date<span
                                                        class="text-danger">*</span></label>

                                                <div class="input-group flatpickr" id="flatpickr-date">
                                                    <input type="text"name="expense_date"
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
                                            @error('expense_date')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3" bis_skin_checked="1">
                                                <label for="ageSelect" class="form-label">Select Bank
                                                    Acoount</label>
                                                <select
                                                    class="form-select bank_id is-valid @error('bank_account_id') is-invalid @enderror js-example-basic-single"data-width="100%"
                                                    name="bank_account_id" aria-invalid="false">
                                                    <option selected="" disabled="" value="">Select
                                                        Bank</option>
                                                    @foreach ($bank as $banks)
                                                        <option value="{{ $banks->id }}">{{ $banks->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('bank_account_id')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <span class="text-danger related_sign_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h6 class="card-title">Expense Image</h6>
                                                        <p class="mb-3 text-warning">Note: <span class="fst-italic">Image not
                                                            required.</span></p>
                                                        <input type="file"  name="image" id="myDropify" />
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3 form-valid-groups">
                                                <label class="form-label">Note</label>
                                                <textarea name="note" class="form-control" id="" cols="10" rows="5"></textarea>
                                            </div>
                                        </div>
                                    </div><!-- Row -->
                                    <div>
                                        <input type="submit" class="btn btn-primary submit" value="Save">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- /////////////////Add Modal//////////////// --}}
                <div class="modal fade" id="exampleModalLongScollable" tabindex="-1"
                    aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalScrollableTitle">Add Expense Category</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="btn-close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="signupForm" class="categoryForm">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Expense Category Name</label>
                                        <input id="defaultconfig" class="form-control category_name"
                                            maxlength="250" name="name" type="text"
                                            onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                                        <span class="text-danger category_name_error"></span>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary save_category">Save</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!---------------->
            </div>
        </div>
    </div>
    {{-- ///End Expense --}}

    <div class="tab-pane fade show " id="expense" role="tabpanel" aria-labelledby="expense-tab">
    {{-- /////Expensse Report Start --}}
    <div class="row">
    <div class="col-md-12   grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <!-- Col -->
                    <div class="col-sm-4">
                        <div class="input-group flatpickr" id="flatpickr-date">
                            <input type="text" class="form-control from-date"
                                placeholder="Select date" data-input>
                            <span class="input-group-text input-group-addon" data-toggle><i
                                    data-feather="calendar"></i></span>
                        </div>
                    </div><!-- Col -->
                    <div class="col-sm-4">
                        <div class="input-group flatpickr" id="flatpickr-date">
                            <input type="text" class="form-control to-date" placeholder="Select date"
                                data-input>
                            <span class="input-group-text input-group-addon" data-toggle><i
                                    data-feather="calendar"></i></span>
                        </div>
                    </div>
                    <style>
                        .select2-container--default {
                            width: 100% !important;
                        }
                    </style>

                </div><br>
                <div class="row">
                    <div class="col-md-11 mb-2"> <!-- Left Section -->
                        <div class="justify-content-left">
                            <a href="" class="btn btn-sm bg-info text-dark mr-2"
                                id="filter">Filter</a>
                            <a class="btn btn-sm bg-primary text-dark" onclick="resetWindow()">Reset</a>
                        </div>
                    </div>

                    <div class="col-md-1"> <!-- Right Section -->
                        <div class="justify-content-end">
                            <a href="#" onclick="printTable()"
                                class="btn btn-sm bg-info text-dark mr-2"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-printer btn-icon-prepend">
                                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                    <path
                                        d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2">
                                    </path>
                                    <rect x="6" y="14" width="12" height="8"></rect>
                                </svg>Print</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    {{-- ////list// --}}
    <div id="filter-rander">
        @include('pos.expense.expense-filter-rander-table')

    </div>
    </div>
    {{-- /////Expensse Report End --}}
    </div>
    {{-- /////End Report --}}
    </div>

    <!-----Expense Categories Start---->
    <div class="tab-content border border-top-0 p-3" id="myTabContent">
    <div class="tab-pane fade " id="profile" role="tabpanel" aria-labelledby="profile-tab">
        <div class="row">
            <div>

            </div>
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title text-info">Expense Category </h6>
                        <div id="tableContainer" class="table-responsive">
                            <table class="table">
                                <thead class="action">
                                    <tr>
                                        <th>SN</th>
                                        <th>Category name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="showData">
                                    @if ($expenseCat->count() > 0)
                                        @foreach ($expenseCat as $key => $expensesCategory)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $expensesCategory->name ?? '-' }}</td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-primary category_edit"
                                                        title="Edit" data-bs-toggle="modal"
                                                        data-bs-target="#exampleModalLongScollable1{{ $expensesCategory->id }}">
                                                        Edit
                                                    </a>
                                                    <a href="{{ route('expense.category.delete', $expensesCategory->id) }}"
                                                        id="delete" class="btn btn-sm btn-danger "
                                                        title="Delete">
                                                        Delete
                                                    </a>
                                                </td>
                                            </tr>
                                            <div class="modal fade"
                                                id="exampleModalLongScollable1{{ $expensesCategory->id }}"
                                                tabindex="-1" aria-labelledby="exampleModalScrollableTitle"
                                                aria-hidden="true">
                                                <div
                                                    class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="exampleModalScrollableTitle">Edit Expense
                                                                Category</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"
                                                                aria-label="btn-close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form id="signupForm{{ $expensesCategory->id }}"
                                                                class="categoryFormEdit">
                                                                <div class="mb-3">
                                                                    <label for="name" class="form-label">Edit
                                                                        Expense Category Name</label>
                                                                    <input id="defaultconfig"
                                                                        class="form-control category_name"
                                                                        maxlength="250" name="name"
                                                                        type="text"
                                                                        onkeyup="errorRemove(this);"
                                                                        onblur="errorRemove(this);"
                                                                        value="{{ $expensesCategory->name }}">
                                                                    <span
                                                                        class="text-danger category_name_error"></span>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button type="button"
                                                                class="btn btn-primary update_expense_category"
                                                                data-category-id="{{ $expensesCategory->id }}">Update</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="12">
                                                <div class="text-center text-warning mb-2">Data Not Found</div>

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
    </div>
    </div>

        <!-----Expense Categories End---->
    </div>
    {{-- ///////////tab//////////// --}}

    <script>
        $(document).ready(function() {
            $(document).on('click', '.update_expense_category', function(e) {
                e.preventDefault();
                let categoryId = $(this).data(
                'category-id'); // Get the category ID from the button's data attribute
                let formData = new FormData($('#signupForm' + categoryId)[
                0]); // Use the category ID to select the correct form

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: `/expense/category/update/${categoryId}`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        $('#exampleModalLongScollable1' + categoryId).modal(
                        'hide'); // Hide the correct modal using the category ID
                        $('#signupForm' + categoryId)[0].reset(); // Reset the form
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: res.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        window.location.reload();
                    },
                    error: function(xhr, status, error) {
                        // Handle errors if necessary
                    }
                });
            });


            const saveCategory = document.querySelector('.save_category');
            saveCategory.addEventListener('click', function(e) {
                e.preventDefault();
                let formData = new FormData($('.categoryForm')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/expense/category/store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            $('#exampleModalLongScollable').modal('hide');
                            // formData.delete(entry[0]);
                            // alert('added successfully');
                            $('.categoryForm')[0].reset();
                            toastr.success(res.message);
                            window.location.reload();
                        } else {
                            showError('.category_name', res.error.name);
                        }
                    }
                });
            })
        });
        const filter = document.querySelector('#filter').addEventListener('click', function(e) {
            e.preventDefault();
            filterData();
        });
        document.querySelector('.filter-category').addEventListener('change', function(e) {
            e.preventDefault();
            filterData();
        });

        function filterData() {
            let startDate = document.querySelector('.from-date').value;
            let endDate = document.querySelector('.to-date').value;
            // let filterCtegory = document.querySelector('.filter-category').value;
            // console.log(filterCtegory);
            $.ajax({
                url: "{{ route('expense.filter.view') }}",
                method: 'GET',
                data: {
                    startDate,
                    endDate,

                },
                success: function(res) {
                    jQuery('#filter-rander').html(res);
                }
            });
        } //

        // Print function
        function printTable() {

            // Hide action buttons
            var actionButtons = document.querySelectorAll('.btn-icon');
            actionButtons.forEach(function(button) {
                button.style.display = 'none';
            });
            var actionColumn = document.querySelectorAll('.action th:last-child');
            actionColumn.forEach(function(column) {
                column.style.display = 'none';
            });
            var actionthColumn = document.querySelectorAll('.showData td:last-child');
            actionthColumn.forEach(function(column) {
                column.style.display = 'none';
            });
            // Hide all other elements on the page temporarily
            var bodyContent = document.body.innerHTML;
            var tableContent = document.getElementById('tableContainer').innerHTML;

            document.body.innerHTML = tableContent;

            // Print the specific data table
            window.print();

            // Restore the original content of the page
            document.body.innerHTML = bodyContent;

            // Restore action buttons
            actionButtons.forEach(function(button) {

                button.style.display = 'block';
            });
            // var tabToActivateId = "#expense-tab";
            // window.location.reload();
            window.location.reload();
            // document.getElementById(tabToActivateId).click();
            //
        }
        ////reset button
        function resetWindow() {
            // Reload the page
            window.location.reload();
            // Restore the "Expense Report" tab after the page reloads
            // document.getElementById("profile-tab").click();
        }
        ////////
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
        ////Expense
        const saveCategory = document.querySelector('.save_category');
        saveCategory.addEventListener('click', function(e) {
            e.preventDefault();
            let formData = new FormData($('.categoryForm')[0]);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/expense/category/store',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.status == 200) {
                        $('#exampleModalLongScollable').modal('hide');
                        // formData.delete(entry[0]);
                        // alert('added successfully');
                        $('.categoryForm')[0].reset();
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: res.message,
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        window.location.reload();
                    } else {
                        showError('.category_name', res.error.name);
                    }
                }
            });
        })
    </script>
@endsection
