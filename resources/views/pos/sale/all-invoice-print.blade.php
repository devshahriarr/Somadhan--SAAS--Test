@if ($salesInvoice->count() > 0)
@foreach ($salesInvoice as $sale)
<tr>
    <td>{{ $sale->sale_date ?? 'N/A' }}</td>
    <td>  <span class="font-bold"> {{ $sale->invoice_number ?? ''}} </span></td>
    {{-- <td class="border">{{ $sale->total }}</td> --}}
    <td>
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Customer Name: <span class="text-dark">{{ $sale->customer->name ?? 'N/A' }}</span></th>
                    <th>Sale By : <span class="text-dark">{{$sale->saleBy->name ?? 'N/A'}}</span> </th>
                    <th>Total: <span class="text-dark">{{ $sale->change_amount ?? 'N/A'}} </span></th>
                    <th>Paid: <span class="text-dark">{{ $sale->paid ?? 'N/A'}} </span></th>
                    <th>Due: <span class="text-dark">{{ $sale->change_amount - $sale->paid ?? 'N/A'}} </span></th>
                    <th>Discount: <span class="text-dark">{{ $sale->discount?? 'N/A'}} </span></th>
                    <th>Receive Account: <span class="text-dark">{{ $sale->accountReceive->name ?? 'N/A'}} </span></th>
                </tr>
                <tr>
                    <th>Item Name</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Price/Unit</th>
                    <th>Discount</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->saleItem as $item)
                <tr>
                    <td>{{ \Illuminate\Support\Str::limit($item->product->name, 23, '**') ?? 'N/A' }}</td>
                    <td>{{ $item->variant->colorName->name ?? 'N/A' }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($item?->variant?->variationSize?->size, 23, '**') ?? 'N/A' }}</td>
                    <td>{{ $item->qty  ?? 'N/A'}}</td>
                    <td>{{ $item->product->productUnit->name ?? 'N/A' }}</td>
                    <td>{{ $item->rate ?? 'N/A'}}</td>
                    <td>{{ $item->discount ?? 'N/A'}}</td>
                    <td>{{ $item->sub_total ?? 'N/A'}}</td>
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
