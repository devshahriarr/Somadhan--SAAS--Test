@extends('master')
@section('title', '| Damage Add')
@section('admin')
<style>
       /* ///drag & drop// */
       .responsive-table {
            overflow: auto;
            }
            table {
            width: 100%;
            border-spacing: 0;
            border-collapse: collapse;
            white-space:nowrap;
            }
            .damage_bars{
            cursor: move;
            }
</style>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
            <div class="">
                @if (Auth::user()->can('damage.list'))
                    <h4 class="text-right"><a href="{{ route('report.damage') }}" class="btn btn-info">All Damage History</a>
                    </h4>
                @endif
            </div>
        </div>
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-info">Add Damage</h6>
                    <form id="myValidForm" action="{{ route('damage.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <!-- Col -->
                            <div class="mb-3 col-md-12">
                                @php
                                    $products = App\Models\Product::withSum(
                                        [
                                            'stockQuantity as stock_quantity_sum' => function ($query) {
                                                $query->where('branch_id', Auth::user()->branch_id);
                                            },
                                        ],
                                        'stock_quantity',
                                        )
                                        ->having('stock_quantity_sum', '>', 0) // Use having method here
                                        ->orderBy('stock_quantity_sum', 'asc')
                                        ->get();
                                    // $products = App\Models\Product::get();
                                @endphp
                                <label for="ageSelect" class="form-label">Products <span
                                        class="text-danger">*</span></label>
                                <select class="js-example-basic-single form-select product_select view_product"
                                    name="product_id" data-width="100%">
                                    {{-- onchange="show_quantity(this)" --}}
                                    @if ($products->count() > 0)
                                        <option selected disabled>Select Damaged Product</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    @else
                                        <option selected disabled>Please Add Product</option>
                                    @endif
                                </select>
                                <span class="text-danger product_select_error"></span>
                            </div>
                            <div class="col-sm-12">
                                <div class="mb-3 form-valid-groups">
                                    <label class="form-label">Enter Quantity
                                        <span class="text-danger">*</span>
                                        <span class="text-primary" id="show_stock"></span>
                                        <span class="text-primary" id="show_unit"></span>
                                    </label>
                                    <div class="row">
                                        <div class=" mb-1 grid-margin stretch-card">
                                            <div class="card">
                                                <div class="card-body px-4 py-2">
                                                    <div class="mb-3">
                                                        <h6 class="card-title">Items</h6>
                                                    </div>

                                                    <div id="" class="table-responsive">
                                                        <table class="table" id="sortable">
                                                            <thead>
                                                                <tr  class="ui-state-default">
                                                                    <th>Product</th>
                                                                    <th>Color</th>
                                                                    <th>Size</th>
                                                                    <th>Cost Price</th>
                                                                    <th>Available Qty</th>
                                                                    <th>Qty</th>
                                                                    <th>
                                                                        <i class="fa-solid fa-trash-can"></i>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="showDamageData">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <input type="text" id="damageQty" name="pc" onkeyup="damage_qty(this);"
                                        class="form-control" placeholder="0" value="{{ old('pc') }}" disabled autocomplete="off"> --}}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3 form-valid-groups">
                                        <label class="form-label">Date<span class="text-danger">*</span></label>
                                        <div class="input-group flatpickr me-2 mb-2 mb-md-0" id="dashboardDate">
                                            <span class="input-group-text input-group-addon bg-transparent border-primary"
                                                data-toggle><i data-feather="calendar" class="text-primary"></i></span>
                                            <input type="text" name="date"
                                                class="form-control bg-transparent border-primary"
                                                value="{{ old('date') }}" placeholder="Select date" data-input>
                                        </div>
                                        {{-- <input type="date"  class="form-control" placeholder="Enter Date"> --}}
                                    </div>
                                </div><!-- Col -->
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label">Note</label>
                                        <textarea name="note" class="form-control" value="{{ old('note') }}" placeholder="Write About Damages"
                                            rows="4" cols="50"></textarea>
                                    </div>
                                </div><!-- Col -->
                            </div><!-- Row -->
                            <div>
                                <input type="submit" id="submit_btn" class="btn btn-primary submit" value="Save">
                            </div>
                    </form>
                </div>
            </div>
        </div>

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
    <script type="text/javascript">
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
        let drag_and_drop = "{{ $drag_and_drop }}";
        if(drag_and_drop === "1"){
        $("#sortable tbody").sortable({
            cursor: "move",
            placeholder: "sortable-placeholder",
            helper: function(e, tr) {
                var $originals = tr.children();
                var $helper = tr.clone();
                $helper.children().each(function(index) {
                    $(this).width($originals.eq(index).width());
                });
                return $helper;
            }
        }).disableSelection();
    }
        // Select product
        $(document).ready(function() {

            $('#myValidForm').on('submit', function(e) {
                let isValid = true; // Flag to check if all quantities are valid

                // Loop through all quantity inputs
                $('input[name="quantity[]"]').each(function() {
                    let value = $(this).val();
                    if (value === '' || value <= 0) {
                        isValid = false; // If any field is empty or less than or equal to 0
                        return false; // Exit the loop early
                    }
                });

                // If any invalid quantity is found, show an alert and prevent submission
                if (!isValid) {
                    e.preventDefault(); // Prevent form submission
                    toastr.error('Please Ensure All Quantities are greater than 0.'); // Show toaster
                }
            });

            $('.product_select').change(function() {
                let id = $(this).val();
                console.log('Selected Product ID:', id);
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
                            damageShowVariants(product.variations);
                        } else {
                            fetchVariantDetails(product.variations[0].id);
                            // console.log(product.id);
                            // alert('ok');
                        }
                    },
                });
            })
        });
        //-------------------------------Add Product  ------------------------------//
        // show Product function
        function showDamageAddProduct(product, quantity,variantSumStock) {
            // console.log('stocks',variantSumStock)
            const quantity1 = quantity ;
            // console.log("showAddProduct Func", product);
            // console.log('nwe',product.product.id);
            // Check if a row with the same product ID already exists
            const existingRow = $(`.data_row${product.id}`);
            if (existingRow.length > 0) {
                // If the row exists, update the quantity
                const quantityInput = existingRow.find('.quantity');
                const currentQuantity = parseInt(quantityInput.val());
                quantityInput.val(currentQuantity + 1);
                if (newQuantity > variantSumStock) {
                    toaster.error("Quantity exceeds available stock!");
                    return;
                }
                quantityInput.val(newQuantity);
                return;
            }

            // If the row doesn't exist, add a new row
            const rowHtml = `
            <tr class="data_row${product.id}">
                <td class="damage_bars">
                    <input type="text" class="form-control product_name${product.id} border-0" name="product_name[]" style="width:60px;margin:0;padding:0;font-size:12px"  readonly value="${product?.product?.name ?? ""}" />
                </td>
                <td>
                    <input type="text" class="form-control product_color${product.id} border-0" name="product_color[]" style="width:60px;margin:0;padding:0;font-size:12px"  readonly value="${product?.color_name?.name ?? ""}" />
                </td>
                <td>
                    <input type="text" class="form-control product_size${product.id} border-0" name="product_size[]" style="width:60px;margin:0;padding:0;font-size:12px"  readonly value="${product?.variation_size?.size ?? ""}" />
                </td>
                <td>
                    <input type="hidden" class="" name="damageProductId[]"  value="${product.product.id ?? 0}" />
                    <input type="hidden" class="product_id" name="product_id[]" readonly value="${product.id ?? 0}" />
                    <input type="hidden" class="variant_cost_price${product.id}" name="variant_cost_price[]" readonly style="width:60px;margin:0;padding:0;font-size:12px"  value="${product.cost_price ?? 0}" />
                    <input type="hidden" class="variant_sale_price${product.id}" name="variant_sale_price[]" readonly style="width:60px;margin:0;padding:0;font-size:12px" value="${priceType == "b2c_price" ? product.b2b_price : product.b2c_price ?? 0}" />

                    <input type="number" product-id="${product.id}" readonly class="form-control  border-0 unit_price product_price${product.id}" style="width:60px;margin:0;padding:0;font-size:12px"  id="product_price" name="unit_price[]"  value="${product.cost_price ?? 0}" />
                </td>
                    <td>
                    <input type="number" product-id="${product.id}" readonly class="form-control border-0 avaliableQuantity" style="width:60px;margin:0;padding:0;font-size:12px"  value="${variantSumStock}" />
                </td>
                <td>
                    <input type="number" product-id="${product.id}" id="quantity" class="form-control quantity productQuantity${product.id}" style="width:60px;margin:0;padding:0;font-size:12px"   name="quantity[]" value="${quantity1}" />
                </td>

                <td style="padding-top: 20px;">
                    <a href="#" class="btn btn-sm btn-danger btn-icon damage_delete" style="font-size: 8px; height: 25px; width: 25px;" data-id=${product.id}>
                        <i class="fa-solid fa-trash-can" style="font-size: 0.8rem; margin-top: 2px;"></i>
                    </a>
                </td>
            </tr>
        `;
            $('.showDamageData').append(rowHtml);
             // Add validation for quantity input
            $(`.productQuantity${product.id}`).on('input', function () {
                const inputQuantity = parseInt($(this).val()) || 0;

                if (inputQuantity > variantSumStock) {
                    toastr.warning("Quantity exceeds available stock!");
                    $(this).val(''); // Reset to maximum allowed quantity
                }
            });
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
                const variantId = $(this).attr('data-id'); // Get the data-id of the active variant
                fetchVariantDetails(variantId); // Call fetchVariantDetails for each active variant
            });

            // Hide the modal after processing
            $('#varientModal').modal('hide');
        });

        ////////////Variation Select ////////////////
        function damageShowVariants(variants) {
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
                            <p class="">${variant?.color_name.name ?? ""}</p>
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

                    // console.log(variantSumStock);
                    showDamageAddProduct(variant ,'',variantSumStock);

                },
                error: function(error) {
                    console.error('Error fetching variant details:', error);
                }
            });
        }
        //show available Quantity information
        // function show_quantity(event) {

        //     let newValue = event.value;


        //     $.ajax({
        //         url: '/damage/show_quantity/' + newValue,
        //         type: 'get',
        //         success: function(res) {
        //             $('#show_stock').text(res.stock_quantity);
        //             $('#show_unit').text(res.unit.name);
        //             $('#damageQty').removeAttr('disabled');
        //         }
        //     });
        // }
        //Damage Quantity validation
        function damage_qty(event) {

            let newValue = event.value;
            let available_stock = parseInt($('#show_stock').text());

            if (available_stock < newValue) {
                event.value = '';
                $('#submit_btn').attr("disabled", "disabled");
                Swal.fire({
                    position: "top-end",
                    icon: "warning",
                    title: 'Invalid Quantity',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                $('#submit_btn').removeAttr('disabled')
            }

        }


        $(document).ready(function() {

            $('#myValidForm').validate({
                rules: {
                    product_id: {
                        required: true,
                    },
                    pc: {
                        required: true,
                    },
                    date: {
                        required: true,
                    },
                },
                messages: {
                    damaged_product_id: {
                        required: 'Please Enter the Name of Damaged Product',
                    },
                    pc: {
                        required: 'Please Enter the number of Damaged Products',
                    },
                    date: {
                        required: 'Please Enter date of Damaged Products',
                    },
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-valid-groups').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                    $(element).addClass('is-valid');
                },
            });
        });
    </script>
@endsection
