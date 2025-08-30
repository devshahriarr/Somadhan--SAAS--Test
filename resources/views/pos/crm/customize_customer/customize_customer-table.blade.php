@if ($customers->count() > 0)
    @foreach ($customers as $key => $customer)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $customer['branch']['name'] ?? '' }}</td>
            <td>
                <a href="{{ route('customer.profile', $customer->id) }}">
                    {{ $customer->name ?? '' }}
                </a>
            </td>
            <td>{{ $customer->phone ?? '' }}</td>
            <td>{{ $customer->total_sales ?? 0 }}</td>
            <td>{{ $customer->total_due ?? 0 }}</td>
            <td>{{ $customer->opening_payable ?? '' }}</td>
            <td>{{ $customer->total_receivable ?? '' }}</td>
            <td>{{ $customer->total_payable ?? '' }}</td>
            <td class="text-danger">{{ $customer->wallet_balance ?? '' }}</td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="12">
            <div class="text-center">
                Data Not Found
            </div>
        </td>
    </tr>
@endif
