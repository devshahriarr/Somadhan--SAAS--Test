{{-- @foreach ($groupedVariationDetails as $productId => $variations)
    <h4>Product Name: {{ $productSummary[$productId]['product_name'] }}</h4>
    <table class="table my-2">
        <thead>
            <tr>
                <th>Variation Status</th>
                <th>Stock Quantity</th>
                <th>Cost Price</th>
                <th>B2B Price</th>
                <th>B2C Price</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($variations as $variation)
                <tr>
                    <td>{{ $variation['variation_name'] }}</td>
                    <td>{{ $variation['stock_quantity'] }}</td>
                    <td>{{ number_format($variation['cost_price'], 2) }}</td>
                    <td>{{ number_format($variation['b2b_price'], 2) }}</td>
                    <td>{{ number_format($variation['b2c_price'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endforeach --}}


{{-- @dd($totalB2BPrice) --}}

@foreach ($variations as $variation)

<tr>
    <td>{{ $variation->product->name ?? ''}}</td>
    <td>{{ $variation->stocks_sum_stock_quantity ?? 0}}</td>
    <td>{{ $variation->variationSize->size ?? '-'}}</td>
    <td>{{ $variation->ColorName->name ?? '-'}}</td>
    <td>{{ $variation->cost_price  ?? 0}}</td>
    <td>{{ $variation->b2b_price ?? 0}}</td>
    <td>{{ $variation->b2c_price  ?? 0}}</td>
</tr>
@endforeach
