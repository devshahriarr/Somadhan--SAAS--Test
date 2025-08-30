@extends('master')
@section('title','| Employee Profile')
@section('admin')
<style>
    .nav-link:hover, .nav-link:focus {
    color: #5660D9 !important;
    /* background-color: var(--nav-hover-background-color); */
}
</style>
    <div class="row">
        <div class="col-12  grid-margin">
            <div class="card">
                <div class="position-relative">
                    <figure class="overflow-hidden mb-0 d-flex justify-content-center">
                        <img src="{{ asset('dummy/default-cover.jpeg') }}" height="390" width="1560"
                            class="rounded-top" alt="profile cover">
                    </figure>
                    <div
                        class="d-flex justify-content-between align-items-center position-absolute top-90 w-100 px-2 px-md-4 mt-n4">
                        <div>
                            <img class="wd-70 rounded-circle"
                                src="{{ $employee->pic ? asset('uploads/employee/' . $employee->pic) : asset('uploads/employee/dafault-profile.jpg') }}"
                                alt="profile">
                            <span class="h4 ms-3 text-dark">{{ $employee->full_name }}</span>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center p-3 rounded-bottom">
                    <ul class="d-flex align-items-center m-0 p-0 nav nav-tabs" id="myTab" role="tablist">

                        <li class="d-flex align-items-center active">
                            <i class="me-1 icon-md" data-feather="credit-card"></i>
                            <a class="pt-1px d-md-block text-secondary nav-link active" id="Home-tab"
                                data-bs-toggle="tab" href="#home" role="tab" aria-controls="home"
                                aria-selected="true">Salary Month Info</a>
                        </li>

                        <li class="ms-3 ps-3 border-start d-flex align-items-center">
                            <i class="me-1 icon-md" data-feather="dollar-sign"></i>
                            <a class="pt-1px d-md-block text-body nav-link" id="convenience-tab" data-bs-toggle="tab"
                                href="#convenience" role="tab" aria-controls="convenience"
                                aria-selected="false">Total Sale</a>
                        </li>

                        <li class="ms-3 ps-3 border-start d-flex align-items-center">
                            <i class="me-1 icon-md" data-feather="file-text"></i>
                            <a class="pt-1px d-md-block text-body nav-link" id="payslip-tab" data-bs-toggle="tab"
                                href="#payslip" role="tab" aria-controls="payslip" aria-selected="false">Total Purchase</a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row profile-body">
        <!-- left wrapper start -->
        <div class="d-none d-md-block col-md-4 col-xl-3 left-wrapper">
            <div class="card rounded">
                <div class="card-body ">
                    {{-- <h5 class="text-center">Info</h5> --}}
                    <div class=" align-items-center  justify-content-between mb-2">
                        <h4 class="card-title mb-0  ">Name :</h4>
                        <h6 class="mt-1" style="color: #7987a1">{{ $employee->full_name ?? '-' }}</h6>
                    </div>
                    <div class=" align-items-center justify-content-between mb-2">
                        <h4 class="card-title mb-0 ">Phone :</h4>
                        <h6 class="mt-1" style="color: #7987a1">{{ $employee->phone ?? '-' }}</h6>
                    </div>
                    <div class=" align-items-center justify-content-between mb-2">
                        <h4 class="card-title mb-0 ">Email :</h4>
                        <h6 class="mt-1" style="color: #7987a1">{{ $employee->email ?? '-' }}</h6>
                    </div>
                    <div class=" align-items-center   justify-content-between mb-2">
                        <h4 class="card-title mb-0  ">Designation :</h4>
                        <h6 class="mt-1" style="color: #7987a1">{{ $employee->designation ?? '-' }}</h6>
                    </div>
                    <div class="align-items-center   justify-content-between mb-2">
                        <h4 class="card-title mb-0  ">Address :</h4>
                        <h6 class="mt-1" style="color: #7987a1">{{ $employee->address ?? '-' }}</h6>
                    </div>
                </div>
            </div>
            <div class="card rounded mt-3">
                <div class="card-body ">
                    <h5 class="text-center">Salary Structure</h5>
                    <div class=" align-items-center mt-3  justify-content-between mb-2">
                        <h4 class="card-title mb-0  ">Salary :</h4>
                        <h6 class="mt-1" style="color: #7987a1">{{ $employee->salary ?? '-' }}</h6>
                    </div>
                    <div class=" align-items-center justify-content-between mb-2">
                        <h4 class="card-title mb-0 ">House Rent :</h4>
                        <h6 class="mt-1" style="color: #7987a1">{{ $salaryStructure->house_rent ?? '-' }}</h6>
                    </div>
                    <div class=" align-items-center justify-content-between mb-2">
                        <h4 class="card-title mb-0 ">Transport Allowance :</h4>
                        <h6 class="mt-1" style="color: #7987a1">{{ $salaryStructure->transport_allowance ?? '-' }}</h6>
                    </div>
                    <div class=" align-items-center   justify-content-between mb-2">
                        <h4 class="card-title mb-0  ">Other Fixed Allowance :</h4>
                        <h6 class="mt-1" style="color: #7987a1">{{ $salaryStructure->other_fixed_allowances ?? '-' }}
                        </h6>
                    </div>
                    <div class="align-items-center   justify-content-between mb-2">
                        <h4 class="card-title mb-0  ">Deductions :</h4>
                        <h6 class="mt-1" style="color: #7987a1">{{ $salaryStructure->deductions ?? '-' }}</h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 middle-wrapper">
            <div class="row">
                <div class="col-md-12 grid-margin ">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="home" role="tabpanel"
                            aria-labelledby="home-tab">
                            <div class="card rounded">
                                <div class="card-body table-responsive">
                                    <p class="mb-3 tx-14">Salary Month Info</p>
                                    <table class="table table-bordered ">
                                        <thead>
                                            <tr>
                                                <th>Pay Date</th>
                                                <th>Debit</th>
                                                <th>Credit</th>
                                                <th>Balance</th>
                                                <th>Note</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($employeeSalarys as $key=> $employeeSalary)
                                            <tr>
                                                <td>{{$employeeSalary->date	}}</td>
                                                <td>{{$employeeSalary->debit	}}</td>
                                                <td>{{$employeeSalary->creadit	}}</td>
                                                <td>{{$employeeSalary->balance	}}</td>
                                                <td>{{$employeeSalary->note ?? "N/A"	}}</td>

                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade " id="convenience" role="tabpanel" aria-labelledby="convenience-tab">
                            <div class="card rounded">
                                <div class="card-body table-responsive">
                                    <div class="dropdown">
                                        <a type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                            <a class="dropdown-item salesAllDays d-flex align-items-center" href="javascript:;">
                                                <i data-feather="eye" class="icon-sm me-2"></i> <span>Today</span>
                                            </a>
                                            <a class="dropdown-item salesAllDays d-flex align-items-center" href="javascript:;">
                                                <i data-feather="eye" class="icon-sm me-2"></i> <span>Weekly</span>
                                            </a>
                                            <a class="dropdown-item salesAllDays d-flex align-items-center" href="javascript:;">
                                                <i data-feather="eye" class="icon-sm me-2"></i> <span>Monthly</span>
                                            </a>
                                            <a class="dropdown-item salesAllDays d-flex align-items-center" href="javascript:;">
                                                <i data-feather="eye" class="icon-sm me-2"></i> <span>Yearly</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered" id="sales-summary-table">
                                                <thead>
                                                    <tr>
                                                        <th>Total Sale</th>
                                                        <th>Total Discount</th>
                                                        <th>Total Paid</th>
                                                        <th>Total Due</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                            <table class="table table-bordered" id="sales-details-table">
                                                <thead>
                                                    <tr>
                                                        <th>Invoice</th>
                                                        <th>Anount</th>
                                                        <th>Sale Date</th>
                                                        <th>Discount</th>
                                                        <th>Paid</th>
                                                        <th> Due</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade " id="payslip" role="tabpanel" aria-labelledby="payslip-tab">
                            <div class="card rounded">
                                <div class="card-body">
                                    <p class="mb-3 tx-14">Purchase</p>
                                    <hr>
                                    <div class="dropdown">
                                        <a type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                            <a class="dropdown-item purchaseDays d-flex align-items-center" href="javascript:;">
                                                <i data-feather="eye" class="icon-sm me-2"></i> <span>Today</span>
                                            </a>
                                            <a class="dropdown-item purchaseDays d-flex align-items-center" href="javascript:;">
                                                <i data-feather="eye" class="icon-sm me-2"></i> <span>Weekly</span>
                                            </a>
                                            <a class="dropdown-item purchaseDays d-flex align-items-center" href="javascript:;">
                                                <i data-feather="eye" class="icon-sm me-2"></i> <span>Monthly</span>
                                            </a>
                                            <a class="dropdown-item purchaseDays d-flex align-items-center" href="javascript:;">
                                                <i data-feather="eye" class="icon-sm me-2"></i> <span>Yearly</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <table class="table table-bordered" id="purchase-summary-table">
                                            <thead>
                                                <tr>
                                                    <th>Total Purchase</th>
                                                    <th>Total Discount</th>
                                                    <th>Total Paid</th>
                                                    <th>Total Due</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                        <table class="table table-bordered" id="purchase-details-table">
                                            <thead>
                                                <tr>
                                                    <th>Invoice</th>
                                                    <th>Anount</th>
                                                    <th>Purchase Date</th>
                                                    <th>Discount</th>
                                                    <th>Paid</th>
                                                    <th>Due</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for Preview -->
    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="border: 2px solid #333; padding: 20px;">

            </div>
        </div>
    </div>
    <!-- update Modal for Preview -->
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="border: 2px solid #333; padding: 20px;">

            </div>
        </div>
    </div>
    <script>
    $(document).ready(function() {
    const employeeId = {{ $employee->id ?? 'null' }};

    function loadSalesData(filter) {
        $.ajax({
            url: "{{ route('filter.employee.profile.sale.data') }}",
            type: "GET",
            data: {
                filter: filter,
                id: employeeId
            },
            success: function(response) {
                const sales = response.sale;
                // Sales Summary Table
                const summaryRow = `
                   <tr>
                        <td>${sales.reduce((sum, sale) => sum + parseFloat(sale.change_amount || 0), 0).toFixed(2)}</td>
                        <td>${sales.reduce((sum, sale) => sum + parseFloat(sale.actual_discount || 0), 0).toFixed(2)}</td>
                        <td>${sales.reduce((sum, sale) => sum + parseFloat(sale.paid || 0), 0).toFixed(2)}</td>
                        <td>${sales.reduce((sum, sale) => sum + parseFloat(sale.due || 0), 0).toFixed(2)}</td>
                    </tr>
                    `;
                       $('#sales-summary-table tbody').html(summaryRow);

                // Sales Details Table
                let detailsRows = '';
                sales.forEach(sale => {
                    detailsRows += `
                        <tr>
                            <td>${sale.invoice_number}</td>
                            <td>${sale.change_amount}</td>
                            <td>${sale.sale_date}</td>
                            <td>${sale.actual_discount}</td>
                            <td>${sale.paid}</td>
                            <td>${sale.due}</td>
                        </tr>`;
                });
                $('#sales-details-table tbody').html(detailsRows);
            },
            error: function() {
                alert('Failed to fetch Sale data.');
            }
        });
    }

    // Initial load
    loadSalesData('today');

    // Dropdown filter event
    $('.salesAllDays').on('click', function() {
        const filter = $(this).text().toLowerCase();
        loadSalesData(filter);
    });
});
///////////////////////////Purchase ////////////////////////////////////
const employeId = {{ $employee->id ?? 'null' }};
function loadPurchaseData(filter) {
        $.ajax({
            url: "{{ route('filter.employee.profile.purchase.data') }}",
            type: "GET",
            data: {
                filter: filter,
                id: employeId
            },
            success: function(response) {
                const purchases = response.purchase;
                // Sales Summary Table
                const summaryRow = `
                   <tr>
                        <td>${purchases.reduce((sum, purchase) => sum + parseFloat(purchase.sub_total || 0), 0).toFixed(2)}</td>
                        <td>${purchases.reduce((sum, purchase) => sum + parseFloat(purchase.discount_amount || 0), 0).toFixed(2)}</td>
                        <td>${purchases.reduce((sum, purchase) => sum + parseFloat(purchase.paid || 0), 0).toFixed(2)}</td>
                        <td>${purchases.reduce((sum, purchase) => sum + parseFloat(purchase.due || 0), 0).toFixed(2)}</td>
                    </tr>
                    `;
                $('#purchase-summary-table tbody').html(summaryRow);

                // Sales Details Table
                let detailsRows = '';
                purchases.forEach(purchase => {
                    detailsRows += `
                        <tr>
                            <td>${purchase.invoice}</td>
                            <td>${purchase.sub_total}</td>
                            <td>${purchase.purchase_date}</td>
                            <td>${purchase.discount_amount ?? 0}</td>
                            <td>${purchase.paid}</td>
                            <td>${purchase.due}</td>
                        </tr>`;
                });
                $('#purchase-details-table tbody').html(detailsRows);
            },
            error: function() {
                alert('Failed to fetch Sale data.');
            }
        });
    }
    loadPurchaseData('today');

// Dropdown filter event
$('.purchaseDays').on('click', function() {
    const filter = $(this).text().toLowerCase();
    loadPurchaseData(filter);
});
        </script>

@endsection
