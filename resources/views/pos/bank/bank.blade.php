@extends('master')
@section('title', '| Bank')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Bank</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title">Bank Table</h6>
                        @if (Auth::user()->can('bank.add'))
                            <button class="btn btn-rounded-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#exampleModalLongScollable"><i data-feather="plus"></i></button>
                        @endif
                    </div>
                    <div id="" class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Account Name</th>
                                    <th>Branch Name</th>
                                    <th>Manager/Owner Name</th>
                                    <th>Phone Number</th>
                                    <th>Account No</th>
                                    <th>Opening Balance</th>
                                    <th>Balance</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="showData">
                            </tbody>
                            <tr>
                                <td colspan="7" style="text-align: right;"><strong>Total Balance:</strong></td>
                                <td colspan="2" id="total-balance">0</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Add Bank Modal -->
    <div class="modal fade" id="exampleModalLongScollable" tabindex="-1" aria-labelledby="exampleModalScrollableTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Add Bank Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="bankForm row">
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Account Name <span class="text-danger">*</span></label>
                            <input id="defaultconfig" class="form-control bank_name" maxlength="100" name="name"
                                type="text" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger bank_name_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Branch Name </label>
                            <input id="defaultconfig" class="form-control branch_name" maxlength="39" name="branch_name"
                                type="text" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger branch_name_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Manager Name/Owner Name</label>
                            <input id="defaultconfig" class="form-control manager_name" maxlength="39" name="manager_name"
                                type="text">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Phone Nnumber </label>
                            <input id="defaultconfig" class="form-control phone_number" maxlength="39" name="phone_number"
                                type="tel" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger phone_number_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Account Number</label>
                            <input id="defaultconfig" class="form-control account" maxlength="39" name="account"
                                type="text" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger account_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Email</label>
                            <input id="defaultconfig" class="form-control email" maxlength="39" name="email"
                                type="email">
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="name" class="form-label">Opening Balance <span
                                    class="text-danger">*</span></label>
                            <input id="defaultconfig" class="form-control opening_balance" maxlength="39"
                                name="opening_balance" type="number" onkeyup="errorRemove(this);"
                                onblur="errorRemove(this);">
                            <span class="text-danger opening_balance_error"></span>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_bank">Save</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    {{-- //Edit Modal --}}
    <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalScrollableTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Edit Bank Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="editBankForm row">
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Account Name</label>
                            <input id="defaultconfig" class="form-control edit_bank_name" maxlength="100" name="name"
                                type="text" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger bank_name_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Branch Name</label>
                            <input id="defaultconfig" class="form-control edit_branch_name" maxlength="39"
                                name="branch_name" type="text">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Manager Name/Owner Name</label>
                            <input id="defaultconfig" class="form-control edit_manager_name" maxlength="39"
                                name="manager_name" type="text">

                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Phone Nnumber</label>
                            <input id="defaultconfig" class="form-control edit_phone_number" maxlength="39"
                                name="phone_number" type="tel">

                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Account Number</label>
                            <input id="defaultconfig" class="form-control edit_account" maxlength="39" name="account"
                                type="text" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger edit_account"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Email</label>
                            <input id="defaultconfig" class="form-control edit_email" maxlength="39" name="email"
                                type="email">
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary update_bank">Update</button>
                </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        // error remove
        function errorRemove(element) {
            if (element.value != '') {
                $(element).siblings('span').hide();
                $(element).css('border-color', 'green');
            }
        }

        $(document).ready(function() {
            // show error
            function showError(name, message) {
                $(name).css('border-color', 'red'); // Highlight input with red border
                $(name).focus(); // Set focus to the input field
                $(`${name}_error`).show().text(message); // Show error message
            }
            // save bank
            const saveBank = document.querySelector('.save_bank');
            saveBank.addEventListener('click', function(e) {
                e.preventDefault();
                let formData = new FormData($('.bankForm')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/bank/store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            $('#exampleModalLongScollable').modal('hide');
                            $('.bankForm')[0].reset();
                            bankView();
                            toastr.success(res.message);
                        } else {
                            if (res.error.name) {
                                showError('.bank_name', res.error.name);
                            }
                            if (res.error.opening_balance) {
                                showError('.opening_balance', res.error.opening_balance);
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




            // function bankView() {
            //     // console.log('hello');
            //     $.ajax({
            //         url: '/bank/view',
            //         method: 'GET',
            //         success: function(res) {
            //             const banks = res.data;
            //             // console.log(banks.account_transaction);
            //             $('.showData').empty();
            //             if (banks.length > 0) {
            //                 $.each(banks, function(index, bank) {
            //                     // Calculate the sum of account_transaction balances
            //                     const tr = document.createElement('tr');
            //                     tr.innerHTML = `
        //                         <td>${index + 1}</td>
        //                         <td>${bank.name ?? ""}</td>
        //                         <td>${bank.branch_name ?? ""}</td>
        //                         <td>${bank.manager_name ?? ""}</td>
        //                         <td>${bank.phone_number ?? 0}</td>
        //                         <td>${bank.account ?? 0}</td>
        //                         <td>${bank?.latest_transaction?.balance ?? 0}</td>
        //                         <td>
        //                               @can('bank.edit')
        //                             <div class="dropdown">
        //                                 <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton1"
        //                                     data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        //                                     Manage
        //                                 </button>

        //                                 <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
        //                                     <a href="#" class="dropdown-item bank_edit" data-id=${bank.id} data-bs-toggle="modal" data-bs-target="#edit">
        //                                         <i class="fa-solid fa-pen-to-square"></i>
        //                                     Edit</a>
        //                                     ${bank?.latest_transaction?.purpose === "Bank" || !bank?.latest_transaction ? <a href="#" class="dropdown-item bank_delete" data-id="${bank.id}"><i class="fa-solid fa-trash-can"></i> Delete</a> : ""}
        //                                 </div>

        //                             </div>
        //                          @endcan
        //                         </td>
        //                     `;
            //                     $('.showData').append(tr);
            //                 });
            //             } else {
            //                 $('.showData').html(`
        //                 <tr>
        //                     <td colspan='9'>
        //                         <div class="text-center text-warning mb-2">Data Not Found</div>
        //                         <div class="text-center">
        //                             <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalLongScollable">Add Bank Info<i data-feather="plus"></i></button>
        //                         </div>
        //                     </td>
        //                 </tr>
        //                 `);
            //             }
            //         }
            //     });
            // }
            // bankView();
            function bankView() {
                // console.log('hello');
                $.ajax({
                    url: '/bank/view',
                    method: 'GET',
                    success: function(res) {
                        const banks = res.data;
                        // console.log(banks.account_transaction);
                        $('.showData').empty();
                        if (banks.length > 0) {
                            $.each(banks, function(index, bank) {
                                // Calculate the sum of account_transaction balances
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                                    <td>${index + 1}</td>
                                    <td>${bank.name ?? ""}</td>
                                    <td>${bank.branch_name ?? ""}</td>
                                    <td>${bank.manager_name ?? ""}</td>
                                    <td>${bank.phone_number ?? 0}</td>
                                    <td>${bank.account ?? 0}</td>
                                    <td>${bank.opening_balance ?? 0}</td>
                                    <td>${bank?.latest_transaction?.balance ?? 0}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton1"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Manage
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <a href="#" class="dropdown-item bank_edit" data-id=${bank.id} data-bs-toggle="modal" data-bs-target="#edit">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                Edit</a>
                                                ${bank?.latest_transaction?.purpose === "Bank" || !bank?.latest_transaction ? `<a href="#" class="dropdown-item bank_delete" data-id="${bank.id}"><i class="fa-solid fa-trash-can"></i> Delete</a>` : ""}
                                            </div>
                                        </div>
                                    </td>
                                `;
                                $('.showData').append(tr);
                            });
                            const totalBalances = res.totalBalance ?? 0;
                            document.getElementById('total-balance').innerText = totalBalances;

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
                    }
                });
            }
            bankView();




            // edit Unit
            $(document).on('click', '.bank_edit', function(e) {
                e.preventDefault();
                // console.log('0k');
                let id = this.getAttribute('data-id');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: `/bank/edit/${id}`,
                    type: 'GET',
                    success: function(res) {
                        if (res.status == 200) {
                            $('.edit_bank_name').val(res.bank.name);
                            $('.edit_branch_name').val(res.bank.branch_name);
                            $('.edit_manager_name').val(res.bank.manager_name);
                            $('.edit_phone_number').val(res.bank.phone_number);
                            $('.edit_account').val(res.bank.account);
                            $('.edit_email').val(res.bank.email);
                            $('.edit_opening_balance').val(res.bank.opening_balance);
                            $('.update_bank').val(res.bank.id);
                        } else {
                            toastr.warning("No Data Found");
                        }
                    }
                });
            })

            // update bank
            $('.update_bank').click(function(e) {
                e.preventDefault();
                // alert('ok');
                let id = $(this).val();
                // console.log(id);
                let formData = new FormData($('.editBankForm')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: `/bank/update/${id}`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            $('#edit').modal('hide');
                            $('.editBankForm')[0].reset();
                            bankView();
                            toastr.success(res.message);
                        } else {
                            if (res.error.name) {
                                showError('.edit_bank_name', res.error.name);
                            }
                            if (res.error.branch_name) {
                                showError('.edit_branch_name', res.error.branch_name);
                            }
                            if (res.error.manager_name) {
                                showError('.edit_manager_name', res.error.manager_name);
                            }
                            if (res.error.account) {
                                showError('.edit_account', res.error.edit_account);
                            }
                            if (res.error.phone_number) {
                                showError('.edit_phone_number', res.error.phone_number);
                            }
                            if (res.error.opening_balance) {
                                showError('.edit_opening_balance', res.error.opening_balance);
                            }
                        }
                    }
                });
            })

            // bank Delete
            $(document).on('click', '.bank_delete', function(e) {
                // $('.bank_delete').click(function(e) {
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
                            url: `/bank/destroy/${id}`,
                            type: 'GET',
                            success: function(data) {
                                if (data.status == 200) {
                                    toastr.success(data.message);
                                    bankView();
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


            // add id in bank modal
            $(document).on('click', '.add_money_modal_open', function(e) {
                e.preventDefault();
                let id = this.getAttribute('data-id');
                $('.add_balance').val(id);
            })
            // <a href="#" class="dropdown-item add_money_modal_open" data-id=${bank.id} data-bs-toggle="modal" data-bs-target="#bank_money_add">
            //                                                             <i class="fas fa-money-bill"></i>
            //                                     Add balance</a>
            //Add
            // $('.add_balance').click(function(e) {
            //     e.preventDefault();
            //     let id = $(this).val();
            //     // console.log(id);
            //     let formData = new FormData($('#addBalaceForm')[0]);
            //     $.ajaxSetup({
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         }
            //     });

            //     $.ajax({
            //         url: `/add/bank/balance/${id}`,
            //         type: 'POST',
            //         data: formData,
            //         processData: false,
            //         contentType: false,
            //         success: function(res) {
            //             if (res.status == 200) {
            //                 $('#bank_money_add').modal('hide');
            //                 $('#addBalaceForm')[0].reset();
            //                 bankView();
            //                 toastr.success(res.message);
            //             } else {
            //                 if (res.error.update_balance) {
            //                     showError('.add_amount', res.error.update_balance);
            //                 }

            //             }
            //         }
            //     });
            // })
        });
    </script>
@endsection
