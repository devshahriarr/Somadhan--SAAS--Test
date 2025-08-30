@if ($purchase->count() > 0)
    @foreach ($purchase as $index => $data)
        <tr>
            <td class="id">{{ $index + 1 }}</td>
            <td>
                <a href="{{ route('purchase.invoice', $data->id) }}">#{{ $data->invoice ?? $data->id }}</a>
            </td>
            {{-- @dd($data->supplier_id); --}}
            <td>
                <a href="{{ route('customer.profile', $data->supplier_id) }}">
                    {{ $data->supplier->name ?? '' }}
                </a>
            </td>
            <td>{{ $data->purchase_date ?? 0 }}</td>
            <td>{{ $data->purchaseBy->name  ?? 'N/A' }}</td>
            {{-- <td>
                @php
                    $totalItems = $data->purchaseItem->count();
                    $displayItems = $data->purchaseItem->take(5);
                    $remainingItems = $totalItems - 5;
                @endphp
                <ul>
                    @foreach ($displayItems as $items)
                        <li>{{ $items->product->name ?? '' }} | Size: {{$items->variant->variationSize->size ?? 'N/A'}} | Color : {{$items->variant->colorName->name  ?? 'N/A'}}</li>

                    @endforeach

                    @if ($totalItems > 5)
                        <li>and more {{ $remainingItems }}...</li>
                    @endif
                </ul>
            </td> --}}
            <td>
                ৳ {{ $data->grand_total ?? 0 }}
            </td>
            <td>
                ৳ {{ $data->carrying_cost ?? 0 }}
            </td>
            <td>
                ৳ {{ number_format($data->grand_total + $data->carrying_cost, 2) ?? 0 }}
            </td>
            <td>
                ৳ {{ $data->paid ?? 0 }}
            </td>
            <td>
                @if ($data->due > 0)
                    <span class="text-danger">৳ {{ $data->due ?? 0 }}</span>
                @else
                    ৳ {{ $data->due ?? 0 }}
                @endif

            </td>
            <td class="id">
                <div class="dropdown">
                    <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton1"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Manage
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <a class="dropdown-item" href="{{ route('purchase.invoice', $data->id) }}"><i
                                class="fa-solid fa-file-invoice me-2"></i> Invoice</a>
                        @if ($data->document)
                            <a class="dropdown-item" href="{{ route('purchase.money.receipt', $data->id) }}"><i
                                    class="fa-solid fa-receipt me-2"></i> Money Receipt</a>
                        @endif
                        @if (Auth::user()->can('purchase.edit'))
                            <a class="dropdown-item" href="{{ route('purchase.edit', $data->id) }}"><i
                                    class="fa-solid fa-pen-to-square me-2"></i> Edit</a>
                        @endif
                        @if (Auth::user()->can('purchase.delete'))
                            <a class="dropdown-item" id="delete"
                                href="{{ route('purchase.destroy', $data->id) }}"><i
                                    class="fa-solid fa-trash-can me-2"></i>Delete</a>
                        @endif
                        @if ($data->due > 0)
                            <a class="dropdown-item add_payment" href="#" data-bs-toggle="modal"
                                data-bs-target="#paymentModal" data-id="{{ $data->id }}"><i
                                    class="fa-solid fa-credit-card me-2"></i> Payment</a>
                        @endif
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
