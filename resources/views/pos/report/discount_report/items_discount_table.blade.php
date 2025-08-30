@if ($itemsDiscounts->count() > 0 )
@php $serial = 1; @endphp
    @foreach ($itemsDiscounts as $key => $itemsDiscount)
    @if($itemsDiscount->discount > 0 )
        <tr>
            <td>{{$serial }}</td>
            {{-- <td><a href="{{ route('sale.invoice', $itemsDiscount->id) }}">
                #{{$itemsDiscount->saleId->invoice_number}}
            </a>
                </td> --}}
            <td>
                {{ $itemsDiscount->product->name  ?? '' }} | {{$itemsDiscount->variant->variationSize->size  ?? 'N/A' }} | {{ $itemsDiscount->variant->colorName->name  ?? 'N/A' }}
            </td>
            <td>{{ $itemsDiscount->discount  ?? '' }} Tk</td>
            <td>{{$itemsDiscount->saleId->sale_date}}</td>
        </tr>
        @php $serial++; @endphp
    @endif
    @endforeach
@else
    <tr>
        <td colspan="12">
            <div class="text-center text-warning mb-2">Sales Items Not Found</div>
        </td>
    </tr>
@endif
