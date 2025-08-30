@extends('master')
@section('title', '| Settings Page')
@section('admin')
    @php
        $mode = App\models\PosSetting::all()->first();
    @endphp
    <h2 style="margin: 20px">Product & Stock Settings</h2>
    <div class="row">
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <form id="myValidForm" action="{{ route('product.stock.settings.update') }}" method="post">
                        @csrf
                        <input type="hidden" name="setting_id"value="1">
                        <!-- Row -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            {{ $mode->product_set_low_stock == 1 ? 'checked' : '' }}
                                            name="product_set_low_stock" role="switch" id="flexSwitchCheckDefault">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">Product Set low
                                            Stock</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            {{ $mode->low_stock_alert == 1 ? 'checked' : '' }} name="low_stock_alert"
                                            role="switch" id="flexSwitchCheckDefault">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">low Stock
                                            Alert</label>
                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            {{ $mode->color_view == 1 ? 'checked' : '' }} name="color_view" role="switch"
                                            id="flexSwitchCheckDefault">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">Color View</label>
                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            {{ $mode->size_view == 1 ? 'checked' : '' }} name="size_view" role="switch"
                                            id="flexSwitchCheckDefault">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">Size View</label>
                                    </div>

                                </div>
                            </div>
                            @if (Auth::user()->can('manufacture.date'))
                                <div class="col-sm-6">
                                    <div class="mb-3 form-valid-groups">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                {{ $mode->manufacture_date == 1 ? 'checked' : '' }} name="manufacture_date"
                                                role="switch" id="flexSwitchCheckDefaulmanufacture_datet">
                                            <label class="form-check-label"
                                                for="flexSwitchCheckDefaultmanufacture_date">Manufacture Date</label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->can('expiry.date'))
                                <div class="col-sm-6">
                                    <div class="mb-3 form-valid-groups">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                {{ $mode->expiry_date == 1 ? 'checked' : '' }} name="expiry_date"
                                                role="switch" id="flexSwitchCheckexpiry_date">
                                            <label class="form-check-label" for="flexSwitchCheckexpiry_date">Expiry
                                                Date</label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->can('bulk.update'))
                                <div class="col-sm-6">
                                    <div class="mb-3 form-valid-groups">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                {{ $mode->bulk_update == 1 ? 'checked' : '' }} name="bulk_update"
                                                role="switch" id="flexSwitchCheckDefault">
                                            <label class="form-check-label" for="flexSwitchCheckDefault">Bulk
                                                Update</label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="col-sm-6">
                                    <div class="mb-3 form-valid-groups">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                {{ $mode->multiple_category == 1 ? 'checked' : '' }} name="multiple_category"
                                                role="switch" id="flexSwitchCheckDefault">
                                            <label class="form-check-label" for="flexSwitchCheckDefault">Multiple Category
                                                </label>
                                        </div>
                                    </div>
                                </div>
                            @if (Auth::user()->can('low.stock.quantity'))
                                <div class="col-sm-6">
                                    <div class="mb-3 form-valid-groups">
                                        <label class="form-label">
                                            Set Global Low Stock Alert<span class="text-danger"></span></label>
                                        <input type="number" required name="low_stock" class="form-control"
                                            placeholder="Enter low stock"
                                            value="{{ !empty($allData->id) ? $allData->low_stock : '' }}">
                                    </div>
                                </div>
                            @endif
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
