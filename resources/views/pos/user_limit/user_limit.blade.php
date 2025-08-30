@extends('master')
@section('title', '| User Limit')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">User Limit</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title">User Limit</h6>
                        <button class="btn btn-rounded-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#exampleModalLongScollable"><i data-feather="plus"></i></button>
                    </div>
                    <div id="" class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Company Name</th>
                                    <th>User Limit</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="showData">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalLongScollable" tabindex="-1" aria-labelledby="exampleModalScrollableTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Add User Limit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="userLimitForm row">
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Company Name<span class="text-danger">*</span></label>
                            <select class="form-control company_name" name="company_name" id="">
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger company_name_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">User Limit<span class="text-danger">*</span></label>
                            <select class="form-control user_limit" name="user_limit" id="">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="40">40</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span class="text-danger user_limit_error"></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_user_limit">Save</button>
                </div>
            </div>
        </div>
    </div>
    {{-- //Edit Modal --}}
    <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Update User Limit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="editUserLimit row">
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Company Name <span class="text-danger">*</span></label>
                            <select class="form-control edit_company_name" name="company_name" id="">
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger company_name_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">User Limit<span class="text-danger">*</span></label>
                            <select class="form-control edit_user_limit" name="user_limit" id="">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="40">40</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span class="text-danger user_limit_error"></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary update_user_limit">Update</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        // remove error
        function errorRemove(element) {
            tag = element.tagName.toLowerCase();
            if (element.value != '') {
                if (tag == 'select') {
                    $(element).closest('.mb-3').find('.text-danger').hide();
                } else {
                    $(element).siblings('span').hide();
                    $(element).css('border-color', 'green');
                }
            }
        }

        $(document).ready(function() {
            // show error
            function showError(name, message) {
                $(name).css('border-color', 'red'); // Highlight input with red border
                $(name).focus(); // Set focus to the input field
                $(`${name}_error`).show().text(message); // Show error message
            }


            function userLimitView() {
                $.ajax({
                    url: '/user-limit/view',
                    method: 'GET',
                    success: function(res) {
                        const userLimits = res.data;
                        $('.showData').empty();

                        if (res.status === 200 && userLimits.length > 0) {
                            $.each(userLimits, function(index, limit) {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                                    <td>${index + 1}</td>
                                    <td>${limit.company ? limit.company.name : ""}</td>
                                    <td>${limit.user_limit ?? ""}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton1"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Manage
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <a href="#" class="dropdown-item user_limit_edit" data-id=${limit.id} data-bs-toggle="modal" data-bs-target="#edit">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                Edit</a>
                                                <a href="#" class="dropdown-item user_limit_delete" data-id=${limit.id}>
                                                    <i class="fa-solid fa-trash-can"></i>
                                                Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                    `;
                                $('.showData').append(tr);
                            });
                        } else {
                            $('.showData').html(`
                                <tr>
                                    <td colspan='9'>
                                        <div class="text-center text-warning mb-2">Data Not Found</div>
                                        <div class="text-center">
                                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalLongScollable">Add Bank Info<i data-feather="plus"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                `);
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 500) {
                            $('body').html(xhr.responseText);
                        } else {
                            // Handle other errors
                            toastr.error('An error occurred. Please try again.');
                        }
                    }
                });
            }
            userLimitView();


            // save User Limit
            const saveUserLimit = document.querySelector('.save_user_limit');
            saveUserLimit.addEventListener('click', function(e) {
                e.preventDefault();
                let formData = new FormData($('.userLimitForm')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/user-limit/store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            $('#exampleModalLongScollable').modal('hide');
                            $('.userLimitForm')[0].reset();
                            userLimitView();
                            toastr.success(res.message);
                        } else {
                            if (res.error.company_name) {
                                showError('.company_name', res.error.company_name);
                            }
                            if (res.error.user_limit) {
                                showError('.user_limit', res.error.user_limit);
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        // Display the custom error page when a server error occurs
                        if (xhr.status === 500) {
                            $('body').html(xhr.responseText);
                        } else {
                            // Handle other errors
                            toastr.error('An error occurred. Please try again.');
                        }
                    }
                });
            })


            // edit User Limit
            $(document).on('click', '.user_limit_edit', function(e) {
                e.preventDefault();
                // console.log('0k');
                let id = this.getAttribute('data-id');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: `/user-limit/edit/${id}`,
                    type: 'GET',
                    success: function(res) {
                        if (res.status == 200) {
                            $('.edit_company_name').val(res.userLimit.id);
                            $('.edit_user_limit').val(res.userLimit.user_limit);
                            $('.update_user_limit').val(res.userLimit.id);
                        } else {
                            toastr.warning("No Data Found");
                        }
                    }
                });
            })

            // update User Limit //
            $('.update_user_limit').click(function(e) {
                e.preventDefault();
                let id = $(this).val();
                let formData = new FormData($('.editUserLimit')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: `/user-limit/update/${id}`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            $('#edit').modal('hide');
                            $('.editUserLimit')[0].reset();
                            userLimitView();
                            toastr.success(res.message);
                        } else {
                            if (res.error.company_name) {
                                showError('.edit_company_name', res.error.company_name);
                            }
                            if (res.error.user_limit) {
                                showError('.edit_user_limit', res.error.user_limit);
                            }
                        }
                    }
                });
            })

            // bank Delete
            $(document).on('click', '.user_limit_delete', function(e) {
                e.preventDefault();
                let id = this.getAttribute('data-id');

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to Delete this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: `/user-limit/delete/${id}`,
                            type: 'GET',
                            success: function(data) {
                                if (data.status == 200) {
                                    toastr.success(data.message);
                                    userLimitView();
                                } else {
                                    Swal.fire({
                                        icon: "error",
                                        title: "Oops...",
                                        text: data.message,
                                        footer: '<a href="#">Why do I have this issue?</a>'
                                    });
                                }
                            }
                        });
                    }
                });
            })
        });
    </script>
@endsection
