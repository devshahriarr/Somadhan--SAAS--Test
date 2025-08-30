@extends('master')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Monthly Report</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Monthly Report</h6>
                    <div class="table-responsive">
                        <table id="example" class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Total Incoming</th>
                                    <th>Total Outgoing</th>
                                    <th>Balance</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $key = 1;
                                @endphp
                                @foreach ($dailyReports as $report)
                                    <tr>
                                        <td>{{ $key++ }}</td>
                                        <td>{{ $report['date'] }}</td>
                                        <td>{{ number_format($report['totalIngoing'], 2) }}</td>
                                        <td>{{ number_format($report['totalOutgoing'], 2) }}</td>
                                        <td>{{ number_format($report['totalBalance'], 2) }}</td>
                                        <td>
                                            <button type="button" value="{{ $report['id'] }}"
                                                class="btn btn-primary view_details" data-bs-toggle="modal"
                                                data-bs-target="#details_modal">
                                                View Details
                                            </button>
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

    <!-- Details Modal -->
    <div class="modal fade" id="details_modal" tabindex="-1" aria-labelledby="exampleModalScrollableTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle"><span class="date"></span> Reports</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary print_btn">Print</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            $(document).on('click', '.view_details', function(e) {
                e.preventDefault();
                let id = $(this).val();
                // alert(id);
                $.ajax({
                    url: '/report/monthly/view/' + id,
                    method: 'GET',
                    success: function(res) {
                        const report = res.report;
                        // console.log(report);
                        $('.date').html(`${report.date}`);
                        $('.modal-body').html(`
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th colspan="2">Incomming</th>
                                        <th colspan="2">Outgoing</th>
                                    </tr>
                                    <tr>
                                        <th>Purpose</th>
                                        <th class="text-end">TK</th>
                                        <th>Purpose</th>
                                        <th class="text-end">TK</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Previous Day Balance</td>
                                        <td class="text-end">${report.previousDayBalance}</td>
                                        <td>Salary</td>
                                        <td class="text-end">${report.totalSalary}</td>
                                    </tr>
                                    <tr>
                                        <td>Paid Sales</td>
                                        <td class="text-end">${report.totalSale}</td>
                                        <td>Purchase</td>
                                        <td class="text-end">${report.totalPurchaseCost}</td>

                                    </tr>
                                    <tr>
                                        <td>Due Collection</td>
                                        <td class="text-end">${report.dueCollection}</td>
                                        <td>Due Paid</td>
                                        <td class="text-end">${report.purchaseDuePay}</td>
                                    </tr>
                                    <tr>
                                        <td>Other Deposit</td>
                                        <td class="text-end">${report.otherCollection}</td>
                                        <td>Other Withdraw</td>
                                        <td class="text-end">${report.otherPaid}</td>
                                    </tr>
                                    <tr>
                                        <td>Adjust Due Collcetion</td>
                                        <td class="text-end">${report.adjustDueCollection}</td>
                                        <td>Return</td>
                                        <td class="text-end">${report.todayReturnAmount}</td>
                                            00</td>
                                    </tr>
                                    <tr>
                                        <td>Add Balance</td>
                                        <td class="text-end">${report.addBalance}</td>
                                        <td>Expanse</td>
                                        <td class="text-end">${report.totalExpense}</td>
                                    </tr>
                                    <tr>
                                        <td>Via Sale</td>
                                        <td class="text-end">${report.viaSale}</td>
                                        <td>Via Purchase</td>
                                        <td class="text-end">${report.viaPayment}</td>
                                    </tr>

                                    <tr>
                                        <td>Total</td>
                                        <td class="text-end">${report.totalIngoing}</td>
                                        <td>Total</td>
                                        <td class="text-end">${report.totalOutgoing}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3">Total Balance</th>
                                        <td class="text-end">${report.totalBalance}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        `)
                    }
                })
            })

            $(document).on('click', '.print_btn', function(e) {
                e.preventDefault();
                // Get the modal content
                let printContents = document.querySelector('.modal-content').innerHTML;

                // Open a new window
                let originalContents = document.body.innerHTML;
                let printWindow = window.open('', '', 'height=600,width=800');

                // Write the modal content to the new window
                printWindow.document.write('<html><head><title>Print</title>');
                printWindow.document.write('</head><body>');
                printWindow.document.write(printContents);
                printWindow.document.write('</body></html>');

                $('.modal-footer').hide();

                // Print the contents of the new window
                printWindow.document.close();
                printWindow.print();
            });
        });
    </script>
@endsection
