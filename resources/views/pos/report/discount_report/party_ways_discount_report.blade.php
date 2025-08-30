@extends('master')
@section('title', '| Party Ways Discount Report')
@section('admin')
@php

        $grandTotalDiscount = 0;
        if ($partyDiscounts->count() > 0) {
            foreach ($partyDiscounts as $partyDiscount) {
                $saleDiscount = $partyDiscount->salesCustomer->sum('actual_discount');
                $saleItemDiscount = 0;
                foreach ($partyDiscount->salesCustomer as $saleCustomer) {
                    $saleItemDiscount += $saleCustomer->saleItem->sum('discount');
                }
                $totalDiscount = $saleDiscount + $saleItemDiscount;
                $grandTotalDiscount += $totalDiscount;
            }
        }
    @endphp
<div class="row">
    <table class="table table-bordered table-striped text-center  mb-4">
        <thead class="text-dark">
            <tr>
                <th>Total Discount</th>

            </tr>
        </thead>
        <tbody>
            <tr class="fw-bold">
                <td>৳ {{$grandTotalDiscount ?? 0}} </td>
            </tr>
        </tbody>
    </table>
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title ">Party Ways Discount Report</h6>
                <div id="" class="table-responsive">
                    <table id="example" class="table">
                        <thead>
                            <tr>
                                <th>SN#</th>
                                <th>Party Name</th>
                                <th>Discount</th>

                            </tr>
                        </thead>
                        <tbody class="showData">
                            {{-- @dd($partyDiscounts); --}}
                            @if ($partyDiscounts->count() > 0 )
                            @php
                            $serial = 1;
                            $grandTotalDiscount = 0;
                            @endphp
                                @foreach ($partyDiscounts as $key => $partyDiscount)
                                    <tr>
                                        <td>{{$serial }}</td>
                                        <td>{{$partyDiscount->name ?? '' }} </td>
                                        @php
                                        $saleDiscount = $partyDiscount->salesCustomer->sum('actual_discount');
                                        $saleItemDiscount = 0;
                                        foreach ($partyDiscount->salesCustomer as $saleCustomer) {
                                            $saleItemDiscount += $saleCustomer->saleItem->sum('discount');
                                        }
                                        $totalDiscount = $saleDiscount + $saleItemDiscount;
                                        // dd($totalDiscount);
                                        $grandTotalDiscount += $totalDiscount;
                                        // dd($grandTotalDiscount);
                                        @endphp

                                        <td>৳ {{ $totalDiscount ?? 0 }}</td>
                                    </tr>
                                    @php $serial++; @endphp
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="12">
                                        <div class="text-center text-warning mb-2">Sales Items Not Found</div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>

                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
