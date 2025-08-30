@extends('master')
@section('title', '| Summarey Report')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Summary Report</li>
        </ol>
    </nav>
    <div class="row">
        @if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin')
            @foreach ($branchData as $branchId => $data)
                <div class="col-12 col-xl-12 stretch-card">
                    <div class="row flex-grow-1">
                        <h6 class="card-title">{{ $data['branch']->name }}</h6> <br>
                        <div class="col-md-3 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-baseline">
                                        <h6 class="card-title mb-0">Sale Amount</h6>
                                        <div class="dropdown mb-3">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3 class="mb-3">
                                                ৳ {{ number_format($data['saleAmount'], 2) }}
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-baseline">
                                        <h6 class="card-title mb-0">Purchase Cost</h6>
                                        <div class="dropdown mb-3">

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3 class="mb-3">
                                                ৳ {{ number_format($data['purchaseAmount'], 2) }}
                                            </h3>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-baseline">
                                        <h6 class="card-title mb-0">EXPENSE</h6>
                                        <div class="dropdown mb-3">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3 class="mb-3">
                                                ৳ {{ number_format($data['expenseAmount'], 2) }}
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-baseline">
                                        <h6 class="card-title mb-0">Sell Profit</h6>
                                        <div class="dropdown mb-3">

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3 class="mb-2">
                                                ৳ {{ number_format($data['sellProfit'], 2) }}
                                            </h3>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    </div>
    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow-1">
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Salary Sheet</h6>
                                <div class="dropdown mb-3">

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="mb-2">
                                        ৳ {{ number_format($data['totalSalary'], 2) }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow-1">
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Sale Amount</h6>
                                <div class="dropdown mb-3">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="mb-3">
                                        ৳ {{ $saleAmount }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Purchase Cost</h6>
                                <div class="dropdown mb-3">

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="mb-3">
                                        ৳ {{ $purchaseAmount }}
                                    </h3>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">EXPENSE</h6>
                                <div class="dropdown mb-3">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="mb-3">
                                        ৳ {{ $expenseAmount }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Sell Profit</h6>
                                <div class="dropdown mb-3">

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="mb-2">
                                        ৳ {{ $sellProfit }}
                                    </h3>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow-1">
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Salary Sheet</h6>
                                <div class="dropdown mb-3">

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="mb-2">
                                        ৳ {{ $totalSalary }}
                                    </h3>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        {{-- //End Total Summary --}}
    </div>

    <div class="row">
        <!--//Top Sale Product Start// --->
        {{-- <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-info">Top Sale Product</h6>

                    <div id="" class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th>SN#</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>No Of Sales</th>
                                    <th>Sale Amount</th>
                                </tr>
                            </thead>
                            <tbody class="showData">
                                @if ($products->count() > 0)
                                    @php
                                        $num = 1;
                                    @endphp
                                    <?php
                                    $totalSaleAmount = 0;
                                    $totalQty = 0;
                                    $totalNoSale = 0;
                                    ?>
                                    @foreach ($products as $key => $productItem)
                                        <tr>
                                            <td>{{ $num++ }}</td>
                                            <td>{{ $productItem->name ?? '' }}</td>
                                            @php
                                                $purchaseItems = App\Models\PurchaseItem::where(
                                                    'product_id',
                                                    $productItem->id,
                                                )->get();
                                                $totalPurchaseQuantity = $purchaseItems->sum('quantity');

                                                $saleItems = App\Models\SaleItem::where(
                                                    'product_id',
                                                    $productItem->id,
                                                )->get();

                                                $noOfSales = $saleItems->sum('qty');
                                                $totalSalePrice = $saleItems->sum('sub_total');

                                            @endphp

                                            <td> {{ $productItem->stock ?? 0 }}</td>
                                            <td>{{ $noOfSales }}</td>
                                            <td>{{ $totalSalePrice ?? 0 }}</td>
                                            <?php
                                            $totalSaleAmount += isset($totalSalePrice) ? $totalSalePrice : 0;
                                            $totalQty += isset($productItem->stock) ? $productItem->stock : 0;
                                            $totalNoSale += isset($noOfSales) ? $noOfSales : 0;
                                            ?>

                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="12">
                                            <div class="text-center text-warning mb-2">Data Not Found</div>
                                        </td>
                                    </tr>
                                @endif
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Qty : {{ $totalQty ?? 0 }}</th>
                                    <th>Total : {{ $totalNoSale ?? 0 }}</th>
                                    <th>Total : {{ $totalSaleAmount ?? 0 }}Tk</th>
                                </tr>
                            </tfoot>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-info">Expense</h6>

                    <div id="" class="table-responsive">
                        <table id="dataTableExample2" class="table">
                            <thead>
                                <tr>
                                    <th>SN#</th>
                                    <th>Expense Purpose</th>
                                    <th>Category</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody class="showData">
                                @if ($expense->count() > 0)
                                    @php
                                        $num = 1;
                                    @endphp
                                    <?php
                                    $totalAmount = 0;
                                    ?>
                                    @foreach ($expense as $key => $expenseData)
                                        <tr>
                                            <td>{{ $num++ }}</td>
                                            <td>{{ $expenseData->purpose ?? '' }}</td>
                                            <td>{{ $expenseData['expenseCat']['name'] ?? '' }}</td>
                                            <td>{{ $expenseData->amount ?? '' }}</td>
                                            <?php $totalAmount += isset($expenseData->amount) ? $expenseData->amount : 0; ?>
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
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><strong>Total : {{ $totalAmount ?? 0 }} Tk</strong></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!--//Top Sale Product End// --->
        </div>
    </div>

    <div class="row">
        <!---Pay to Supplier Start -->
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-info">Pay to Supplier</h6>

                    <div id="" class="table-responsive">
                        <table id="dataTableExample1"class="table">
                            <thead>
                                <tr>
                                    <th>SN#</th>
                                    <th>Supplier Name</th>
                                    <th>Payment Date</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody class="showData">

                                @if ($supplier->count() > 0)
                                    @php
                                        $num = 1;
                                        $totalSupplierAmount = 0;
                                    @endphp
                                    @foreach ($supplier as $key => $supplierData)
                                        <tr>
                                            <td>{{ $num++ }}</td>
                                            <td>{{ $supplierData['supplier']['name'] ?? '' }}</td>
                                            <td>{{ $supplierData->date ?? '' }}</td>
                                            <td>{{ $supplierData->debit ?? '' }} <span>TK</span></td>
                                            <?php
                                            $totalSupplierAmount += isset($supplierData->debit) ? $supplierData->debit : 0;
                                            ?>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="12">
                                            <div class="text-center text-warning mb-2">Data Not Found</div>
                                        </td>
                                    </tr>
                                @endif
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>Total : {{ $totalSupplierAmount ?? 0 }}Tk</th>
                                </tr>
                            </tfoot>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!---Pay to Supplier End -->
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-info">Receive from Customer</h6>

                    <div id="" class="table-responsive">
                        <table id="dataTableExample4" class="table">
                            <thead>
                                <tr>
                                    <th>SN#</th>
                                    <th>Customer Name</th>
                                    <th>Payment Date</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody class="showData">
                                @if ($customer->count() > 0)
                                    @php
                                        $num = 1;
                                    @endphp
                                    <?php
                                    $customerTotalPayment = 0;
                                    ?>
                                    @foreach ($customer as $key => $customerData)
                                        <tr>
                                            <td>{{ $num++ }}</td>
                                            <td>{{ $customerData['customer']['name'] ?? '' }}</td>
                                            <td>{{ $customerData->date ?? '' }}</td>
                                            <td>{{ $customerData->debit ?? '' }} <span>TK</span></td>
                                            <?php
                                            $customerTotalPayment += isset($customerData->debit) ? $customerData->debit : 0;
                                            ?>
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
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><strong>Total : {{ $customerTotalPayment ?? 0 }} Tk</strong></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!--//Top Sale Product End// --->
        </div>
    </div>


@endsection
