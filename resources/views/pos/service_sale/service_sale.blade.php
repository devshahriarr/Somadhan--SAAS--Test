@extends('master')
@section('title', '| Services Name')
@section('admin')
    @php
        $mode = App\models\PosSetting::all()->first();
    @endphp
    <style>
        .no-gutters {
            margin-right: 0;
            margin-left: 0;
        }

        .no-gutters>.col-md-2,
        .no-gutters>.col-md-4 {
            padding-right: 0;
            padding-left: 0;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .bill-header p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .serviceSale table th,
        .serviceSale table td,
        .otherExpensesCosts table th,
        .otherExpensesCosts table td,
        .overnightStayCosts table th,
        .overnightStayCosts table td,
        .foodingcosts table th,
        .foodingcosts table td {
            border: 1px solid #6587ff;
            padding: 5px;
            text-align: left;
        }

        table th {
            font-weight: bold;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #6587ff !important;
        }

        .p-3 {
            padding-bottom: 50px !important;
        }

        /*  list Table css*/
        #expenseTable,
        #foodingTable,
        #overnightStayTable,
        #otherExpensesTable {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ddd;
        }

        #expenseTable th,
        #foodingTable th,
        #overnightStayTable th,
        #otherExpensesTable th,
        #expenseTable td,
        #foodingTable td,
        #overnightStayTable td,
        #otherExpensesTable td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        #expenseTable th,
        #foodingTable th,
        #overnightStayTable th,
        #otherExpensesTable th {
            @if ($mode->dark_mode == 2)

            @else
                background-color: #f2f2f2;
            @endif

        }
    </style>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card ">
                <div class="card-body">
                    <div class="col-md-12 grid-margin stretch-card d-flex  mb-0 justify-content-between">
                        <div>
                            <h6 class="card-title">Service Sale </h6>
                        </div>
                        <div class="">
                            <h4 class="text-right"><a href="{{ route('service.sale.view') }}" class="btn"
                                    style="background: #5660D9">View Service Sale</a></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form action="" id="serviceForm">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="card-title">Service Name Table</h6>
                            {{-- <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#exampleModalLongScollable">Add Departments</button> --}}
                        </div>
                        <div id="" class="table-responsive">
                            <div class="bill-header">
                                <div class="row no-gutters">
                                    <div class="col-md-2">
                                        <strong>Party Name:</strong>
                                    </div>

                                    <div class="col-md-4 d-flex">
                                        <select class="form-control js-example-basic-single " name="customer_id"
                                            id="customer-select">

                                        </select>
                                        <div class="ms-3">
                                            <a class="btn btn-rounded-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#exampleModalLongScollable"><i data-feather="plus"></i></a>
                                        </div>

                                    </div>
                                    <div class="col-md-5">
                                        <div class="ms-5">
                                            <h3 class="grandTotal">Total Amount: <span id="grandTotalDisplay">0</span></h3>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mt-2">
                                        <strong>Date:</strong>
                                    </div>
                                    <div class="col-md-4 mb-4 mt-2">
                                        <div class="input-group flatpickr me-2 mb-2 mb-md-0 date-select" id="dashboardDate">
                                            <span class="input-group-text input-group-addon bg-transparent border-primary"
                                                data-toggle><i data-feather="calendar" class="text-primary"></i></span>
                                            <input type="text" name="date"
                                                class="form-control bg-transparent border-primary" placeholder="Select date"
                                                data-input>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- /////////Tabing Start//// -->
                            <div class="row">
                                <div class="col-md-12 grid-margin stretch-card">
                                    <div class="example w-100">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <!---First li--->
                                            {{-- <li class="nav-item">
                                            <a class="nav-link active" id="serviceSale-tab" data-bs-toggle="tab"
                                                href="#serviceSale" role="tab" aria-controls="serviceSale"
                                                aria-selected="true">Movement Costs
                                            </a>
                                            </li> --}}

                                        </ul>
                                        <!--First Tab  Start-->

                                        <div class="tab-content border border-top-0 p-3" id="myTabContent">

                                            <div class="tab-pane fade show active" id="serviceSale" role="tabpanel"
                                                aria-labelledby="serviceSale-tab">
                                                <div class="col-md-12 serviceSale">

                                                    <table id="serviceTable">
                                                        <thead>
                                                            <tr>
                                                                <th><button type="button" class="form-control"
                                                                        id="addServiceRowBtn">+
                                                                    </button></th>
                                                                <th>Product/Service Name</th>
                                                                <th>Volume</th>
                                                                <th>Price</th>
                                                                <th>Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="4" class="text-end"><strong>Total
                                                                        Amount</strong>
                                                                </td>
                                                                <td><strong id="totalAmount">00</strong></td>

                                                            </tr>

                                                        </tfoot>
                                                    </table>
                                                </div>
                                                {{-- <button type="submit" class="btn btn-md float-end serviceSaleAdd"
                                                        style="border:1px solid #6587ff ">Submit</button> --}}
                                                <a class="btn btn-primary serviceSaleAdd payment_service__btn"><i
                                                        class="fa-solid fa-money-check-dollar"></i>
                                                    Receive
                                                </a>
                                                {{-- payement modal  --}}
                                                <div class="modal fade" id="paymentModal" tabindex="-1"
                                                    aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalScrollableTitle">
                                                                    Payment</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="btn-close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div id="" class="table-responsive mb-3">
                                                                    <table class="table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Paying Items:</th>
                                                                                <th><span class="paying_items">0</span></th>
                                                                                <th>Sub Total :</th>
                                                                                <th>
                                                                                    <input type="number" name="subTotal" i
                                                                                        class="subTotal  form-control border-0 "
                                                                                        readonly value="00">
                                                                                </th>
                                                                            </tr>

                                                                        </thead>
                                                                    </table>
                                                                </div>
                                                                {{-- <form id="signupForm" class="supplierForm row"> --}}
                                                                <div class="supplierForm row">
                                                                    <div class="mb-3 col-md-6">
                                                                        <label for="name" class="form-label">Transaction
                                                                            Method <span
                                                                                class="text-danger">*</span></label>
                                                                        @php
                                                                            $payments = App\Models\Bank::get();
                                                                        @endphp
                                                                        <select class="form-select payment_method"
                                                                            data-width="100%" onclick="errorRemove(this);"
                                                                            onblur="errorRemove(this);"
                                                                            name="payment_method">
                                                                            @if ($payments->count() > 0)
                                                                                @foreach ($payments as $payemnt)
                                                                                    <option value="{{ $payemnt->id }}">
                                                                                        {{ $payemnt->name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            @else
                                                                                <option selected disabled>Please Add
                                                                                    Transaction</option>
                                                                            @endif
                                                                        </select>
                                                                        <span
                                                                            class="text-danger payment_method_error"></span>
                                                                    </div>
                                                                    <div class="mb-3 col-md-6">
                                                                        <label for="name" class="form-label">Pay
                                                                            Amount <span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="d-flex align-items-center">
                                                                            <input
                                                                                class="form-control total_payable border-end-0 rounded-0"
                                                                                name="total_payable" type="number"
                                                                                onkeyup="payFunc();"
                                                                                onclick="errorRemove(this);"
                                                                                onblur="errorRemove(this);"
                                                                                step="0.01">
                                                                            <span
                                                                                class="text-danger total_payable_error"></span>
                                                                            <button
                                                                                class="btn btn-info border-start-0 rounded-0 paid_btn">Paid</button>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mb-3 col-md-6">
                                                                        <label for="name"
                                                                            class="form-label">Due</label>
                                                                        <input name="note"
                                                                            class="form-control final_due" id=""
                                                                            placeholder="" readonly></input>
                                                                    </div>
                                                                    <div class="mb-3 col-md-6">
                                                                        <label for="name"
                                                                            class="form-label">Note</label>
                                                                        <input name="note" class="form-control note"
                                                                            id=""
                                                                            placeholder="Enter Note (Optional)"
                                                                            rows="3"></input>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit"
                                                                        class="btn btn-primary serviceSaleAdd"><i
                                                                            class="fa-solid fa-cart-shopping"></i>
                                                                        Service Sale
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- ////////payment // --}}
                                            </div>
                                            <!--First Tab End -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModalLongScollable" tabindex="-1" aria-labelledby="exampleModalScrollableTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Add Party Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="serviceSupplierForm row">
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Party Name <span
                                    class="text-danger">*</span></label>
                            <input id="defaultconfig" class="form-control supplier_name" maxlength="255" name="name"
                                type="text" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger supplier_name_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Party Nnumber <span
                                    class="text-danger">*</span></label>
                            <input id="defaultconfig" class="form-control phone" maxlength="39" name="phone"
                                type="tel" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger phone_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Email</label>
                            <input id="defaultconfig" class="form-control email" maxlength="39" name="email"
                                type="email">
                            <span class="text-danger email_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Address</label>
                            <input id="defaultconfig" class="form-control address" maxlength="39" name="address"
                                type="text">
                            <span class="text-danger address_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Opening Payable</label>
                            <input id="defaultconfig" class="form-control opening_payable" maxlength="39"
                                name="opening_payable" type="number">
                            <span class="text-danger opening_payable_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Opening Receivable</label>
                            <input id="defaultconfig" class="form-control opening_receivable" maxlength="39"
                                name="opening_receivable" type="number">
                            <span class="text-danger opening_receivable_error"></span>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="name" class="form-label">Party Type</label>
                            <select name="party_type" class="form-control party_type">
                                <option value="both">Both</option>
                                <option value="customer">Customer</option>
                                {{-- <option value="supplier">Supplier</option> --}}
                            </select>
                            <span class="text-danger party_type_error"></span>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary save_party">Save</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <script>
        ///////////Payment////

        ///////////Payment////
        function errorRemove(element) {
            if (element.value != '') {
                $(element).siblings('span').hide();
                $(element).css('border-color', 'green');
            }
        }

        document.getElementById('addServiceRowBtn').addEventListener('click', function() {
            let tableBody = document.querySelector('#serviceTable tbody');
            let totalAmountElement = document.getElementById('totalAmount');
            let grandTotalAmountElement = document.getElementById('grandTotalDisplay');
            let newRow = document.createElement('tr');

            // Create new row with input fields
            newRow.innerHTML = `
              <td><button type="button" class="removeServiceRowBtn form-control text-danger btn-xs btn-danger">
                <i class="fa-solid fa-trash-can "></i></button></td>
                <td> <input type="text" class="input-group flatpickr form-control" name="serviceName[]" placeholder="Service Name" value=""></td>

                <td><input type="number" class="form-control volume-input"  name="volume[]" placeholder="Volume"></td>

                <td> <input type="number" class="form-control price-input"  name="price[]"  placeholder="Price"></td>

                <td><input type="number" class="form-control  total-input" readonly name="total[]" value=""  placeholder="Total Price"></td>

            `;
            // Append the new row to the table body
            tableBody.appendChild(newRow);

            let volumeInput = newRow.querySelector('.volume-input');
            let priceInput = newRow.querySelector('.price-input');
            let totalInput = newRow.querySelector('.total-input');
            [volumeInput, priceInput].forEach(input => {
                input.addEventListener('input', function() {
                    if (this.value < 0) {
                        this.value = '';
                        toastr.warning('⚠️ Negative values are not allowed.');
                    }
                });
            });

            function calculateTotal() {
                let volume = parseFloat(volumeInput.value) || 0;
                let price = parseFloat(priceInput.value) || 0;
                totalInput.value = (volume * price).toFixed(2);
                calculateTotalSum();
            }
            volumeInput.addEventListener('input', calculateTotal);
            priceInput.addEventListener('input', calculateTotal);

            function calculateTotalSum() {
                let totalInputs = document.querySelectorAll('.total-input');
                let grandTotal = 0;

                totalInputs.forEach(input => {
                    grandTotal += parseFloat(input.value) || 0;
                });

                totalAmountElement.textContent = grandTotal.toFixed(2);
                grandTotalAmountElement.textContent = grandTotal.toFixed(2);
                let modalSubTotal = document.querySelector('#paymentModal .subTotal');
                if (modalSubTotal) {
                    modalSubTotal.value = grandTotal.toFixed(2); // Reflect the grand total in the modal
                }

                // ADDED: Update the number of paying items in the modal (optional, based on row count)
                let payingItems = document.querySelector('#paymentModal .paying_items');
                if (payingItems) {
                    payingItems.textContent = totalInputs.length; // Show the number of rows/items
                }
            }

            newRow.querySelector('.removeServiceRowBtn').addEventListener('click', function() {
                newRow.remove();
                calculateTotalSum();
            });
        });
        //Validate
        // validation before payment button click
        const serviceSaleAddBtn = document.querySelector('.payment_service__btn');
        const serviceSaleSubmitBtn = document.querySelector('#paymentModal .serviceSaleAdd');
        const serviceForm = document.getElementById('serviceForm');

        //////////////////////////////////Paid Button Function ////////////////////////////////////////
        const paidBtn = document.querySelector('#paymentModal .paid_btn');
        function payFunc() {
        const subTotal = parseFloat(document.querySelector('#paymentModal .subTotal').value) || 0;
        const totalPayable = parseFloat(document.querySelector('.total_payable').value) || 0;
        const dueAmount = subTotal - totalPayable;
        document.querySelector('.final_due').value = dueAmount.toFixed(2);

        if (totalPayable > 0) {
            document.querySelector('.total_payable_error').textContent = '';
        }
      }//
      paidBtn.addEventListener('click', function(e) {
        e.preventDefault(); // Prevent any default button behavior
        const subTotal = parseFloat(document.querySelector('#paymentModal .subTotal').value) || 0;
        const totalPayableInput = document.querySelector('.total_payable');
        totalPayableInput.value = subTotal.toFixed(2);
        payFunc();

        document.querySelector('.total_payable_error').textContent = '';
    });
///////////////////////////////////////////////////////////////////////////////////////////////
        serviceSaleAddBtn.addEventListener('click', function(e) {
            e.preventDefault();



            const rows = document.querySelectorAll('#serviceTable tbody tr');
            let allFieldsFilled = true;
            let errorMessages = [];

            rows.forEach(function(row) {
                let serviceName = row.querySelector('input[name="serviceName[]"]').value.trim();
                let volume = row.querySelector('input[name="volume[]"]').value.trim();
                let price = row.querySelector('input[name="price[]"]').value.trim();

                if (!serviceName) {
                    errorMessages.push('⚠️ Service Name field is required.');
                    allFieldsFilled = false;
                }
                if (!volume) {
                    errorMessages.push('⚠️ Volume field is required.');
                    allFieldsFilled = false;
                } else if (isNaN(volume) || volume <= 0) {
                    errorMessages.push('⚠️ Volume must be a positive number.');
                    allFieldsFilled = false;
                }
                if (!price) {
                    errorMessages.push('⚠️ Price field is required.');
                    allFieldsFilled = false;
                } else if (isNaN(price) || price <= 0) {
                    errorMessages.push('⚠️ Price must be a positive number.');
                    allFieldsFilled = false;
                }
            });

            if (rows.length > 0) {
                if ($('#customer-select').val() === '' || $('#customer-select').val() === null) {
                    errorMessages.push('⚠️ Please select a Customer.');
                    allFieldsFilled = false;
                }
            } else {
                errorMessages.push('⚠️ Please add a service first.');
                allFieldsFilled = false;
            }

            if (!allFieldsFilled) {
                toastr.warning(errorMessages.join('<br>'));
                return;
            }

            $('#paymentModal').modal('show');
        });
        ///payment insert
        serviceSaleSubmitBtn.addEventListener('click', function(e) {
            e.preventDefault();
            let paymentValid = true;
            let paymentErrorMessages = [];

            // Validate Payment Method
            const paymentMethod = document.querySelector('.payment_method').value.trim();
            if (!paymentMethod || paymentMethod === '' || paymentMethod === 'Please Add Transaction') {
                paymentErrorMessages.push('⚠️ Please select a valid Transaction Method.');
                paymentValid = false;
                document.querySelector('.payment_method_error').textContent = 'Required';
            } else {
                document.querySelector('.payment_method_error').textContent = '';
            }
            if (!paymentValid) {
                toastr.warning(paymentErrorMessages.join('<br>'));
                return;
            }
            const totalPayable = document.querySelector('.total_payable').value.trim();
            const subTotal = parseFloat(document.querySelector('.subTotal').value) || 0;
            if (!totalPayable) {
                paymentErrorMessages.push('⚠️ Pay Amount is required.');
                paymentValid = false;
                document.querySelector('.total_payable_error').textContent = 'Required';
            }
            if (!paymentValid) {
                toastr.warning(paymentErrorMessages.join('<br>'));
                return;
            }
            let formData = new FormData(serviceForm);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('service.sale.store') }}", // Replace with your actual route
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 200) {
                        serviceForm.reset();
                        $('#serviceTable tbody').empty();
                        $('#paymentModal').modal('hide');
                        toastr.success(response.message);
                        window.location.href = '/service/sale/view';
                    } else {
                        toastr.error(response.error || 'Something went wrong.');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorList = Object.values(errors).flat().join('<br>');
                        toastr.error(errorList);
                    } else {
                        toastr.warning('An unexpected error occurred.');
                    }
                }
            });
        });
        $(document).ready(function() {
            function showError(name, message) {
                $(name).css('border-color', 'red'); // Highlight input with red border
                $(name).focus(); // Set focus to the input field
                $(`${name}_error`).show().text(message); // Show error message
            }

            const saveParty = document.querySelector('.save_party');
            saveParty.addEventListener('click', function(e) {
                e.preventDefault();
                // alert('okay');
                let formData = new FormData($('.serviceSupplierForm')[0]);
                // console.log('formData');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/party/store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        // console.log(res);
                        if (res.status == 200) {
                            $('#exampleModalLongScollable').modal('hide');
                            $('.serviceSupplierForm')[0].reset();
                            loadCustomers()
                            toastr.success(res.message);
                        } else {
                            if (res.errors.name) {
                                showError('.supplier_name', res.errors.name);
                            }
                            if (res.errors.phone) {
                                showError('.phone', res.errors.phone);
                            }
                            if (res.errors.email) {
                                showError('.email', res.errors.email);
                            }
                            if (res.errors.address) {
                                showError('.address', res.errors.address);
                            }
                            if (res.errors.opening_payable) {
                                showError('.opening_payable', res.errors.opening_payable);
                            }
                            if (res.errors.opening_receivable) {
                                showError('.opening_receivable', res.errors.opening_receivable);
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        // console.log(error);
                        // Handle AJAX errors
                        console.error('AJAX Error:', error);
                        toastr.error(
                            'An error occurred while saving the supplier. Please try again.'
                        );
                    }
                });
            })

            function loadCustomers() {
                $.ajax({
                    url: '/party/view/service/sale', // Replace with your route to fetch customers
                    type: 'GET',
                    success: function(res) {
                        if (res.status === 200) {
                            let options = '<option selected disabled>Select Name</option>';
                            res.customers.forEach(customer => {
                                options +=
                                    `<option value="${customer.id}">${customer.name}</option>`;
                            });
                            $('#customer-select').html(options);
                        } else {
                            toastr.error('Failed to load customers. Please try again.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        toastr.error('An error occurred while fetching customers.');
                    }
                });
            }


            loadCustomers();


        });
    </script>
@endsection
