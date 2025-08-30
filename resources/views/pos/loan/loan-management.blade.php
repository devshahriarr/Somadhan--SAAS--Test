@extends('master')
@section('title', '| Loan Management')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Loan Management</li>
        </ol>
    </nav>
    <style>
        .nav-link:hover,
        .nav-link.active {
            color: #6587ff !important;
        }
    </style>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title">Loan Table</h6>
                        <button class="btn btn-rounded-primary btn-sm" data-bs-toggle="modal" data-bs-target="#loanModal"><i
                                data-feather="plus"></i></button>
                    </div>
                    <div id="" class="table-responsive">
                        <table id="loanTable" class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Loan Name</th>
                                    <th>Loan Duration</th>
                                    <th>Loan Principal</th>
                                    <th>Interest Rate</th>
                                    <th>Loan Balance</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="loan_data">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loan Modal -->
    <div class="modal fade" id="loanModal" tabindex="-1" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Loan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form class="loanForm row">
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Loan Name<span class="text-danger">*</span></label>
                            <input class="form-control loan_name" name="loan_name" type="text"
                                onkeyup="errorRemove(this);">
                            <span class="text-danger loan_name_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Bank Account<span class="text-danger">*</span></label>
                            <select class="form-control bank_loan_account_id" name="bank_loan_account_id"
                                onchange="errorRemove(this);">
                                @if ($banks->count() > 0)
                                    <option value="">Select Loan Account</option>
                                    @foreach ($banks as $account)
                                        <option value="{{ $account->id }}">{{ $account->name ?? '' }}</option>
                                    @endforeach
                                @else
                                    <option value="">No Account Found</option>
                                @endif
                            </select>
                            <span class="text-danger bank_loan_account_id_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Loan Amount<span class="text-danger">*</span></label>
                            <input class="form-control loan_principal" name="loan_principal" type="number"
                                onkeyup="errorRemove(this);">
                            <span class="text-danger loan_principal_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Interest Rate %<span
                                    class="text-danger">*</span></label>
                            <input class="form-control interest_rate" name="interest_rate" type="number"
                                onkeyup="errorRemove(this);" placeholder="0.00">
                            <span class="text-danger interest_rate_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Loan Duration <span
                                    class="text-danger">*</span></label>
                            <input class="form-control loan_duration" name="loan_duration" type="number"
                                onkeyup="errorRemove(this);" placeholder="0.00">
                            <span class="text-danger loan_duration_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Repayment Schedule<span
                                    class="text-danger">*</span></label>
                            <select class="form-control repayment_schedule" name="repayment_schedule"
                                onchange="errorRemove(this);">
                                <option value="">Select Repayment Schedule</option>
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                            <span class="text-danger repayment_schedule_error"></span>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="name" class="form-label">Start Date<span class="text-danger">*</span></label>
                            <div class="input-group flatpickr me-2 mb-2 mb-md-0" id="dashboardDate">
                                <span class="input-group-text input-group-addon bg-transparent border-primary"
                                    data-toggle><i data-feather="calendar" class="text-primary"></i></span>
                                <input type="text" name="start_date"
                                    class="form-control bg-transparent border-primary start_date"
                                    placeholder="Select date" data-input>
                            </div>
                            <span class="text-danger start_date_error"></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modal_close" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_loan">Save</button>
                </div>
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


        // ready function
        $(document).ready(function() {

            // show error
            function showError(name, message) {
                $(name).css('border-color', 'red'); // Highlight input with red border
                $(name).focus(); // Set focus to the input field
                $(`${name}_error`).show().text(message); // Show error message
            }



            // save Loan information
            const saveLoan = document.querySelector('.save_loan');
            saveLoan.addEventListener('click', function(e) {
                e.preventDefault();
                // Convert the date inputs to YYYY-MM-DD format
                let startDate = new Date($('.loanForm [name="start_date"]').val());

                // Set the dates in the form fields in the correct format
                $('.loanForm [name="start_date"]').val(startDate.toISOString().slice(0, 10));

                let formData = new FormData($('.loanForm')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/loan/store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            $('#loanModal').modal('hide');
                            $('.loanForm')[0].reset();
                            LoanView();
                            toastr.success(res.message);
                        } else {
                            if (res.error.loan_name) {
                                showError('.loan_name', res.error.loan_name);
                            }
                            if (res.error.bank_loan_account_id) {
                                showError('.bank_loan_account_id', res.error
                                    .bank_loan_account_id);
                            }
                            if (res.error.loan_principal) {
                                showError('.loan_principal', res.error.loan_principal);
                            }
                            if (res.error.interest_rate) {
                                showError('.interest_rate', res.error.interest_rate);
                            }
                            if (res.error.repayment_schedule) {
                                showError('.repayment_schedule', res.error.repayment_schedule);
                            }
                            if (res.error.start_date) {
                                showError('.start_date', res.error.start_date);
                            }
                            if (res.error.loan_duration) {
                                showError('.loan_duration', res.error.loan_duration);
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error(xhr.responseJSON.message ||
                            'An unexpected error occurred');
                    }
                });
            })

            // // Loan Info View Function
            function LoanView() {
                $.ajax({
                    url: '/loan/view',
                    method: 'GET',
                    success: function(res) {
                        const loans = res.data;
                        $('.loan_data').empty();
                        if ($.fn.DataTable.isDataTable('#loanTable')) {
                            $('#loanTable').DataTable().clear().destroy();
                        }
                        if (loans.length > 0) {
                            $.each(loans, function(index, loan) {
                                const tr = document.createElement('tr');
                                let statusBadge = ''; // Initialize status badge variable

                                // Check loan status and assign the correct badge
                                if (loan.status === 'active') {
                                    statusBadge =
                                        '<span class="badge bg-success">Active</span>';
                                } else if (loan.status === 'closed') {
                                    statusBadge =
                                        '<span class="badge bg-danger">Closed</span>';
                                } else if (loan.status === 'defaulted') {
                                    statusBadge =
                                        '<span class="badge bg-warning">Defaulted</span>';
                                }
                                tr.innerHTML = `
                                <td>
                                ${index+1}
                                </td>
                                <td>
                                    <a href="/loan/view/${loan.id}">
                                        ${loan.loan_name ?? ""}
                                        </a>
                                </td>
                                <td>${loan.loan_duration ?? ""}</td>
                                <td>${loan.loan_principal ?? ""}</td>
                                <td>${loan.interest_rate ?? ""}</td>
                                <td>${loan.loan_balance ?? 0}</td>
                                <td>
                                    ${statusBadge}
                                </td>
                                <td>
                                    <a href="/loan/view/${loan.id}" class="btn btn-icon btn-xs btn-primary">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                </td>
                            `;
                                $('.loan_data').append(tr);

                            });
                        } else {
                            $('.loan_data').html(`
                        <tr>
                            <td colspan='9'>
                                <div class="text-center text-warning mb-2">Data Not Found</div>
                                <div class="text-center">
                                    <button class="btn btn-xs btn-primary" data-bs-toggle="modal" data-bs-target="#loanModal">Add Loan Info<i data-feather="plus"></i></button>
                                </div>
                            </td>
                        </tr>
                        `);
                        }
                        // Reinitialize DataTable
                        dynamicDataTableFunc('loanTable');
                    }
                });
            }
            LoanView();

        }); //


        document.addEventListener("DOMContentLoaded", function() {
            // tab active on the page reload
            // Get the last active tab from localStorage
            let activeTab = localStorage.getItem('activeTab');

            // If there is an active tab stored, activate it
            if (activeTab) {
                let tabElement = document.querySelector(`a[href="${activeTab}"]`);
                if (tabElement) {
                    new bootstrap.Tab(tabElement).show();
                }
            }

            // Store the currently active tab in localStorage
            document.querySelectorAll('.nav-link').forEach(tab => {
                tab.addEventListener('shown.bs.tab', function(event) {
                    let activeTabHref = event.target.getAttribute('href');
                    localStorage.setItem('activeTab', activeTabHref);
                });
            });



            // modal on off
            // Initialize modal with backdrop and keyboard options
            modalShowHide('loanModal');
        });
    </script>


@endsection
