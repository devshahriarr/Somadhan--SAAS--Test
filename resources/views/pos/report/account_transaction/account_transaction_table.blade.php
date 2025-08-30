<div class="row">
    <div class="col-md-12 ">
        <div id="" class="table-responsive">
            <table id="example" class="table w-100">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Date/Time</th>
                        <th>Created By</th>
                        <th>Payment Type</th>
                        <th>INV No.</th>
                        <th>Purpose</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th class="action-edit">Action</th>
                    </tr>
                </thead>
                <tbody class="showData">
                    @if ($accountTransaction->count() > 0)
                        @foreach ($accountTransaction as $key => $acountData)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $acountData->created_at->timezone('Asia/Dhaka')->format('d-m-Y h:i a') }}</td>
                                <td>{{ $acountData['user']['name'] ?? '' }}</td>
                                <td>{{ $acountData['bank']['name'] ?? '' }}</td>
                                @if (in_array($acountData->purpose, [ 'to bank to bank transfer Update', 'from bank to bank transfer Update', 'to bank to bank transfer', 'from bank to bank transfer']))
                                <td>{{ $acountData->bankToBankTransfer->invoice ?? 'N/A' }}</td>
                                @elseif($acountData->purpose === 'Sale')
                                <td>{{ $acountData->sale->invoice_number ?? 'N/A' }}</td>
                                 @elseif($acountData->purpose === 'Purchase' || $acountData->purpose === 'Purchase Edit')
                                <td>{{ $acountData->purchase->invoice ?? 'N/A' }}</td>
                                @else
                                 <td>N/A</td>
                                @endif
                                {{-- <td>@if ($acountData->purpose == 'receive'){{'Deposit'}}@else{{'Withdrawal'}}@endif</td> --}}
                                <td>{{ $acountData->purpose ?? '' }}</td>
                                <td>{{ $acountData->debit ?? '0' }} TK</td>
                                <td>{{ $acountData->credit ?? '0' }}TK</td>
                                <td>{{ $acountData->balance ?? '0' }}TK</td>
                                <td>
                                    @if ($acountData->purpose === 'Sale' || $acountData->purpose === 'Purchase')
                                        @if ($acountData->purpose === 'Sale')

                                        @if ($acountData->sale->status  === 'paid')
                                        <span class="badge bg-success">Paid</span>
                                        @elseif ($acountData->sale->status  === 'unpaid')
                                            <span class="badge bg-danger">Unpaid</span>
                                        @elseif ($acountData->sale->status  === 'partial')
                                            <span class="badge bg-warning text-dark">Partial</span>
                                        @else
                                            <span class="badge bg-secondary">{{$acountData->sale->status ?? 'N/A' }}</span>
                                        @endif

                                        @elseif($acountData->purpose === 'Purchase')
                                             @if ($acountData->purchase->payment_status  === 'paid')
                                        <span class="badge bg-success">Paid</span>
                                        @elseif ($acountData->purchase->payment_status  === 'unpaid')
                                            <span class="badge bg-danger">Unpaid</span>
                                        @elseif ($acountData->purchase->payment_status  === 'partial')
                                            <span class="badge bg-warning text-dark">Partial</span>
                                        @else
                                            <span class="badge bg-secondary">{{$acountData->purchase->payment_status ?? 'N/A' }}</span>
                                        @endif

                                        @endif
                                    @endif
                                </td>
                                <td>
                                @if (in_array($acountData->purpose, ['Sale', 'Purchase', 'to bank to bank transfer Update', 'from bank to bank transfer Update', 'to bank to bank transfer', 'from bank to bank transfer']))                                        @if ($acountData->purpose === 'Sale')
                                            <a href="{{ route('sale.edit', $acountData->reference_id) }}"
                                                class="btn btn-primary btn-icon ">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                        @elseif($acountData->purpose === 'Purchase')
                                            <a href="{{ route('purchase.edit', $acountData->reference_id) }}"
                                                class="btn btn-primary btn-icon ">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>

                                          @elseif (in_array($acountData->purpose, [
                                            'to bank to bank transfer Update',
                                            'from bank to bank transfer Update',
                                            'to bank to bank transfer',
                                            'from bank to bank transfer'
                                        ]))
                                            <a href="#"
                                            class="btn btn-primary btn-icon bank_to_bank_edit"
                                            data-id="{{ $acountData->reference_id }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                        @endif

                                    @endif
                                </td>
                            </tr>
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
 {{-- //Edit Modal // --}}
    @php
   $banks = App\Models\Bank::all();

    @endphp
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
                            toastr.success(res.message);
                           window.location.reload();
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

</script>
