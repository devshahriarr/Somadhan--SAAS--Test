@extends('master')
@section('title', '| Transactions')

@section('admin')
    <style>
        .nav.nav-tabs .nav-item .nav-link.active {
            color: #010205 !important;
            background: rgb(101 209 209) !important;
        }

        .nav.nav-tabs .nav-item .nav-link {
            border-color: #090c0f #030406 #1a1d1f;
            color: #000;
            background-color: #6571ff;
            cursor: pointer;
        }
    </style>
    <link rel="stylesheet" type="text/css" media="print">

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="profile-tab" data-bs-toggle="tab" href="#profile" style="background: "
                role="tab" aria-controls="profile" aria-selected="false">Balance Transfer</a>
        </li>

        @if (Auth::user()->can('transaction.history'))
            <li class="nav-item">
                <a class="nav-link " id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home"
                    aria-selected="true">Balance History</a>
            </li>
        @endif
        <li class="nav-item">
            <a class="nav-link " id="investor-tab" data-bs-toggle="tab" href="#investor" role="tab"
                aria-controls="investor" aria-selected="true">Investor History</a>
        </li>
    </ul>
    <div class="tab-content border border-print border-top-0 p-3" id="myTabContent">
        <!---//-----------------------Investor History Start ---------------------//-->
        <div class="tab-pane fade" id="investor" role="tabpanel" aria-labelledby="investor-tab">
            <div class="row">
                {{-- ////list// --}}
                <div>
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title text-info ">Investor History</h6>
                                <div class="table-responsive">
                                    <table id="example" class="table">
                                        <thead class="action">
                                            <tr>
                                                <th>SN</th>
                                                <th>Name</th>
                                                <th>Transaction Date & Time</th>
                                                <th>Phone</th>
                                                <th>Transaction Type</th>
                                                <th>Debit</th>
                                                <th>Crdit</th>
                                                <th>Balance</th>
                                                <th class="actions">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="showData">
                                            @if ($investors->count() > 0)
                                                @foreach ($investors as $key => $investor)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>
                                                            <a
                                                                href="{{ route('investor.details', $investor->id) }}">{{ $investor->name ?? '' }}</a>
                                                        </td>
                                                        @php
                                                            $dacTimeZone = new DateTimeZone('Asia/Dhaka');
                                                            $created_at = optional($investor->created_at)->setTimezone(
                                                                $dacTimeZone,
                                                            );
                                                            $formatted_date =
                                                                optional($investor->created_at)->format('d F Y') ?? '-';
                                                            $formatted_time = $created_at
                                                                ? $created_at->format('h:i A')
                                                                : '-';
                                                        @endphp

                                                        <td>{{ $formatted_date ?? '-' }} <Span style="color:brown">:</Span>
                                                            {{ $formatted_time ?? '' }}</td>
                                                        <td>{{ $investor->phone ?? '-' }}</td>
                                                        <td>{{ $investor->type ?? '-' }}</td>
                                                        <td>{{ $investor->debit ?? '-' }}</td>
                                                        <td>{{ $investor->credit ?? '-' }}</td>
                                                        <td>{{ $investor->balance ?? '-' }}</td>
                                                        <td class="actions">
                                                            <a href="{{ route('investor.invoice', $investor->id) }}"
                                                                class="btn btn-sm btn-primary " title="Print">
                                                                <i class="fa fa-print"></i><span
                                                                    style="padding-left: 5px">Receipt</span>
                                                            </a>
                                                            {{-- <a href="#" id="delete" class="btn btn-sm btn-danger "
                                                                title="Delete">
                                                                Delete
                                                            </a> --}}
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
                    </div>

                </div>
            </div>
        </div>
        <!---//-----------------------Investor History End ---------------------//-->
        <!---//-----------------------Transaction Filter & View Start---------------------//-->
        <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="row">
                <div class="col-md-12  grid-margin stretch-card filter_table">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <!-- Col -->
                                <div class="col-sm-3">
                                    <div class="mb-3 w-100">
                                        {{-- <label class="form-label">Amount<span class="text-danger">*</span></label> --}}
                                        <select
                                            class="transaction_customer_name is-valid js-example-basic-single form-control filter-category @error('transaction_customer_id') is-invalid @enderror"
                                            name="transaction_customer_id" aria-invalid="false" width="100">
                                            <option>Select Customer</option>
                                            @foreach ($customer as $customers)
                                                <option value="{{ $customers->id }}">{{ $customers->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="mb-3 w-100">
                                        {{-- <label class="form-label">Amount<span class="text-danger">*</span></label> --}}
                                        <select
                                            class="transaction_supplier_name is-valid js-example-basic-single form-control filter-category @error('transaction_supplier_id') is-invalid @enderror"
                                            name="transaction_supplier_id" aria-invalid="false" width="100">
                                            <option>Select Supplier</option>
                                            @foreach ($supplier as $suppliers)
                                                <option value="{{ $suppliers->id }}">{{ $suppliers->name }}</option>
                                            @endforeach


                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group flatpickr" id="flatpickr-date1">
                                        <input type="text" class="form-control start-date" placeholder="Start date"
                                            data-input>
                                        <span class="input-group-text input-group-addon" data-toggle><i
                                                data-feather="calendar"></i></span>
                                    </div>
                                </div><!-- Col -->
                                <div class="col-sm-3">
                                    <div class="input-group flatpickr" id="flatpickr-date2">
                                        <input type="text" class="form-control end-date" placeholder="End date"
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
                            </div>
                            <div class="row">
                                <div class="col-md-11 mb-2"> <!-- Left Section -->
                                    <div class="justify-content-left">
                                        <a href="" class="btn btn-sm bg-info text-dark mr-2"
                                            id="transactionfilter">Filter</a>
                                        <a class="btn btn-sm bg-primary text-dark"
                                            onclick="window.location.reload();">Reset</a>
                                    </div>
                                </div>

                                <div class="col-md-1"> <!-- Right Section -->

                                    <button type="button"
                                        class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0 print-btn">
                                        <i class="btn-icon-prepend" data-feather="printer"></i>
                                        Print
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                {{-- ////list// --}}
                <div id="transaction-filter-rander">
                    @include('pos.transaction.transaction-filter-rander-table')
                </div>
            </div>
        </div>
        <!---//-----------------------Transaction Filter & View End---------------------//-->
        <!---//-----------------------Transaction Store/Add  Start---------------------//-->
        {{-- <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="row">
                <div class="col-md-12 stretch-card mt-1">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title text-info">Add Transaction</h6>
                            <form id="myValidForm" action="{{ route('transaction.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-3 form-valid-groups">
                                            <label class="form-label">Transaction Date<span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group flatpickr" id="flatpickr-date">
                                                <input type="date" id="datepicker" name="date"
                                                    class="form-control active" placeholder="Select date">
                                                <span class="input-group-text input-group-addon" data-toggle><i
                                                        data-feather="calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3 form-valid-groups">
                                            <label class="form-label">Account Type<span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" data-width="100%" name="account_type"
                                                id="account_type" aria-invalid="false">
                                                <option selected disabled value="">Select Account Type</option>
                                                <option value="supplier">Supplier</option>
                                                <option value="customer">Customer</option>
                                                <option id="otherId" value="other">Other</option>
                                            </select>
                                        </div>
                                    </div><!-- Col -->
                                    <div class="col-sm-6">
                                        <div class="mb-3 form-valid-groups">
                                            <label class="form-label">Transaction Type <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select  bank_id" data-width="100%"
                                                name="transaction_type" style="background: transparent"
                                                id="transaction_type" aria-invalid="false" disabled>
                                                <option selected="" disabled value="">Select Type</option>
                                                <option value="receive">Cash Receive</option>
                                                <option value="pay">Cash Payment</option>
                                            </select>
                                        </div>
                                    </div><!-- Col -->

                                    <div class="col-sm-6 d-none" id="investment-col">
                                        <div class="mb-3 form-valid-groups">
                                            <label class="form-label">Investment <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" data-width="100%" name="type" id=""
                                                aria-invalid="false">
                                                <option selected="" disabled value="">Select Type</option>
                                                <option value="add-balance">Add Balance</option>
                                                <option value="investment">Investment</option>
                                                <option value="loan">Loan</option>
                                                <option value="borrow">Borrow</option>
                                            </select>
                                        </div>
                                    </div><!-- Col -->
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">Account Name<span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select js-example-basic-single select-account-id"
                                                data-width="100%" name="account_id" id="account_id"
                                                aria-invalid="false">
                                                <option selected disabled value="">Select Account ID</option>
                                            </select>
                                        </div>
                                    </div><!-- Col -->
                                    <div class="col-sm-6 d-none" id="investment-col2">
                                        <div class="mb-3 form-valid-groups">
                                            <label class="form-label">Add New Investor</label><br>
                                            <a class="btn btn-primary ms-2"
                                                data-bs-toggle="modal"data-bs-target="#investorModal">Add</a>
                                        </div>
                                    </div><!-- Col -->

                                    <div>
                                        <h5 style="display: none;" class="account-info" id="account-details"></h5>
                                        <h5 style="display: none;" class="account-info" id="due_invoice_count"></h5>
                                        <h5 style="display: none;" class="account-info" id="total_invoice_due"></h5>
                                        <h5 style="display: none;" class="account-info" id="personal_balance"></h5>
                                        <h5 style="display: none;" class="account-info" id="total_due"></h5>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3 form-valid-groups">
                                            <label class="form-label">Amount<span class="text-danger">*</span></label>
                                            <input type="number" name="amount" value="{{ old('amount') }}"
                                                class="form-control" placeholder="Enter Amount">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">Transaction Account <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select "data-width="100%" name="payment_method"
                                                aria-invalid="false">
                                                <option selected="" disabled value="">Select Account</option>
                                                @foreach ($paymentMethod as $payment)
                                                    <option value="{{ $payment->id }}">{{ $payment->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="mb-3">
                                            <label class="form-label">Note</label>
                                            <textarea name="note" class="form-control" placeholder="Write Note (Optional)" rows="4" cols="50"></textarea>
                                        </div>
                                    </div><!-- Col -->
                                </div><!-- Row -->

                                <div>
                                    <input type="submit" class="btn btn-primary submit" value="Payment">
                                </div>
                            </form>

                            <table>
                                <tbody id="account_data">
                                    <!-- Data will be dynamically populated here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div> --}}
        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="row">
                <div class="col-md-12 stretch-card mt-1">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title text-info">Add Transaction</h6>
                            <form id="myValidForm" action="{{ route('transaction.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-3 form-valid-groups">
                                            <label class="form-label">Transaction Date<span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group flatpickr" id="flatpickr-date">
                                                <input type="date" id="datepicker" name="date"
                                                    class="form-control active" placeholder="Select date">
                                                <span class="input-group-text input-group-addon" data-toggle><i
                                                        data-feather="calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 d-none">
                                        <div class="mb-3 form-valid-groups ">
                                            <label class="form-label">Account Type<span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" data-width="100%" name="account_type"
                                                id="account_type" aria-invalid="false">
                                                <option selected disabled value="">Select Account Type</option>
                                               <option value="other">Other</option>
                                               <option value="party">Party</option>
                                            </select>
                                        </div>
                                    </div><!-- Col -->
                                    <div class="col-sm-6">
                                        <div class="mb-3 form-valid-groups">
                                            <label class="form-label">Transaction Type <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select  bank_id" data-width="100%"
                                                name="transaction_type" style="background: transparent"
                                                id="transaction_type" aria-invalid="false">
                                                <option selected="" disabled value="">Select Type</option>
                                                <option value="receive">Cash Receive</option>
                                                <option value="pay">Cash Payment</option>
                                            </select>
                                        </div>
                                    </div><!-- Col -->

                                    <div class="col-sm-6 d-none" id="investment-col">
                                        <div class="mb-3 form-valid-groups">
                                            <label class="form-label">Purpose <span class="text-danger">*</span></label>
                                            <select class="form-select " data-width="100%" name="type" id="due-payment"
                                                aria-invalid="false">
                                                <option selected="" disabled value="">Select Type</option>
                                                <option value="investment">Investment</option>
                                                <option value="loan">Loan</option>
                                                <option value="borrow">Borrow</option>
                                                <option value="due-payment">Due Payment</option>
                                            </select>
                                        </div>
                                    </div><!-- Col -->
                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label class="form-label">Account Name<span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select js-example-basic-single select-account-id"
                                                data-width="100%" name="account_id" id="account_id"
                                                aria-invalid="false">
                                                <option selected disabled value="">Select Name </option>
                                            </select>
                                        </div>

                                    </div><!-- Col -->

                                    <div class="col-sm-2 " id="investment-col2">
                                        <div class="mb-3 form-valid-groups">
                                            <label class="form-label">Add New Investor</label><br>
                                            <a class="btn btn-primary ms-2"
                                                data-bs-toggle="modal"data-bs-target="#investorModal">Add</a>
                                        </div>
                                    </div><!-- Col -->
                                    <div>
                                        <h5 style="display: none;" class="account-info" id="account-details"></h5>
                                        <h5 style="display: none;" class="account-info" id="due_invoice_count"></h5>
                                        <h5 style="display: none;" class="account-info" id="total_invoice_due"></h5>
                                        <h5 style="display: none;" class="account-info" id="personal_balance"></h5>
                                        <h5 style="display: none;" class="account-info" id="total_due"></h5>
                                    </div>
                                    {{-- <div class="col-sm-6">
                                        <div class="mb-3 form-valid-groups">
                                            <label class="form-label">Amount<span class="text-danger">*</span></label>
                                            <input type="number" name="amount" value="{{ old('amount') }}"
                                                class="form-control" placeholder="Enter Amount">
                                        </div>
                                    </div> --}}
                                    <div class="col-sm-6">
                                      <div class="mb-3">
                                    <label class="form-label">Transaction Account <span class="text-danger">*</span></label>
                                    <select class="compose-multiple-select form-select" data-width="100%" multiple="multiple" name="payment_method[]" id="payment_method" aria-invalid="false">
                                        @foreach ($paymentMethod as $payment)
                                            <option value="{{ $payment->id }}">{{ $payment->name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                    </div>
                                    <div class="col-sm-6">

                                    <div id="amount-inputs" class="mt-2"></div>
                                </div>

                                    <div class="col-sm-12">
                                        <div class="mb-3">
                                            <label class="form-label">Note</label>
                                            <textarea name="note" class="form-control" placeholder="Write Note (Optional)" rows="4" cols="50"></textarea>
                                        </div>
                                    </div><!-- Col -->
                                </div><!-- Row -->
                                <div>
                                    <input type="submit" class="btn btn-primary submit" value="Payment">
                                </div>
                            </form>

                            <table>
                                <tbody id="account_data">
                                    <!-- Data will be dynamically populated here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!---//-----------------------Transaction Store/Add  End---------------------//-->
    </div> <!--//Main tab End-->

    <!-- Investor Modal -->
    <div class="modal fade" id="investorModal" tabindex="-1" aria-labelledby="exampleModalScrollableTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Add Investor Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form class="investorForm row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label"> Investor Name <span class="text-danger">*</span></label>
                            <input id="defaultconfigs" class="form-control investor_name" maxlength="255" name="name"
                                type="text" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger investor_name_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Phone Number<span
                                    class="text-danger">*</span></label>
                            <input id="defaultconfig" class="form-control phone investor_phone" maxlength="39"
                                name="phone" type="tel" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger investor_phone_error"></span>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_new_investor">Save</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $(document).ready(function() {
            // viewInvestor();
            let transactionTypeElement = document.getElementById('transaction_type');
            transactionTypeElement.removeAttribute('disabled');
            transactionTypeElement.style.backgroundColor = 'transparent !important';
            //
            let investmentCol = document.getElementById('investment-col');
            investmentCol.classList.remove('d-none');
            let investmentCol2 = document.getElementById('investment-col2');
            investmentCol2.classList.remove('d-none');

            $(document).on('change', '#transaction_type', function() {
                let transactionType = $(this).val();
                // Show account-info section if the selected option is "pay"
                // if (transactionType === 'pay') {
                //     $('.account-info').show();
                // } else {
                //     $('.account-info').hide();
                // }
            });
            //


// Event listener for select-account-id dropdown change
$(document).on('change', '.select-account-id', function() {
    let accountId = this.value;
    let account_type = document.querySelector('#account_type').value;
    let duePaymentType = $('#due-payment').val(); // Get the due-payment dropdown value

    if (duePaymentType === 'due-payment') {
        // Load specific "Due Payment" information
        $.ajax({
            url: '/get/due/party/data', // Use a different endpoint for Due Payment data
            method: 'GET',
            data: {
                id: accountId,
                account_type
            },
            success: function(data) {
                // console.log(data);
                // Customize how you want to display Due Payment info
                $('#account-details').text('Party Name: ' + data.info.name +' Type:'+ data.info.party_type)
              $('#account-details').text('Type:'+ data.info.party_type)
                if (data.info.wallet_balance > 0) {
                    $('#total_due').text(`Total Due Amount: ${data.info.wallet_balance}`);
                }else if(data.info.wallet_balance < 0)  {
                    $('#total_due').text(`Total Return Amount: ${data.info.wallet_balance}`);
                }
                else {
                    $('#total_due').text('Total Due Amount: 0');
                }
                $('.account-info').show();
            },
            error: function(xhr, status, error) {
                console.error('Due Payment Request failed:', error);
            }
        });
    } else {
        // Existing behavior for other options (investment, loan, borrow)
        $.ajax({
            url: '/getDataForAccountId',
            method: 'GET',
            data: {
                id: accountId,
                account_type
            },
            success: function(data) {
                let paySelect = document.getElementById('transaction_type').value === 'pay';
                if (paySelect) {
                    $('#account-details').text('Investor Name: ' + data.info.name);
                    if (data.info.wallet_balance > 0) {
                        $('#total_due').text(`Total Due: ${data.info.wallet_balance}`);
                    } else {
                        $('#total_due').text('Total Due: 0');
                    }
                    $('.account-info').show();
                } else {
                    document.getElementById('transaction_type').value = 'receive';
                    $('.account-info').hide();
                }
                document.getElementById('transaction_type').addEventListener('change', function() {
                    // if (this.value === 'receive') {
                    //     $('.account-info').hide();
                    // } else if (this.value === 'pay') {
                    //     $('.account-info').show();
                    // }
                });
            },
            error: function(xhr, status, error) {
                console.error('Request failed:', error);
            }
        });
    }
});


            //------Filter -----//
            document.querySelector('#transactionfilter').addEventListener('click', function(e) {
                e.preventDefault();
                let startDate = document.querySelector('.start-date').value;

                let endDate = document.querySelector('.end-date').value;
                // alert(endDate);
                let filterCustomer = document.querySelector('.transaction_customer_name').value;
                let filterSupplier = document.querySelector('.transaction_supplier_name').value;
                //   alert(filterCustomer);
                //   alert(filterSupplier);
                $.ajax({
                    url: "{{ route('transaction.filter.view') }}",
                    method: 'GET',
                    data: {
                        startDate,
                        endDate,
                        filterCustomer,
                        filterSupplier,
                    },
                    success: function(res) {
                        jQuery('#transaction-filter-rander').html(res);
                    }
                });
            });

            /////Validation////
            $('#myValidForm').validate({
                rules: {
                    account_type: {
                        required: true,
                    },
                    transaction_type: {
                        required: true,
                    },
                    date: {
                        required: true,
                    },
                    balance: {
                        required: true,
                    },
                    payment_method: {
                        required: true,
                    },
                    amount: {
                        required: true,
                    },
                    type: {
                        required: true,
                    },
                },
                messages: {
                    account_type: {
                        required: 'Please Select Account Type',
                    },
                    date: {
                        required: 'Please Select Date',
                    },
                    transaction_type: {
                        required: 'Please Select Transaction Type',
                    },
                    balance: {
                        required: 'Enter Transaction Balance',
                    },
                    payment_method: {
                        required: 'Select Payment Method',
                    },
                    amount: {
                        required: 'Enter Amount',
                    },
                    type: {
                        required: 'Enter Amount',
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
        ///Print
        $('.print-btn').click(function() {
            // Remove the id attribute from the table
            $('#dataTableExample').removeAttr('id');
            $('.table-responsive').removeAttr('class');
            // Trigger the print function
            window.print();
        });

        ///

        //Add Investor
        function showError(name, message) {
            $(name).css('border-color', 'red');
            $(name).focus();
            $(`${name}_error`).show().text(message);
        }

        function errorRemove(element) {
            tag = element.tagName.toLowerCase();
            if (element.value != '') {
                // console.log('ok');
                if (tag == 'select') {
                    $(element).closest('.mb-3').find('.text-danger').hide();
                } else {
                    $(element).siblings('span').hide();
                    $(element).css('border-color', 'green');
                }
            }
        }

       function viewInvestor() {
    $.ajax({
        url: '/get/investor',
        method: 'GET',
        success: function(res) {
            const investor = res.allData;
            const selectAccountId = $('.select-account-id');
            selectAccountId.empty();
            selectAccountId.append('<option selected disabled value="">Select Name </option>');
            if (investor.length > 0) {
                $.each(investor, function(index, investors) {
                    selectAccountId.append(
                        `<option value="${investors.id}">${investors.name} </option>`
                    );
                });
            }
            // Enable the dropdown after populating
            selectAccountId.prop('disabled', false);
        }
    });
}
          // Function to fetch and display investors
function viewInvestor() {
    $.ajax({
        url: '/get/investor',
        method: 'GET',
        success: function(res) {
            const investor = res.allData;
            const selectAccountId = $('.select-account-id');
            selectAccountId.empty();
            selectAccountId.append('<option selected disabled value="">Select Account ID</option>');
            if (investor.length > 0) {
                $.each(investor, function(index, investors) {
                    selectAccountId.append(
                        `<option value="${investors.id}">${investors.name}</option>`
                    );
                });
            }
            selectAccountId.prop('disabled', false);
        }
    });
}

// Function to fetch and display parties
function viewParty() {
    $.ajax({
        url: '/get/party',
        method: 'GET',
        success: function(res) {
            const partys = res.allData;
            const selectAccountId = $('.select-account-id');
            selectAccountId.empty();
            selectAccountId.append('<option selected disabled value="">Select Name </option>');
            if (partys.length > 0) {
                $.each(partys, function(index, party) {
                    selectAccountId.append(
                        `<option value="${party.id}">${party.name} (${party.phone}) (${party.party_type})</option>`
                    );
                });
            }
            // Enable the dropdown after populating
            selectAccountId.prop('disabled', false);
        }
    });
    }
    $('#due-payment').on('change', function() {
        const selectedValue = $(this).val();
        const selectAccountId = $('.select-account-id');
         const accountTypeSelect = $('#account_type');
        if (!selectedValue) {
          accountTypeSelect.val(''); // Reset account type to default
            selectAccountId.prop('disabled', true).empty().append('<option selected disabled value="">Select Account ID</option>');
        } else if (selectedValue === 'due-payment') {
            viewParty();
             accountTypeSelect.val('party');
        } else {
            viewInvestor();
            accountTypeSelect.val('other');
        }
    });
$('.select-account-id').prop('disabled', true);

        const saveInvestor = document.querySelector('.save_new_investor');
        saveInvestor.addEventListener('click', function(e) {
            e.preventDefault();
            let formData = new FormData($('.investorForm')[0]);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/add/investor',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.status == 200) {
                        // console.log(res);
                        $('#investorModal').modal('hide');
                        $('.investorForm')[0].reset();
                        viewInvestor()
                        toastr.success(res.message);
                    } else {
                        // console.log(res);
                        if (res.error.name) {
                            showError('.investor_name', res.error.name);
                        }
                        if (res.error.phone) {
                            showError('.investor_phone', res.error.phone);
                        }
                    }
                }
            });
        })
        flatpickr("#datepicker", {
            maxDate: "today",
            disable: []
        });
//  if ($('#payment_method').length) {
//     console.log('tigger')
    // $('#payment_method').select2({
    //     placeholder: 'Select Account',
    //     allowClear: true,
    //     width: '100%'
    // }).on('select2:select select2:unselect', function() {
    //     $(this).trigger('change');
    // });
// }

// Replace the #payment_method event handler
$(document).ready(function() {

$('#payment_method').on('change', function() {
    // console.log('payment_method changed');
    const selectedOptions = $(this).find('option:selected');

    const amountInputsContainer = $('#amount-inputs');
    const existingInputs = amountInputsContainer.find('.amount-input-group');
    const selectedIds = selectedOptions.map(function() { return $(this).val(); }).get();
    // Remove inputs for deselected accounts
    existingInputs.each(function() {
        const accountId = $(this).data('account-id');
        if (!selectedIds.includes(accountId.toString())) {
            console.log('Removing input for account ID:', accountId);
            $(this).remove();
        }
    });

    // Add inputs for newly selected accounts
    selectedOptions.each(function() {
        const accountId = $(this).val();
        const accountName = $(this).text().trim();
        if (!amountInputsContainer.find(`.amount-input-group[data-account-id="${accountId}"]`).length) {
            console.log('Adding input for account ID:', accountId);
            const inputHtml = `
                <div class="mb-2 amount-input-group" data-account-id="${accountId}">
                    <label class="form-label">Amount for ${accountName}</label>
                    <input type="number" class="form-control amount-input" name="amounts[${accountId}]" placeholder="Enter amount" min="0" step="0.01" required>
                </div>
            `;
            amountInputsContainer.append(inputHtml);
        }
    });
});
});
    </script>

    <style>
        @media print {

            nav,
            .nav,
            .footer {
                display: none !important;
            }

            .page-content {
                margin-top: 0 !important;
                padding-top: 0 !important;
            }

            .btn_group,
            .filter_table,
            .dataTables_length,
            .pagination,
            .dataTables_info {
                display: none !important;
            }

            #dataTableExample_filter {
                display: none !important;
            }

            .border {
                border: none !important;
            }

            table,
            th,
            td {
                border: 1px solid black;
                background: #fff
            }

            .actions {
                display: none !important;
            }

            .card {
                background: #fff !important;
                box-shadow: none !important;
                border: none !important;
            }

            .note_short {}
        }
    </style>
@endsection
