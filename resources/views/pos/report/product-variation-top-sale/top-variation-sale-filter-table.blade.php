
                            @if ($variations->count() > 0)
                                @foreach ($variations as $variation)
                                    @if ($variation->saleItem->count() > 0)
                                        <tr>
                                            <td>{{ $variation->product->name ?? 'N/A' }}</td>
                                            <td>{{ $variation->sale_item_sum_qty ?? 'N/A' }}</td>
                                            <td>{{ $variation->status ?? 'N/A' }}</td>
                                            <td>{{ $variation->variationSize->size ?? 'N/A' }}</td>
                                            <td>{{ $variation->saleItem->sum('total_purchase_cost') ?? 'N/A' }}</td>
                                            <td>{{ $variation->saleItem->sum('sub_total') ?? 'N/A' }}</td>
                                            <td>{{ $variation->saleItem->sum('total_profit') ?? 'N/A' }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="12">
                                        <div class="text-center text-warning mb-2">Data Not Found</div>
                                    </td>
                                </tr>
                            @endif

