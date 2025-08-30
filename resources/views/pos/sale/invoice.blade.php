@extends('master')
@section('admin')
    @php
        $branch = App\Models\Branch::findOrFail($sale->branch_id);
        $customer = App\Models\Customer::findOrFail($sale->customer_id);
        $products = App\Models\SaleItem::where('sale_id', $sale->id)->get();
        // dd($products);
    @endphp


    <div class="row ">
        <div class="col-md-12 ">
            <div class="card border-0 shadow-none invoice_bg">
                <div class="card-body ">
                    <div class="container-fluid d-flex justify-content-between">
                        <div class="col-lg-3 ps-0">
                            @if (!empty($invoice_logo_type))
                                @if ($invoice_logo_type == 'Name')
                                    <a href="#" class="noble-ui-logo logo-light d-block mt-3">{{ $siteTitle }}</a>
                                @elseif($invoice_logo_type == 'Logo')
                                    @if (!empty($logo))
                                        <img class="margin_left_m_14" height="90" width="150" src="{{ url($logo) }}"
                                            alt="logo">
                                    @else
                                        <p class="mt-1 mb-1 show_branch_name"><b>{{ $siteTitle }}</b></p>
                                    @endif
                                @elseif($invoice_logo_type == 'Both')
                                    @if (!empty($logo))
                                        <img class="margin_left_m_14" height="90" width="150"
                                            src="{{ url($logo) }}" alt="logo">
                                    @endif
                                    <p class="mt-1 mb-1 show_branch_name"><b>{{ $siteTitle }}</b></p>
                                @endif
                            @else
                                <a href="#" class="noble-ui-logo logo-light d-block mt-3">EIL<span>POS</span></a>
                            @endif
                            <p class="show_branch_address w_40">{{ $address ?? 'Banasree' }}</p>
                            <p class="show_branch_address">{{ $phone ?? '' }}, 01708008705, 01720389177</p>
                            <p class="show_branch_address">{{ $email ?? '' }}</p>
                            <!--<hr>-->
                            <p class="mt-2 show_supplier_name"><span>Customer Name:</span>
                                <b>{{ $customer->name ?? '' }}</b>
                            </p>
                            @if ($customer->address)
                                <p class="show_supplier_address"><span>Address:</span> {{ $customer->address ?? '' }}</p>
                            @endif
                            @if ($customer->email)
                                <p class="show_supplier_email"><span>Email:</span> {{ $customer->email ?? '' }}</p>
                            @endif
                            <p class="show_supplier_phone"><span>Phone:</span> {{ $customer->phone ?? '' }}</p>

                        </div>
                        <div class="col-lg-3 pe-0 text-end">
                            <h4 class="fw-bolder text-uppercase text-end mt-4 mb-2">invoice</h4>
                            <h6 class="text-end mb-5 pb-4"># INV-{{ $sale->invoice_number ?? 0 }}</h6>
                            @if ($sale->due > 0)
                                <p class="text-end mb-1 mt-5">Due</p>
                                <h4 class="text-end fw-normal text-danger">৳ {{ $sale->due ?? 00.0 }}</h4>
                            @else
                                <p class="text-end mb-1 mt-5">Total Paid</p>
                                <h4 class="text-end fw-normal text-success">৳ {{ $sale->paid ?? 00.0 }}</h4>
                            @endif
                            <h6 class="mb-0 mt-2 text-end fw-normal"><span class="text-muted show_purchase_date">Invoice
                                    Date :</span> {{ $sale->sale_date ?? '' }}</h6>
                        </div>
                    </div>
                    <img src="{{ asset('assets/images/stamp.png') }}" class="img-fluid stamp-image" alt="">
                    <div class="container-fluid mt-2 d-flex justify-content-center w-100">
                        <div class="w-100">
                            {{-- @dd($products); --}}
                            <style>

                            </style>
                            <table class="table table-bordered invoice_table_bg">
                                <thead>
                                    <tr class="invoice_table_th_bg" style="border: 1px solid #29ADF9;">
                                        <th>#</th>
                                        <th>Product Name</th>
                                        @if ($color_view == 1)
                                            <th>Color</th>
                                        @endif
                                        @if ($size_view == 1)
                                            <th>Size</th>
                                        @endif
                                        @if ($warranty_status == 1)
                                            <th class="text-end">Warranty</th>
                                        @endif

                                        <th class="text-end">Unit cost</th>
                                        <th class="text-end">Quantity</th>
                                        @if ($sale_hands_on_discount == 1)
                                            <th class="text-end">Discount</th>
                                        @endif
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($products->count() > 0)
                                        @php $lastIndex = 0; @endphp
                                        @foreach ($products as $index => $product)
                                            <tr class="text-end" style="border: 1px solid #29ADF9;">
                                                <td class="text-start">{{ $index + 1 }}</td>
                                                <td class="text-start">{{ $product->product->name ?? '' }}</td>
                                                @if ($color_view == 1)
                                                    <td class="text-start">{{ $product->variant->colorName->name ?? '' }}
                                                    </td>
                                                @endif
                                                @if ($size_view == 1)
                                                    <td class="text-start">
                                                        {{ $product->variant->variationSize->size ?? '' }}
                                                    </td>
                                                @endif
                                                @if ($warranty_status == 1)
                                                    <td>{{ $product->wa_duration ?? 0 }}</td>
                                                @endif
                                                <td>{{ $product->rate ?? 0 }}</td>
                                                <td>{{ $product->qty ?? 0 }}</td>
                                                @if ($sale_hands_on_discount == 1)
                                                    <td>{{ $product->discount ?? 0 }}</td>
                                                @endif
                                                <td>{{ $product->sub_total ?? 0 }}</td>
                                            </tr>
                                            @php $lastIndex = $index + 1; @endphp
                                        @endforeach
                                        @for ($i = $lastIndex + 1; $i < 16; $i++)
                                            <tr class="text-start" style="border: 1px solid #29ADF9;">
                                                <td class="text-start">{{ $i }}</td>
                                                <td class="text-start"></td>
                                                @if ($color_view == 1)
                                                    <td></td>
                                                @endif
                                                @if ($color_view == 1)
                                                    <td></td>
                                                @endif
                                                @if ($warranty_status == 1)
                                                    <td></td>
                                                @endif
                                                <td></td>
                                                @if ($sale_hands_on_discount == 1)
                                                    <td></td>
                                                @endif
                                                <td></td>
                                                <td class="table-bordered "></td>
                                            </tr>
                                        @endfor
                                    @else
                                        <tr class="text-center">
                                            <td>Data Not Found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="container-fluid mt-2">
                        <div class="row">
                            <div class="col-md-6 ms-auto total_calculation">
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

                                                $subTotalFormatted = number_format($subTotal, 2);
                                                $discount = number_format($sale->actual_discount, 2);
                                                $percentage = number_format($sale->discount);
                                            @endphp
                                            @if ($sale->actual_discount > 0)
                                                @if ($sale->discount_type == 'fixed')
                                                    <tr>
                                                        <td>Discount</td>
                                                        <td class="text-end">৳ {{ $discount }}</td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td>Discount ({{ $percentage }} %)</td>
                                                        <td class="text-end">৳ {{ $discount }}</td>
                                                    </tr>
                                                @endif
                                            @endif

                                            @if ($tax == 1)
                                                <tr>
                                                    <td>TAX ({{ $sale->tax }}%)</td>
                                                    <td class="text-end">৳
                                                        {{ number_format(($subTotal * $sale->tax) / 100, 2) }}
                                                    </td>
                                                </tr>
                                            @endif

                                            @if ($invoice_payment == 0)
                                                <tr>
                                                    <td class="text-bold-800">Sub Total</td>
                                                    <td class="text-bold-800 text-end">৳
                                                        {{ $sale->change_amount ?? 00 }} </td>
                                                </tr>

                                                @if ($sale->receivable > $subTotal)
                                                    @php
                                                        $previousDue = floatval(
                                                            $sale->receivable - $sale->change_amount,
                                                        );
                                                        $previousDueFormatted = number_format($previousDue, 2);
                                                    @endphp
                                                    <tr>
                                                        <td class="text-bold-800">Previous Due</td>
                                                        <td class="text-bold-800 text-end">৳
                                                            {{ $previousDueFormatted }} </td>
                                                    </tr>
                                                @endif
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

                                            @if ($sale->due > 0)
                                                <tr class=" print_bg_white">
                                                    <td class="text-bold-800">Due</td>
                                                    <td class="text-bold-800 text-end text-danger">৳
                                                        {{ number_format($sale->due, 2) }} </td>
                                                </tr>
                                            @elseif ($sale->due == 0)
                                                <tr></tr>
                                            @else
                                                <tr class=" print_bg_white">
                                                    <td class="text-bold-800">Return</td>
                                                    <td class="text-bold-800 text-end">৳
                                                        {{ number_format($sale->due, 2) }} </td>
                                                </tr>
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
                        @elseif($invoice_type == 'a5')
                            <a href="#" class="btn btn-outline-primary float-end mt-4" onclick="window.print();"><i
                                    data-feather="printer" class="me-2 icon-md"></i>Print Invoice</a>
                        @else
                            <a target="" href="{{ route('sale.print', $sale->id) }}"
                                class="btn btn-outline-primary float-end mt-4 "><i data-feather="printer"
                                    class="me-2 icon-md"></i>Print Invoice</a>
                        @endif
                    </div>
                    <div>
                        <h5 class="">Sale by:</h5>
                        <h6 class="underline-name">{{ $sale->saleBy->name }}</h6>
                        <div style="width:10px; height:10px; color:#000"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="w-100 mx-auto btn_group">
                <a href="{{ route('sale.view.all') }}" class="btn btn-primary  mt-4 ms-2"><i
                        class="fa-solid fa-arrow-rotate-left me-2"></i>Manage Sale</a>
                <a href="{{ route('sale') }}" class="btn btn-outline-primary mt-4"><i data-feather="plus-circle"
                        class="me-2 icon-md"></i>Sale</a>
            </div>
        </div>
    </div>
    <style>
        .underline-name {
            position: relative;
            display: inline-block;
            padding-bottom: 4px;
        }

        .underline-name::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 1px;
            background-color: #494949;
        }


        .table> :not(caption)>*>* {
            padding: 0px 10px !important;
        }

        .margin_left_m_14 {
            margin-left: -14px;
        }

        .w_40 {
            width: 250px !important;
            text-wrap: wrap;
        }

        @if ($sale->due <= 0)
            .stamp-image {
                position: absolute !important;
                top: 50% !important;
                left: 50% !important;
                transform: translate(-50%, -50%);
                height: 220px !important;
                opacity: 0.3 !important;
                display: block;
            }
        @else
            .stamp-image {
                display: none !important;
                opacity: 0 !important;
            }
        @endif

        @if ($sale->due <= 0)
            .stamp-image {
                position: absolute !important;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                height: 220px !important;
                opacity: 0.5 !important;
                display: block;
            }
        @else
            .stamp-image {
                display: none !important;
                opacity: 0 !important;
            }
        @endif

        @media print {
            @if ($invoice_type == 'a4')
                @page {
                    size: A4;
                }
            @elseif($invoice_type == 'a5')
                @page {
                    size: A5;
                }
            @endif
            nav,
            .footer {
                display: none !important;
            }

            .page-content {
                margin-top: 0 !important;
                padding-top: 0 !important;
                min-height: 740px !important;
                /* background-color: #eec6a1 !important; */
                /* background-color: #cce9fa !important; */
                background-color: #ffffff !important;
                /* border: 1px solid #29ADF9; */
            }

            .btn_group {
                display: none !important;
            }

            .total_calculation {
                float: right !important;
                /* margin-right: -40px; */
                width: 50%;
                color: #000
            }

            .card-body {
                padding: 0px !important;
                margin-left: 0px !important;
            }

            .card {
                /* padding: 0px !important; */
                /* margin: 0px !important; */
            }

            .main-wrapper .page-wrapper .page-content {
                /* margin-left: -10px !important; */
                padding: 0px;

            }

            .margin_left_m_14 {
                margin-left: -14px;
            }

            .w_40 {
                width: 240px !important;
            }

            /*.table> :not(caption)>*>* {*/
            /*    padding: 0px 10px !important;*/
            /*}*/

            .invoice_bg {
                background-color: #ffffff !important;
                /* background-color: #cce9fa !important; */
                /* background-color: #eec6a1 !important; */
                color: #000 !important;
                height: 740px;
            }

            .invoice_table_bg {
                /* background-color: rgb(241, 204, 204) !important; */
                color: #000000 !important;
                border-color: #29ADF9;
            }

            .invoice_table_th_bg {
                background-color: #29ADF9 !important;
                color: #000000 !important;
            }

            .invoice_table_th_bg th {

                color: #000000 !important;
            }

            .total_calculation_bg {
                color: #000 !important;
            }

            .print_bg_white {
                background-color: transparent !important;
            }
        }
    </style>

    <script>
        let invoiceType = "{{ $invoice_type }}";

        function setPaperSize(invoiceType) {
            let styleElement = document.getElementById('print-style');

            if (!styleElement) {
                styleElement = document.createElement('style');
                styleElement.id = 'print-style';
                document.head.appendChild(styleElement);
            }

            let sizeCss;

            switch (size) {
                case 'a4':
                    sizeCss = '@page { size: A4; }';
                    break;
                case 'a5':
                    sizeCss = '@page { size: A5; }';
                    break;
                case 'letter':
                    sizeCss = '@page { size: letter; }';
                    break;
                case 'custom':
                    sizeCss = '@page { size: 210mm 297mm; }'; // Example for A4 size in custom dimensions
                    break;
                default:
                    sizeCss = '@page { size: auto; }'; // Default
            }

            styleElement.innerHTML = `
                @media print {
                    ${sizeCss}
                }
            `;
        }
    </script>
@endsection
