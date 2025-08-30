@extends('master')
@section('title', '| Affliator Commission')

@section('admin')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Affiliator commission List</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">


                    <div class="table-responsive">
                        <table id="commissionTableData" class="table display nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Affiliator Name</th>
                                    <th>Sale Invoice</th>
                                    <th>Commission Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($affliatorCommission as $affliatorCommission)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $affliatorCommission->affliator->name }}</td>
                                        <td>{{ $affliatorCommission->sale->invoice_number }}</td>
                                        <td>{{ $affliatorCommission->commission_amount }}</td>
                                        <td>
                                            @if($affliatorCommission->status == 'paid')
                                                <span class="badge bg-success">Paid</span>
                                            @elseif($affliatorCommission->status == 'unpaid')
                                                <span class="badge bg-danger">Unpaid</span>
                                            @elseif($affliatorCommission->status == 'partial paid')
                                                <span class="badge bg-warning text-dark">Partial Paid</span>
                                            @else
                                                <span class="badge bg-secondary">Unknown</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($affliatorCommission->status != 'paid')
                                                <a class="add_payment d-inline-flex align-items-center text-white px-3 py-2 rounded shadow"
                                                   href="#" data-bs-toggle="modal" data-bs-target="#paymentModal"
                                                   data-id="{{ $affliatorCommission->id }}"
                                                   data-amount="{{ $affliatorCommission->commission_amount }}"
                                                   style="background-color: #28a745; text-decoration: none; font-weight: 600; transition: 0.3s; white-space: nowrap;">
                                                   <i class="fa-solid fa-credit-card me-2 fs-5"></i>
                                                </a>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>


    {{-- payement modal  --}}
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="exampleModalScrollableTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="paymentForm row">
                        <input type="hidden" name="id" id="id">
                        <div class="mb-3 col-md-12">
                            <label for="name" class="form-label">Payment Date<span
                                    class="text-danger">*</span></label>
                            <div class="input-group flatpickr" id="flatpickr-date">
                                <input type="text" class="form-control from-date flatpickr-input payment_date"
                                    placeholder="Payment Date" data-input="" readonly="readonly" name="payment_date">
                                <span class="input-group-text input-group-addon" data-toggle=""><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2">
                                        </rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg></span>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Transaction Account<span
                                    class="text-danger">*</span></label>
                            @php
                                $payments = App\Models\Bank::all();
                            @endphp
                            <select class="form-select transaction_account" data-width="100%" name="transaction_account"
                                onclick="errorRemove(this);" onblur="errorRemove(this);">
                                @if ($payments->count() > 0)
                                    {{-- <option selected disabled>Select Transaction</option> --}}
                                    @foreach ($payments as $payment)
                                        <option value="{{ $payment->id }}">{{ $payment->name }}</option>
                                    @endforeach
                                @else
                                    <option selected disabled>Please Add Payment</option>
                                @endif
                            </select>
                            <span class="text-danger transaction_account_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Amount<span class="text-danger">*</span></label>
                            <input id="defaultconfig" class="form-control amount" maxlength="39" name="amount"
                                type="number" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger amount_error"></span>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="name" class="form-label">Note</label>
                            <textarea name="note" class="form-control note" id="" placeholder="Enter Note (Optional)"
                                rows="3"></textarea>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_payment">Payment</button>
                </div>
                </form>
            </div>
        </div>
    </div>

  <script>
   $(document).ready(function(){


    $(document).on('click','.add_payment',function(){
       let id= $(this).data('id');
       let amount = $(this).data('amount');

       $('#id').val(id);
       $('.amount').val(amount);

    });

    $(document).on('click','.save_payment',function(){
        let formData = new FormData($('.paymentForm')[0]);
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url:"{{route('affiliator.commission.payment')}}",
            type:"POST",
            data:formData,
            processData: false,
            contentType: false,
            success:function(data){
               if(data.status==200){
                $('#paymentModal').modal('hide');
                $('.paymentForm')[0].reset();
                toastr.success("Commission Paid Successfully")
                location.reload();
               }
            }
        })
    });

   });

  </script>
  <style>
    .custom-copy {
    background-color: #6c757d !important;
    color: white !important;
}

.custom-excel {
    background-color: #28a745 !important;
    color: white !important;
}

.custom-csv {
    background-color: #17a2b8 !important;
    color: white !important;
}

.custom-pdf {
    background-color: #dc3545 !important;
    color: white !important;
}

.custom-print {
    background-color: #ffc107 !important;
    color: black !important;
}
.dt-buttons {
    display: flex;
    justify-content: flex-start;
    position: static !important;
    margin-bottom: 10px;
}
.dt-buttons .btn {
    margin-right: 8px; /* Add space between buttons */
}

  </style>
  <script>
$(document).ready(function() {
    $('#commissionTableData').DataTable({
        dom: "<'row'<'col-md-8'B><'col-md-4'f>>" +  // Buttons on the left, Search bar on the right
             "<'row'<'col-sm-12'tr>>" +             // Table
             "<'row'<'col-md-5'i><'col-md-7'p>>",   // Info & Pagination

        buttons: [
            {
                extend: 'copy',
                text: '<i class="fa-solid fa-copy"></i> Copy',
                className: 'btn btn-secondary custom-copy' // Gray
            },
            {
                extend: 'excel',
                text: '<i class="fa-solid fa-file-excel"></i> Excel',
                className: 'btn btn-success custom-excel' // Green
            },
            {
                extend: 'csv',
                text: '<i class="fa-solid fa-file-csv"></i> CSV',
                className: 'btn btn-info custom-csv' // Light Blue
            },
            {
                extend: 'pdf',
                text: '<i class="fa-solid fa-file-pdf"></i> PDF',
                className: 'btn btn-danger custom-pdf' // Red
            },
            {
                extend: 'print',
                text: '<i class="fa-solid fa-print"></i> Print',
                className: 'btn btn-warning custom-print' // Yellow
            }
        ]
    });
});

</script>

@endsection
