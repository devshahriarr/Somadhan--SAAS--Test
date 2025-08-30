@extends('master')
@section('title', '| Invoice Page')
@section('admin')
<div class="invoice-1 invoice-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="invoice-inner clearfix">
                    <div class="invoice-info clearfix" id="invoice_wrapper">
                        <div class="invoice-headar">
                            <div class="row g-0">
                                <div class="col-sm-6">
                                    <div class="invoice-logo">
                                        <!-- logo started -->
                                        <div class="logo">
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
                                           </span>
                                            <a href="#" class="noble-ui-logo logo-light d-block mt-3">EIL<span>POS</span></a>
                                        @endif
                                        </div>
                                        <!-- logo ended -->
                                    </div>
                                </div>
                                <div class="col-sm-6 invoice-id">
                                    <div class="info">
                                        <h1 class="color-white inv-header-1">Invoice</h1>
                                        <p class="color-white mb-1">Invoice Number <span>#45613</span></p>
                                        <p class="color-white mb-0">Invoice Date <span>21 Sep 2021</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="invoice-top">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="invoice-number mb-30">
                                        <h4 class="inv-title-1">Invoice To</h4>
                                        <h2 class="name mb-10">Jhon Smith</h2>
                                        <p class="invo-addr-1">
                                            Theme Vessel <br/>
                                            info@themevessel.com <br/>
                                            21-12 Green Street, Meherpur, Bangladesh <br/>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="invoice-number mb-30">
                                        <div class="invoice-number-inner">
                                            <h4 class="inv-title-1">Invoice From</h4>
                                            <h2 class="name mb-10">Animas Roky</h2>
                                            <p class="invo-addr-1">
                                                Apexo Inc  <br/>
                                                billing@apexo.com <br/>
                                                169 Teroghoria, Bangladesh <br/>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="invoice-center">
                            <div class="table-responsive">
                                <table class="table mb-0 table-striped invoice-table">
                                    <thead class="bg-active">
                                    <tr class="tr">
                                        <th>No.</th>
                                        <th class="pl0 text-start">Item Description</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="tr">
                                        <td>
                                            <div class="item-desc-1">
                                                <span>01</span>
                                            </div>
                                        </td>
                                        <td class="pl0">Businesscard Design</td>
                                        <td class="text-center">$300</td>
                                        <td class="text-center">2</td>
                                        <td class="text-end">$600.00</td>
                                    </tr>
                                    <tr class="bg-grea">
                                        <td>
                                            <div class="item-desc-1">
                                                <span>02</span>

                                            </div>
                                        </td>
                                        <td class="pl0">Fruit Flayer Design</td>
                                        <td class="text-center">$400</td>
                                        <td class="text-center">1</td>
                                        <td class="text-end">$60.00</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="item-desc-1">
                                                <span>03</span>
                                            </div>
                                        </td>
                                        <td class="pl0">Application Interface Design</td>
                                        <td class="text-center">$240</td>
                                        <td class="text-center">3</td>
                                        <td class="text-end">$640.00</td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="item-desc-1">
                                                <span>04</span>
                                            </div>
                                        </td>
                                        <td class="pl0">Theme Development</td>
                                        <td class="text-center">$720</td>
                                        <td class="text-center">4</td>
                                        <td class="text-end">$640.00</td>
                                    </tr>
                                    <tr class="tr2">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-center">SubTotal</td>
                                        <td class="text-end">$710.99</td>
                                    </tr>
                                    <tr class="tr2">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-center">Tax</td>
                                        <td class="text-end">$85.99</td>
                                    </tr>
                                    <tr class="tr2">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-center f-w-600 active-color">Grand Total</td>
                                        <td class="f-w-600 text-end active-color">$795.99</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="invoice-bottom">
                            <div class="row">
                                <div class="col-lg-6 col-md-8 col-sm-7">
                                    <div class="mb-30 dear-client">
                                        <h3 class="inv-title-1">Terms & Conditions</h3>
                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been typesetting industry. Lorem Ipsum</p>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-4 col-sm-5">
                                    <div class="mb-30 payment-method">
                                        <h3 class="inv-title-1">Payment Method</h3>
                                        <ul class="payment-method-list-1 text-14">
                                            <li><strong>Account No:</strong> 00 123 647 840</li>
                                            <li><strong>Account Name:</strong> Jhon Doe</li>
                                            <li><strong>Branch Name:</strong> xyz</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="invoice-contact clearfix">
                            <div class="row g-0">
                                <div class="col-lg-9 col-md-11 col-sm-12">
                                    <div class="contact-info">
                                        <a href="tel:+55-4XX-634-7071"><i class="fa fa-phone"></i> +00 123 647 840</a>
                                        <a href="tel:info@themevessel.com"><i class="fa fa-envelope"></i> info@themevessel.com</a>
                                        <a href="tel:info@themevessel.com" class="mr-0 d-none-580"><i class="fa fa-map-marker"></i> 169 Teroghoria, Bangladesh</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="invoice-btn-section clearfix d-print-none">
                        <a href="javascript:window.print()" class="btn btn-lg btn-print">
                            <i class="fa fa-print"></i> Print Invoice
                        </a>
                        <a id="invoice_download_btn" class="btn btn-lg btn-download btn-theme">
                            <i class="fa fa-download"></i> Download Invoice
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@php
$mode = App\models\PosSetting::all()->first();
@endphp
<style>
    .table {
    color: #535353;
}

.invoice-content {
    font-family: 'Poppins', sans-serif;
    color: #120a0a;
    font-size: 14px;
}

.invoice-content a {
    text-decoration: none;
}

.invoice-content .img-fluid {
    max-width: 100% !important;
    height: auto;
}

.invoice-content .form-control:focus {
    box-shadow: none;
}

.invoice-content h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
    font-family: 'Poppins', sans-serif;
    color: #535353;
}

.mb-0{
    margin-bottom: 0;
}

.mb-10{
    margin-bottom: 10px;
}

.mb-20{
    margin-bottom: 20px;
}

.mb-30{
    margin-bottom: 30px;
}

.container{
    max-width: 1000px;
    margin: 0 auto;
}

/** BTN LG **/
.btn-lg {
    font-size: 14px;
    height: 50px;
    padding: 0 30px;
    line-height: 50px;
    border-radius: 3px;
    color: #ffffff;
    border: none;
    margin: 0 3px 3px;
    display: inline-block;
    vertical-align: middle;
    -webkit-appearance: none;
    text-transform: capitalize;
    transition: all 0.3s linear;
    z-index: 1;
    position: relative;
    overflow: hidden;
    text-align: center;
}

.btn-lg:hover {
    color: #ffffff;
}

.btn-lg:hover:after {
    transform: perspective(200px) scaleX(1.05) rotateX(0deg) translateZ(0);
    transition: transform 0.9s linear, transform 0.4s linear;
}

.btn-lg:after {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    content: "";
    transform: perspective(200px) scaleX(0.1) rotateX(90deg) translateZ(-10px);
    transform-origin: bottom center;
    transition: transform 0.9s linear, transform 0.4s linear;
    z-index: -1;
}

.btn-check:focus+.btn, .btn:focus {
    outline: 0;
    box-shadow: none;
}
.btn-print{
    background-image: linear-gradient(to bottom, #54544d, #1a1918);
}

.btn-print:after {
    background-image: linear-gradient(to bottom, #1a1918, #54544d);
}

.invoice-content .f-w-600 {
    font-weight: 500 !important;
}

.invoice-content .text-14 {
    font-size: 14px;
}

.invoice-content .invoice-table th:first-child,
.invoice-content .invoice-table td:first-child {
    text-align: left;
}

.invoice-content .color-white {
    color: #fff!important;
}

.invoice-content .inv-header-1 {
    text-transform: uppercase;
    font-weight: 700;
    font-size: 24px;
}

.invoice-content .inv-header-2 {
    text-transform: uppercase;
    font-weight: 600;
    font-size: 20px;
}

.invoice-content .inv-title-1 {
    font-weight: 500;
    font-size: 16px;
}

.invoice-content .invo-addr-1 {
    font-size: 14px;
    margin-bottom: 20px;
    line-height: 23px;
}

.invoice-content .item-desc-1 {
    text-align: left;
}

.invoice-content .item-desc-1 span {
    display: block;
}

.invoice-content .item-desc-1 small {
    display: block;
}

.invoice-content .important-notes-list-1 {
    font-size: 14px !important;
    padding-left: 15px;
    margin-bottom: 15px;
}

.invoice-content .important-notes-list-1 li {
    margin-bottom: 5px;
}

.invoice-content .bank-transfer-list-1 {
    font-size: 13px !important;
    padding-left: 0px;
}

.invoice-content .important-notes {
    font-size: 12px !important;
}

.invoice-content .invoice-btn-section {
    text-align: center;
    margin-top: 27px;
}

table th{
    font-weight: 400;
}

.btn-download {
    background: #3965e3;
}

.btn-download:after {
    background: #325cd5;
}

.btn-print{
    background: #3a3939;
}

.btn-print:after {
    background: #1d1c1c;
}

/** Invoice 1 Start **/
.invoice-1 {
    padding: 30px 0;
    background: #fff6f6;
}

.invoice-1 .mb-30 {
    margin-bottom: 30px;
}

.invoice-1 .invoice-info {
    background: #fff;
    position: relative;
}

.invoice-1 .name{
    font-size: 18px;
    margin-bottom: 5px;
    text-transform: uppercase;
    color: #262525;
    font-weight: 500;
}

.invoice-1 .mb-10{
    margin-bottom: 10px;
}

.invoice-1 .invoice-headar {
    height: 125px;
    margin-bottom: 25px;
    background: #f3f3f3;
}

.invoice-1 .invoice-headar p span{
    float: right;
}

.invoice-1 .invoice-number-inner{
    max-width: 200px;
    margin-left: auto;
}

.invoice-1 .invoice-id .info{
    max-width: 200px;
    margin:0 50px 0 auto;
    padding: 34px 0;
}

.invoice-1 .invoice-id{
    border-radius: 75px 0 0 75px;
    z-index: 0;
    background-image: linear-gradient(to bottom, #ff0000, #ff8100);
}

.invoice-1 .payment-method-list-1{
    padding: 0;
}

.invoice-1 .item-desc-1 span {
    font-size: 14px;
    font-weight: 500;
}

.invoice-1 .payment-method{
    max-width: 200px;
    margin-left: auto;
}

.invoice-1 .payment-method ul {
    list-style: none;
}

.invoice-1 .payment-method ul li strong{
    font-weight: 500;
}

.invoice-1 .table-striped > tbody > tr:nth-of-type(odd) {
    --bs-table-accent-bg: rgb(255 255 255 / 5%);
    color: var(--bs-table-striped-color);
}

.invoice-1 table th {
    font-weight: 500;
    text-transform: uppercase;
}

.invoice-1 .invoice-top {
    padding: 40px 50px 10px;
    font-size: 15px;
}

.invoice-1 .inv-title-1{
    color: #575352;
    margin-bottom: 5px;
}

.invoice-1 .invoice-logo{
    padding: 50px;
}

.invoice-1 .invoice-logo img {
    height: 25px;
}

.invoice-1 .table-section {
    text-align: center;
}

.invoice-1 .invoice-center {
    padding: 0 50px 40px;
}

.invoice-1 .table > :not(caption) > * > * {
    padding: 13px 30px;
}

.invoice-1 .table > :not(caption) > * > * {
    background-color: var(--bs-table-bg);
    border-bottom-width: 0;
}

.invoice-1 .table .pl0{
    padding-left: 0;
}

.invoice-1 .table td.pl0{
    padding-left: 0;
}

.invoice-1 .table td, .invoice-1 .table th {
    vertical-align: middle;
    border: none !important;
}

.invoice-1 .table td {
    font-size: 15px;
    color: #555;
}

.invoice-1 p{
    font-size: 14px;
}

.invoice-1 .invoice-info-buttom .table .invoice-1 .invoice-info-buttom .table tr, .table tr {
    border: 1px solid hsl(210, 16%, 93%);
}
.invoice-1 .caption-top {
    caption-side: top;
    text-align: right;
    margin-bottom: 0;
}
.invoice-1 .invoice-bottom {
    padding: 0 50px 10px;
}

.invoice-1 .bg-active {
    background: #f3f3f3;
    color: #535353!important;
}
.invoice-1 .active-color{
    color: #535353!important;
}

.invoice-1 .invoice-bottom h3 {
    margin-bottom: 7px;
}
.invoice-1 .contact-info {
    padding: 30px 50px;
    border-radius: 0 40px 40px 0;
    background-image: linear-gradient(to bottom, #f3f3f3, #ffffff);
}
.invoice-1 .contact-info a {
    margin-right: 20px;
    color: #535353;
    font-size: 14px;
}
.invoice-1 .contact-info .mr-0{
    margin-right: 0;
}
.invoice-1 .inv-header-1 {
    font-weight: 600;
}
.invoice-1 .invoice-contact::after {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 30%;
    height: 30px;
    border-radius: 15px 0 0 15px;
    z-index: 0;
    background-image: linear-gradient(to bottom, #ff0000, #ff8100);
}
/** MEDIA **/
@media (max-width: 992px) {
    .invoice-1 {
        padding: 30px 0;
    }
}

@media (max-width: 768px) {
    .invoice-1 .table > :not(caption) > * > * {
        padding: 15px 10px;
    }

    .invoice-1.invoice-content .color-white {
        color: #262525!important;
    }

    .invoice-1 .payment-method {
        margin: 0 auto 30px 0;
    }

    .invoice-1 .invoice-top {
        padding: 30px 30px 0;
    }

    .invoice-1 .contact-info {
        padding: 30px;
        border-radius: 0;
    }

    .invoice-1 .invoice-center {
        padding: 0 30px 30px;
    }

    .invoice-1 .invoice-contact::after {
        display: none;
    }

    .invoice-1 .invoice-bottom {
        padding: 0 30px;
    }

    .invoice-1 .invoice-id {
        background: transparent;
    }

    .invoice-1 .invoice-logo {
        padding: 0;
        margin-bottom: 10px;
    }

    .invoice-1 .invoice-headar {
        height: auto;
        margin-bottom: 0;
        padding: 30px;
    }

    .invoice-1 .invoice-id .info {
        margin: 0 auto 0 15px;
        padding: 0;
    }

    .invoice-1 .invoice-number-inner {
        margin: 0 auto 0 0;
    }
}

@media (max-width: 580px){
    .invoice-1 .invoice-id .info {
        margin: 0 auto 0 15px;
    }

    .invoice-1 .invoice-id .info {
        margin: 0 auto 0 0;
    }

    .invoice-1 .d-none-580{
        display: none!important;
    }
}
/** Invoice 1 end **/

@media (max-width: 768px) {
    .btn-lg {
        font-size: 13px;
        height: 40px;
        padding: 0 20px;
        line-height: 40px;
        border-radius: 3px;
    }
}


/** Print **/
@media print {
    .col-sm-12 {
        width: 100%;
    }

    .col-sm-11 {
        width: 91.66666667%;
    }

    .col-sm-10 {
        width: 83.33333333%;
    }

    .col-sm-9 {
        width: 75%;
    }

    .col-sm-8 {
        width: 66.66666667%;
    }

    .col-sm-7 {
        width: 58.33333333%;
    }

    .col-sm-6 {
        width: 50%;
    }

    .col-sm-5 {
        width: 41.66666667%;
    }

    .col-sm-4 {
        width: 33.33333333%;
    }

    .col-sm-3 {
        width: 25%;
    }

    .col-sm-2 {
        width: 16.66666667%;
    }
    .col-sm-1 {
        width: 8.33333333%;
    }

    .text-end {
        text-align: right !important;
    }
    .invoice-1 {
        padding: 0;
        background: #fff;
    }

    .invoice-1 .invoice-inner {
        background: #f8f8f8;
    }

    .invoice-1 .container {
        padding: 0px;
    }

    .invoice-1 .invoice-info {
        box-shadow: none;
        margin: 0px;
    }

    .invoice-1 .invoice-headar {
        background: #f3f3f3;
    }

    .invoice-1 .inv-title-1 {
        color: #535353;
    }

    .invoice-content .color-white {
        color: #262525!important;
    }

    .invoice-1 .bg-active {
        background: #f3f3f3!important;
        color: #262525!important;
    }

    .invoice-1 .contact-info {
        background: #f3f3f3;
    }

    .invoice-1 .active-color {
        color: #262525!important;
    }

}

 @if($mode->dark_mode == 2)
 .invoice-1 {
    padding: 30px 0;
    background: #0C1427;
}
.invoice-1 {
        padding: 0;
        background: #0C1427;
    }

    .invoice-1 .invoice-info {
    background: #0C1427;
    position: relative;
}
.invoice-1 .invoice-headar {
        background: #21305216;
    }
    .invoice-1 .inv-title-1{
    color: #fff;
    margin-bottom: 5px;
}
.invoice-1 .bg-active {
        background: #0C1427!important;
        color: #fff!important;
    }
    .invoice-content {
    font-family: 'Poppins', sans-serif;
    color: #ffff;
    font-size: 14px;
}
.invoice-1 .name{
    color: #fff;
}
.invoice-1 .contact-info {
    background-image: linear-gradient(to bottom, #21305216, #21305216);
}
.invoice-1 .contact-info a {
    color: #fff;
}
    @else
    @endif
</style>
@endsection
