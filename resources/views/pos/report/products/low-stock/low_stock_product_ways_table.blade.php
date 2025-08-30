@forelse ($productSummary as $stock)
    <tr>
        <td>{{ $stock['product_name'] }}</td>
        <td>{{ $stock['category'] }}</td>
        <td>{{ $stock['total_stock_quantity'] }}</td>
        <td>{{ number_format($stock['total_cost_price'], 2) }}</td>
        <td>{{ number_format($stock['total_b2b_price'], 2) }}</td>
        <td>{{ number_format($stock['total_b2c_price'], 2) }}</td>
    </tr>
@empty
    <tr>
        <td colspan="5">No Data Found</td>
    </tr>
@endforelse
