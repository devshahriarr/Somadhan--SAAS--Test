@extends('master')
@section('title', '| Dashboard')
@section('admin')

    {{-- ///// css style design //// --}}
    <style>
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

        }
    </style>
    @php
        use Carbon\Carbon;
        // --------------------///// Total summary ////----------------------------//
        $sales = App\Models\Sale::all();
        $purchase = App\Models\Purchase::all();
        $expanse = App\Models\Expense::all();
        $balance = App\Models\AccountTransaction::all();
    @endphp
    {{-- /////// ToTal Summary ////// --}}
    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow-1">
                <h3 class="mb-3">Total Summary</h3>
                <div class="col-md-4 col-xl-4 col-6 new-margin grid-margin stretch-card">
                    <div class="card" style="">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-5 p-0 d-flex align-items-center">
                                    <img src="uploads/dashboard/Artboard1@300x-100.jpg" height="50" width="50"
                                        alt="Image" style="border-radius:5px">
                                </div>
                                <div class="col-md-8 col-7 d-flex align-items-center">
                                    <div class="">
                                        <h5 class="responsive-text mar-1">{{ $sales->sum('total') }}
                                            <span>({{ $sales->count() }})</span>
                                        </h5>
                                        <h5>{{ $sales->sum('paid') }}</h5>
                                        <p>{{ $sales->sum('due') }}</p>
                                        <h6 class="text-1 mb-0">Invoice</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4  col-xl-4 col-6  grid-margin stretch-card">
                    <div class="card" style="">
                        <div class="card-body">
                            <div class="row">
                                <div class=" col-md-4 col-5 d-flex align-items-center">
                                    <img src="uploads/dashboard/Artboard5@300x-100.jpg" height="60px" width="60px"
                                        alt="Image" style="border-radius:5px">
                                </div>
                                <div class="col-md-8 col-7 d-flex align-items-center">
                                    <div>
                                        <h4 class="responsive-text mar-1">{{ $purchase->sum('sub_total') }}
                                            <span>({{ $purchase->count() }})</span>
                                        </h4>
                                        <h5>{{ $purchase->sum('paid') }}</h5>
                                        <h6 class="text-1 mb-0">Purchase</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-xl-4 col-6 grid-margin stretch-card">
                    <div class="card" style="">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-5 d-flex align-items-center">
                                    <img src="uploads/dashboard/Artboard3@300x-100.jpg" height="60px" width="60px"
                                        alt="Image" style="border-radius:5px">
                                </div>
                                <div class="col-md-8 col-7 d-flex align-items-center">
                                    <div>
                                        <h4 class="responsive-text mar-1">{{ $expanse->sum('amount') }}</h4>
                                        <h6 class="text-1 mb-0">Expanse</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-xl-3 col-6 new-margin grid-margin stretch-card">
                    <div class="card" style="">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-5 d-flex align-items-center">
                                    <img src="uploads/dashboard/Artboard4@300x-100.jpg" height="60px" width="60px"
                                        alt="Image" style="border-radius:5px">
                                </div>
                                <div class="col-md-8 col-7 d-flex align-items-center">
                                    <div>
                                        <h4 class="responsive-text mar-1">{{ $sales->sum('profit') }}</h4>
                                        <h6 class="text-1 mb-0">Profit</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- /////// End Total Summary ////// --}}

    @php
        // --------------------///// Today summary Calculation ////----------------------------//

        $todaySales = App\Models\Sale::whereDate('created_at', Carbon::now())->get();
        $todayPurchase = App\Models\Purchase::whereDate('created_at', Carbon::now())->get();
        $todayExpanse = App\Models\Expense::whereDate('created_at', Carbon::now())->get();

        $todayBalance = App\Models\AccountTransaction::whereDate('created_at', Carbon::now())->get();
        $yesterdayBalance = App\Models\AccountTransaction::whereDate('created_at', Carbon::yesterday())->get();
    @endphp
    {{-- ///////Today Summary ////// --}}
    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow-1">
                <h3 class="mb-3">Today Summary</h3>
                <div class="col-md-3  col-xl-3 col-6  grid-margin stretch-card">
                    <div class="card" style="">
                        <div class="card-body">
                            <div class="row">
                                <div class=" col-md-4 col-5 d-flex align-items-center">
                                    <img src="uploads/dashboard/Artboard1@300x-100.jpg" height="60px" width="60px"
                                        alt="Image" style="border-radius:5px">
                                </div>
                                <div class="col-md-8 col-7 d-flex align-items-center">
                                    <div>
                                        <h4 class="responsive-text mar-1">{{ $todaySales->sum('total') }}
                                            <span>({{ $todaySales->count() }})</span>
                                        </h4>
                                        <h5>{{ $todaySales->sum('paid') }}</h5>
                                        <h6 class="text-1 mb-0">Invoice</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3  col-xl-3 col-6  grid-margin stretch-card">
                    <div class="card" style="">
                        <div class="card-body">
                            <div class="row">
                                <div class=" col-md-4 col-5 d-flex align-items-center">
                                    <img src="uploads/dashboard/Artboard 2@300x-100.jpg" height="60px" width="60px"
                                        alt="Image" style="border-radius:5px">
                                </div>
                                <div class="col-md-8 col-7 d-flex align-items-center">
                                    <div>
                                        <h4 class="responsive-text mar-1">{{ $todayPurchase->sum('sub_total') }}
                                            <span>({{ $todayPurchase->count() }})</span>
                                        </h4>
                                        <h5>{{ $todayPurchase->sum('paid') }}</h5>
                                        <h6 class="text-1 mb-0">Purchase</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-xl-3 col-6 grid-margin stretch-card">
                    <div class="card" style="">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-5 d-flex align-items-center">
                                    <img src="uploads/dashboard/Artboard3@300x-100.jpg" height="60px" width="60px"
                                        alt="Image" style="border-radius:5px">
                                </div>
                                <div class="col-md-8 col-7 d-flex align-items-center">
                                    <div>
                                        <h4 class="responsive-text mar-1">{{ $todayExpanse->sum('amount') }}</h4>
                                        <h6 class="text-1 mb-0">Expanse</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-xl-3 col-6  grid-margin stretch-card">
                    <div class="card" style="">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-5 d-flex align-items-center">
                                    <img src="uploads/dashboard/Artboard5@300x-100.jpg" height="60px" width="60px"
                                        alt="Image" style="border-radius:5px">
                                </div>
                                <div class="col-md-8 col-7 d-flex align-items-center">
                                    <div>
                                        <h4 class="responsive-text mar-1">{{ $todayBalance->sum('balance') }}</h4>
                                        <h6 class="text-1 mb-0">Balance</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="col-md-3  col-xl-3 col-6  grid-margin stretch-card">
                    <div class="card" style="">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">

                            </div>
                            <div class="row">
                                <div class="col-md-8 col-12 p-0">
                                    <p>Total Sales : {{ $mysales->sum('final_receivable') }}</p>
                                    <p>Paid : {{ $mysales->sum('paid') }}</p>
                                    <p>Due: {{ $mysales->sum('due') }}</p>
                                    <p>----------------------------------</p>
                                    <p>Total Purchase : {{ $mypurchase->sum('grand_total') }}</p>
                                    <p>Paid : {{ $mypurchase->sum('paid') }}</p>
                                    <p>Due : {{ $mypurchase->sum('due') }}</p>
                                    <p>----------------------------------</p>
                                    <p>Total Expenses : {{ $myexpenses->sum('amount') }}</p>
                                    <p>Total Balance : {{ $totalAccountBalance->sum('balance') }}</p>
                                    <p>Yesterday Balance : {{ $yesterdayTotalAccountBalance->sum('balance') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
    {{-- ///////End Today Summary ////// --}}


    {{-- //////Revenew Chart Start /////// --}}
    <div class="row">
        @php
            $salesByDay = [];
            $salesProfitByDay = [];
            $purchaseByDay = [];

            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->toDateString();
                $dailySales = App\Models\Sale::whereDate('sale_date', $date)->sum('receivable');
                $dailyProfit = App\Models\Sale::whereDate('sale_date', $date)->sum('profit');
                $dailyPurchase = App\Models\Purchase::whereDate('purchase_date', $date)->sum('grand_total');

                $salesByDay[$date] = $dailySales;
                $salesProfitByDay[$date] = $dailyProfit;
                $purchaseByDay[$date] = $dailyPurchase;
            }
        @endphp
        <div class="col-xl-6 grid-margin stretch-card">

            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Daily Profit</h6>
                    <div id="apexLine1"></div>
                </div>
            </div>
        </div>
        @php
            // Fetching the latest 5 bank records
            $banks = App\Models\Bank::take(5)->get();

            // Array to store total transaction amounts for each bank
            $totalTransactionAmounts = [];

            // Loop through each bank to calculate total transaction amount
            foreach ($banks as $bank) {
                $totalTransactionAmount = App\Models\AccountTransaction::where('account_id', $bank->id)
                    ->where('balance', '>', 0)
                    ->sum('balance');
                // array_push(floatval($totalTransactionAmounts), floatval($totalTransactionAmount));
                array_push($totalTransactionAmounts, floatval($totalTransactionAmount));
            }
        @endphp
        <div class="col-xl-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Banking Details</h6>
                    <div id="apexPie1"></div>
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
                series: [{
                        name: "Weekly Sale",
                        data: [
                            @foreach ($salesByDay as $date => $dailySales)
                                {{ $dailySales }},
                            @endforeach
                        ]
                    },
                    {
                        name: "Weekly Profit",
                        data: [
                            @foreach ($salesProfitByDay as $date => $dailyProfit)
                                {{ $dailyProfit }},
                            @endforeach
                        ]
                    },
                    {
                        name: "Weekly Purchase",
                        data: [
                            @foreach ($purchaseByDay as $date => $dailyPurchase)
                                {{ $dailyPurchase }},
                            @endforeach
                        ]
                    }
                ],
                xaxis: {
                    type: "datetime",
                    categories: [
                        @foreach ($salesByDay as $date => $salesCount)
                            '{{ $date }}',
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
            var apexLineChart = new ApexCharts(document.querySelector("#apexLine1"), lineChartOptions);
            apexLineChart.render();


            // pie chart 
            var options = {
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
                colors: [colors.primary, colors.warning, colors.danger, colors.info, colors.success],
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
                series: [
                    @foreach ($totalTransactionAmounts as $element)
                        {{ $element }},
                    @endforeach
                ],
                labels: [
                    @foreach ($banks as $bank)
                        '{{ $bank->name }}',
                    @endforeach
                ],
            };

            var chart = new ApexCharts(document.querySelector("#apexPie1"), options);
            chart.render();
        });
    </script>
    {{-- /// pie chart end /// --}}
    <br>
    {{-- total chart  --}}
    @php
        $salesByMonth = [];
        $profitsByMonth = [];
        $purchasesByMonth = [];

        for ($i = 0; $i < 12; $i++) {
            $monthStart = now()->subMonths($i)->startOfMonth();
            $monthEnd = now()->subMonths($i)->endOfMonth();

            $monthlySales = App\Models\Sale::whereBetween('sale_date', [$monthStart, $monthEnd])->sum('receivable');
            $monthlyProfit = App\Models\Sale::whereBetween('sale_date', [$monthStart, $monthEnd])->sum('profit');
            $monthlyPurchase = App\Models\Purchase::whereBetween('purchase_date', [$monthStart, $monthEnd])->sum(
                'grand_total',
            );

            $salesByMonth[$monthStart->format('Y-m')] = $monthlySales;
            $profitsByMonth[$monthStart->format('Y-m')] = $monthlyProfit;
            $purchasesByMonth[$monthStart->format('Y-m')] = $monthlyPurchase;
        }

    @endphp
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
