@extends('master')
@section('title', '| Purchase Invoice')
@section('admin')
    @php
        $branch = App\Models\Branch::findOrFail($purchase->branch_id);
        $supplier = App\Models\Customer::findOrFail($purchase->supplier_id);
        $products = App\Models\PurchaseItem::where('purchase_id', $purchase->id)->get();
    @endphp
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-none">
                <div class="card-body ">
                    <div class="container-fluid d-flex justify-content-between">
                        <div class="col-lg-3 ps-0">
                            <a href="#" class="noble-ui-logo logo-light d-block mt-3">EIL<span>POS</span></a>
                            <p class="mt-1 mb-1 show_branch_name"><b>{{ $branch->name ?? '' }}</b></p>
                            <p class="show_branch_address">{{ $branch->address ?? 'accordion ' }}</p>
                            <p class="show_branch_email">{{ $branch->email ?? '' }}</p>
                            <p class="show_branch_phone">{{ $branch->phone ?? '' }}</p>

                        </div>
                        <div class="col-lg-3 pe-0 text-end">
                            <p class="mt-1 mb-1 show_supplier_name"><b>{{ $supplier->name ?? '' }}</b></p>
                            <p class="show_supplier_address">{{ $supplier->address ?? '' }}</p>
                            <p class="show_supplier_email">{{ $supplier->email ?? '' }}</p>
                            <p class="show_supplier_phone">{{ $supplier->phone ?? '' }}</p>
                            <p class="text-end mb-1 mt-5">Total </p>
                            <h4 class="text-end fw-normal">৳
                                {{ number_format($purchase->grand_total, 2) ?? 00 }}
                            </h4>
                            <h6 class="mb-0 mt-2 text-end fw-normal"><span class="text-muted show_purchase_date">Invoice
                                    Date :</span> {{ $purchase->purchase_date ?? '' }}</h6>
                        </div>
                    </div>
                    <div class="container-fluid mt-5 d-flex justify-content-center w-100">
                        <div class="table-responsive w-100">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Description</th>
                                        @if ($color_view == 1)
                                            <th>Color</th>
                                        @endif
                                        @if ($size_view == 1)
                                            <th>Size</th>
                                        @endif
                                        <th>Quantity</th>
                                        <th>Unit cost</th>
                                        @if ($purchase_individual_product_discount === 1)
                                            <th>Discount</th>
                                        @endif
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($products->count() > 0)
                                        @foreach ($products as $index => $product)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td class="product_name">{{ $product->product->name ?? '' }}
                                                </td>
                                                @if ($color_view == 1)
                                                    <td>{{ $product->variant->colorName->name ?? '' }}</td>
                                                @endif
                                                @if ($size_view == 1)
                                                    <td>{{ $product->variant->variationSize->size ?? '' }}</td>
                                                @endif
                                                <td>{{ $product->quantity ?? 0 }}</td>
                                                <td>{{ number_format($product->unit_price, 2) ?? 0 }}</td>
                                                @if ($purchase_individual_product_discount === 1)
                                                    <td>{{ number_format($product->discount, 2) ?? 0 }}
                                                @endif
                                                <td>{{ number_format($product->total_price, 2) ?? 0 }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5">
                                                No Data Found
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="container-fluid mt-5 w-100">
                        <div class="row print_design">
                            <div class="col-sm-3">
                                @php
                                    $transactions = App\Models\Transaction::where(
                                        'particulars',
                                        'Purchase#' . $purchase->id,
                                    )->get();
                                    $totalSum = $transactions->sum('debit');
                                    // dd($transactions);
                                @endphp
                                {{-- @dd($purchase->transactions) --}}
                                @if ($transactions)
                                    <h5>Payment Made</h5>
                                    <table class="table">
                                        <tbody>
                                            @foreach ($transactions as $transaction)
                                                <tr>
                                                    <td class="text-start">{{ $transaction->date ?? '' }}</td>
                                                    <td class="text-end">৳
                                                        {{ number_format($transaction->debit, 2) ?? 00 }}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td class="text-start">Total</td>
                                                <td class="text-end">৳ {{ number_format($totalSum, 2) ?? 0 }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                            <div class="col-sm-3">
                                @if ($purchase->purchaseCostItems)
                                    <h5>Total extra Cost</h5>
                                    <table class="table">
                                        <tbody>
                                            @foreach ($purchase->purchaseCostItems as $item)
                                                <tr>
                                                    <td class="text-start">{{ $item->purpose ?? '' }}</td>
                                                    <td class="text-end">৳ {{ number_format($item->amount, 2) ?? 00 }}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td class="text-start">Total</td>
                                                <td class="text-end">{{ $purchase->total_purchase_cost ?? 0 }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </div>

                            <div class="col-sm-6">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td class="text-start">Product Total</td>
                                                <td class="text-end">৳ {{ number_format($purchase->total_amount, 2) }}</td>
                                            </tr>
                                            @if ($purchase->discount_amount > 0)
                                                @if ($purchase->discount_type == 'fixed')
                                                    <tr>
                                                        <td class="text-start">Discount</td>
                                                        <td class="text-end">৳
                                                            {{ number_format($purchase->discount_amount, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">Sub Total</td>
                                                        <td class="text-end">৳
                                                            {{ number_format($purchase->total_amount - $purchase->discount_amount, 2) }}
                                                        </td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td class="text-start">Discount ({{ $purchase->discount_amount }}
                                                            %) </td>
                                                        @php
                                                            $discount =
                                                                ($purchase->total_amount * $purchase->discount_amount) /
                                                                100;
                                                        @endphp
                                                        <td class="text-end">৳
                                                            {{ number_format($discount, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">Sub Total</td>
                                                        <td class="text-end">৳
                                                            {{ number_format($purchase->total_amount - $discount, 2) }}
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endif

                                            @if (max(0, $purchase->grand_total - ($purchase->total_amount + $purchase->total_purchase_cost)) > 0)
                                                <tr>
                                                    <td class="text-start">Previous Due</td>
                                                    <td class="text-end">৳
                                                        {{ number_format(max(0, $purchase->grand_total - ($purchase->total_amount + $purchase->total_purchase_cost)), 2) }}
                                                    </td>
                                                </tr>
                                            @endif

                                            @if ($purchase->total_purchase_cost > 0)
                                                <tr>
                                                    <td class="text-start">Total Purchase Cost</td>
                                                    <td class="text-end">৳
                                                        {{ number_format($purchase->total_purchase_cost, 2) ?? 0 }}
                                                    </td>
                                                </tr>
                                            @endif

                                            <tr>
                                                <td class="text-bold-800 text-start">Grand Total</td>
                                                <td class="text-bold-800 text-end">৳
                                                    {{ number_format($purchase->grand_total, 2) ?? 0 }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">Payment Made</td>
                                                <td
                                                    class="text-end {{ $purchase->grand_total <= $purchase->paid ? 'text-success' : 'text-danger' }}">
                                                    {{ $purchase->grand_total <= $purchase->paid ? '৳' : '(-) ৳' }}
                                                    {{ number_format($purchase->paid, 2) ?? 0 }}
                                                </td>
                                            </tr>

                                            @if ($purchase->due != 0)
                                                <tr class="">
                                                    <td class="text-bold-800 text-start">Balance Due</td>
                                                    <td class="text-bold-800 text-end">৳
                                                        {{ number_format($purchase->due, 2) }}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid w-100 btn_group">
                        {{-- <a href="javascript:;" class="btn btn-primary float-end mt-4 ms-2"><i data-feather="send"
                                class="me-3 icon-md"></i>Send Invoice</a> --}}
                        <a href="javascript:;" class="btn btn-outline-primary float-end mt-4" onclick="window.print();"><i
                                data-feather="printer" class="me-2 icon-md"></i>Print</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="w-100 mx-auto btn_group">
                <a href="{{ route('purchase.view') }}" class="btn btn-primary  mt-4 ms-2"><i
                        class="fa-solid fa-arrow-rotate-left me-2"></i>Manage Purchase</a>
                <a href="{{ route('purchase') }}" class="btn btn-outline-primary mt-4"><i data-feather="plus-circle"
                        class="me-2 icon-md"></i>Add Purchase</a>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .product_name {
                word-wrap: break-word !important;
                max-width: 150px !important;
                white-space: normal !important;
                /* টেক্সট যেন ভেঙে যায় */
                overflow: visible !important;
                /* ওভারফ্লো যেন না কাটে */
            }

            /* .print_design {
                                                                                                                                                                                                                                                                                display: flex;
                                                                                                                                                                                                                                                                                justify-content: space-between;
                                                                                                                                                                                                                                                                                align-items: center;
                                                                                                                                                                                                                                                                            } */

            table,
            td,
            th {
                vertical-align: middle !important;
                text-align: center !important;
                margin: 0 !important;
                padding: 5px 0 !important;

            }

            nav,
            .footer {
                display: none !important;
            }

            .page-content {
                margin-top: 0 !important;
                padding-top: 0 !important;
            }

            .btn_group {
                display: none !important;
            }
        }
    </style>
@endsection
