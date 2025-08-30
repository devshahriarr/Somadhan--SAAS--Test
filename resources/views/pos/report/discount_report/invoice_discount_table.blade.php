@if ($invoiceDiscounts->count() > 0)
    @php $serial = 1; @endphp
    @foreach ($invoiceDiscounts as $key => $invoiceDiscount)
        @if ($invoiceDiscount->actual_discount > 0)
            <tr>
                <td>{{ $serial }}</td>
                <td> <a href="{{ route('sale.invoice', $invoiceDiscount->id) }}">
                        #{{ $invoiceDiscount->invoice_number ?? 0 }}
                    </a></td>
                <td>{{ $invoiceDiscount->customer->name ?? '' }} </td>
                <td>{{ $invoiceDiscount->actual_discount ?? '' }} Tk</td>
                <td>{{ $invoiceDiscount->sale_date ?? '' }}</td>
                <td>{{ $invoiceDiscount->saleBy->name ?? 'N/A' }}</td>
            </tr>
            @php $serial++; @endphp
        @endif
    @endforeach
@else
    <tr>
        <td colspan="12">
            <div class="text-center text-warning mb-2">Invoice Discount  Not Found</div>
        </td>
    </tr>
@endif
