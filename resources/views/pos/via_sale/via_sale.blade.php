@extends('master')
@section('title', '| Supplier Page')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Via Purchase</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title">Via Purchase Table</h6>
                    </div>
                    <div class="table-responsive">
                        <table id="example" class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Supplier Name</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Sub Total</th>
                                    <th>Paid</th>
                                    <th>Due</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($viaSale->count() > 0)
                                    @foreach ($viaSale as $key => $via)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $via->supplier_name ?? '' }}</td>
                                            <td>{{ $via->product_name ?? '' }}</td>
                                            <td>{{ $via->quantity ?? 0 }}</td>
                                            <td>{{ $via->cost_price ?? 0 }}</td>
                                            <td>{{ $via->sub_total ?? 0 }}</td>
                                            <td>{{ $via->paid ?? 0 }}</td>
                                            <td>{{ $via->due ?? 0 }}</td>
                                            <td>
                                                <span class="{{ $via->status == 1 ? 'text-success' : 'text-danger' }}">
                                                    {{ $via->status == 1 ? 'Paid' : 'Unpaid' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-warning dropdown-toggle" type="button"
                                                        id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        Manage
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <a class="dropdown-item"
                                                            href="{{ route('via.sale.invoice', $via->id) }}"><i
                                                                class="fa-solid fa-file-invoice me-2"></i> Invoice</a>
                                                        @if ($via->status == 0)
                                                        @if(Auth::user()->can('via.purchase.payment'))
                                                            <a class="dropdown-item add_payment" href="#"
                                                                data-id="{{ $via->id }}" data-bs-toggle="modal"
                                                                data-bs-target="#paymentModal"><i
                                                                    class="fa-solid fa-credit-card me-2"></i> Payment</a>
                                                        @endif
                                                        @endif
                                                        @if(Auth::user()->can('via.purchase.delete'))
                                                        <a class="dropdown-item delete_via_sale"
                                                            data-id="{{ $via->id }}" href="#"><i
                                                                class="fa-solid fa-trash-can me-2"></i>Delete</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
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
                                    $payments = App\Models\Bank::where('branch_id', Auth::user()->branch_id)->get();
                                @endphp
                                <select class="form-select transaction_account" data-width="100%"
                                    name="transaction_account" onclick="errorRemove(this);" onblur="errorRemove(this);">
                                    @if ($payments->count() > 0)
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
    </div>



    <script>
        function errorRemove(element) {
            if (element.value != '') {
                $(element).siblings('span').hide();
                $(element).css('border-color', 'green');
            }
        }
        $(document).ready(function() {

            function showError(name, message) {
                $(name).css('border-color', 'red');
                $(name).focus();
                $(`${name}_error`).show().text(message);
            }


            $(document).on('click', '.add_payment', function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id');
                // alert(id);
                var currentDate = new Date().toISOString().split('T')[0];
                $('.payment_date').val(currentDate);
                $('.save_payment').val(id);


                $.ajax({
                    url: '/via-sale/get/' + id,
                    method: "GET",
                    success: function(res) {
                        console.log(res);
                        if (res.status == 200) {
                            console.log(res);
                            $('.amount').val(res.data.due);
                        }
                    }
                })
            });

            // save payment
            $(document).on('click', '.save_payment', function(e) {
                e.preventDefault();
                let id = $(this).val();
                // alert(id);
                let formData = new FormData($('.paymentForm')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: `/via-sale/payment/${id}`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            $('#paymentModal').modal('hide');
                            $('.paymentForm')[0].reset();
                            toastr.success(res.message);
                            window.location.reload();
                        } else if (res.status == 400) {
                            $('#paymentModal').modal('hide');
                            toastr.warning(res.message);
                        } else {
                            if (res.error.paid) {
                                showError('.amount', res.error.paid);
                            }
                            if (res.error.amount) {
                                showError('.amount', res.error.amount);
                            }
                            if (res.error.payment_method) {
                                showError('.transaction_account', res.error.payment_method);
                            }
                        }
                    }
                });
            })
        })
        $(document).ready(function() {
            $('.delete_via_sale').on('click', function(e) {
                e.preventDefault();

                var id = $(this).data('id');
                var url = '/via/sale/delete/' + id;
                // alert(id)
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
                        $.ajax({
                            url: url,
                            type: 'GET',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.message) {
                                    Swal.fire({
                                        title: "Deleted!",
                                        text: "Your file has been deleted.",
                                        icon: "success"
                                    });
                                    location.reload();
                                } else {
                                    Swal.fire({
                                        position: "top-end",
                                        icon: "warning",
                                        title: "Deleted Unsuccessful!",
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }
                                location.reload(); // Reload the page
                            },
                            error: function(xhr) {
                                alert('Error deleting record.');
                            }
                        });
                    }
                });
            });

        });
    </script>
@endsection
