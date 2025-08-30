<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-info">Purchase Report</h6>

                <div id="" class="table-responsive">
                    <table id="example" class="table">
                        <thead>
                            <tr>
                                <th>SN#</th>
                                <th>Date</th>
                                <th>Purchase No</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Subtotal</th>

                            </tr>
                        </thead>
                        <tbody class="showData">
                            @if ($purchaseItem->count() > 0)
                                @foreach ($purchaseItem as $key => $purchase)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $purchase['Purchas']['purchase_date'] ?? '' }}</td>
                                        <td><a
                                                href="{{ route('purchse.details.invoice', $purchase->purchase_id) }}">Purchase#</a>{{ $purchase->purchase_id ?? '' }}
                                        </td>
                                        <td>{{ $purchase['product']['name'] ?? '' }}</td>
                                        <td>{{ $purchase->quantity ?? '' }}</td>
                                        <td>{{ $purchase->unit_price ?? '' }}</td>
                                        <td>{{ $purchase->total_price ?? '' }}</td>
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
