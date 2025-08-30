{{-- <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-info">Top Product Sale Report</h6>
                <div id="" class="table-responsive">
                    <table id='productDataTable' class="table">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Total Sale Qty</th>
                                <th>Total Purchase Cost</th>
                                <th>Sale Price </th>
                                <th>Total Profit</th>
                            </tr>
                        </thead>
                        <tbody class="showData"> --}}
                            @if ($products->count() > 0)
                                @foreach ($products as $product)
                                    @if ($product->saleItem->count() > 0)
                                        <tr>
                                            <td>{{ $product->name ?? 'N/A' }}</td>
                                            <td>{{ $product->sale_item_sum_qty ?? 'N/A' }}</td>
                                            <td>{{ $product->saleItem->sum('total_purchase_cost') ?? 'N/A' }}</td>
                                            <td>{{ $product->saleItem->sum('sub_total') ?? 'N/A' }}</td>
                                            <td>{{ $product->saleItem->sum('total_profit') ?? 'N/A' }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="12">
                                        <div class="text-center text-warning mb-2">Data Not Found</div>
                                    </td>
                                </tr>
                            @endif
                        {{-- </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> --}}



