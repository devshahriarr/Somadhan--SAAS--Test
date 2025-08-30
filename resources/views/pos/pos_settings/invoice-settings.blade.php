@extends('master')
@section('title', '| Settings Page')
@section('admin')
    @php
        $mode = App\models\PosSetting::all()->first();
    @endphp
    <h2 style="margin: 20px">Print Settings</h2>
    <div class="row">
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <form id="myValidForm" action="{{ route('invoice.settings.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="setting_id"value="1">
                        <div class="row">
                            @if (Auth::user()->can('invoice.logo.type'))
                                <div class="col-sm-6">
                                    <div class="mb-3 form-valid-groups">
                                        <label class="form-label">Invoice Logo Type</label><br>

                                        <input type="radio" id="age11" class="form-check-input"
                                            name="invoice_logo_type" value="logo"
                                            {{ !empty($allData->id) && $allData->invoice_logo_type == 'Logo' ? 'checked' : '' }}>
                                        <label for="age11" style="padding-right: 10px;padding-left: 5px">Logo</label>

                                        <input type="radio" id="age11s" class="form-check-input"
                                            name="invoice_logo_type" value="name"
                                            {{ !empty($allData->id) && $allData->invoice_logo_type == 'Name' ? 'checked' : '' }}>
                                        <label for="age11s" style="padding-right: 10px;padding-left: 5px">Name</label>

                                        <input type="radio" id="age111" class="form-check-input"
                                            name="invoice_logo_type" value="both"
                                            {{ !empty($allData->id) && $allData->invoice_logo_type == 'Both' ? 'checked' : '' }}>
                                        <label for="age111" style="padding-right: 10px;padding-left: 5px">Both</label>
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->can('invoice.design'))
                                <div class="col-sm-6">
                                    <div class="mb-3 form-valid-groups">
                                        <label class="form-label">Invoice Print Design
                                            {{-- <span class="text-danger">*</span> --}}
                                        </label>
                                        <select name="invoice_type" class="form-control">
                                            <option value="a4"
                                                {{ !empty($allData->id) && $allData->invoice_type == 'a4' ? 'selected' : '' }}>
                                                A4</option>
                                            <option value="a5"
                                                {{ !empty($allData->id) && $allData->invoice_type == 'a5' ? 'selected' : '' }}>
                                                A5</option>
                                            <option value="pos"
                                                {{ !empty($allData->id) && $allData->invoice_type == 'pos' ? 'selected' : '' }}>
                                                Pos Printer</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->can('barcode.type'))
                                <div class="col-sm-6">
                                    <div class="mb-3 form-valid-groups">
                                        <label class="form-label">Barcode Print Type</label><br>
                                        <input type="radio" class="form-check-input" id="barcode" name="barcode_type"
                                            value="single"
                                            {{ !empty($allData->id) && $allData->barcode_type == 'single' ? 'checked' : '' }}>
                                        <label for="barcode" style="padding-right: 10px;padding-left: 5px">Single</label>
                                        <input type="radio" class="form-check-input" id="barcode1" name="barcode_type"
                                            value="a4"
                                            {{ !empty($allData->id) && $allData->barcode_type == 'a4' ? 'checked' : '' }}>
                                        <label for="barcode1" style="padding-right: 10px;padding-left: 5px">A4</label>
                                        <input type="radio" class="form-check-input" id="barcode1" name="barcode_type"
                                            value="a4"
                                            {{ !empty($allData->id) && $allData->barcode_type == 'a5' ? 'checked' : '' }}>
                                        <label for="barcode1" style="padding-right: 10px;padding-left: 5px">A5</label>
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
