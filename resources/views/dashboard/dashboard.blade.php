@extends('master')
@section('title', '| Dashboard')
@section('admin')

    {{-- ///// css style design //// --}}
    <style>
        .summary_table {
            font-size: 12px !important;
        }

        @media (max-width: 768px) {
            .responsive-text {
                font-size: 1rem;
                /* Adjust as needed for medium screens */
            }

            .grid-margin {
                margin-bottom: .7rem;
                padding-right: 6px;
                padding-left: 6px;
            }

            .new-margin {
                padding-right: 0px !important;
            }

            .text-1 {
                font-size: 13px
            }

            .mar-1 {
                margin-bottom: 4px
            }

            .table {
                border-bottom-width: 0 !important;
            }
        }
    </style>

    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow-1">
                @include('dashboard.dashboard-card') <!-----Aded Card page---->
                {{-- ///////Today Summary ////// --}}
                @if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin')
                    @foreach ($branchData as $branchId => $data)
                        <div class="col-md-12 col-xl-6 col-12  grid-margin stretch-card">
                            <div class="card" style="">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $data['branch']->name }} Today Summary</h6>
                                    <table class="table summary_table">
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
                                                <td class="text-end">{{ number_format($data['previousDayBalance'], 2) }}
                                                </td>
                                                <td>Salary</td>
                                                <td class="text-end">
                                                    {{ number_format($data['todayEmployeeSalary'], 2) }}
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>Paid Sales</td>
                                                <td class="text-end">{{ number_format($data['todaySales'], 2) }}</td>
                                                <td>Purchase</td>
                                                <td class="text-end">{{ number_format($data['todayPurchase'], 2) }}</td>

                                            </tr>
                                            <tr>
                                                <td>Due Collection</td>
                                                <td class="text-end">{{ number_format($data['dueCollection'], 2) }}</td>
                                                <td>Due Paid</td>
                                                <td class="text-end">{{ number_format($data['purchaseDuePay'], 2) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Other Deposit</td>
                                                <td class="text-end">{{ number_format($data['otherCollection'], 2) }}
                                                </td>
                                                <td>Other Withdraw</td>
                                                <td class="text-end">{{ number_format($data['otherPaid'], 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Adjust Due Collcetion</td>
                                                <td class="text-end">
                                                    {{ number_format($data['adjustDueCollection'], 2) }}
                                                </td>
                                                <td>Return</td>
                                                <td class="text-end">
                                                    {{ number_format($data['todayReturnAmount'], 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Add Balance</td>
                                                <td class="text-end">{{ number_format($data['addBalance'], 2) }}</td>
                                                <td>Expanse</td>
                                                <td class="text-end">{{ number_format($data['todayExpanse'], 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Via Sale</td>
                                                <td class="text-end">{{ number_format($data['viaSale'], 2) }}</td>
                                                <td>Via Purchase</td>
                                                <td class="text-end">{{ number_format($data['viaPayment'], 2) }}</td>
                                            </tr>

                                            <tr>
                                                <td>Total</td>
                                                <td class="text-end">{{ number_format($data['totalIngoing'], 2) }}</td>
                                                <td>Total</td>
                                                <td class="text-end">{{ number_format($data['totalOutgoing'], 2) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3">Total Balance</th>
                                                <th class="text-end">
                                                    {{ number_format($data['totalIngoing'] - $data['totalOutgoing'], 2) }}
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if (count($branchData) > 1)
                        <div class="col-md-12 col-xl-6 col-12  grid-margin stretch-card">
                            <div class="card" style="">
                                <div class="card-body">
                                    <h6 class="card-title">Today Total Summary</h6>
                                    <table class="table summary_table">
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
                                                <td class="text-end">{{ number_format($previousDayTotalBalance, 2) }}
                                                </td>
                                                <td>Salary</td>
                                                <td class="text-end">{{ number_format($todayTotalEmployeeSalary, 2) }}
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>Paid Sales</td>
                                                <td class="text-end">{{ number_format($todayTotalSales, 2) }}</td>
                                                <td>Purchase</td>
                                                <td class="text-end">{{ number_format($todayTotalPurchase, 2) }}</td>

                                            </tr>
                                            <tr>
                                                <td>Due Collection</td>
                                                <td class="text-end">{{ number_format($todayTotalDueCollection, 2) }}
                                                </td>
                                                <td>Due Paid</td>
                                                <td class="text-end">{{ number_format($todayTotalPurchaseDuePay, 2) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Other Deposit</td>
                                                <td class="text-end">{{ number_format($todayTotalOtherCollection, 2) }}
                                                </td>
                                                <td>Other Withdraw</td>
                                                <td class="text-end">{{ number_format($todayTotalOtherPaid, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Adjust Due Collcetion</td>
                                                <td class="text-end">
                                                    {{ number_format($todayTotalAdjustDueCollection, 2) }}
                                                </td>
                                                <td>Return</td>
                                                <td class="text-end">
                                                    {{ number_format($todayTotalReturnAmount, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Add Balance</td>
                                                <td class="text-end">{{ number_format($todayTotalAddBalance, 2) }}</td>
                                                <td>Expanse</td>
                                                <td class="text-end">{{ number_format($todayTotalExpanse, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Via Sale</td>
                                                <td class="text-end">{{ number_format($todayTotalViaSale, 2) }}</td>
                                                <td>Via Purchase</td>
                                                <td class="text-end">{{ number_format($todayTotalViaPayment, 2) }}</td>
                                            </tr>

                                            <tr>
                                                <td>Total</td>
                                                <td class="text-end">{{ number_format($todayTotalIngoing, 2) }}</td>
                                                <td>Total</td>
                                                <td class="text-end">{{ number_format($todayTotalOutgoing, 2) }}</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3">Total Balance</th>
                                                <th class="text-end">
                                                    {{ number_format($todayTotalIngoing - $todayTotalOutgoing, 2) }}
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="col-md-12 col-xl-6 col-12  grid-margin stretch-card">
                        <div class="card" style="">
                            <div class="card-body">
                                <h6 class="card-title">Today Summary</h6>
                                <table class="table summary_table">
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
                                            <td class="text-end">{{ number_format($previousDayBalance, 2) }}</td>
                                            <td>Salary</td>
                                            <td class="text-end">{{ number_format($todayEmployeeSalary->sum('debit'), 2) }}
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>Paid Sales</td>
                                            <td class="text-end">{{ number_format($todaySales, 2) }}</td>
                                            <td>Purchase</td>
                                            <td class="text-end">{{ number_format($todayPurchase->sum('paid'), 2) }}</td>

                                        </tr>
                                        <tr>
                                            <td>Due Collection</td>
                                            <td class="text-end">{{ number_format($dueCollection->sum('credit'), 2) }}</td>
                                            <td>Due Paid</td>
                                            <td class="text-end">{{ number_format($purchaseDuePay->sum('debit'), 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Other Deposit</td>
                                            <td class="text-end">{{ number_format($otherCollection->sum('credit'), 2) }}
                                            </td>
                                            <td>Other Withdraw</td>
                                            <td class="text-end">{{ number_format($otherPaid->sum('debit'), 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Adjust Due Collcetion</td>
                                            <td class="text-end">
                                                {{ number_format($adjustDueCollection, 2) }}
                                            </td>
                                            <td>Return</td>
                                            <td class="text-end">
                                                {{ number_format($todayReturnAmount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Add Balance</td>
                                            <td class="text-end">{{ number_format($addBalance->sum('credit'), 2) }}</td>
                                            <td>Expanse</td>
                                            <td class="text-end">{{ number_format($todayExpanse->sum('amount'), 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Via Sale</td>
                                            <td class="text-end">{{ number_format($viaSale->sum('sub_total'), 2) }}</td>
                                            <td>Via Purchase</td>
                                            <td class="text-end">{{ number_format($viaPayment->sum('debit'), 2) }}</td>
                                        </tr>

                                        <tr>
                                            <td>Total</td>
                                            <td class="text-end">{{ number_format($totalIngoing, 2) }}</td>
                                            <td>Total</td>
                                            <td class="text-end">{{ number_format($totalOutgoing, 2) }}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3">Total Balance</th>
                                            <th class="text-end">{{ number_format($totalIngoing - $totalOutgoing, 2) }}
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ///////End Today Summary ////// --}}
                @php
                 if(Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin'){
                    $serviceSale = App\Models\ServiceSale::all();
                 }else{
                    $serviceSale = App\Models\ServiceSale::where('branch_id', Auth::user()->branch_id)->get();
                 }
                @endphp
                {{-- /////// ToTal Summary ////// --}}
                <div class="col-md-12 col-xl-6 col-12 new-margin grid-margin stretch-card">
                    <div class="card" style="">
                        <div class="card-body">
                            <h6 class="card-title">Total Summary</h6>
                            <table class="table border-none summary_table">
                                <thead>
                                    <tr>
                                        <th>Summary</th>
                                        <th class="text-end">Total</th>
                                        <th class="text-end">Paid</th>
                                        <th class="text-end">Due</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Sales</td>
                                        <td class="text-end">{{ number_format($sales->sum('change_amount'), 2) }}</td>
                                        <td class="text-end">{{ number_format($sales->sum('paid'), 2) }}</td>
                                        <td class="text-end">
                                            {{ number_format($totalCustomerDue > 0 ? $totalCustomerDue : 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Services Sales</td>
                                        <td class="text-end">{{ number_format($serviceSale->sum('grand_total'), 2) }}</td>
                                        <td class="text-end">{{ number_format($serviceSale->sum('paid'), 2) }}</td>
                                        <td class="text-end">{{ number_format($serviceSale->sum('due'), 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Purchase</td>
                                        <td class="text-end">{{ number_format($purchase->sum('sub_total'), 2) }}</td>
                                        <td class="text-end">{{ number_format($purchase->sum('paid'), 2) }}</td>
                                        <td class="text-end">
                                            {{ number_format($totalSupplierDue > 0 ? $totalSupplierDue : 0, 2) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Exapnse</td>
                                        <td class="text-end">{{ number_format($expanse->sum('amount'), 2) }}</td>
                                        <td class="text-end">0.00</td>
                                        <td class="text-end">0.00</td>
                                    </tr>
                                    <tr>
                                        <td>Salary</td>
                                        <td class="text-end">{{ number_format($salary->sum('debit'), 2) }}</td>
                                        <td class="text-end">0.00</td>
                                        <td class="text-end">0.00</td>
                                    </tr>
                                    <tr>
                                        <td>Balance</td>
                                        <td class="text-end">{{ number_format($grandTotal, 2) }}</td>
                                        <td class="text-end">0.00</td>
                                        <td class="text-end">0.00</td>
                                    </tr>
                                    @php
                                        $products = App\Models\Product::withSum(
                                            [
                                                'stockQuantity as stock_quantity_sum' => function ($query) {
                                                    $query->where('branch_id', Auth::user()->branch_id);
                                                },
                                            ],
                                            'stock_quantity',
                                        )
                                            ->orderBy('stock_quantity_sum', 'asc') // or 'desc' for descending order
                                            ->get();
                                        //Show Stock Value
                                        $products->each(function ($product) {
                                            $product->total_stock_value = $product->cost * $product->stock_quantity_sum;
                                        });
                                        //Total stock Value
                                        $totalStockValueSum = $products->sum('total_stock_value');
                                    @endphp

                                    <tr>
                                        <td>Stock Value</td>
                                        <td class="text-end">{{ $totalStockValueSum }}</td>
                                        <td class="text-end">0.00</td>
                                        <td class="text-end">0.00</td>
                                    </tr>
                                    <tr>
                                        <td>Sales Profit</td>
                                        <td class="text-end">{{ number_format($sales->sum('profit'), 2) }}</td>
                                        <td class="text-end">0.00</td>
                                        <td class="text-end">0.00</td>
                                    </tr>
                                    <tr>
                                        <td>Total Bonus</td>
                                        <td class="text-end">{{ number_format($totalBonus, 2) }}</td>
                                        <td class="text-end">0.00</td>
                                        <td class="text-end">0.00</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- /////// End Total Summary ////// --}}
            </div>
        </div>
    </div>

    {{-- //////Revenew Chart Start /////// --}}
    <div class="row">
        <div class="col-xl-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Daily Profit</h6>
                    <div id="apexLine1"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Banking Details</h6>
                    <div id="apexPie1"></div>
                    <div id="totalAmount" style="text-align: center; margin-top: 20px;">
                        <h5>Total: {{ number_format($grandTotal, 2) }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // $(document).ready(function() {
        //     // var colors = {
        //     //     primary: "#6571ff",
        //     //     secondary: "#7987a1",
        //     //     success: "#05a34a",
        //     //     info: "#66d1d1",
        //     //     warning: "#fbbc06",
        //     //     danger: "#ff3366",
        //     //     light: "#e9ecef",
        //     //     dark: "#060c17",
        //     //     muted: "#7987a1",
        //     //     gridBorder: "rgba(77, 138, 240, .15)",
        //     //     bodyColor: "#b8c3d9",
        //     //     cardBg: "#0c1427"
        //     // }

        //     // var fontFamily = "'Roboto', Helvetica, sans-serif"

        //     // var lineChartOptions = {
        //     //     chart: {
        //     //         type: "line",
        //     //         height: '320',
        //     //         parentHeightOffset: 0,
        //     //         foreColor: colors.bodyColor,
        //     //         background: colors.cardBg,
        //     //         toolbar: {
        //     //             show: false
        //     //         },
        //     //     },
        //     //     theme: {
        //     //         mode: 'dark'
        //     //     },
        //     //     tooltip: {
        //     //         theme: 'dark'
        //     //     },
        //     //     colors: [colors.primary, colors.danger, colors.warning],
        //     //     grid: {
        //     //         padding: {
        //     //             bottom: -4
        //     //         },
        //     //         borderColor: colors.gridBorder,
        //     //         xaxis: {
        //     //             lines: {
        //     //                 show: true
        //     //             }
        //     //         }
        //     //     },
        //     //     series: [{
        //     //             name: "Weekly Sale",
        //     //             data: [
        //     //                 @foreach ($salesByDay as $date => $dailySales)
        //     //                     {{ $dailySales }},
        //     //                 @endforeach
        //     //             ]
        //     //         },
        //     //         {
        //     //             name: "Weekly Profit",
        //     //             data: [
        //     //                 @foreach ($salesProfitByDay as $date => $dailyProfit)
        //     //                     {{ $dailyProfit }},
        //     //                 @endforeach
        //     //             ]
        //     //         },
        //     //         {
        //     //             name: "Weekly Purchase",
        //     //             data: [
        //     //                 @foreach ($purchaseByDay as $date => $dailyPurchase)
        //     //                     {{ $dailyPurchase }},
        //     //                 @endforeach
        //     //             ]
        //     //         }
        //     //     ],
        //     //     xaxis: {
        //     //         type: "datetime",
        //     //         categories: [
        //     //             @foreach ($salesByDay as $date => $salesCount)
        //     //                 '{{ $date }}',
        //     //             @endforeach
        //     //         ],
        //     //         lines: {
        //     //             show: true
        //     //         },
        //     //         axisBorder: {
        //     //             color: colors.gridBorder,
        //     //         },
        //     //         axisTicks: {
        //     //             color: colors.gridBorder,
        //     //         },
        //     //     },
        //     //     markers: {
        //     //         size: 0,
        //     //     },
        //     //     legend: {
        //     //         show: true,
        //     //         position: "top",
        //     //         horizontalAlign: 'center',
        //     //         fontFamily: fontFamily,
        //     //         itemMargin: {
        //     //             horizontal: 8,
        //     //             vertical: 0
        //     //         },
        //     //     },
        //     //     stroke: {
        //     //         width: 3,
        //     //         curve: "smooth",
        //     //         lineCap: "round"
        //     //     },
        //     // };
        //     // var apexLineChart = new ApexCharts(document.querySelector("#apexLine1"), lineChartOptions);
        //     // apexLineChart.render();
        //     // // pie chart
        //     // var bankLabels = @json($bankLabels);
        //     // var options = {
        //     //     chart: {
        //     //         height: 300,
        //     //         type: "pie",
        //     //         foreColor: colors.bodyColor,
        //     //         background: colors.cardBg,
        //     //         toolbar: {
        //     //             show: false
        //     //         },
        //     //     },
        //     //     theme: {
        //     //         mode: 'dark'
        //     //     },
        //     //     tooltip: {
        //     //         theme: 'dark'
        //     //     },
        //     //     colors: [colors.primary, colors.warning, colors.danger, colors.success, colors.info, colors
        //     //         .secondary, colors.dark
        //     //     ],
        //     //     legend: {
        //     //         show: true,
        //     //         position: "top",
        //     //         horizontalAlign: 'center',
        //     //         fontFamily: fontFamily,
        //     //         itemMargin: {
        //     //             horizontal: 8,
        //     //             vertical: 0
        //     //         },
        //     //     },
        //     //     stroke: {
        //     //         colors: ['rgba(0,0,0,0)']
        //     //     },
        //     //     dataLabels: {
        //     //         enabled: false
        //     //     },
        //     //     series: bankLabels.map(function(label) {
        //     //         return parseFloat(label.amount.replace(/,/g, '')); // Convert amount string to float
        //     //     }),
        //     //     labels: bankLabels.map(function(label) {
        //     //         return label.name;
        //     //     })
        //     // };

        //     // var chart = new ApexCharts(document.querySelector("#apexPie1"), options);
        //     // chart.render();
        // });
        $(document).ready(function() {
    var colors = {
        primary: "#6571ff",
        secondary: "#7987a1",
        success: "#05a34a",
        info: "#66d1d1",
        warning: "#fbbc06",
        danger: "#ff3366",
        light: "#e9ecef",
        dark: "#060c17",
        muted: "#7987a1",
        gridBorder: "rgba(77, 138, 240, .15)",
        bodyColor: "#b8c3d9",
        cardBg: "#0c1427"
    };

    var fontFamily = "'Roboto', Helvetica, sans-serif";

    // Pie Chart
    var bankLabels = @json($bankLabels);
    var pieData = bankLabels.map(function(label) {
        return parseFloat(label.amount.replace(/,/g, '')); // Convert amount string to float
    });
    var pieLabels = bankLabels.map(function(label) {
        return label.name;
    });

    if (pieData.length === 0 || pieData.every(val => val === 0)) {
        pieData = [1]; // Show a single slice for "No Data"
        pieLabels = ["No Data"];
        colors.primary = "#e0e0e0"; // Neutral color for empty data
    }

    var pieOptions = {
        chart: {
            height: 300,
            type: "pie",
            foreColor: colors.bodyColor,
            background: colors.cardBg,
            toolbar: {
                show: false
            },
        },
        theme: {
            mode: 'dark'
        },
        tooltip: {
            theme: 'dark'
        },
        colors: [colors.primary, colors.warning, colors.danger, colors.success, colors.info, colors.secondary, colors.dark],
        legend: {
            show: true,
            position: "top",
            horizontalAlign: 'center',
            fontFamily: fontFamily,
            itemMargin: {
                horizontal: 8,
                vertical: 0
            },
        },
        stroke: {
            colors: ['rgba(0,0,0,0)']
        },
        dataLabels: {
            enabled: false
        },
        series: pieData,
        labels: pieLabels,
    };

    var pieChart = new ApexCharts(document.querySelector("#apexPie1"), pieOptions);
    pieChart.render();

    // Line Chart
    var weeklySaleData = [
        @foreach ($salesByDay as $date => $dailySales)
            {{ $dailySales }},
        @endforeach
    ];
    var weeklyProfitData = [
        @foreach ($salesProfitByDay as $date => $dailyProfit)
            {{ $dailyProfit }},
        @endforeach
    ];
    var weeklyPurchaseData = [
        @foreach ($purchaseByDay as $date => $dailyPurchase)
            {{ $dailyPurchase }},
        @endforeach
    ];
    var categories = [
        @foreach ($salesByDay as $date => $salesCount)
            '{{ $date }}',
        @endforeach
    ];

    var lineChartOptions = {
        chart: {
            type: "line",
            height: '320',
            parentHeightOffset: 0,
            foreColor: colors.bodyColor,
            background: colors.cardBg,
            toolbar: {
                show: false
            },
        },
        theme: {
            mode: 'dark'
        },
        tooltip: {
            theme: 'dark'
        },
        colors: [colors.primary, colors.danger, colors.warning],
        grid: {
            padding: {
                bottom: -4
            },
            borderColor: colors.gridBorder,
            xaxis: {
                lines: {
                    show: true
                }
            }
        },
        series: [
            {
                name: "Weekly Sale",
                data: weeklySaleData.length > 0 ? weeklySaleData : [0],
            },
            {
                name: "Weekly Profit",
                data: weeklyProfitData.length > 0 ? weeklyProfitData : [0],
            },
            {
                name: "Weekly Purchase",
                data: weeklyPurchaseData.length > 0 ? weeklyPurchaseData : [0],
            }
        ],
        xaxis: {
            type: "datetime",
            categories: categories.length > 0 ? categories : ["No Data"],
            lines: {
                show: true
            },
            axisBorder: {
                color: colors.gridBorder,
            },
            axisTicks: {
                color: colors.gridBorder,
            },
        },
        markers: {
            size: 0,
        },
        legend: {
            show: true,
            position: "top",
            horizontalAlign: 'center',
            fontFamily: fontFamily,
            itemMargin: {
                horizontal: 8,
                vertical: 0
            },
        },
        stroke: {
            width: 3,
            curve: "smooth",
            lineCap: "round"
        },
    };

    var lineChart = new ApexCharts(document.querySelector("#apexLine1"), lineChartOptions);
    lineChart.render();
});

    </script>
    {{-- /// pie chart end /// --}}
    <br>
    {{-- total chart  --}}

    <div class="row">
        <div class="col-xl-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Monthly Profit</h6>
                    <div id="apexLine2"></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            var colors = {
                primary: "#6571ff",
                secondary: "#7987a1",
                success: "#05a34a",
                info: "#66d1d1",
                warning: "#fbbc06",
                danger: "#ff3366",
                light: "#e9ecef",
                dark: "#060c17",
                muted: "#7987a1",
                gridBorder: "rgba(77, 138, 240, .15)",
                bodyColor: "#b8c3d9",
                cardBg: "#0c1427"
            }

            var fontFamily = "'Roboto', Helvetica, sans-serif"

            var lineChartOptions = {
                chart: {
                    type: "line",
                    height: '320',
                    parentHeightOffset: 0,
                    foreColor: colors.bodyColor,
                    background: colors.cardBg,
                    toolbar: {
                        show: false
                    },
                },
                theme: {
                    mode: 'dark'
                },
                tooltip: {
                    theme: 'dark'
                },
                colors: [colors.success, colors.info, colors.primary],
                grid: {
                    padding: {
                        bottom: -4
                    },
                    borderColor: colors.gridBorder,
                    xaxis: {
                        lines: {
                            show: true
                        }
                    }
                },
                series: [{
                        name: "Monthly Sale",
                        data: [
                            @foreach ($salesByMonth as $month => $monthlySales)
                                {{ $monthlySales }},
                            @endforeach
                        ]
                    },
                    {
                        name: "Monthly Profit",
                        data: [
                            @foreach ($profitsByMonth as $month => $monthlyProfit)
                                {{ $monthlyProfit }},
                            @endforeach
                        ]
                    },
                    {
                        name: "Monthly Purchase",
                        data: [
                            @foreach ($purchasesByMonth as $month => $monthlyPurchase)
                                {{ $monthlyPurchase }},
                            @endforeach
                        ]
                    }
                ],
                xaxis: {
                    type: "datetime",
                    categories: [
                        @foreach ($salesByMonth as $month => $salesCount)
                            '{{ $month }}-01',
                        @endforeach
                    ],
                    lines: {
                        show: true
                    },
                    axisBorder: {
                        color: colors.gridBorder,
                    },
                    axisTicks: {
                        color: colors.gridBorder,
                    },
                },
                markers: {
                    size: 0,
                },
                legend: {
                    show: true,
                    position: "top",
                    horizontalAlign: 'center',
                    fontFamily: fontFamily,
                    itemMargin: {
                        horizontal: 8,
                        vertical: 0
                    },
                },
                stroke: {
                    width: 3,
                    curve: "smooth",
                    lineCap: "round"
                },
            };
            var apexLineChart = new ApexCharts(document.querySelector("#apexLine2"), lineChartOptions);
            apexLineChart.render();
        });
    </script>
@endsection
