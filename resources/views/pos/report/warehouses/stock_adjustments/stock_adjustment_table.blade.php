@if ($adjustments->count() > 0)
    @foreach ($adjustments as $adjustment)
        <tr>
            <td>
                <a href="#">
                    {{ $adjustment->adjustment_number ?? 'N/A' }}
                </a>
            </td>
            <td>{{ $adjustment->branch->name ?? 'N/A' }}</td>
            <td>{{ $adjustment->warehouse->warehouse_name ?? 'N/A' }}</td>
            <td>{{ $adjustment->rack->rack_name ?? 'N/A' }}</td>
            <td>{{ $adjustment->adjustment_type ?? 'N/A' }}</td>
            <td>
                @php
                    $text = $adjustment->reason ?? 'N/A';
                    $chunks = str_split($text, 20);
                @endphp

                @foreach ($chunks as $chunk)
                    {{ $chunk }}<br>
                @endforeach
            </td>
            <td>{{ $adjustment->userName->name ?? 'N/A' }}</td>

            @foreach ($adjustment->items as $item)
                <td>
                    {{ $item->product->name ?? 'N/A' }}
                </td>
                <td>
                    {{ $item->variation->variationSize->size ?? 'N/A' }}
                </td>
                <td>
                    {{ $item->previous_quantity ?? 'N/A' }}
                </td>
                <td>
                    {{ $item->adjusted_quantity ?? 'N/A' }}
                </td>
                <td>
                    {{ $item->final_quantity ?? 'N/A' }}
                </td>
            @endforeach
        </tr>
    @endforeach
@endif
