<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-info">damage Report</h6>
                <div id="" class="table-responsive">
                    <table id="example" class="table">
                        <thead>
                            <tr>
                                <th>SN#</th>
                                <th>Date</th>
                                <th>Product Name</th>
                                <th>Branch Name</th>
                                <th>Size</th>
                                <th>Color</th>
                                <th>Cost Price</th>
                                <th>Quantity</th>
                                <th>Damage Amount</th>
                                <th>Note</th>

                            </tr>
                        </thead>
                        <tbody class="showData">
                            @if ($damageItem->count() > 0)
                                @foreach ($damageItem as $key => $damage)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $damage->date ?? '' }}</td>
                                        <td>{{ $damage['product']['name'] ?? '' }}</td>
                                        <td>{{ $damage['branch']['name'] ?? '' }}</td>
                                        <td>{{ $damage->variation->variationSize->size ?? '' }}</td>
                                        <td>{{ $damage->variation->colorName->name ?? '' }}</td>
                                        <td>{{ $damage->variation->cost_price ?? '' }}</td>
                                        <td>{{ $damage->qty ?? '' }}</td>
                                        <td><span>৳</span> {{ $damage->damage_cost ?? 0 }}</td>
                                        <td>{{ $damage->note ?? '' }}</td>
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
                            {{-- <tr>
                                <th colspan="6" class="text-end">Total Damage Amount:</th>
                                <th><span>৳</span> {{ $totalSum ?? '0' }}</th>
                                <th></th>
                            </tr> --}}
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
