@if ($returns->count() > 0)
    @foreach ($returns as $index => $data)
        <tr>
            <td class="id">{{ $index + 1 }}</td>
            <td>
                <a href="{{ route('return.products.invoice', $data->id) }}">
                    #{{ $data->return_invoice_number ?? 0 }}
                </a>

            </td>
            <td>
                {{ $data->customer->name ?? 0 }}
            </td>
            <td>
                <ul>
                    @foreach ($data->returnItem as $item)
                        <li>{{ $item->product->name ?? 'Product Name Not Available' }} </li>
                    @endforeach
                </ul>
            </td>
            <td>{{ $data->return_date ?? 'Date not Available' }}</td>
            <td>৳ {{ $data->refund_amount ?? 0 }}</td>
            <td>{{ $data->return_reason ?? 'Data not Available' }}</td>
            <td>
                ৳ {{ $data->total_return_profit ?? 0 }}
            </td>
            @php
                $user = App\Models\User::findOrfail($data->processed_by);
            @endphp
            <td>
                {{ $user->name ?? 'Processed by Not Available' }}
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="9"> No Data Found</td>
    </tr>
@endif
