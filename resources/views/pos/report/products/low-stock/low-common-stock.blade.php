
<ul class="nav nav-tabs" id="myTab" role="  ">
    <li class="nav-item">
        <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home"
            aria-selected="true">Product Ways Low Stock</a>
    </li>
    <li class="nav-item">
        {{-- <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#" role="tab" aria-controls="contact" aria-selected="false"></a> --}}
        <a class="nav-link " id="variationStock-tab" data-bs-toggle="tab" href="#variationStock " role="tab"
            aria-controls="profile" aria-selected="false">Variation Low Ways Stock</a>
    </li>

</ul>
<div class="tab-content border border-top-0 p-3 active" id="myTabContent">
    <div class="tab-pane show active " id="home" role="tabpanel" aria-labelledby="home-tab">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Product Ways Low Stock Table</h6>
                    <div class="table-responsive">
                        <table id="lowStocProductkDataTable" class="table">
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
                                @include('pos.report.products.low-stock.low_stock_product_ways_table')
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
                    <h6 class="card-title">Variation Ways Low Stock Table</h6>
                    <div class="table-responsive">
                        <table id="lowStockVariationDataTable" class="table">
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
                            @include('pos.report.products.low-stock.variation_ways_low_stock_table')
                        </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
