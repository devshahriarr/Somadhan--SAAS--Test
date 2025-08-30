@extends('master')
@section('title', '| Settings Page')
@section('admin')
    @php
        $mode = App\models\PosSetting::all()->first();
    @endphp
    <h2 style="margin: 20px">Purchase Settings</h2>
    <div class="row">
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <form id="myValidForm" action="{{ route('purchase.settings.update') }}" method="post">
                        @csrf
                        <input type="hidden" name="setting_id"value="1">

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            {{ $mode->purchase_page == 1 ? 'checked' : '' }}
                                            name="purchase_page" role="switch" id="flexSwitchCheckDefault23">
                                        <label class="form-check-label" for="flexSwitchCheckDefault23">Purchase Page
                                            </label>
                                    </div>
                                </div>
                            </div>
                            @if (Auth::user()->can('parchase.price.edit'))
                                <div class="col-sm-6">
                                    <div class="mb-3 form-valid-groups">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                {{ $mode->purchase_price_edit == 1 ? 'checked' : '' }}
                                                name="purchase_price_edit" role="switch" id="flexSwitchCheckDefault">
                                            <label class="form-check-label" for="flexSwitchCheckDefault">Purchase Price
                                                Edit</label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            {{ $mode->purchase_price_update == 1 ? 'checked' : '' }}
                                            name="purchase_price_update" role="switch" id="flexSwitchCheckDefault">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">Update Cost Price From
                                            Purchase</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            {{ $mode->purchase_individual_product_discount == 1 ? 'checked' : '' }}
                                            name="purchase_individual_product_discount" role="switch"
                                            id="flexSwitchCheckDefault">
                                        <label class="form-check-label"
                                            for="purchase_individual_product_discount">Individual Product
                                            Discount</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            {{ $mode->purchase_hands_on_discount == 1 ? 'checked' : '' }}
                                            name="purchase_hands_on_discount" role="switch" id="flexSwitchCheckDefault">
                                        <label class="form-check-label" for="purchase_hands_on_discount">Hands On
                                            Discount</label>
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
