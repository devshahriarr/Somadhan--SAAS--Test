@if ($purchaseInvoice->count() > 0)
@foreach ($purchaseInvoice as $purchase)
<tr>
    <td>{{ $purchase->purchase_date }}</td>
    <td>  <span class="font-bold"> {{ $purchase->invoice }} </span></td>
    {{-- <td class="border">{{ $sale->total }}</td> --}}
    <td>
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Supplier Name: <span class="text-dark">{{ $purchase->supplier->name ?? '' }}</span></th>
                    {{-- <th>Sale By : <span class="text-dark">{{$purchase->saleBy->name}}</span> </th> --}}
                    <th>Total: <span class="text-dark">{{ $purchase->grand_total ?? ''}} </span></th>
                </tr>
                <tr>
                    <th>Item Name</th>
                    <th>Size</th>
                    <th>Color</th>
                    <th>Unit</th>
                    <th>Quantity</th>
                    <th>Price/Unit</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchase->purchaseItem as $item)
                <tr>
                    <td>{{ \Illuminate\Support\Str::limit($item->product->name , 23, '**') }}</td>

                    <td>{{ $item->variant->variationSize->size ?? 'N/A'}}</td>
                    <td>{{ $item->variant->colorName->name ?? 'N/A'}}</td>
                     <td>{{$item->product->productUnit->name ?? 'N/A' }}</td>
                    <td>{{ $item->quantity ?? ''}}</td>
                    <td>{{ $item->unit_price ?? ''}}</td>
                    <td>{{ $item->total_price ?? '' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </td>
    {{-- <td class="border">{{ $sale->total }}</td> --}}
</tr>
@endforeach
@else
    <tr>
        <td colspan="9"> No Data Found</td>
    </tr>
@endif
