@extends('master')
@section('title', '| Settings Page')
@section('admin')
    @php
        $mode = App\models\PosSetting::all()->first();
    @endphp
    <h2 style="margin: 20px">System Settings</h2>
    <div class="row">
        <div class="col-md-12 stretch-card">

            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-info">Company Details</h6>
                    <form id="myValidForm" action="{{ route('system.settings.update') }}" method="post">
                        @csrf
                        <input type="hidden" name="setting_id"value="1">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            {{ $mode->sms_manage == 1 ? 'checked' : '' }} name="sms_manage" role="switch"
                                            id="flexSwitchCheckDefault">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">SMS Manage</label>
                                    </div>
                                </div>
                            </div>
                            @if (Auth::user()->can('invoice.payment'))
                                <div class="col-sm-6">
                                    <div class="mb-3 form-valid-groups">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                {{ $mode->invoice_payment == 1 ? 'checked' : '' }} name="invoice_payment"
                                                role="switch" id="flexSwitchCheckDefault">
                                            <label class="form-check-label" for="flexSwitchCheckDefault">Invoice
                                                Payment</label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->can('link.invoice.payment'))
                                <div class="col-sm-6">
                                    <div class="mb-3 form-valid-groups">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                {{ $mode->link_invoice_payment == 1 ? 'checked' : '' }}
                                                name="link_invoice_payment" role="switch" id="flexSwitchCheckDefault">
                                            <label class="form-check-label" for="flexSwitchCheckDefault">Link Invoice
                                                Payment</label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            {{ $mode->due_reminder == 1 ? 'checked' : '' }} name="due_reminder"
                                            role="switch" id="flexSwitchCheckDefault">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">Due Reminder</label>
                                    </div>
                                </div>
                            </div>
                            @if (Auth::user()->can('affliate.program'))
                                <div class="col-sm-6">
                                    <div class="mb-3 form-valid-groups">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                {{ $mode->affliate_program == 1 ? 'checked' : '' }} name="affliate_program"
                                                role="switch" id="flexSwitchCheckDefault">
                                            <label class="form-check-label" for="flexSwitchCheckDefault">Affliate
                                                Program</label>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- @if (Auth::user()->can('sell.commission')) --}}
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            {{ $mode->sale_commission == 1 ? 'checked' : '' }} name="sale_commission"
                                            role="switch" id="flexSwitchCheckDefault">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">Sell
                                            Commission</label>
                                    </div>
                                </div>
                            </div>
                          {{-- @endif --}}






                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            {{ $mode->courier_management == 1 ? 'checked' : '' }} name="courier_management"
                                            role="switch" id="flexSwitchCheckDefault">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">Couriar
                                            Management</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            {{ $mode->elastic_search == 1 ? 'checked' : '' }} name="elastic_search"
                                            role="switch" id="flexSwitchCheckDefault">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">Elastic Search</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            {{ $mode->drag_and_drop == 1 ? 'checked' : '' }} name="drag_and_drop"
                                            role="switch" id="flexSwitchCheckDefault">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">
                                            Drag and Drop
                                        </label>
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
