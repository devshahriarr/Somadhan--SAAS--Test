@extends('master')
@section('title', '| Bank Adjustments ')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Bank Adjustments</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title">Bank Adjustments Table</h6>

                        <button class="btn btn-rounded-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#exampleModalLongScollable"><i data-feather="plus"></i></button>

                    </div>
                    <div id="" class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Account Name</th>
                                    <th>Adjust Type</th>
                                    <th>Adjust Amount</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Image</th>
                                    <th>Note</th>
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

    <!--Add Bank Modal -->
    <div class="modal fade" id="exampleModalLongScollable" tabindex="-1" aria-labelledby="exampleModalScrollableTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Bank Adjustments </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="save_bank_adjustments_Form row" enctype=multipart/form-data>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Account Name <span class="text-danger">*</span></label>
                            <select name="bank_id" class="form-control bank" onchange="errorRemove(this);"
                                onblur="errorRemove(this);">
                                <option value="" selected disabled>Select Cash/Bank  </option>
                                @foreach ($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger bank_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Select Adjustment Type <span class="text-danger">*</span></label>
                            <select name="adjustment_type" class="form-control adjustment_type"  onchange="errorRemove(this);"
                                onblur="errorRemove(this);">
                                <option value="" selected disabled>Select Adjustment Type </option>
                                    <option value="increase">Increase</option>
                                    <option value="decrease">Decrease</option>
                            </select>
                            <span class="text-danger adjustment_type_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Amount <span class="text-danger">*</span></label>
                            <input id="defaultconfig" class="form-control amount" maxlength="39" name="amount"
                                type="number" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger amount_error"></span>
                        </div>
                        <div class=" col-md-6 ">
                            <label class=" bg-transparent"> Adjustments Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control bg-transparent border-primary date"
                                onchange="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger date_error"></span>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Image</label>
                            <input id="defaultconfig" class="form-control account" name="image" type="file">
                            {{-- <span class="text-danger image_error"></span> --}}
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="name" class="form-label">Note</label>
                            <textarea name="note" class="form-control" id="" cols="30" rows="3"></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_bank_adjustments">Save</button>
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
            // Show error
            function showError(name, message) {
                $(name).css('border-color', 'red');
                $(name).focus();
                $(`${name}_error`).show().text(message);
            }


            // Save bank transfer
            const save_bank_adjustments = document.querySelector('.save_bank_adjustments');
            save_bank_adjustments.addEventListener('click', function(e) {
                e.preventDefault();
                const amountField = document.querySelector('.amount');
                const amountValue = parseFloat(amountField.value);

                // Check for negative value
                if (amountValue < 0) {
                    toastr.warning('Negative values are not allowed in the Amount field.');
                    return;
                }
                let formData = new FormData($('.save_bank_adjustments_Form')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/bank/adjustments/store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        // console.log(res);
                        if (res.status === 200) {
                            $('#exampleModalLongScollable').modal('hide');
                            bankAdjustmentView();
                            $('.save_bank_adjustments_Form')[0].reset();
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
                            // console.log('Server Error:', xhr.responseText);
                        } else if (xhr.status === 422) {
                            let errors = xhr.responseJSON.error;
                            if (errors.bank_id) showError('.bank', errors.bank_id[0]);
                            if (errors.adjustment_type) showError('.adjustment_type', errors.adjustment_type[0]);
                            if (errors.amount) showError('.amount', errors.amount[0]);
                            if (errors.date) showError('.date', errors.date[0]);
                        } else {
                            toastr.error('An error occurred. Please try again.');
                        }
                    }
                });
            });
        });




        function bankAdjustmentView() {
            // console.log('hello');
            $.ajax({
                url: '/bank/adjustment/view',
                method: 'GET',
                success: function(res) {
                    const banks = res.data;
                    // console.log(banks);
                    $('.showData').empty();
                    if (banks.length > 0) {
                        $.each(banks, function(index, bank) {
                            //  Calculate the sum of account_transaction balances
                            // console.log(bank);
                            function convertToBDTimeOnly(dateString) {
                                if (!dateString) return 'N/A';
                                let date = new Date(dateString); // Parse the date string
                                let options = {
                                    timeZone: 'Asia/Dhaka',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour24: true // Use 12-hour format with AM/PM
                                };
                                return new Intl.DateTimeFormat('en-US', options).format(date);
                            }
                            const imageHtml = bank.image ?
                                `<img src="/uploads/bank_adjustments/${bank.image}" alt="Adjustments Receipt" style="width: 50px; height: 50px; cursor: pointer;" onclick="showImage('/uploads/bank_transfer/${bank.image}')">` :
                                'No Image';
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                    <td>${index + 1}</td>
                                    <td>${bank.bank ? bank.bank.name : 'N/A'}</td> <!-- Access related bank name -->
                                     <td>${bank.adjustment_type ?? 0}</td>
                                    <td>${bank.amount ?? 0}</td>
                                    <td>${bank.adjustments_date ?? 0}</td>
                                     <td>${convertToBDTimeOnly(bank.created_at)}</td>
                                    <td>${imageHtml}</td>
                                    <td>${bank?.note ?? 'N/A'}</td>

                                `;
                            $('.showData').append(tr);
                        });


                    } else {
                        $('.showData').html(`
                            <tr>
                                <td colspan='9'>
                                    <div class="text-center text-warning mb-2">Data Not Found</div>
                                    <div class="text-center">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalLongScollable">Add Bank Adjustments<i data-feather="plus"></i></button>
                                    </div>
                                </td>
                            </tr>
                            `);
                    }
                }
            });
        }
        bankAdjustmentView();
    </script>
@endsection
