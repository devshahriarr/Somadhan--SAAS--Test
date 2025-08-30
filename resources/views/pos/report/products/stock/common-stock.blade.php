
                     @php
                     $products = App\Models\Product::get();
                        $totalCost = 0;
                        $totalStockValue = 0;
                        $totalB2BPrice = 0;
                        $totalB2CPrice = 0;
                     foreach ($products as $product) {
                        foreach ($product->variations as $variation) {
                            $stock = $variation->stocks->sum('stock_quantity');
                            $totalStockValue+=$stock;
                            $totalCost += $stock * $variation->cost_price;
                            $totalB2BPrice += $stock * $variation->b2b_price;
                            $totalB2CPrice += $stock * $variation->b2c_price;

                        }
                     }
                    //  dd($totalB2BPrice,$totalB2CPrice,$totalCost,$totalStockValue);
                     @endphp



{{-- @dd($totalB2CPrice) --}}
<table class="table table-bordered table-striped text-center  mb-4">
    <thead class="text-dark">
        <tr>
            <th>Total Stock</th>
            <th>Total Cost Price</th>
            <th>Total B2B Price</th>
            <th>Total B2C Price</th>
        </tr>
    </thead>
    <tbody>
        <tr class="fw-bold">
            <td>{{ $totalStockValue }}</td>
            <td>৳{{ number_format($totalCost, 2) }}</td>
            <td>৳{{ number_format($totalB2BPrice, 2) }}</td>
            <td>৳{{ number_format($totalB2CPrice, 2) }}</td>
        </tr>
    </tbody>
</table>


<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home"
            aria-selected="true">Product Ways Stock</a>
    </li>
    <li class="nav-item">
        {{-- <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#" role="tab" aria-controls="contact" aria-selected="false"></a> --}}
        <a class="nav-link " id="variationStock-tab" data-bs-toggle="tab" href="#variationStock " role="tab"
            aria-controls="profile" aria-selected="false">Variation Ways Stock</a>
    </li>

</ul>
<div class="tab-content border border-top-0 p-3 active" id="myTabContent">
    <div class="tab-pane show active " id="home" role="tabpanel" aria-labelledby="home-tab">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Product Ways Stock Table</h6>
                    <div class="table-responsive">
                        <table id="example" class="table">
                            <thead>
                                <tr>
                                    {{-- <th class="id">#SI</th> --}}
                                    <th>Product Name</th>
                                    <th>category</th>
                                    <th>Total Stock Quantity</th>
                                    <th>Total Cost Price</th>
                                    <th>Total B2B Price</th>
                                    <th>Total B2C Price</th>
                                </tr>
                            </thead>
                            <tbody id="showData">

                                @include('pos.report.products.stock.stock_product_ways_table')
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane show  " id="variationStock" role="tabpanel" aria-labelledby="variationStock-tab">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Variation Ways Stock Table</h6>
                    <div class="table-responsive">
                        {{-- <table id="example" class="table"> --}}
                            <table id="variationDataTable" class="table">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Stock Quantity </th>
                                        <th>Size</th>
                                        <th>Color</th>
                                        <th>Cost Price </th>
                                        <th>B2B Price</th>
                                        <th>B2C Price</th>
                                    </tr>
                                </thead>
                            <tbody>
                              @include('pos.report.products.stock.variation_ways_stock_table')
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
