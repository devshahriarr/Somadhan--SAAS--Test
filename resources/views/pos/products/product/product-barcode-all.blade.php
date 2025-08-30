<div class="bbcode">
    @foreach ($variants as $item)
        @php
            $variant = $item['variant'];
            $quantity = $item['quantity'];
        @endphp

        @for ($i = 0; $i < $quantity; $i++)
            <div class="printable" style="@if ($barcode_type === 'single') width: 100%; @else width: 33.3%; @endif">
                <div class="barcode-container ">
                    <span class="dblock">
                        @php
                            $barcodeData = DNS1D::getBarcodePNG($variant->barcode, 'C39', 2, 60);
                        @endphp
                        <img class="barcode_image" src="data:image/png;base64,{{ $barcodeData }}" alt="Barcode"> </br>
                        <span class="barcode_text">{{ $variant->barcode }}</span><br>
                        <span class="barcode_text">{{ $variant->product->name ?? '' }} </span><br>
                        @if (!empty($variant->colorName->name))
                            <span class="barcode_text">Color :{{ $variant->colorName->name }} </span><br>
                        @endif
                        @if (!empty($variant->variationSize->size))
                            <span class="barcode_text"> Size :{{ $variant->variationSize->size }} </span><br>
                        @endif
                        {{-- <span class="bold barcode_text">{{ $variant->b2c_price ?? 0 }} TK</span> --}}
                    </span>
                </div>
            </div>
        @endfor
    @endforeach
</div>


<style>
    @media print {

        .dblock {
            margin: 0px;
        }

        .barcode_text {
            font-size: 8px;
        }

        .barcode-container {
            /* padding-top: 3px; */
            width: 95%;
            height: 96px;
        }

        .header,
        .nav,
        .sidebar,
        .navbar,
        #myfooter {
            display: none !important;
            height: 0px !important;
        }

        .printable {
            page-break-inside: avoid;
            margin: 0px;
            display: flex;
            justify-content: center;
            text-align: center;
        }

        .barcode_image {
            margin-top: 5px !important;
            width: 100%;
            height: 60%;
        }

        @page {
            size: auto;
        }

        body {
            margin: 0;
            padding: 0;
        }
    }
</style>
