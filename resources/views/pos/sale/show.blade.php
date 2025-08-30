@extends('master')
@section('admin')
    @php
        $branch = App\Models\Branch::findOrFail($sale->branch_id);
        $customer = App\Models\Customer::findOrFail($sale->customer_id);
        $products = App\Models\SaleItem::where('sale_id', $sale->id)->get();
    @endphp
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-none">
                <div class="card-body ">
                    <div class="container-fluid d-flex justify-content-between">
                        <div class="col-lg-3 ps-0">
                            @if (!empty($invoice_logo_type))
                                @if ($invoice_logo_type == 'Name')
                                    <a href="#" class="noble-ui-logo logo-light d-block mt-3">{{ $siteTitle }}</a>
                                @elseif($invoice_logo_type == 'Logo')
                                    @if (!empty($logo))
                                        <img height="50" width="150" src="{{ url($logo) }}" alt="logo">
                                    @else
                                        <p class="mt-1 mb-1 show_branch_name"><b>{{ $siteTitle }}</b></p>
                                    @endif
                                @elseif($invoice_logo_type == 'Both')
                                    @if (!empty($logo))
                                        <img height="50" width="150" src="{{ url($logo) }}" alt="logo">
                                    @endif
                                    <p class="mt-1 mb-1 show_branch_name"><b>{{ $siteTitle }}</b></p>
                                @endif
                            @else
                                <a href="#" class="noble-ui-logo logo-light d-block mt-3">EIL<span>POS</span></a>
                            @endif
                            <p class="show_branch_address">{{ $address ?? 'Banasree' }}</p>
                            <p class="show_branch_address">{{ $email ?? '' }}</p>
                            <p class="show_branch_address">{{ $phone ?? '' }}</p>
                            {{-- <a href="#" class="noble-ui-logo logo-light d-block mt-3">EIL<span>POS</span></a>
                            <p class="mt-1 mb-1 show_branch_name"><b>{{ $branch->name ?? '' }}</b></p>
                            <p class="show_branch_address">{{ $branch->address ?? 'accordion ' }}</p>
                            <p class="show_branch_email">{{ $branch->email ?? '' }}</p>
                            <p class="show_branch_phone">{{ $branch->phone ?? '' }}</p> --}}

                            <hr>


                            <p class="mt-3 mb-1 show_supplier_name"><b>{{ $customer->name ?? '' }}</b></p>
                            <p class="show_supplier_address">{{ $customer->address ?? '' }}</p>
                            <p class="show_supplier_email">{{ $customer->email ?? '' }}</p>
                            <p class="show_supplier_phone">{{ $customer->phone ?? '' }}</p>

                        </div>
                        <div class="col-lg-3 pe-0 text-end">
                            <h4 class="fw-bolder text-uppercase text-end mt-4 mb-2">invoice</h4>
                            <h6 class="text-end mb-5 pb-4"># INV-{{ $sale->invoice_number }}</h6>
                            <p class="text-end mb-1 mt-5">Total </p>
                            <h4 class="text-end fw-normal">৳ {{ $sale->receivable ?? 00.0 }}</h4>
                            <h6 class="mb-0 mt-2 text-end fw-normal"><span class="text-muted show_purchase_date">Invoice
                                    Date :</span> {{ $sale->sale_date ?? '' }}</h6>
                        </div>
                    </div>
                    <div class="container-fluid mt-5 d-flex justify-content-center w-100">
                        <div class="table-responsive w-100">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Description</th>
                                        <th class="text-end">Unit cost</th>
                                        <th class="text-end">Quantity</th>
                                        <th class="text-end">Discount</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($products->count() > 0)
                                        @foreach ($products as $index => $product)
                                            <tr class="text-end">
                                                <td class="text-start">{{ $index + 1 }}</td>
                                                <td class="text-start">{{ $product->product->name }}</td>
                                                <td>{{ $product->rate ?? 0 }}</td>
                                                <td>{{ $product->qty ?? 0 }}</td>
                                                <td>{{ $product->discount ?? 0 }}</td>
                                                <td>{{ $product->sub_total ?? 0 }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="text-end">
                                            <td>No Data Found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="container-fluid mt-5 w-100">
                        <div class="row">
                            <div class="col-md-6 ms-auto">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody class="total_calculation_bg">
                                            <tr>
                                                @php
                                                    $productTotal = number_format($sale->total, 2);
                                                @endphp
                                                <td>Product Total</td>
                                                <td class="text-end">৳ {{ $productTotal }}</td>
                                            </tr>
                                            @php
                                                $subTotal = floatval($sale->total - $sale->actual_discount);
                                            @endphp
                                            @if ($sale->actual_discount > 0)
                                                @php
                                                    $subTotalFormatted = number_format($subTotal, 2);
                                                    $discount = number_format($sale->actual_discount, 2);
                                                @endphp
                                                <tr>
                                                    <td>Discount</td>
                                                    <td class="text-end">৳ {{ $discount }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-bold-800">Sub Total</td>
                                                    <td class="text-bold-800 text-end">৳
                                                        {{ $subTotalFormatted }} </td>
                                                </tr>
                                            @endif

                                            @if ($sale->tax != null)
                                                <tr>
                                                    <td>TAX ({{ $sale->tax }}%)</td>
                                                    <td class="text-end">৳ {{ number_format($sale->receivable, 2) }} </td>
                                                </tr>
                                            @endif
                                            @if ($sale->receivable > $subTotal)
                                                @php
                                                    $previousDue = floatval($sale->receivable - $subTotal);
                                                    $previousDueFormatted = number_format($previousDue, 2);
                                                @endphp
                                                <tr>
                                                    <td class="text-bold-800">Previous Due</td>
                                                    <td class="text-bold-800 text-end">৳
                                                        {{ $previousDueFormatted }} </td>
                                                </tr>
                                            @endif

                                            <tr>
                                                <td class="text-bold-800">Grand Total</td>
                                                <td class="text-bold-800 text-end">৳
                                                    {{ number_format($sale->receivable, 2) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Paid</td>
                                                <td class="text-success text-end">৳ {{ number_format($sale->paid, 2) }}
                                                </td>
                                            </tr>

                                            @php
                                                $mode = App\models\PosSetting::all()->first();
                                            @endphp
                                            @if ($mode->dark_mode == 1)
                                                @if ($sale->due >= 0)
                                                    <tr class="">
                                                        <td class="text-bold-800 text-danger">Due</td>
                                                        <td class="text-bold-800 text-end text-danger ">৳
                                                            {{ number_format($sale->due, 2) }} </td>
                                                    </tr>
                                                @else
                                                    <tr class="">
                                                        <td class="text-bold-800">Return</td>
                                                        <td class="text-bold-800 text-end">৳
                                                            {{ number_format($sale->due, 2) }} </td>
                                                    </tr>
                                                @endif
                                            @else
                                                @if ($sale->due >= 0)
                                                    <tr class="bg-dark print_bg_white">
                                                        <td class="text-bold-800">Due</td>
                                                        <td class="text-bold-800 text-end text-danger">৳
                                                            {{ number_format($sale->due, 2) }} </td>
                                                    </tr>
                                                @else
                                                    <tr class="bg-dark print_bg_white">
                                                        <td class="text-bold-800">Return</td>
                                                        <td class="text-bold-800 text-end">৳
                                                            {{ number_format($sale->due, 2) }} </td>
                                                    </tr>
                                                @endif
                                            @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid w-100 btn_group">
                        @if ($invoice_type == 'a4')
                            <a href="#" class="btn btn-outline-primary float-end mt-4 me-3"
                                onclick="window.print();"><i data-feather="printer" class="me-2 icon-md"></i>Print
                                Invoice</a>
                        @else
                            <a target="_blank" href="{{ route('sale.print', $sale->id) }}"
                                class="btn btn-outline-primary float-end mt-4 "><i data-feather="printer"
                                    class="me-2 icon-md"></i>Print Invoice</a>
                        @endif

                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="w-100 mx-auto btn_group">
                <a href="{{ route('sale.view') }}" class="btn btn-primary  mt-4 ms-2"><i
                        class="fa-solid fa-arrow-rotate-left me-2"></i>Manage Sale</a>
                <a href="{{ route('sale') }}" class="btn btn-outline-primary mt-4"><i data-feather="plus-circle"
                        class="me-2 icon-md"></i>Sale</a>
            </div>
        </div>
    </div>
    <style>
        @media print {

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
