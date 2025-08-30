@extends('master')
@section('title', '| Settings Page')
@section('admin')
    @php
        $mode = App\models\PosSetting::all()->first();
    @endphp
    <h2 style="margin: 20px">Sale Settings</h2>
    <div class="row">
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <form id="myValidForm" action="{{ route('sale.settings.update') }}" method="post">
                        @csrf
                        <input type="hidden" name="setting_id"value="1">
                        <!-- Row -->
                        <div class="row">
                            <h6 class="card-title text-info">Sale Settings</h6><br><br>
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            {{ $mode->sale_page == 1 ? 'checked' : '' }}
                                            name="sale_page" role="switch" id="flexSwitchCheckDefault12">
                                        <label class="form-check-label" for="flexSwitchCheckDefault12">Sale Page
                                            </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            {{ $mode->make_invoice_print == 1 ? 'checked' : '' }}
                                            name="make_invoice_print" role="switch" id="flexSwitchCheckDefault125">
                                        <label class="form-check-label" for="flexSwitchCheckDefault125">Make Invoice Print
                                            </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            {{ $mode->auto_genarate_invoice == 1 ? 'checked' : '' }}
                                            name="auto_genarate_invoice" role="switch" id="flexSwitchCheckDefault">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">Update Manual Invoice
                                            Number</label>
                                    </div>
                                </div>
                            </div>
                            @if (Auth::user()->can('discount.promotion'))
                                <div class="col-sm-6">
                                    <div class="mb-3 form-valid-groups">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                {{ $mode->discount == 1 ? 'checked' : '' }} name="discount" role="switch"
                                                id="flexSwitchCheckDefault">
                                            <label class="form-check-label"
                                                for="flexSwitchCheckDefault">Discount/Promotion</label>
                                        </div>
                                        {{-- {{ $allData->dark_mode == 2 ?  'checked' : '' }} --}}
                                    </div>
                                </div>
                            @endif
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            {{ $mode->sale_hands_on_discount == 1 ? 'checked' : '' }} name="sale_hands_on_discount" role="switch"
                                            id="flexSwitchCheckDefault">
                                        <label class="form-check-label"
                                            for="flexSwitchCheckDefault">Sale Hands on Discount</label>
                                    </div>
                                    {{-- {{ $allData->dark_mode == 2 ?  'checked' : '' }} --}}
                                </div>
                            </div>
                            @if (Auth::user()->can('tax'))
                                <div class="col-sm-6">
                                    <div class="mb-3 form-valid-groups">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                {{ $mode->tax == 1 ? 'checked' : '' }} name="tax" role="switch"
                                                id="flexSwitchCheckDefault">
                                            <label class="form-check-label" for="flexSwitchCheckDefault">Tax</label>
                                        </div>
                                        {{-- {{ $allData->dark_mode == 2 ?  'checked' : '' }} --}}
                                    </div>
                                </div>
                            @endif
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            {{ $mode->sale_with_low_price == 1 ? 'checked' : '' }} name="sale_with_low_price"
                                            role="switch" id="flexSwitchCheckDefault">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">sale with low price</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            {{ $mode->sale_commission == 1 ? 'checked' : '' }} name="sale_commission"
                                            role="switch" id="flexSwitchCheckDefault">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">Sale Commission</label>
                                    </div>
                                </div>
                            </div>
                            @if (Auth::user()->can('barcode'))
                                <div class="col-sm-6">
                                    <div class="mb-3 form-valid-groups">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                {{ $mode->barcode == 1 ? 'checked' : '' }} name="barcode" role="switch"
                                                id="flexSwitchCheckDefault">
                                            <label class="form-check-label" for="flexSwitchCheckDefault">Barcode</label>
                                        </div>
                                        {{-- {{ $allData->dark_mode == 2 ?  'checked' : '' }} --}}
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->can('via.sale'))
                                <div class="col-sm-6">
                                    <div class="mb-3 form-valid-groups">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                {{ $mode->via_sale == 1 ? 'checked' : '' }} name="via_sale" role="switch"
                                                id="flexSwitchCheckDefault">
                                            <label class="form-check-label" for="flexSwitchCheckDefault">Via Sale</label>
                                        </div>
                                        {{-- {{ $allData->dark_mode == 2 ?  'checked' : '' }} --}}
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->can('sale.price.edit'))
                                <div class="col-sm-6">
                                    <div class="mb-3 form-valid-groups">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                {{ $mode->selling_price_edit == 1 ? 'checked' : '' }}
                                                name="selling_price_edit" role="switch" id="flexSwitchCheckDefault">
                                            <label class="form-check-label" for="flexSwitchCheckDefault">Selling Price
                                                Edit</label>
                                        </div>
                                        {{-- {{ $allData->dark_mode == 2 ?  'checked' : '' }} --}}
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->can('sale.price.update'))
                                <div class="col-sm-6">
                                    <div class="mb-3 form-valid-groups">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                {{ $mode->selling_price_update == 1 ? 'checked' : '' }}
                                                name="selling_price_update" role="switch" id="flexSwitchCheckDefault">
                                            <label class="form-check-label" for="flexSwitchCheckDefault">Update Price from
                                                Sale</label>
                                        </div>
                                        {{-- {{ $allData->dark_mode == 2 ?  'checked' : '' }} --}}
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->can('warranty.satus'))
                                <div class="col-sm-6">
                                    <div class="mb-3 form-valid-groups">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                {{ $mode->warranty == 1 ? 'checked' : '' }} name="warranty"
                                                role="switch" id="flexSwitchCheckDefault">
                                            <label class="form-check-label" for="flexSwitchCheckDefault">Warranty
                                                Status</label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            {{-- @if (Auth::user()->can('')) --}}

                                <div class="col-sm-6">
                                    <div class="mb-3 form-valid-groups">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                {{ $mode->sale_without_stock == 1 ? 'checked' : '' }} name="sale_without_stock"
                                                role="switch" id="flexSwitchCheckDefault">
                                            <label class="form-check-label" for="flexSwitchCheckDefault">Sale Without Stock</label>
                                        </div>
                                    </div>
                                </div>
                            {{-- @endif --}}
                            @if (Auth::user()->can('sale.price.type'))
                                <div class="col-sm-12">
                                    <div class="mb-3 form-valid-groups">
                                        <label class="form-label">Sale Price Type</label><br>
                                        <input type="radio" class="form-check-input" id="sale_price_type"
                                            name="sale_price_type" value="b2b_price"
                                            {{ !empty($allData->id) && $allData->sale_price_type == 'b2b_price' ? 'checked' : '' }}>
                                        <label for="sale_price_type" style="padding-right: 10px;padding-left: 5px">B2B
                                            Price</label>
                                        <input type="radio" class="form-check-input" id="b2c_price"
                                            name="sale_price_type" value="b2c_price"
                                            {{ !empty($allData->id) && $allData->sale_price_type == 'b2c_price' ? 'checked' : '' }}>
                                        <label for="sale_price_type" style="padding-right: 10px;padding-left: 5px">B2C
                                            Price</label>
                                    </div>
                                </div>
                            @endif
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            {{ $mode->party_ways_rate_kit == 1 ? 'checked' : '' }} name="party_ways_rate_kit"
                                            role="switch" id="flexSwitchCheckDefault">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">Party wise Rate Kit</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <input type="submit" class="btn btn-primary submit" value="Save Changes">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
