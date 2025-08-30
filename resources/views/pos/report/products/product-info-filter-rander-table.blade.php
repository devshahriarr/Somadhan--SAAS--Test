
                            @if ($productInfo->count() > 0)
                                @foreach ($productInfo as $key => $product)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <img src="{{ $product->image ? asset('uploads/product/' . $product->image) : asset('dummy/image.jpg') }}"
                                                alt="product image">
                                        </td>
                                        <td>
                                            <a href="{{ route('product.ledger', $product->id) }}">
                                                {{ $product->name ?? '' }}
                                            </a>
                                        </td>
                                        @if($sale_price_type == 'b2c_price')
                                        <td>{{ $product->variation->b2c_price ?? '' }}</td>
                                        @else
                                        <td>{{ $product->variation->b2b_price ?? '' }}</td>
                                        @endif

                                        <td>{{ $product->category->name ?? '' }}</td>
                                        <td>{{ $product->subcategory->name ?? '' }}</td>
                                        <td>{{ $product->brand->name ?? '' }}</td>
                                        <td>{{ $product->stock_quantity_sum ?? $product->stock_quantity_sum_stock_quantity?? 0 }}</td>
                                        <td>{{ $product->productUnit->name ?? '' }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="12">
                                        <div class="text-center text-warning mb-2">Data Not Found</div>
                                    </td>
                                </tr>
                            @endif

