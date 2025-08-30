<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Daily Sale Report</h6>
                <div id="" class="table-responsive">
                    <table id="lowStockVariationDataTable" class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Sale</th>
                                <th>Paid </th>
                                <th>Due</th>
                            </tr>
                        </thead>
                        <tbody class="showData">
                            @if ($sales->count() > 0)
                            @foreach ($sales as $key => $sale)
                                <tr>

                                    <td>    
                                        {{ $sale->sale_dates ??'' }}
                                    </td>
                                    <td>
                                        {{ $sale->total_sale ?? 0 }}
                                    </td>
                                    <td>
                                        {{ $sale->total_paid ?? 0 }}
                                    </td>
                                    <td>
                                        {{ $sale->total_due ?? 0 }}
                                    </td>

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

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
