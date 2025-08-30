@if ($saleItem->count() > 0)
    @foreach ($saleItem as $index => $data)
        <tr>
            <td class="id">{{ $index + 1 }}</td>
            <td>
               {{$data->product->name ?? 'N/A' }}
            </td>
            <td>
                <a href="{{ route('sale.invoice', $data->saleId->id) }}">
                    #{{ $data->saleId->invoice_number ?? 0 }}
                </a>
            </td>
            <td>
                <a href="{{ route('customer.profile', $data->saleId->customer->id) }}">
                    {{ $data->saleId->customer->name ?? '' }}
                </a>
            </td>

            <td>{{ $data->qty ?? 0 }}</td>
            <td>{{ $data->saleId->sale_date ?? 0 }}</td>
            <td>৳ {{ $data->rate ?? 0 }}</td>
            <td>৳ {{ $data->sub_total ?? 0 }}</td>
            <td>৳ {{ $data->discount ?? 0 }}</td>
            {{-- <td>
                ৳ {{ $data->saleId->receivable ?? 0 }}
            </td>
            <td>
                ৳ {{ $data->saleId->paid ?? 0 }}
            </td>
            <td>
                {{$data->saleId->returned > 0 ? "Yes" : "No"}}
            </td> --}}
            {{-- <td>
                @if ($data->saleId->due > 0)
                    <span class="text-danger">৳ {{ $data->saleId->due ?? 0 }}</span>
                @else
                    ৳ {{ $data->saleId->due ?? 0 }}
                @endif
            </td> --}}
            @if (Auth::user()->role !== 'salesman')
            <td> ৳
               {{$data->total_purchase_cost ?? 0}}

            </td>

            <td>
                ৳ {{$data->total_profit ?? 0}}
            </td>
            @endif
            <td>{{$data->saleId->accountReceive->name ?? 'N/A'}}</td>
            <td>{{$data->saleId->saleBy->name ?? 'N/A'}}</td>
            <td>
               {{$data->saleId->status}}
            </td>
            <td>
                @if ($data->saleId->order_status === 'completed')
                    <span class="badge bg-success">Completed</span>
                @elseif ($data->saleId->order_status === 'draft')
                    <span class="badge bg-warning">Draft</span>
                @elseif ($data->saleId->order_status === 'return')
                    <span class="badge bg-danger">Return</span>
                @elseif ($data->saleId->order_status === 'updated')
                    <span class="badge bg-info">Updated</span>
                @else
                    <span class="badge bg-secondary">Unknown</span>
                @endif
            </td>


        </tr>
    @endforeach
@else
    <tr>
        <td colspan="9"> No Data Found</td>
    </tr>
@endif
