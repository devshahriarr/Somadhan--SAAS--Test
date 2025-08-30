@extends('master')
@section('title', '| Settings Page')
@section('admin')
    @php
        $mode = App\models\PosSetting::all()->first();
    @endphp
    <h2 style="margin: 20px">SMS Settings</h2>
    <div class="row">
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">

                    <form id="myValidForm" action="{{ route('sms.settings.update') }}" method="post">
                        @csrf
                        <input type="hidden" name="setting_id"value="1">
                        <div class="row">
                            @if (Auth::user()->can('sale.sms'))
                                <div class="col-sm-6">
                                    <div class="mb-3 form-valid-groups">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                {{ $mode->sale_sms == 1 ? 'checked' : '' }} name="sale_sms" role="switch"
                                                id="flexSwitchCheckDefault">
                                            <label class="form-check-label" for="flexSwitchCheckDefault">Sale SMS</label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->can('transaction.sms'))
                                <div class="col-sm-6">
                                    <div class="mb-3 form-valid-groups">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                {{ $mode->transaction_sms == 1 ? 'checked' : '' }} name="transaction_sms"
                                                role="switch" id="flexSwitchCheckDefault">
                                            <label class="form-check-label" for="flexSwitchCheckDefault">Transaction
                                                SMS</label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->can('profile.invoice.payment.sms'))
                                <div class="col-sm-6">
                                    <div class="mb-3 form-valid-groups">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                {{ $mode->profile_payment_sms == 1 ? 'checked' : '' }}
                                                name="profile_payment_sms" role="switch" id="flexSwitchCheckDefault">
                                            <label class="form-check-label" for="flexSwitchCheckDefault">Profile Invoice
                                                Payment SMS</label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->can('link.invoice.payment.sms'))
                                <div class="col-sm-6">
                                    <div class="mb-3 form-valid-groups">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                {{ $mode->link_invoice_payment_sms == 1 ? 'checked' : '' }}
                                                name="link_invoice_payment_sms" role="switch" id="flexSwitchCheckDefault">
                                            <label class="form-check-label" for="flexSwitchCheckDefault">link Invoice
                                                Payment
                                                SMS</label>
                                        </div>
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
