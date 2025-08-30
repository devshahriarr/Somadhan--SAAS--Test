@extends('master')
@section('title')
    | {{ $data->name }}
@endsection
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">
                Product Ledger
            </li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card border-0 shadow-none">
                <div class="card-body">
                    <div class="container-fluid d-flex justify-content-between">
                        <div class="col-lg-3 ps-0">
                            @if (!empty($invoice_logo_type))
                                @if ($invoice_logo_type == 'Name')
                                    <a href="#" class="noble-ui-logo logo-light d-block mt-3">{{ $siteTitle }}</a>
                                @elseif($invoice_logo_type == 'Logo')
                                    @if (!empty($logo))
                                        <img class="margin_left_m_14" height="100" width="200"
                                            src="{{ url($logo) }}" alt="logo">
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
                                <a href="#" class="noble-ui-logo logo-light d-block mt-3">EIL<span>Electro</span></a>
                            @endif
                            <hr>
                            <p class="show_branch_address">{{ $branch->address ?? '' }}</p>
                            <p class="show_branch_email">{{ $branch->email ?? '' }}</p>
                            <p class="show_branch_phone">{{ $branch->phone ?? '' }}</p>
                        </div>
                        <div>
                            <a>
                                <input class="btn btn-secondary  " type="btn" readonly value="Add Variation"
                                    id="toggleButton">
                            </a>
                            <button type="button"
                                class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0 barcode-print-btn">
                                <i class="btn-icon-prepend fa-solid fa-barcode"></i>
                                Print Barcode
                            </button>
                            <button type="button"
                                class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0 print-btn">
                                <i class="btn-icon-prepend" data-feather="printer"></i>
                                Print
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body show_ledger">
                    <div class="container-fluid w-100">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td>Product Name</td>
                                                <td>{{ $data->name ?? '-' }}</td>
                                                <td>Barcode</td>
                                                <td>{{ $data->barcode ?? '-' }}</td>
                                                <td>Total Stock</td>
                                                <td> {{ $data->stockQuantity->sum('stock_quantity') ?? 0 }} </td>
                                            </tr>
                                            <tr>
                                                <td>Category</td>
                                                <td>{{ $data->category->name ?? '-' }}</td>
                                                <td>Subcategory</td>
                                                <td>{{ $data->subcategory->name ?? '-' }}</td>
                                                <td>Brand</td>
                                                <td>{{ $data->brand->name ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Total Sold</td>
                                                <td>{{ $data->saleItem->sum('qty') ?? 0 }}</td>
                                                <td>Unit</td>
                                                <td>{{ $data->productUnit->name ?? '-' }}</td>

                                            </tr>
                                            <tr>
                                                <td>Description</td>
                                                <td colspan="5">{!! wordwrap($data->description ?? 'N/A', 130, '<br>') !!}</td>
                                            </tr>
                                            @if ($atttributes->count() > 0)
                                                <tr>
                                                    <td colspan="6" class="text-center font-weight-bold">Extra Field Data
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th colspan="3">Field Name</th>
                                                    <th colspan="3">Value</th>
                                                </tr>

                                            @endif
                                        </thead>
                                        @if ($atttributes->count() > 0)
                                            <tbody>

                                                @foreach ($atttributes as $item)
                                                    @php
                                                        $decodedValue = json_decode($item->value, true);
                                                    @endphp
                                                    <tr>
                                                        <td colspan="3">{{ $item->extra_field->field_name ?? 'N/A' }}
                                                        </td>
                                                        <td colspan="3">
                                                            @if (is_array($decodedValue))
                                                                {{ implode(', ', $decodedValue) }}
                                                            @else
                                                                {{ $item->value ?? 'N/A' }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="my-3 text-center">
                        Product Variation
                    </h4>
                    <div class="container-fluid w-100">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Variation Name</th>
                                                <th>Cost Price</th>
                                                <th>B2B Price</th>
                                                <th>B2C Price</th>
                                                <th>Stock</th>
                                                <th>Size</th>
                                                <th>Color</th>
                                                <th>Model No</th>
                                                <th>Quality</th>
                                                <th>Image</th>
                                                <th>Origin</th>
                                                <th>Status</th>
                                                {{-- <th>Barcode</th> --}}
                                                <th>variation Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @if (count($variations))
                                                @foreach ($variations as $variation)
                                                    <tr>
                                                        <td>{{ $variation->variation_name ?? 'N/A' }}</td>
                                                        <td>{{ $variation->cost_price ?? 'N/A' }}</td>
                                                        <td>{{ $variation->b2b_price ?? 'N/A' }}</td>
                                                        <td>{{ $variation->b2c_price ?? 'N/A' }}</td>
                                                        <td>{{ $variation->stocks->sum('stock_quantity') ?? 0 }}</td>
                                                        <td>{{ $variation->variationSize->size ?? 'N/A' }}</td>
                                                        <td>{{ $variation->colorName->name ?? 'N/A' }}</td>
                                                        <td>{{ $variation->model_no ?? 'N/A' }}</td>
                                                        <td>{{ $variation->quality ?? 'N/A' }}</td>
                                                        <td>
                                                            <img src="{{ $variation->image && file_exists(public_path('uploads/products/' . $variation->image))
                                                                ? asset('uploads/products/' . $variation->image)
                                                                : asset('dummy/image.jpg') }}"
                                                                alt="Product Image" style="max-width: auto; ">
                                                        </td>
                                                        <td>{{ $variation->origin ?? 'N/A' }}</td>
                                                        <td>{{ $variation->status ?? 'N/A' }}</td>
                                                        <td>
                                                            @if ($variation->productStatus)
                                                                <a href="javascript:void(0)"
                                                                    class="btn btn-sm {{ $variation->productStatus === 'active' ? 'btn-success' : 'btn-danger' }} variation-status-btn"
                                                                    data-variation-id="{{ $variation->id }}">
                                                                    {{ $variation->productStatus }}
                                                                </a>
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>

                                                        {{-- <td>
                                                            <button class="btn btn-primary barcode"
                                                                data-id="{{ $variation->id }}">Barcode</button>
                                                        </td> --}}
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td>No Data Fount</td>
                                                </tr>
                                            @endif
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="my-3 text-center">
                        Product Ledger
                    </h4>
                    <div class="container-fluid w-100">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Invoice</th>
                                                <th>Particulars</th>
                                                <th>Party</th>
                                                <th>Stock In/Out</th>
                                                <th>Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @if (count($reports))
                                                @php
                                                    $currentStock = 0;
                                                @endphp
                                                @foreach ($reports as $report)
                                                    <tr>
                                                        <td>{{ $report['date']->format('F j, Y') }}</td>
                                                     <td>{{ $report['invoice'] ?? 'N/A' }}</td>
                                                        <td>{{ $report['particulars'] }}</td>
                                                        <td>{{ $report['Prty_name'] }}</td>


                                                        <td>
                                                            @if ($report['stockIn'] || $report['stockOut'])
                                                                @if ($report['stockIn'])
                                                                    <span>{{ $report['stockIn'] }}</span>
                                                                @else
                                                                    <span
                                                                        class="text-danger">{{ $report['stockOut'] }}</span>
                                                                @endif
                                                            @else
                                                                0
                                                            @endif
                                                        </td>
                                                        <td>{{ $report['balance'] }}</td>

                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td>No Data Found</td>
                                                </tr>
                                            @endif
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <iframe id="printFrame" src="" width="0" height="0"></iframe>

    <!--------------------------------Add Variation ----------------------------->
    <div class="controll-variation" style="display: none">
        <div class="latest-product-container">
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <form action="" id="variationForm">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="card-title"> Add New Variation</h6>
                                </div>
                                <div id="" class="table-responsive">
                                    <div class="bill-header">
                                        <div class="row no-gutters">
                                            <div>
                                                <p> <span id="latestProductName" class="text-success fs-5 fs-bold "> <input
                                                            type="text" value="" style="display: none"></span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 grid-margin stretch-card">
                                            <div class="example w-100">
                                                <div class="tab-content border border-top-0 p-3" id="myTabContent">
                                                    <div class="tab-pane fade show active" id="serviceSale"
                                                        role="tabpanel" aria-labelledby="serviceSale-tab"
                                                        style="padding-bottom: 30px">
                                                        <div class="col-md-12 serviceSale">
                                                            <table id="variationTable">
                                                                <thead>
                                                                    <tr>
                                                                        <th><button type="button" class="form-control"
                                                                                id="addVariationRowBtn">+
                                                                            </button></th>

                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <input value="{{ $data->id }}" type="hidden"
                                                                        name="productId">
                                                                </tbody>
                                                                <tfoot>
                                                                </tfoot>
                                                            </table>

                                                            <button type="submit"
                                                                class="btn mt-1 btn-md float-end variationStoreAdd"
                                                                style="border:1px solid #6587ff ">Submit</button>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--------------------------------Add Variation End ----------------------------->

    <style>
        #printFrame {
            display: none;
            /* Hide the iframe */
        }

        .table> :not(caption)>*>* {
            padding: 0px 10px !important;
        }

        thead,
        tbody,
        tfoot,
        tr,
        td,
        th {
            vertical-align: middle;
        }

        .margin_left_m_14 {
            margin-left: -14px;
        }

        .w_40 {
            width: 250px !important;
            text-wrap: wrap;
        }

        @media print {
            .page-content {
                margin-top: 0 !important;
                padding-top: 0 !important;
                min-height: 740px !important;
                background-color: #ffffff !important;
            }

            .grid-margin,
            .card,
            .card-body,
            table {
                background-color: #ffffff !important;
                color: #000 !important;
            }

            .footer_invoice p {
                font-size: 12px !important;
            }

            button,
            a,
            .filter_box,
            nav,
            .footer,
            .id,
            .dataTables_filter,
            .dataTables_length,
            .dataTables_info {
                display: none !important;
            }
        }

        #variationTable .form-control {
            width: 100%;
            box-sizing: border-box;
        }

        #variationTable td {
            padding: 5px;
        }

        #variationTable tr {
            display: flex;
            flex-wrap: wrap;
        }

        #variationTable tr td {
            flex: 1 1 30%;
            margin: 5px;
        }

        @media (max-width: 1024px) {
            #variationTable tr td {
                flex: 1 1 45%;
            }
        }

        @media (max-width: 767px) {
            #variationTable tr td {
                flex: 1 1 100%;
            }
        }
    </style>

    <script>
        let manufacture_date = '{{ $manufacture_date }}';
        let expiry_date = '{{ $expiry_date }}';
        let low_stock_alert = '{{ $product_set_low_stock }}';
        // Error Remove Function
        function errorRemove(element) {
            tag = element.tagName.toLowerCase();
            if (element.value != '') {
                // console.log('ok');
                if (tag == 'select') {
                    $(element).closest('.mb-3').find('.text-danger').hide();
                } else {
                    $(element).siblings('span').hide();
                    $(element).css('border-color', 'green');
                }
            }
        }
        //variation controll Hide Show
        const toggleButton = document.getElementById('toggleButton');
        const controllVariation = document.querySelector('.controll-variation');

        toggleButton.addEventListener('click', () => {
            // Toggle the display property of controll-variation
            if (controllVariation.style.display === 'none' || controllVariation.style.display === '') {
                controllVariation.style.display = 'block';
            } else {
                controllVariation.style.display = 'none';
            }
        });
        // Show Error Function
        // <!---------------------Variation Code ------------------>//
        function showColor() {
            $.ajax({
                url: '/color/view',
                method: 'GET',
                success: function(res) {
                    const colors = res.colors;

                    // সব .show_color এর জন্য লুপ
                    $('.show_color').each(function() {
                        let $select = $(this);
                        // console.log($select);
                        $select.empty();

                        if (colors.length > 0) {
                            $select.html(`<option disabled>Select Colors</option>`);
                            $.each(colors, function(index, color) {
                                $select.append(
                                    `<option value="${color.id}">${color.name}</option>`
                                );
                            });
                        } else {
                            $select.html(
                                `<option selected disabled>Please Add Color</option>`);
                        }
                    });
                }
            });
        }
        showColor();

        function variationProductSize() {
            let id = {{ $data->id }};
            //    console.log(id);
            $.ajax({
                url: '/variation-product-size/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    let sizes = response.sizes;
                    // console.log(sizes);

                    document.querySelectorAll('select[name="variation_size[]"]').forEach(function(
                        dropdown) {
                        dropdown.innerHTML =
                            `<option selected disabled value=''>Select Size</option>`; // Reset the dropdown
                        sizes.forEach(function(size) {
                            let option = document.createElement('option');
                            option.value = size.id;
                            option.textContent = size
                                .size; // e.g., "large", "medium"
                            dropdown.appendChild(option);
                        });
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching latest product Size:', error);
                }
            });
        } //
        variationProductSize()
        document.getElementById('addVariationRowBtn').addEventListener('click', function() {
            let table = document.getElementById('variationTable');
            let tableHead = table.querySelectorAll('.dynamic-head');
            let tableBody = table.querySelector('tbody');

            // Create a new row
            let newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>
                      <label for="action">Action</label>
                    <button type="button" class="removeVariationRowBtn form-control text-danger btn-xs btn-danger">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </td>
                <td>
                    <label>Variation Name</label>
                    <input type="text" class="form-control" name="variation_name[]" placeholder="Enter Variation Name">
                    </td>
                <td>
                    <label>Current Stock</label>
                    <input type="number" class="form-control" name="current_stock[]" placeholder="Stock">
                    </td>
                <td>    <label for="cost_price">Cost Price</label>
                        <input type="number" class="form-control" name="cost_price[]" placeholder="Price"></td>
                <td> <label for="b2b_price">B2B Price</label>
                    <input type="number" class="form-control" name="b2b_price[]" placeholder="b2b Price"></td>
                <td><label for="b2c_price">B2C Price</label>
                    <input type="number" class="form-control" name="b2c_price[]" placeholder=" b2c Price"></td>
                <td>
                     <label for="variation_size">Size</label>
                    <select class="form-control" id="variation_size" name="variation_size[]">
                            <option selected disabled value=''>Select Size</option>
                    </select>

                    </td>
                <td> <label for="color">Color</label>
                    <select class="form-control js-example-basic-single show_color" name="color[]">

                    </select>
                </td>
                <td>
                    <label for="model_no">Model No</label>
                    <input type="text" class="form-control" name="model_no[]" placeholder="Model No"></td>
                <td>
                    <label for="quality">Quality</label>
                    <select class="form-control" name="quality[]">
                        <option selected disabled>Select Quality</option>
                        <option value="grade-a">Grade A</option>
                        <option value="grade-b">Grade B</option>
                        <option value="grade-c">Grade C</option>
                    </select>
                </td>
                ${manufacture_date == 1 ? `
                        <td>
                        <label for="manufacture_date">Manufacture Date</label>
                        <input type="date" class="form-control" name="manufacture_date[]">
                        </td>
                    ` : ''}
                ${expiry_date == 1 ? `
                    <td>
                    <label for="expiry_date">Expiry Date</label>
                    <input type="date" class="form-control" name="expiry_date[]"></td>
                    <td>
                    ` : ''}
                    ${low_stock_alert == 1 ? `
                    <td>
                    <label for="">Set Low Stock QTY</label>
                    <input type="number" class="form-control" id="low_stock_input" name="low_stock_alert[]">

                    <td>

                ` : ''}
                <td>
                <label for="image">Image</label>
                <input type="file" class="form-control" name="image[]"></td>


            `;
            // Append the new row to the table body

            newRow.style.backgroundColor = "#f0f8ff";

            tableBody.appendChild(newRow);
            let hr = document.createElement('hr');
            hr.style.border = "1px solid #ff0000"; // Red color for the <hr>
            hr.style.margin = "10px 0"; // Add margin above and below the <hr>

            // Append the <hr> after the row
            tableBody.appendChild(hr);
            variationProductSize();
            tableHead.forEach(function(head) {
                head.style.display = 'table-cell';
            });
            toastr.success('Added Variation  Row');
            showColor();
            // Add event listener for the remove button in the new row
            newRow.querySelector('.removeVariationRowBtn').addEventListener('click', function() {
                newRow.remove();
                if (tableBody.querySelectorAll('tr').length === 0) {
                    tableHead.forEach(function(head) {
                        head.style.display = 'none';
                    });
                }
                toastr.info('Removed Variation  Row');
            });

        });
        const variationStoreAdd = document.querySelector('.variationStoreAdd');
        const variationForm = document.getElementById('variationForm');
        variationStoreAdd.addEventListener('click', function(e) {
            e.preventDefault();
            ///////////////Validation Start /////////////
            const rows = document.querySelectorAll('#variationTable tbody tr');
            let allFieldsFilled = true;
            let errorMessages = [];

            // If no rows are present
            if (rows.length === 0) {
                toastr.warning('⚠️ Please add at least one variation before submitting.');
                return;
            }

            // Loop through each row and validate inputs
            rows.forEach(function(row, index) {
                let variation_name = row.querySelector('input[name="variation_name[]"]').value.trim();
                let priceVari = row.querySelector('input[name="cost_price[]"]').value.trim();
                let b2b = row.querySelector('input[name="b2b_price[]"]').value.trim();
                let b2c = row.querySelector('input[name="b2c_price[]"]').value.trim();
                const input = row.querySelector('input[name="low_stock_alert[]"]');
                let low_stock_alert = input ? input.value.trim() : '';
                let sizeVari = row.querySelector('select[name="variation_size[]"]').value;

                let modelVari = row.querySelector('input[name="model_no[]"]').value
                    .trim(); // Use `input` for model_no[] if it's an input field.
                let qualityVari = row.querySelector('select[name="quality[]"]').value;
                let colorVari = row.querySelector('select[name="color[]"]')
                    .value; // Use `input` for color[] if it's an input field.

                const validSize = sizeVari !== '' && sizeVari !== 'Select Size';
                const validQuality = qualityVari !== '' && qualityVari !== 'Select Quality';
                const validColor = colorVari !== '' && colorVari !==
                    'Select Color'; // Treat '#000000' as invalid.
                const validModel = modelVari !== '';
                //   const validPrice = priceVari !== '';

                //console.log(`Row ${index + 1}:`, { sizeVari, modelVari, qualityVari, colorVari, priceVari });
                if (!validSize && !validModel && !validQuality && !validColor) {
                    // console.log(`Validation failed for row ${index + 1}`);
                    errorMessages.push(
                        `⚠️ Row ${index + 1}: At least one of Size, Model No, Quality,  Color  must be filled.`
                    );
                    allFieldsFilled = false;
                }
                //  if (!priceVari) {
                //     errorMessages.push(`⚠️ Row ${index + 1}: Price field is required.`);
                //     allFieldsFilled = false;
                // }

            });

            // Display error messages if validation fails
            if (!allFieldsFilled) {
                toastr.warning(errorMessages.join('<br>'));
                return;
            }

            ///////////////Validation End /////////////
            if (rows.length > 0) {
                showSpinner()
                // AJAX Submission
                // $('input[name="productId"]').val(productId);
                let formData = new FormData(variationForm);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '/store-variation',
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status === 200) {
                            variationForm.reset();
                            hideSpinner()
                            // $('#variationTable tbody').empty();
                            toastr.success(response.message);
                            // Optionally reload the page
                            window.location.reload();
                            // window.location.href = "{{ route('product.all.view') }}";
                        } else {
                            hideSpinner()
                            toastr.error(response.error || 'Something went wrong.');
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422) { // Validation error from server
                            let errors = xhr.responseJSON.errors;
                            let errorList = Object.values(errors).flat().join('<br>');
                            toastr.error(errorList);
                        } else {
                            hideSpinner()
                            toastr.warning('An unexpected error occurred.');
                        }
                    }
                });
            } else {
                // toastr.error('⚠️ Please Add a Service First.');
            }

        });
        // <!---------------------Variation Code End------------------>//

        function showError(payment_balance, message) {
            $(payment_balance).css('border-color', 'red');
            $(payment_balance).focus();
            $(`${payment_balance}_error`).show().text(message);
        }


        // print
        document.querySelector('.print-btn').addEventListener('click', function(e) {
            e.preventDefault();
            $('#dataTableExample').removeAttr('id');
            $('.table-responsive').removeAttr('class');
            // Trigger the print function
            window.print();
        });


        $('.print').click(function(e) {
            e.preventDefault();
            let id = $(this).attr('data-id');
            let type = $(this).attr('type');
            var printFrame = $('#printFrame')[0];



            if (type == 'sale') {
                var printContentUrl = '/sale/invoice/' + id;
            } else if (type == 'return') {
                var printContentUrl = '/return/products/invoice/' + id;
            } else if (type == 'purchase') {
                var printContentUrl = '/purchase/invoice/' + id;
            } else {
                var printContentUrl = '/transaction/invoice/receipt/' + id;
            }

            $('#printFrame').attr('src', printContentUrl);
            printFrame.onload = function() {
                printFrame.contentWindow.focus();
                printFrame.contentWindow.print();
            };
        })

        $(document).ready(function() {
            $('.variation-status-btn').on('click', function() {
                var variationId = $(this).data('variation-id'); // Correct method to get data attribute
                var button = $(this);

                $.ajax({
                    url: '/variation/status/' + variationId, // Adjusted endpoint
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}' // Laravel CSRF token
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update button text and class based on new status
                            if (response.newStatus === 'active') {
                                button.text('active').removeClass('btn-danger').addClass(
                                    'btn-success');
                            } else {
                                button.text('inactive').removeClass('btn-success').addClass(
                                    'btn-danger');
                            }
                        } else {
                            alert(response.message || 'Failed to update status');
                        }
                    },
                    error: function() {
                        alert('An error occurred while updating the status');
                    }
                });
            });
        });
    </script>
@endsection
