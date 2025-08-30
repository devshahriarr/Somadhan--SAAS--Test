<div class="col-12 col-xl-12 stretch-card">
    <div class="row flex-grow-1">
        <h6 class="card-title"></h6> <br>
        @if (Auth::user()->can('branch.menu'))
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-baseline">
                        <h6 class="card-title mb-0">Total Branch</h6>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="mb-2">
                                {{ $totalBranch }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Total Customer Section -->
                        <div>
                            <h6 class=" mb-0">Total Customer</h6>
                            <h3 class="mb-2">{{ $totalCustomer }}</h3>
                        </div>
                        <!-- New Customer Section -->
                        <div>
                            <h6 class=" mb-0">New Customer</h6>
                            <h3 class="mb-2">{{ $todayCustomerCount }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Total Customer Section -->
                        <div>
                            <h6 class=" mb-0">Total Supplier</h6>
                            <h3 class="mb-2">{{ $totalSupplier }}</h3>
                        </div>
                        <!-- New Customer Section -->
                        <div>
                            <h6 class=" mb-0">New Supplier</h6>
                            <h3 class="mb-2">{{ $todaySupplierCount }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
          <!--------------------------------Total Sale ------------------------------------------->
          <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                        <h6 class="card-title mb-0">Total Sale</h6>
                        <div class="dropdown">
                            <a type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                <a class="dropdown-item totalSales d-flex align-items-center" href="javascript:;">
                                    <i data-feather="eye" class="icon-sm me-2"></i> <span>Today</span>
                                </a>
                                <a class="dropdown-item totalSales d-flex align-items-center" href="javascript:;">
                                    <i data-feather="eye" class="icon-sm me-2"></i> <span>Weekly</span>
                                </a>
                                <a class="dropdown-item totalSales d-flex align-items-center" href="javascript:;">
                                    <i data-feather="eye" class="icon-sm me-2"></i> <span>Monthly</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="mb-2 totalSaleAmount">
                            </h3>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!--------------------------------Paid Sale ------------------------------------------->
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                        <h6 class="card-title mb-0">Total Paid Sale</h6>
                        <div class="dropdown">
                            <a type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                <a class="dropdown-item paidSaleDays d-flex align-items-center" href="javascript:;">
                                    <i data-feather="eye" class="icon-sm me-2"></i> <span>Today</span>
                                </a>
                                <a class="dropdown-item paidSaleDays d-flex align-items-center" href="javascript:;">
                                    <i data-feather="eye" class="icon-sm me-2"></i> <span>Weekly</span>
                                </a>
                                <a class="dropdown-item paidSaleDays d-flex align-items-center" href="javascript:;">
                                    <i data-feather="eye" class="icon-sm me-2"></i> <span>Monthly</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="mb-2 paidSaleAmount">
                            </h3>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!--------------------------------Purchase ------------------------------------------->
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                        <h6 class="card-title mb-0">Total Purchase</h6>
                        <div class="dropdown">
                            <a type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
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
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="mb-2 purchaseAmount">
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--------------------------------Expanse ------------------------------------------->
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                        <h6 class="card-title mb-0">Total Expense</h6>
                        <div class="dropdown">
                            <a type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                <a class="dropdown-item expenseDays d-flex align-items-center" href="javascript:;">
                                    <i data-feather="eye" class="icon-sm me-2"></i> <span>Today</span>
                                </a>
                                <a class="dropdown-item expenseDays d-flex align-items-center" href="javascript:;">
                                    <i data-feather="eye" class="icon-sm me-2"></i> <span>Weekly</span>
                                </a>
                                <a class="dropdown-item expenseDays d-flex align-items-center" href="javascript:;">
                                    <i data-feather="eye" class="icon-sm me-2"></i> <span>Monthly</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="mb-2 expenseAmount">
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--------------------------------Due Collection ------------------------------------------->
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                        <h6 class="card-title mb-0">Total Due Collection</h6>
                        <div class="dropdown">
                            <a type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                <a class="dropdown-item dueCollectionDays d-flex align-items-center"
                                    href="javascript:;">
                                    <i data-feather="eye" class="icon-sm me-2"></i> <span>Today</span>
                                </a>
                                <a class="dropdown-item dueCollectionDays d-flex align-items-center"
                                    href="javascript:;">
                                    <i data-feather="eye" class="icon-sm me-2"></i> <span>Weekly</span>
                                </a>
                                <a class="dropdown-item dueCollectionDays d-flex align-items-center"
                                    href="javascript:;">
                                    <i data-feather="eye" class="icon-sm me-2"></i> <span>Monthly</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="mb-2 dueCollectionAmount">
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--------------------------------  Return ------------------------------------------->
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                        <h6 class="card-title mb-0">Total Return</h6>
                        <div class="dropdown">
                            <a type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                <a class="dropdown-item returnDays d-flex align-items-center" href="javascript:;">
                                    <i data-feather="eye" class="icon-sm me-2"></i> <span>Today</span>
                                </a>
                                <a class="dropdown-item returnDays d-flex align-items-center" href="javascript:;">
                                    <i data-feather="eye" class="icon-sm me-2"></i> <span>Weekly</span>
                                </a>
                                <a class="dropdown-item returnDays d-flex align-items-center" href="javascript:;">
                                    <i data-feather="eye" class="icon-sm me-2"></i> <span>Monthly</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="mb-2 returnCount">
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--------------------------------  Stock Value ------------------------------------------->
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card ">
                <div class="card-body  p-2">
                    <sapn class="ps-2">Total Stock Value</sapn>
                    <div class="d-flex p-2 justify-content-between align-items-center">
                        <!-- Total Customer Section -->
                        @php
                        $grandTotal = 0;
                        $totalCost = 0;
                        $totalSale = 0;
                     foreach ($totalStockValuePrices as $product) {
                        foreach ($product->variations as $variation) {
                            $stock = $variation->stocks->sum('stock_quantity');
                            $totalCost += $stock * $variation->cost_price;
                            if($sale_price_type == 'b2b_price'){
                                $totalSale += $stock * $variation->b2b_price;
                            }elseif ($sale_price_type == 'b2c_price') {
                                $totalSale += $stock * $variation->b2c_price;
                            }
                        }
                     }
                     @endphp

                        <div>
                            <h6 class=" mb-0">Total Cost Price</h6>
                            <h6 class="mb-2">
                                {{ $totalCost }}
                        </h6>
                        </div>

                        <div>
                            <h6 class=" mb-0">Total Sale Price</h6>
                            <h6 class="mb-2">{{ $totalSale }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                        <h6 class="card-title mb-0">Total Bank Accounts</h6>
                        <div class="dropdown">
                            <a type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                @php
                                    $branchs = App\Models\Branch::all();
                                @endphp
                                @foreach ($branchs as $branch)
                                    <a class="dropdown-item branchSelect d-flex align-items-center"
                                        href="javascript:;" data-id="{{ $branch->id }}">
                                        <i data-feather="eye" class="icon-sm me-2"></i>
                                        <span>{{ $branch->name }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="mb-2 branchCount">
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Total Customer Section -->
                        <div>
                            <h6 class=" mb-0">Total Bank Amount</h6>
                            <h3 class="mb-2 branchAmount"></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Total Customer Section -->
                        <div>
                            <h6 class=" mb-0">Total paid Salary</h6>
                            <h3 class="mb-2 ">{{ $TotalPaidSalary }}</h3>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        {{-- ////////////////////////Service Total Sale //////////// --}}

        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                        <h6 class="card-title mb-0">Total Service  Sale</h6>
                        <div class="dropdown">
                            <a type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                <a class="dropdown-item saleDays d-flex align-items-center" href="javascript:;">
                                    <i data-feather="eye" class="icon-sm me-2"></i> <span>Today</span>
                                </a>
                                <a class="dropdown-item saleDays d-flex align-items-center" href="javascript:;">
                                    <i data-feather="eye" class="icon-sm me-2"></i> <span>Weekly</span>
                                </a>
                                <a class="dropdown-item saleDays d-flex align-items-center" href="javascript:;">
                                    <i data-feather="eye" class="icon-sm me-2"></i> <span>Monthly</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="mb-2 totalServiceSale">
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- //////////---Paid Service Sale---////////// --}}

        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                        <h6 class="card-title mb-0">Paid Service Sale</h6>
                        <div class="dropdown">
                            <a type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                <a class="dropdown-item paidServiceSaleDays d-flex align-items-center" href="javascript:;">
                                    <i data-feather="eye" class="icon-sm me-2"></i> <span>Today</span>
                                </a>
                                <a class="dropdown-item paidServiceSaleDays d-flex align-items-center" href="javascript:;">
                                    <i data-feather="eye" class="icon-sm me-2"></i> <span>Weekly</span>
                                </a>
                                <a class="dropdown-item paidServiceSaleDays d-flex align-items-center" href="javascript:;">
                                    <i data-feather="eye" class="icon-sm me-2"></i> <span>Monthly</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="mb-2 paidServiceSaleAmount">
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- //////////--- Due Service Sale---////////// --}}
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                        <h6 class="card-title mb-0">Due Service Sale</h6>
                        <div class="dropdown">
                            <a type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                <a class="dropdown-item dueServiceSaleDays d-flex align-items-center" href="javascript:;">
                                    <i data-feather="eye" class="icon-sm me-2"></i> <span>Today</span>
                                </a>
                                <a class="dropdown-item dueServiceSaleDays d-flex align-items-center" href="javascript:;">
                                    <i data-feather="eye" class="icon-sm me-2"></i> <span>Weekly</span>
                                </a>
                                <a class="dropdown-item dueServiceSaleDays d-flex align-items-center" href="javascript:;">
                                    <i data-feather="eye" class="icon-sm me-2"></i> <span>Monthly</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="mb-2 dueServiceSaleAmount">
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        //////////---------Only Total  Sales iwith filter  --------//////////
        // Fetch and display today's sales by default
        fetchTotalSales('today');

        // Handle dropdown filter clicks
        $(document).on('click', '.totalSales', function() {
            const filter = $(this).text().toLowerCase(); // Get the filter type
            fetchTotalSales(filter);
        });

        function fetchTotalSales(filter) {
            $.ajax({
                url: "{{ route('filter.dashboard.total.sales') }}", // Laravel route for filtering
                type: "GET",
                data: {
                    filter
                },
                success: function(response) {
                    // Update the sales amount
                    $('.card-body h3.totalSaleAmount').text(response.totalsalesTotal);
                },
                error: function() {
                    alert('Failed to fetch Total sales data.');
                }
            });
        }
        //////////----------Paid Sales  with Filter View----------------//////////
        // Fetch and display today's sales by default
        fetchSales('today');

        // Handle dropdown filter clicks
        $(document).on('click', '.paidSaleDays', function() {
            const filter = $(this).text().toLowerCase(); // Get the filter type
            fetchSales(filter);
        });

        function fetchSales(filter) {
            $.ajax({
                url: "{{ route('filter.dashboard.paid.sales') }}", // Laravel route for filtering
                type: "GET",
                data: {
                    filter
                },
                success: function(response) {
                    // Update the sales amount
                    $('.card-body h3.paidSaleAmount').text(response.paidSalesTotal);
                },
                error: function() {
                    alert('Failed to fetch Paid sales data.');
                }
            });
        }
        /////////Purchase ////////////
        // Fetch and display today's sales by default
        fetchPurchase('today');

        // Handle dropdown filter clicks
        $(document).on('click', '.purchaseDays', function() {
            const filter = $(this).text().toLowerCase(); // Get the filter type
            fetchPurchase(filter);
        });

        function fetchPurchase(filter) {
            $.ajax({
                url: "{{ route('filter.dashboard.purchase') }}", // Laravel route for filtering
                type: "GET",
                data: {
                    filter
                },
                success: function(response) {
                    // Update the sales amount
                    $('.card-body h3.purchaseAmount').text(response.purchaseTotal);
                },
                error: function() {
                    alert('Failed to fetch Purchase data.');
                }
            });
        }
        /////////Expanse ////////////
        // Fetch and display today's sales by default
        fetchExpense('today');

        // Handle dropdown filter clicks
        $(document).on('click', '.expenseDays', function() {
            const filter = $(this).text().toLowerCase(); // Get the filter type
            fetchExpense(filter);
        });

        function fetchExpense(filter) {
            $.ajax({
                url: "{{ route('filter.dashboard.expense') }}", // Laravel route for filtering
                type: "GET",
                data: {
                    filter
                },
                success: function(response) {
                    // Update the sales amount
                    $('.card-body h3.expenseAmount').text(response.expenseTotal);
                },
                error: function() {
                    alert('Failed to fetch Expense data.');
                }
            });
        }
        /////////Due Collection ////////////
        fetchDueCollection('today');

        // Handle dropdown filter clicks
        $(document).on('click', '.dueCollectionDays', function() {
            const filter = $(this).text().toLowerCase(); // Get the filter type
            fetchDueCollection(filter);
        });

        function fetchDueCollection(filter) {
            $.ajax({
                url: "{{ route('filter.dashboard.due-collection') }}", // Laravel route for filtering
                type: "GET",
                data: {
                    filter
                },
                success: function(response) {
                    // Update the sales amount
                    $('.card-body h3.dueCollectionAmount').text(response.dueCollectTotal);
                },
                error: function() {
                    alert('Failed to fetch Due Collection data.');
                }
            });
        }
        /////////Return////////////
        fetchReturn('today');

        // Handle dropdown filter clicks
        $(document).on('click', '.returnDays', function() {
            const filter = $(this).text().toLowerCase(); // Get the filter type
            fetchReturn(filter);
        });

        function fetchReturn(filter) {
            $.ajax({
                url: "{{ route('filter.dashboard.return') }}", // Laravel route for filtering
                type: "GET",
                data: {
                    filter
                },
                success: function(response) {
                    // Update the sales amount
                    $('.card-body h3.returnCount').text(response.returnTotal);
                },
                error: function() {
                    alert('Failed to fetch Return data.');
                }
            });
        }

        //////////////////////Bank ///////////////

        fetchBranch()
        $(document).on('click', '.branchSelect', function() {
            const branchId = $(this).data('id'); // Get the branch ID from the data-id attribute
            fetchBranch(branchId);
            // console.log(branchId)
        })
    });

    function fetchBranch(branchId) {
        $.ajax({
            url: "{{ route('filter.dashboard.branch') }}", // Laravel route for filtering
            type: "GET",
            data: {
                branchId: branchId // Send the selected branch ID
            },
            success: function(response) {
                // Update the total number of branches based on the response from the controller
                $('.card-body h3.branchCount').text(response.totalBranchesBank);
                // Update the total bank amount
                $('.card-body h3.branchAmount').text(response.totalBankAmount);
            },
            error: function() {
                alert('Failed to fetch branch data.');
            }
        });
    }//
    /////////////////Service total  Sale /////
            fetchTotalSale('today');

            // Handle dropdown filter clicks
            $(document).on('click', '.saleDays', function() {
                const filter = $(this).text().toLowerCase(); // Get the filter type
                fetchTotalSale(filter);
            });

            function fetchTotalSale(filter) {
                $.ajax({
                    url: "{{ route('filter.dashboard.total.service.sale') }}", // Laravel route for filtering
                    type: "GET",
                    data: {
                        filter
                    },
                    success: function(response) {
                        // Update the sales amount
                        $('.card-body h3.totalServiceSale').text(response.totalServceSale);
                    },
                    error: function() {
                        alert('Failed to fetch Sale data.');
                    }
                });
            }
    /////////////////Service Paid  Sale /////
            fetchPaidSale('today');

            // Handle dropdown filter clicks
            $(document).on('click', '.paidServiceSaleDays', function() {
                const filter = $(this).text().toLowerCase(); // Get the filter type
                fetchPaidSale(filter);
            });

            function fetchPaidSale(filter) {
                $.ajax({
                    url: "{{ route('filter.dashboard.paid.service.sale') }}", // Laravel route for filtering
                    type: "GET",
                    data: {
                        filter
                    },
                    success: function(response) {
                        // Update the sales amount
                        $('.card-body h3.paidServiceSaleAmount').text(response.paidServceSale);
                    },
                    error: function() {
                        alert('Failed to fetch Sale data.');
                    }
                });
            }
             /////////////////Service Due  Sale /////////////
             fetchDueSale('today');

                // Handle dropdown filter clicks
                $(document).on('click', '.dueServiceSaleDays', function() {
                    const filter = $(this).text().toLowerCase(); // Get the filter type
                    fetchDueSale(filter);
                });

                function fetchDueSale(filter) {
                    $.ajax({
                        url: "{{ route('filter.dashboard.due.service.sale') }}", // Laravel route for filtering
                        type: "GET",
                        data: {
                            filter
                        },
                        success: function(response) {
                            // Update the sales amount
                            $('.card-body h3.dueServiceSaleAmount').text(response.dueServceSale);
                        },
                        error: function() {
                            alert('Failed to fetch Sale data.');
                        }
                    });
                }
</script>
