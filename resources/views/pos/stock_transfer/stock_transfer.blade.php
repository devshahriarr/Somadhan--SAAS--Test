@extends('master')
@section('title', '| Stock Transfer')
@section('admin')

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Stock Transfer</li>
        </ol>
    </nav>
    @if (Auth::user()->can('stock.transfer.view'))
    <div class="d-flex justify-content-end mb-2">
        <a href="{{route('stock.transfer.view')}}" class="btn btn-primary">View History</a>
    </div>
    @endif
    <div class="card mb-4">
        <form id="stockTransferForm" class="stockTransferForm">
            <div class="card-body row">
                <div class="col-md-6">
                    <h5>From</h5>
                    <hr>
                    <div class="row ">
                        <div class="col-md-6 ">
                            <div class="mb-3 ">
                                <label for="ageSelect" class="form-label">Select Product Stock</label>
                                <select class="js-example-basic-single  form-control form-select stockProduct_name"
                                    data-width="100%" data-loaded="false" id="stockProduct" name="stock_id"
                                    onkeyup="errorRemove(this);">
                                    <option selected disabled>Select Stocks </option>
                                    @php

                                    @endphp
                                    @foreach ($stocks as $stock)
                                        <option value="{{ $stock->id }}">{{ $stock->product->name }}
                                            | {{ $stock->variation->variationSize->size ?? '' }} |
                                            {{ $stock->variation->colorName->name ?? '' }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger stockProduct_name_error"></span>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 ">
                                <label for="ageSelect" class="form-label">Select Warehouse <span
                                        class="text-danger">*</span></label>
                                <select class="form-select warehouse_name" id="warehouseId"name="from_warehouse_id">
                                    <option selected disabled>Select Warehouse </option>

                                </select>
                                <span class="text-danger assign_warehouse_name_error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 ">
                                <label for="ageSelect" class="form-label">Select Racks </label>
                                <select class="form-select assign_racks_name" onkeyup="errorRemove(this);"
                                    onblur="errorRemove(this);" id="rackId" name="from_racks_id">
                                    <option selected disabled>Select Racks </option>
                                </select>
                                <span class="text-danger assign_racks_name_error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 ">
                                <label for="ageSelect" class="form-label">From Branch </label>
                                <select class="form-select from_branch_name" onkeyup="errorRemove(this);"
                                    onblur="errorRemove(this);" id="from_branch" name="from_branch">
                                    <option selected disabled>Select from Branch </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 ">
                                <label for="ageSelect" class="form-label">Quantity (Total Stock :<span id="total_stock"
                                        class="text-danger"> 0</span>)</label>
                                <input class="form-control from_quantity_name" type="number" name="from_quantity"
                                    onkeyup="errorRemove(this);">
                                <span class="text-danger from_quantity_name_error"></span>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="col-md-6">
                    <h5>To</h5>

                    <hr>
                    @php
                        $branches = App\Models\Branch::all();

                    @endphp
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3 ">
                                <label for="ageSelect" class="form-label">Select Branch </label>
                                <select class="form-select barnch_name" id="to_branch_id" name="to_branch_id">
                                    <option selected disabled>Select Branch </option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}" selected>{{ $branch->name }}</option>
                                    @endforeach

                                </select>
                                <span class="text-danger barnch_name_error"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3 ">
                                <label for="ageSelect" class="form-label">Select Warehouse </label>
                                <select class=" js-example-basic-single form-select to_warehouse_name" id="to_warehouse_id"
                                    name="to_warehouse_id" onkeyup="errorRemove(this);">
                                    <option selected disabled>Select Warehouse </option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->warehouse_name }} </option>
                                    @endforeach
                                </select>
                                <span class="text-danger to_warehouse_name_error"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3 ">
                                <label for="ageSelect" class="form-label">Select Racks </label>
                                <select class="form-select assign_to_racks_name" id="to_rack_id" name="to_racks_id">
                                    <option selected disabled>Select Racks </option>
                                </select>
                                <span class="text-danger assign_racks_name_error"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer px-5 pb-4">
                <button type="button" class="btn btn-primary save_stock_transfer">Save</button>
            </div>

        </form>
    </div>

    <script>
        function errorRemove(element) {
            if (element.value != '') {
                $(element).siblings('span').hide();
                $(element).css('border-color', 'green');
            }
        }
        $(document).ready(function() {
            function showError(name, message) {
                $(name).css('border-color', 'red'); // Highlight input with red border
                $(name).focus(); // Set focus to the input field
                $(`${name}_error`).show().text(message); // Show error message
            }
            // Initialize Select2 for stock dropdown
            $('#stockProduct').select2();

            // Handle stock selection change
            $('#stockProduct').on('change', function() {
                let stockId = $(this).val();

                if (stockId) {

                    $.ajax({
                        url: '{{ route('stock.warehouse') }}', // Define this route
                        type: 'GET',
                        data: {
                            stock_id: stockId
                        },
                        success: function(response) {

                            $('#warehouseId').empty();
                            $('#rackId').empty();
                            $('#warehouseId').append(
                                '<option selected disabled>Select Warehouse</option>');


                            if (response.stock) {
                                // Add the warehouse option
                                $('#warehouseId').append(
                                    `<option value="${response.stock.warehouse_id}" selected>${response.stock.warehouse.warehouse_name}</option>`
                                );
                                $('#rackId').append(
                                    `<option value="${response.stock.rack_id}" selected>${response.stock.racks?.rack_name ?? ''}</option>`
                                );
                                $('#from_branch').append(
                                    `<option  value="${response.stock.branch_id}" selected>${response.stock.branch.name}</option>`
                                );
                                $('#total_stock').empty();
                                // Append totalStockQuantity to #total_stock
                                console.log(response.totalStockQuantity)
                                $('#total_stock').append(`${response.totalStockQuantity}`);
                            } else {
                                // Handle case where no warehouse is found
                                $('#assign_warehouse_name_error').text(
                                    'No warehouse found for this stock.');
                            }
                        },
                        error: function(xhr) {
                            $('#assign_warehouse_name_error').text(
                                'Error fetching warehouse details.');
                            console.error(xhr);
                        }
                    });
                } else {
                    // Reset warehouse dropdown if no stock is selected
                    $('#warehouseId').empty();
                    $('#warehouseId').append('<option selected disabled>Select Warehouse</option>');
                    $('#assign_warehouse_name_error').text('');
                }
            });



            //////////////////get racks for to//////////////
            $('#to_warehouse_id').on('change', function() {
                var warehouseId = $(this).val(); // Get the selected warehouse ID
                // console.log(warehouseId)
                // Clear the racks dropdown
                $('#to_rack_id').empty().append('<option selected disabled>Select Racks</option>');

                if (warehouseId) {
                    // Make an AJAX request to fetch racks
                    $.ajax({
                        url: '/get-warehouse-racks', // Replace with your server endpoint
                        type: 'GET',
                        data: {
                            warehouse_id: warehouseId
                        },
                        success: function(response) {
                            // Populate the racks dropdown with the received data
                            if (response.length > 0) {
                                $.each(response, function(index, rack) {
                                    $('#to_rack_id').append('<option value="' + rack
                                        .id + '">' + rack.rack_name + '</option>');
                                });
                            } else {
                                $('#to_rack_id').append(
                                    '<option disabled>No racks found</option>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching racks:', error);
                        }
                    });
                }
            });
            //store
            const stockTransfer = document.querySelector('.save_stock_transfer');

            stockTransfer.addEventListener('click', function(e) {
                e.preventDefault();

                let formData = new FormData($('.stockTransferForm')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: `/stock/transfer/store`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            $('.stockTransferForm')[0].reset();
                            window.location.reload();
                            toastr.success(res.message);
                        } else {
                            $('.is-invalid').removeClass('is-invalid');
                            $('.error-message').remove();

                            // Handle each possible error condition
                            if (res.error.to_warehouse_id) {
                                showError('.to_warehouse_name', res.error.to_warehouse_id[0]);
                            }

                            if (res.error.stock_id) {
                                showError('.stockProduct_name', res.error.stock_id[0]);
                            }

                            if (res.error.from_quantity) {
                                showError('.from_quantity_name', res.error.from_quantity[0]);
                            }

                            if (res.error.to_branch_id) {
                                showError('.barnch_name', res.error.to_branch_id[0]);
                            }

                        }
                    }
                });
            })
        }); //End
    </script>
@endsection
