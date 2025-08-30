@extends('master')
@section('title', '| Bank To Bank Transfer')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Bank To Bank Tranfer</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title">Bank to Bank Transfer Table</h6>

                        <button class="btn btn-rounded-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#exampleModalLongScollable"><i data-feather="plus"></i></button>

                    </div>
                    <div id="" class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Invoice No</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Image</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="showData">
                            </tbody>
                            {{-- <tr>
                                <td colspan="7" style="text-align: right;"><strong>Total Balance:</strong></td>
                                <td colspan="2" id="total-balance">0</td>
                            </tr> --}}
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
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Bank To Bank Transfer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="save_bank_transfer_Form row" enctype=multipart/form-data>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">From <span class="text-danger">*</span></label>
                            <select name="from" class="form-control from" id="" onchange="errorRemove(this);"
                                onblur="errorRemove(this);">
                                <option value="" selected disabled>Select Bank From </option>
                                @foreach ($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                @endforeach

                            </select>
                            <span class="text-danger from_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">To <span class="text-danger">*</span> </label>
                            <select name="to" class="form-control to" id="" onchange="errorRemove(this);"
                                onblur="errorRemove(this);">
                                <option value="" selected disabled>Select Bank To </option>
                                @foreach ($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger to_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Amount <span class="text-danger">*</span></label>
                            <input id="defaultconfig" class="form-control amount" maxlength="39" name="amount"
                                type="number" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger amount_error"></span>
                        </div>
                        <div class=" col-md-6  ">
                            <label class=" bg-transparent"> Transfer Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control bg-transparent border-primary date"
                                onchange="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger date_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Description</label>
                            <textarea name="description" class="form-control" id="" cols="30" rows="5"></textarea>

                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Image</label>
                            <input id="defaultconfig" class="form-control account" name="image" type="file">
                            <span class="text-danger image_error"></span>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_bank_transfer">Save</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    {{-- //Edit Modal // --}}

    <!-- Modal -->
    <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalScrollableTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Edit Bank To Bank</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="bankToBankFormEdit row">
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">From <span class="text-danger">*</span></label>
                            <select name="from" class="form-control from_edit" id=""
                                onchange="errorRemove(this);" onblur="errorRemove(this);">
                                <option value="" selected disabled>Select Bank From </option>
                                @foreach ($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                @endforeach

                            </select>
                            <span class="text-danger from_edit_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">To <span class="text-danger">*</span> </label>
                            <select name="to" class="form-control to_edit" id="" onchange="errorRemove(this);"
                                onblur="errorRemove(this);">
                                <option value="" selected disabled>Select Bank To </option>
                                @foreach ($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger to_edit_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Amount <span class="text-danger">*</span></label>
                            <input id="defaultconfig" class="form-control amount_edit" maxlength="39" name="amount"
                                type="number" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger amount_edit_error"></span>
                        </div>
                        <div class=" col-md-6 ">
                            <label class=" bg-transparent"> Transfer Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control bg-transparent border-primary date_edit"
                                onchange="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger date_edit_error"></span>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="name" class="form-label">Description</label>
                            <textarea name="description" class="form-control description_edit" id="" cols="30" rows="2"></textarea>

                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="name" class="form-label">Image</label>
                              <div class="card">
                                <div class="card-body">
                                    <p class="card-title">Bank To Bank Transfer Image</p>
                                    <div style="height:150px;position:relative">
                                        <button class="btn btn-info edit_upload_img"
                                            style="position: absolute;top:50%;left:50%;transform:translate(-50%,-50%)">Browse</button>
                                        <img class="img-fluid showEditImage" src=""
                                            style="height:100%; object-fit:cover">
                                    </div>
                                    <input hidden type="file" class="categoryImage edit_image" name="image" />
                                </div>
                            </div>
                            <span class="text-danger image_error"></span>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary update_bankToBank">Update</button>
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
           let protocol = window.location.protocol + "//";
            let host = window.location.host;
            let url = protocol + host;
             const edit_upload_img = document.querySelector('.edit_upload_img');
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
        $(document).ready(function() {
            // Show error
            function showError(name, message) {
                $(name).css('border-color', 'red');
                $(name).focus();
                $(`${name}_error`).show().text(message);
            }

            // Save bank transfer
            const save_bank_transfer = document.querySelector('.save_bank_transfer');
            save_bank_transfer.addEventListener('click', function(e) {
                e.preventDefault();
                const fromBank = document.querySelector('.from').value;
                const toBank = document.querySelector('.to').value;

                // Check if the "From" and "To" banks are the same
                if (fromBank && toBank && fromBank === toBank) {
                    toastr.error('The "From" and "To" banks cannot be the same.');
                    showError('.from', 'The "From" and "To" banks cannot be the same.');
                    showError('.to', 'The "From" and "To" banks cannot be the same.');
                    return; // Stop further execution
                }
                let formData = new FormData($('.save_bank_transfer_Form')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/transfer/bank/store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        console.log(res);
                        if (res.status === 200) {
                            $('#exampleModalLongScollable').modal('hide');
                            bankTransferView();
                            $('.save_bank_transfer_Form')[0].reset();
                            toastr.success(res.message);
                        } else if (res.status === 405) {
                            // if (res.error.from) showError('.from', res.error.from[0]);
                            // if (res.error.to) showError('.to', res.error.to[0]);
                            // if (res.error.amount) showError('.amount', res.error.amount[0]);
                            // if (res.error.date) showError('.date', res.error.date[0]);

                            toastr.error(res.errormessage); // Specific message

                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 500) {
                            toastr.error('Server error occurred. Please contact support.');
                            console.log('Server Error:', xhr.responseText);
                        } else if (xhr.status === 422) {
                            let errors = xhr.responseJSON.error;
                            if (errors.from) showError('.from', errors.from[0]);
                            if (errors.to) showError('.to', errors.to[0]);
                            if (errors.amount) showError('.amount', errors.amount[0]);
                            if (errors.date) showError('.date', errors.date[0]);
                        } else {
                            toastr.error('An error occurred. Please try again.');
                        }
                    }
                });
            });




        function bankTransferView() {
            // console.log('hello');
            $.ajax({
                url: '/bank/transfer/view',
                method: 'GET',
                success: function(res) {
                    const banks = res.data;
                    // console.log(banks);
                    $('.showData').empty();
                    if (banks.length > 0) {
                        $.each(banks, function(index, bank) {
                            //  Calculate the sum of account_transaction balances
                            console.log(bank);
                            const imageHtml = bank.image ?
                                `<img src="/uploads/bank_transfer/${bank.image}" alt="Transfer Receipt" style="width: 50px; height: 50px; cursor: pointer;" onclick="showImage('/uploads/bank_transfer/${bank.image}')">` :
                                'No Image';
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                    <td>${index + 1}</td>
                                    <td>${bank.invoice ?? 'N/A'}</td>
                                    <td>${bank.from_bank ? bank.from_bank.name : 'N/A'}</td> <!-- Access related bank name -->
                                   <td>${bank.to_bank ? bank.to_bank.name : 'N/A'}</td> <!-- Access related bank name -->
                                    <td>${bank.amount ?? 0}</td>
                                    <td>${bank.transfer_date ?? 0}</td>
                                      <td>${imageHtml}</td>
                                    <td>${bank?.description ?? 'N/A'}</td>
                                    <td><a href="#" class="btn btn-primary btn-icon bank_to_bank_edit" data-id=${bank.id} data-bs-toggle="modal" data-bs-target="#edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="/bank/to/bank/view/transaction/${bank.id}"
                                        class="btn btn-success btn-icon">
                                        View
                                        </a>
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
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalLongScollable">Bank Transfer<i data-feather="plus"></i></button>
                                    </div>
                                </td>
                            </tr>
                            `);
                    }
                }
            });
        }
        bankTransferView();
        //edit
         $(document).on('click', '.bank_to_bank_edit', function(e) {
                e.preventDefault();
                // alert('ok');
                let id = this.getAttribute('data-id');
                // alert(id);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: `/bank/to/bank/edit/${id}`,
                    type: 'GET',
                    success: function(data) {
                        // console.log(data.bankTobank);
                        // console.log(data.bankTobank.from);
                        if (data.bankTobank && data.bankTobank.from) {
                            $('.from_edit').val(data.bankTobank.from);
                        } else {
                            console.log('From ID not found');
                        }
                        if (data.bankTobank && data.bankTobank.to) {
                            $('.to_edit').val(data.bankTobank.to);
                        } else {
                            console.log('To ID not found');
                        }
                          $('.amount_edit').val(data.bankTobank.amount);
                          $('.date_edit').val(data.bankTobank.transfer_date);
                          $('.date_edit').val(data.bankTobank.transfer_date);
                          $('.description_edit').val(data.bankTobank.description);
                          $('.update_bankToBank').val(data.bankTobank.id);
                        if (data.bankTobank.image) {
                            $('.showEditImage').attr('src',
                                `${url}/uploads/bank_transfer/` + data.bankTobank
                                .image);
                        } else {
                            $('.showEditImage').attr('src',
                                `${url}/dummy/image.jpg`);
                        }
                    }
                });
            })

             // update bank To bank
            $('.update_bankToBank').click(function(e) {
                e.preventDefault();
                // alert('ok');
                 const fromBank = document.querySelector('.from_edit').value;
                const toBank = document.querySelector('.to_edit').value;

                // Check if the "From" and "To" banks are the same
                if (fromBank && toBank && fromBank === toBank) {
                    toastr.error('The "From" and "To" banks cannot be the same.');
                    showError('.from', 'The "From" and "To" banks cannot be the same.');
                    showError('.to', 'The "From" and "To" banks cannot be the same.');
                    return; // Stop further execution
                }
                let id = $('.update_bankToBank').val();
                // console.log(id);
                let formData = new FormData($('.bankToBankFormEdit')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: `/bank/to/bank/update/${id}`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            $('#edit').modal('hide');
                            $('.bankToBankFormEdit')[0].reset();
                            bankTransferView();
                            toastr.success(res.message);
                        } else if(res.status === 405) {
                           toastr.error(res.errormessage);
                        }
                    },  error: function(xhr, status, error) {
                        if (xhr.status === 500) {
                            toastr.error('Server error occurred. Please contact support.');
                            console.log('Server Error:', xhr.responseText);
                        } else if (xhr.status === 422) {
                            let errors = xhr.responseJSON.error;
                            if (errors.from) showError('.from_edit', errors.from[0]);
                            if (errors.to) showError('.to_edit', errors.to[0]);
                            if (errors.amount) showError('.amount_edit', errors.amount[0]);
                            if (errors.date) showError('.date_edit', errors.date[0]);
                        } else {
                            toastr.error('An error occurred. Please try again.');
                        }
                    }
                });

            })
              });

    </script>
@endsection
