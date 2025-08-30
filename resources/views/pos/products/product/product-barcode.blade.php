<style>
    .barcode-container {
        text-align: center;
        border: 1px solid #fc4700;
        /* padding: 10px; */

        width: 100%;
        height: 100%;
    }

    .bbcode {
        display: flex;
        flex-wrap: wrap;
    }

    .barcode_image {
        width: 100%;
        height: 100%;
    }
    /* .printable {
            text-align: center;

        } */

    @media print {
        .header {
            display: none !important;
        }

        #printContent,
        #printContent * {
            visibility: visible;
        }

        #printContent {
            position: absolute;
            left: 0;
            top: 0;
        }

        .header,
        .nav,
        .sidebar,
        .navbar {
            display: none;
        }

        .navbar {
            margin-top: 0 !important;
            display: none !important;
        }

        #myfooter {
            display: none !important;
        }

        .page-content {
            margin-top: 0 !important;
        }

        .btn-print {
            display: none !important;
        }

        .printable {
            display: flex !important;
            justify-content: center;
            align-items: center;
            padding: 0px !important;
            margin: 0px !important;
            border: 1px solid #fc4700;
            width: 120px;
            height: 70px;
        }
    }
</style>

<div class="bbcode">
    @for ($i = 0; $i < ($quantity ?? 1); $i++)
        <div class="printable" style="@if ($barcode_type === 'single') width: 100%; @else width: 33.3%; @endif">
            <div class="barcode-container">
                <span class="dblock">
                    <img class="barcode_image"
                        src="data:image/png;base64,{{ DNS1D::getBarcodePNG($variant->barcode, 'C39', 1, 30) }}"
                        alt="Barcode"> </br>
                    <span>{{ $variant->barcode }}</span><br>
                    <span>{{ $variant->product->name ?? '' }} </span><br>
                    <span class="bold">{{ $variant->b2c_price ?? 0 }} TK</span>
                </span>
            </div>
        </div>
    @endfor
</div>
