@if ($sales->count() > 0)
    @foreach ($sales as $index => $data)
        <tr>
            <td class="id">{{ $index + 1 }}</td>
            <td>
                <a href="{{ route('sale.invoice', $data->id) }}">
                    #{{ $data->invoice_number ?? 0 }}
                </a>
            </td>
            {{-- <td>{{ $data->customer->name ?? '' }}
                <br> ({{ $data->customer->phone ?? '' }})
            </td> --}}
            <td>
                <a href="{{ route('customer.profile', $data->customer->id) }}">
                    {{ $data->customer->name ?? '' }}
                </a>
            </td>
            {{-- <td>
                @php
                    $totalItems = $data->saleItem->count();
                    $displayItems = $data->saleItem->take(5);
                    $remainingItems = $totalItems - 5;
                @endphp
                <ul>
                    @foreach ($displayItems as $items)
                        <li>
                            <a href="{{ isset($items->product) ? route('product.ledger', $items->product_id) : '#' }}">
                                {{ isset($items->product) ? $items->product->name : $items->viaSaleProduct->viaProduct->product_name ?? '' }}
                            </a>
                        </li>
                    @endforeach

                    @if ($totalItems > 5)
                        <li>and more {{ $remainingItems }}...</li>
                    @endif
                </ul>
            </td> --}}
            <td>{{ $data->quantity ?? 0 }}</td>
            <td>{{ $data->sale_date ?? 0 }}</td>
            <td>৳ {{ $data->total ?? 0 }}</td>
            <td>৳ {{ $data->actual_discount ?? 0 }}</td>
            <td>৳
                {{ $data->receivable - $data->change_amount ?? 0 }}
            </td>
            <td>
                ৳ {{ $data->receivable ?? 0 }}
            </td>

            <td>
                ৳ {{ $data->paid ?? 0 }}
            </td>
            <td>
                no
            </td>
            <td>
                @if ($data->due > 0)
                    <span class="text-danger">৳ {{ $data->due ?? 0 }}</span>
                @else
                    ৳ {{ $data->due ?? 0 }}
                @endif
            </td>
            <td> ৳
                @php
                    $totalCost = 0;
                @endphp
                @foreach ($data->saleItem as $item)
                    @php
                        $totalCost += $item->product->cost ?? 0;
                    @endphp
                @endforeach
                {{ $totalCost }}
            </td>
            <td>
                ৳ @php
                    $totalSale = 0;
                @endphp
                @foreach ($data->saleItem as $item)
                    @php
                        $totalSale += $item->product->price ?? 0;
                    @endphp
                @endforeach
                {{ $totalSale - $totalCost }}
            </td>
            <td>
                @if ($data->due <= 0)
                    <span class="badge bg-success">Paid</span>
                @else
                    <span class="badge bg-warning">Unpaid</span>
                @endif
            </td>
            <td>
                @if ($data->order_status === 'completed')
                    <span class="badge bg-success">Completed</span>
                @elseif ($data->order_status === 'draft')
                    <span class="badge bg-warning">Draft</span>
                @elseif ($data->order_status === 'return')
                    <span class="badge bg-danger">Return</span>
                @elseif ($data->order_status === 'updated')
                    <span class="badge bg-info">Updated</span>
                @else
                    <span class="badge bg-secondary">Unknown</span>
                @endif
            </td>

            <td>
                @if ($data->courier_status === 'not_send')
                    <a title="Send Courier" href="{{ route('sale.send.courier', $data->id) }}" class="btn btn-sm btn-primary text-white text-center table-btn courier">
                        <i class="fa-solid fa-paper-plane"></i>
                    </a>
                @elseif ($data->courier_status === 'send')
                    <span class="badge bg-warning">Send</span>
                @endif
            </td>
            <td class="id">
                <div class="dropdown">
                    <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton1"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Manage
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">

                        @if (Auth::user()->can('pos-manage.invoice'))
                            <a class="dropdown-item" href="{{ route('sale.invoice', $data->id) }}"><i
                                    class="fa-solid fa-file-invoice me-2"></i> Invoice</a>
                        @endif
                        <a class="dropdown-item " href="{{ route('sale.view.details', $data->id) }}"><i
                                class="fa-solid fa-eye me-2"></i> Show</a>
                        <a class="dropdown-item" href="{{ route('return', $data->id) }}"><i
                                style="transform: rotate(90deg);" class="fa-solid fa-arrow-turn-down me-2"></i></i>
                            Return</a>
                            @php
                            $settings  =  App\Models\PosSetting ::first();

                            @endphp
                            @if($settings && $settings->invoice_payment ===1)
                        @if ($data->due > 0)
                            <a class="dropdown-item add_payment" href="#" data-bs-toggle="modal"
                                data-bs-target="#paymentModal" data-id="{{ $data->id }}"><i
                                    class="fa-solid fa-credit-card me-2"></i> Payment</a>
                        @endif
                        @endif
                        {{-- @if (Auth::user()->can('pos-manage.delete'))
                            <a class="dropdown-item" id="delete" href="{{ route('sale.destroy', $data->id) }}"><i
                                    class="fa-solid fa-trash-can me-2"></i>Delete</a>
                        @endif --}}

                        <a class="dropdown-item" href="{{ route('sale.edit', $data->id) }}"><i
                                class="fa-solid fa-pen me-2"></i>
                            Edit</a>

                        <a class="dropdown-item" href="{{ route('duplicate.sale.invoice', $data->id) }}"><i
                                class="fa-solid fa-file-invoice me-2"></i>
                            Duplicate Invoice</a>
                    </div>
                </div>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="9"> No Data Found</td>
    </tr>
@endif
