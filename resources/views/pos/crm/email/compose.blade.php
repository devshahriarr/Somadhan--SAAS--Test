@extends('master')
@section('title', '| Email Sending Page')
@section('admin')
    @if (!empty($content))
        {{ $content }}
    @endif
    <div class="row inbox-wrapper">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 border-end-lg">
                            <div class="aside-content">
                                <div class="d-flex align-items-center justify-content-between">
                                    <button class="navbar-toggle btn btn-icon border d-block d-lg-none"
                                        data-bs-target=".email-aside-nav" data-bs-toggle="collapse" type="button">
                                        <span class="icon"><i data-feather="chevron-down"></i></span>
                                    </button>
                                    <div class="order-first">
                                        <h4>Mail Marketing</h4>
                                        <p class="text-muted">amiahburton@gmail.com</p>
                                    </div>
                                </div>
                                <div class="d-grid my-3">
                                    <a class="btn btn-primary" href="">Compose Email</a>
                                </div>
                                <div class="email-aside-nav collapse">
                                    <ul class="nav flex-column">
                                        <li class="nav-item active">
                                            <a class="nav-link d-flex align-items-center" href="#">
                                                <i data-feather="mail" class="icon-lg me-2"></i>
                                                Sent Mail
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div>
                                <div class="d-flex align-items-center p-3 border-bottom tx-16">
                                    <span data-feather="edit" class="icon-md me-2"></span>
                                    New message
                                </div>
                            </div>
                            <div class="p-3 pb-0">
                                <div class="to">
                                    <div class="row mb-3 form-valid-groups">
                                        <label class="col-md-2 col-form-label">To:</label>
                                        <div class="col-md-10">
                                            @php
                                                if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
                                                    $customer = App\Models\Customer::where('party_type', 'customer')
                                                        ->whereNotNull('email')
                                                        ->where('email', '<>', '')
                                                        ->get();
                                                } else {
                                                    $customer = App\Models\Customer::where('party_type', 'customer')
                                                        ->where('branch_id', Auth::user()->branch_id)
                                                        ->whereNotNull('email')
                                                        ->where('email', '<>', '')
                                                        ->get();
                                                }

                                            @endphp
                                            <form id="myValidForm" method="POST"
                                                action="{{ route('customer.send.email') }}">
                                                @csrf

                                                {{-- <option id="select_all">Select All</option> --}}
                                                <select id="customerEmailSelect" name="recipients[]"
                                                    class="compose-multiple-select form-select @error('recipients') is-invalid @enderror"
                                                    multiple="multiple" placeholder="Search Email">
                                                    <option value="" disabled hidden>Search Mail</option>
                                                    @foreach ($customer as $customerEmail)
                                                        <option value="{{ $customerEmail->email }}">
                                                            {{ $customerEmail->email }}</option>
                                                    @endforeach
                                                </select>
                                                @error('recipients')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="subject">
                                    <div class="row mb-3 form-valid-groups">
                                        <label class="col-md-2 col-form-label">Subject</label>
                                        <div class="col-md-10">
                                            <input class="form-control @error('subject') is-invalid @enderror"
                                                name="subject" type="text">
                                            @error('subject')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="px-3 form-valid-groups">
                                <div class="col-md-12 ">
                                    <div class="mb-3">
                                        <label class="form-label visually-hidden" for="easyMdeEditor">Descriptions </label>
                                        <textarea class="form-control @error('subject') is-invalid @enderror" name="message" id="easyMdeEditor" rows="5"></textarea>
                                    </div>
                                    @error('message')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <div class="col-md-12">
                                        <button class="btn btn-primary me-1 mb-1" type="submit"> Send</button>
                                        <button class="btn btn-secondary me-1 mb-1" type="button"> Cancel</button>
                                    </div>
                                </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('customerEmailSelect').addEventListener('change', function() {
                var selectAllOption = document.querySelector(
                    '#customerEmailSelect option[value="selectAll"]');
                if (selectAllOption.selected) {
                    var options = document.querySelectorAll(
                        '#customerEmailSelect option:not([disabled]):not([value="selectAll"])');
                    for (var i = 0; i < options.length; i++) {
                        options[i].selected = true;
                    }
                }
            });
        });
    </script>
@endsection
