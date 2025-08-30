@if ($customer->count() > 0)
    @foreach ($customer as $data)
        <tr>
            <td>{{ $data->name ?? '' }}</td>
            <td>{{ $data->email ?? '' }}</td>
            <td>{{ $data->phone ?? '' }}</td>
            <td>{{ $data->address ?? '' }}</td>
            <td>
                @if (is_object($data) && isset($data->wallet_balance) && $data->wallet_balance > 0)
                    ৳ {{ $data->wallet_balance ?? 00 }}
                @endif
            </td>
            <td>
                @if (is_object($data) && isset($data->wallet_balance) && $data->wallet_balance > 0)
                    ৳ {{ $data->wallet_balance ?? 00 }}
                @endif
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="9"> No Data Found</td>
    </tr>
@endif
