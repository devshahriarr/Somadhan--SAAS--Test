@extends('master')
@section('title', '| Warehouse')
@section('admin')
    @php
        // $products = App\Models\Product::withSum(
        //     [
        //         'stockQuantity as stock_quantity_sum' => function ($query) {
        //             $query->where('branch_id', Auth::user()->branch_id);
        //         },
        //     ],
        //     'stock_quantity',
        // )
        //     ->having('stock_quantity_sum', '>', 0) // Use having method here
        //     ->orderBy('stock_quantity_sum', 'asc')
        //     ->get();
        // $products = App\Models\Product::get();
    @endphp
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Stock Adjustments</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-end mb-2">
        <a href="{{route('stock.adjustment.report')}}" class="btn btn-primary">Adjustments Report</a>
    </div>

    {{-- //-----------------------Assign Racks---------------------// --}}
    <div class="card mb-4">
        <form id="signupForm" method="post" class="adjustStockForm">
            <div class="card-body row">
                <div class="col-md-6">
                    <div class="mb-3 ">
                        <label for="ageSelect" class="form-label">Select Branch <span class="text-danger">*</span></label>
                        <select class="form-select branch_id" id="branch_id" onchange="errorRemove(this);" name="branch_id">
                            <option selected disabled>Select Branch </option>
                            @foreach ($branchs as $branch)
                                <option value="{{ $branch->id }}" @if($branchs->count() == 1) selected @endif  >{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger branch_id_error"></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3 ">
                        <label for="ageSelect" class="form-label"> Adjustment Type <span
                                class="text-danger">*</span></label>
                        <select class="form-select adjustment_type" onchange="errorRemove(this);"
                            id="adjustment_type" name="adjustment_type">
                            <option selected disabled>Select Adjustment Type </option>
                            <option value="increase">Increase</option>
                            <option value="decrease">Decrease</option>
                        </select>
                        <span class="text-danger adjustment_type_error"></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <label for="ageSelect" class="form-label"> Reason (Optional) </label>
                    <div class="mb-3 ">
                        <textarea name="reason" class="form-control" id="" cols="5" rows="2"></textarea>
                        <span class="text-danger reason_name_error"></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3 ">
                        <label for="ageSelect" class="form-label">Select Warehouse </label>
                        <select class="js-example-basic-single form-control form-select warehouse_id" id="warehouseSelect"
                        onkeyup="errorRemove(this);"data-width="100%" data-loaded="false" id="warehouse_id"
                            name="warehouse_id">
                            <option selected disabled>Select Warehouse </option>
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->warehouse_name }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger warehouse_id_error"></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3 ">
                        <label for="ageSelect" class="form-label">Select Rack </label>
                        <select class=" form-control form-select rack_id" id="racksSelect"
                            onchange="errorRemove(this);" data-width="100%" data-loaded="false" id="rack_id"
                            name="rack_id">
                            <option selected disabled>Select Rack</option>

                        </select>
                        <span class="text-danger rack_id_error"></span>
                    </div>
                </div>
                {{-- ///////////View Rack Details//////////// --}}
                <div class="col-md-12 mb-3">
                    <table id="dataTable" class="table">
                        <thead id="rack_view_table" class="d-none">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Size</th>
                                <th>Color</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be populated here -->
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12">
                    <div class="mb-3 col-md-12">

                        <label for="ageSelect" class="form-label">Products <span class="text-danger">*</span></label>
                        <select class="js-example-basic-single form-select view_product product_select view_product"
                            name="product_id" data-width="100%">
                            {{-- onchange="show_quantity(this)" --}}
                            {{-- @if ($products->count() > 0)
                                <option selected disabled>Select Product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            @else
                                <option selected disabled>Please Add Product</option>
                            @endif --}}
                        </select>
                        <span class="text-danger product_select_error"></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class=" mb-1 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body px-4 py-2">
                                <div class="mb-3">
                                    <h6 class="card-title">Items</h6>
                                </div>

                                <div id="" class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Color</th>
                                                <th>Size</th>
                                                <th>Available Qty</th>
                                                <th>Adjustment Qty</th>
                                                <th>
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="showAdjustData">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer px-5 pb-4">
                <button type="submit" class="btn btn-primary save_adjusct_racks">Save</button>
            </div>
        </form>
    </div>
    <!-- Varient Modal -->
    <div class="modal fade" id="varientModal" tabindex="-1" aria-labelledby="exampleModalScrollableTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Select Variants</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="show_variants row gx-2">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary add_all_variants">Add Variants</button>
                </div>
            </div>
        </div>
    </div>
    <style>
        .addVariant.active>div {
            background: #0d6efd;
            color: white;
        }
    </style>
    <script>
        let checkSellEdit = '{{ $selling_price_edit }}';
        // discount edit check
        let discountCheck = '{{ $discount }}';
        // check warranty status
        let checkWarranty = '{{ $warranty_status }}';
        // check invoice payment system
        let invoicePayment = '{{ $invoice_payment }}';
        // check invoice payment system
        let checkTax = '{{ $tax }}';
        // check price type
        let priceType = "{{ $sale_price_type }}";
        // checkInvoice type
        let checkPrintType = '{{ $invoice_type }}';

        function errorRemove(element) {
            if (element.value != '') {
                $(element).siblings('span').hide();
                $(element).css('border-color', 'green');
            }
        }

        $(document).ready(function() {
            // show error
            function showError(name, message) {
                $(name).css('border-color', 'red'); // Highlight input with red border
                $(name).focus(); // Set focus to the input field
                $(`${name}_error`).show().text(message); // Show error message
            }
        //     document.addEventListener("input", function (event) {
        //     // Check if the target is the quantity input field
        //     if (event.target && event.target.classList.contains("quantity")) {
        //         const quantityValue = parseFloat(event.target.value) || 0; // Get the input value or set to 0 if invalid
        //         if (quantityValue <= 0) {
        //             toastr.warning("Quantity must be greater than 0.");
        //             event.target.value = "";
        //         }
        //     }
        // });


            var warehouseId = null; // Initialize warehouseId variable

            //----------------- Warehouse selection event-----------------------------//

            $('#warehouseSelect').on('change', function() {
                warehouseId = $(this).val(); // Update the warehouseId

                // Clear the racks dropdown
                $('#racksSelect').empty().append('<option selected disabled>Select Rack</option>');

                if (warehouseId) {
                    $.ajax({
                        url: '/get-warehouse-racks',
                        type: 'GET',
                        data: {
                            warehouse_id: warehouseId
                        },
                        success: function(response) {
                            // Populate the racks dropdown with the received data
                            if (response.length > 0) {
                                $.each(response, function(index, rack) {
                                    $('#racksSelect').append(
                                        '<option value="' + rack.id + '">' + rack
                                        .rack_name + '</option>'
                                    );
                                });
                            } else {
                                $('#racksSelect').append(
                                    '<option disabled>No racks found</option>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching racks:', error);
                        },
                    });
                }
            });

            // //-------------------------- Rack selection event-----------------------------//
            // function filterAdjustProducts() {
            $('#racksSelect').on('change', function() {
                let rackId = $(this).val(); // Get the selected rack ID
                let productSelect = $('.product_select');
                let rackViewTableHead = $('#rack_view_table');
                rackViewTableHead.removeClass('d-none');
                productSelect.empty();
                productSelect.append('<option selected disabled>Select Product</option>');
                $('#dataTable tbody').empty();

                // console.log('Rack:', rackId, 'Warehouse:', warehouseId);
                if (rackId && warehouseId) {
                    $.ajax({
                        url: '/get-adujustment-rack-view-data',
                        type: 'GET',
                        data: {
                            warehouse_id: warehouseId, // Use the stored warehouseId
                            rack_id: rackId, // Pass the rack ID
                        },
                        success: function(response) {
                            // Populate the table with the received data
                            // console.log('Rack:', response)

                            if (response.racks && response.racks.length > 0) {
                                $.each(response.racks, function(index, data) {
                                    productSelect.append(
                                        `<option value="${data.product.id}">${data.product.name} | ${data.variation.variation_size.size} | ${data.variation.color}</option>`
                                    );

                                    $('#dataTable tbody').append(`
                                <tr>
                                     <td>${index + 1}</td>
                                    <td>${data.product.name}</td>
                                    <td>${data.variation.variation_size.size}</td>
                                    <td>${data.variation.color_name.name}</td>
                                    <td>${data.stock_quantity}</td>
                                </tr>
                            `);
                                });
                            } else {
                                $('#dataTable tbody').append(`
                            <tr>
                                <td colspan="5">No data found for this rack</td>
                            </tr>
                        `);
                                productDefault();
                            }

                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching rack data:', error);
                        },
                    });
                }
            });
            // }//filter function
            //--------------------------- Add Product  Event -----------------------------//
            $('.product_select').change(function() {
                let id = $(this).val();
                // console.log('Selected Product ID:', id);
                const product_id = id;
                $.ajax({
                    url: '/product/find/' + id,
                    type: 'GET',
                    dataType: 'JSON',
                    success: function(res) {
                        const product = res.data;
                        // console.log(product);
                        if (product.variations.length > 1) {
                            $('#varientModal').modal('show');
                            rackadjustShowVariants(product.variations);
                        } else {
                            fetchVariantDetails(product.variations[0].id);
                            // console.log(product.id);
                            // alert('ok');
                        }
                    },
                });
            })

            //-------------------------------show Rack Adjust Add Product ------------------------------//
            // show Product function
            function showRackAdjustAddProduct(product, quantity, variantSumStock,varationStockCurrent) {
                // console.log('stocks',varationStockCurrent.stock_quantity)
                const quantity1 = quantity;

                // console.log("showAddProduct Func", product);
                // console.log('nwe',product.product.id);
                // Check if a row with the same product ID already exists
                const existingRow = $(`.data_row${product.id}`);
                if (existingRow.length > 0) {
                    // If the row exists, update the quantity
                    const quantityInput = existingRow.find('.quantity');
                    const currentQuantity = parseInt(quantityInput.val());
                    quantityInput.val(currentQuantity + 1);
                    return;
                }
                const stockQuantity = variantSumStock
                    ? variantSumStock:0;

                // If the row doesn't exist, add a new row
                const rowHtml = `
            <tr class="data_row${product.id}">
                <td>
                    <input type="text" class="form-control product_name${product.id} border-0" name="product_name[]" readonly value="${product?.product?.name ?? ""}" />
                </td>
                <td>
                    <input type="text" class="form-control product_color${product.id} border-0" name="product_color[]" readonly value="${product?.color_name?.name ?? ""}" />
                </td>
                <td>
                    <input type="text" class="form-control product_size${product.id} border-0" name="product_size[]" readonly value="${product?.variation_size?.size ?? ""}" />
                </td>
                    <td>
                    <input type="hidden" class="" name="adjustProductId[]"  value="${product.product.id ?? 0}" />
                    <input type="hidden" class="product_id" name="product_id[]" readonly value="${product.id ?? 0}" />
                 <input type="number" product-id="${product.id}" readonly class="form-control border-0 avaliableQuantity" value="${stockQuantity}" />
                    </td>
                <td>
                    <input type="number" product-id="${product.id}" id="quantity" class="form-control quantity productQuantity${product.id}" name="quantity[]" value="${quantity1}" />
                </td>

                <td style="padding-top: 20px;">
                    <a href="#" class="btn btn-sm btn-danger btn-icon damage_delete" style="font-size: 8px; height: 25px; width: 25px;" data-id=${product.id}>
                        <i class="fa-solid fa-trash-can" style="font-size: 0.8rem; margin-top: 2px;"></i>
                    </a>
                </td>
            </tr>
        `;
                $('.showAdjustData').append(rowHtml);
                // Add validation for quantity input

            }
            //-------------------------------Add Product End -------------------------------//

            // Purchase delete
            $(document).on('click', '.damage_delete', function(e) {
                let id = $(this).attr('data-id');
                let dataRow = $('.data_row' + id);
                dataRow.remove();
            });

            $(document).on('click', '.addVariant', function() {

                const variantId = $(this).attr('data-id');
                // console.log('Selected VAriant ID:', variantId);
                $(this).toggleClass('active');
                const isActive = $(this).hasClass('active');
                $(this).attr('data-active', isActive ? 'true' : 'false');
            });
            // Add variant selection
            $(document).on('click', '.add_all_variants', function() {
                // console.log('ok');
                const activeVariants = $('.addVariant[data-active="true"]');

                if (activeVariants.length === 0) {
                    alert('Please select at least one variant.');
                    return;
                }
                // console.log('Selected variants:', activeVariants);
                // Loop through each active variant and fetch details
                activeVariants.each(function() {
                    const variantId = $(this).attr(
                    'data-id'); // Get the data-id of the active variant
                    fetchVariantDetails(
                    variantId); // Call fetchVariantDetails for each active variant
                });

                // Hide the modal after processing
                $('#varientModal').modal('hide');
            });

            //////////// ---Variation Select ////////////////
            function rackadjustShowVariants(variants) {
                // Clear previous variants (if any)
                $('.show_variants').empty();
                // console.log(variants);
                // Loop through each variant and append to the modal
                variants.forEach(variant => {
                    // console.log(variant);
                    const imageUrl = variant?.image ? `/uploads/products/${variant.image}` :
                        '/dummy/image.jpg';
                    // const imageUrl = variant.image || '/dummy/image.jpg';
                    $('.show_variants').append(`
                <div class="col-md-4 text-center p-2 cursor-pointer addVariant" data-active="false" data-id="${variant.id}">
                    <div class="rounded border pb-2 overflow-hidden">
                        <img src="${imageUrl}" class="img-fluid"
                            style="object-fit: cover; height: 120px; width: 100%" alt="variant image">
                        <div class="mt-2">
                            <p class="">${priceType === "b2c_price" ? variant.b2b_price : variant.b2c_price ?? 0}</p>
                            <p class="">${variant?.variation_size?.size ?? ""}</p>
                            <p class="">${variant?.color_name?.name ?? ""}</p>
                        </div>
                    </div>
                </div>
            `);
                });
            }

            function fetchVariantDetails(variantId, isProduct) {
                $.ajax({
                    url: `/variant/find/${variantId}`,
                    type: 'GET',
                    data: {
                        isProduct: isProduct
                    },
                    dataType: 'JSON',
                    success: function(res) {
                        const variant = res.variant;
                        const product = res.variant.product.id
                        // console.log(product);
                        const variantSumStock = res.totalVariationStock;
                        const varationStockCurrent = res.varationStockCurrent;

                        // console.log('check',varationStockCurrent);
                        showRackAdjustAddProduct(variant, '', variantSumStock,varationStockCurrent);

                    },
                    error: function(error) {
                        console.error('Error fetching variant details:', error);
                    }
                });
            }
            const saveadjustRacks = document.querySelector('.save_adjusct_racks');

            saveadjustRacks.addEventListener('click', function(e) {
                e.preventDefault();
                // console.log('id',stockId);

                let isValid = true;
                const adjustmentType = $('#adjustment_type').val();
                if (adjustmentType === 'decrease') {
                $('.quantity').each(function() {
                    const enteredQty = parseInt($(this).val()) || 0;
                    const availableQty = parseInt($(this).closest('tr').find('.avaliableQuantity').val()) || 0;
                    const productName = $(this).closest('tr').find('input[name="product_name[]"]').val() || 'Unknown Product';
                    if (enteredQty > availableQty) {
                        isValid = false;
                        toastr.error(`Quantity cannot exceed available stock (${availableQty}) for product: ${productName}`);
                        return false; // Break the each loop
                    }
                });

            }
                const quantityFields = document.querySelectorAll('input[name="quantity[]"]');
                quantityFields.forEach((field) => {
                    const quantityValue = parseFloat(field.value) || 0;
                    if (quantityValue <= 0) {
                        toastr.warning("Quantity must be greater than 0.");
                        field.classList.add("is-invalid"); // Add invalid class
                        isValid = false;
                    } else {
                        field.classList.remove("is-invalid"); // Remove invalid class
                    }
                });

                // Stop AJAX request if validation fails
                if (!isValid) {
                    return; // Exit function
                }
                let formData = new FormData($('.adjustStockForm')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: `/stock/adjust/store`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            $('.adjustStockForm')[0].reset();
                            // racksAssignView();
                            toastr.success(res.message);
                            window.location.reload();
                          } else if (res.status == 400) {
                            if (res.error.branch_id) {
                                showError('.branch_id', res.error.branch_id);
                            }
                            if (res.error.adjustment_type) {
                                showError('.adjustment_type', res.error.adjustment_type);
                            }
                            // if (res.error.warehouse_id) {
                            //     showError('.warehouse_id', res.error.warehouse_id);
                            // }
                            // if (res.error.rack_id) {
                            //     showError('.rack_id', res.error.rack_id);
                            // }
                            // Show validation error
                        }
                    }
                });
            })

            function productDefault() {
                $.ajax({
                    url: '/product/default',
                    method: 'GET',
                    success: function(res) {
                        console.log(res);
                        const products = res.products;
                        // console.log(products);
                        $('.view_product').empty();

                        if (products.length > 0) {
                            $('.view_product').append(
                                '<option selected disabled>Select Product</option>'
                            );
                            $.each(products, function(index, product) {
                                $('.view_product').append(
                                    `<option value="${product.id}">${product.name}</option>`
                                );
                            });
                        } else {
                            $('.view_product').html(`
                    <option selected disabled>Please add Product</option>
                `);
                        }
                    }
                });
            }
            productDefault();

        }) //ready endpoint;
    </script>
@endsection
