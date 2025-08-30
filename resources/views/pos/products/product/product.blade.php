@extends('master')
@section('title', '| Add Product')
@section('admin')

    <nav class="page-breadcrumb d-flex justify-content-between align-items-center">
        <!-- Left Breadcrumb -->
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">New Product</li>
        </ol>

        <!-- Right Button -->
        <ol class="breadcrumb mb-0 ms-auto">
            <li class="breadcrumb-item">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFieldModal">
                    <i class="fas fa-plus"></i> Add Extra Field
                </a>
            </li>
        </ol>
    </nav>
    @php
        $categories = App\Models\Category::where('status', '1')->get();
    @endphp
    <form class="productForm" enctype="multipart/form-data">
        <div class="row">
            <div class="col-lg-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="card-title">Add Product</h6>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">Product Name<span
                                        class="text-danger">*</span></label>
                                <input class="form-control name" name="name" type="text" onkeyup="errorRemove(this);"
                                    onchange="errorRemove(this);" value="{{ old('name') }}">
                                <span class="text-danger name_error"></span>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">Variation Name(Optional)</label>
                                <input class="form-control " name="variation_name" type="text">

                            </div>
                            @if($multiple_category ===1)
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">Multiple Category </label>
                                <select class="compose-multiple-select form-select multiple_category "  multiple="multiple"  id="multiple_category"
                                    name="multiple_category[]" onchange="errorRemove(this);">
                                    @if ($categories->count() > 0)
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_name') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}</option>
                                        @endforeach
                                    @else
                                        <option selected disabled>Please Add Category</option>
                                    @endif
                                </select>
                                <span class="text-danger multiple_category_error"></span>
                            </div>
                            @else
                             <div class="mb-3 col-md-6">
                                <label for="ageSelect" class="form-label">Category<span class="text-danger">*</span></label>
                                <select class="js-example-basic-single form-select category_id" id="category_name"
                                    name="category_id" onchange="errorRemove(this);">
                                    @if ($categories->count() > 0)
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_name') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}</option>
                                        @endforeach
                                    @else
                                        <option selected disabled>Please Add Category</option>
                                    @endif
                                </select>
                                <span class="text-danger category_id_error"></span>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="ageSelect" class="form-label">Subcategory</label>
                                <select class="js-example-basic-single form-select subcategory_id" name="subcategory_id">
                                </select>
                            </div>
                            @endif



                            <div class="mb-3 col-md-6">
                                @php
                                    $brands = App\Models\Brand::get();
                                @endphp
                                <label for="ageSelect" class="form-label">Brand <span class="text-danger">*</span></label>
                                <select class="js-example-basic-single form-select brand_id" name="brand_id"
                                    onchange="errorRemove(this);">
                                    @if ($brands->count() > 0)
                                        <option selected disabled>Select Brand</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}"
                                                {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option selected disabled>Please Add Brand</option>
                                    @endif
                                </select>
                                <span class="text-danger brand_id_error"></span>
                            </div>
                          @if($multiple_category ===1)
                            <div class="mb-3 col-md-4">
                                <label for="ageSelect" class="form-label">Select Size <span class="text-danger">*</span></label>
                                <select class="js-example-basic-single form-select multiple_size_id  multiple_size" name="size"
                                    onchange="errorRemove(this);">
                                    <option selected disabled>Select Size</option>
                                </select>
                                <span class="text-danger multiple_size_error"></span>
                            </div>
                            @else
                            <div class="mb-3 col-md-4">
                                <label for="ageSelect" class="form-label">Size <span class="text-danger">*</span></label>
                                <select class="js-example-basic-single form-select size_id  size" name="size"
                                    onchange="errorRemove(this);">
                                    <option selected disabled>Select Size</option>
                                </select>
                                <span class="text-danger size_error"></span>
                            </div>
                            @endif
                            <div class="mb-3 col-md-4">
                                @php
                                    $units = App\Models\Unit::get();
                                @endphp
                                <label for="ageSelect" class="form-label"> Unit <span class="text-danger">*</span></label>
                                <select class="js-example-basic-single form-select unit" name="unit"
                                    onchange="errorRemove(this);">
                                    @if ($units->count() > 0)
                                        <option selected disabled>Select Unit</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option selected disabled>Please Add Unit</option>
                                    @endif
                                </select>
                                <span class="text-danger unit_error"></span>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="ageSelect" class="form-label">Model No </label>
                                <input type="text" class="form-control" name="model_no">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="password" class="form-label">Cost Price <span
                                        class="text-danger">*</span></label>
                                <input class="form-control cost_price" name="cost_price" onkeyup="errorRemove(this);"
                                    onblur="errorRemove(this);" type='number' placeholder="00.00" />
                                <span class="text-danger cost_price_error"></span>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="password" class="form-label">B2C Price <span
                                        class="text-danger">*</span></label>
                                <input class="form-control b2c_price" name="b2c_price" type='number'
                                    placeholder="00.00" onkeyup="errorRemove(this);" onblur="errorRemove(this);" />
                                <span class="text-danger b2c_price_error"></span>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="password" class="form-label">B2B Price <span
                                        class="text-danger">*</span></label>
                                <input class="form-control b2b__price" name="b2b_price" type='number'
                                    placeholder="00.00" onkeyup="errorRemove(this);" onblur="errorRemove(this);" />
                                <span class="text-danger b2b_price_error"></span>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Origin </label>
                                <select class="js-example-basic-single form-select " name="origin"
                                    onchange="errorRemove(this);">
                                    <option selected disabled>Select Origin</option>
                                    <option value="cn">China</option>
                                    <option value="in">India</option>
                                    <option value="us">United States</option>
                                    <option value="id">Indonesia</option>
                                    <option value="pk">Pakistan</option>
                                    <option value="br">Brazil</option>
                                    <option value="ng">Nigeria</option>
                                    <option value="bd">Bangladesh</option>
                                    <option value="ru">Russia</option>
                                    <option value="mx">Mexico</option>
                                </select>
                                <span class="text-danger origin_error"></span>
                            </div>
                            <div class="mb-3 col-12">
                                <label for="" class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3"></textarea>
                            </div>
                            <div class="mb-3 col-md-6">
                                @php
                                    $extra_field = App\Models\Attribute::all();
                                @endphp
                                <label for="" class="mb-2">Extra Field Add</label>
                                <select class="form-select extra_field" name="extra_field">
                                    <option selected disabled>Select Origin</option>
                                    @foreach ($extra_field as $item)
                                        <option value="{{ $item->id }}" data-id="{{ $item->id }}">
                                            {{ strtoupper($item->field_name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="extra_info_field"></div>


                        </div>
                    </div>
                </div>
            </div>
            {{-- @php
                $colors = App\Models\Color::get();
            @endphp --}}
            <div class="col-lg-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-12 row align-items-center">
                                <div class="col-10">
                                    <label for="ageSelect" class="form-label">Color</label>
                                    <select class="form-control js-example-basic-single show_color" name="color">
                                        {{-- @if ($colors->count() > 0)
                                            <option selected disabled>Select Color</option>
                                            @foreach ($colors as $color)
                                                <option value="{{ $color->id }}">${{ $color->name ?? '' }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option selected disabled>No Color Found</option>
                                        @endif --}}
                                    </select>
                                </div>
                                <div class="col-2">
                                    <a href="#" class="btn btn-rounded-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#colorModal"><i data-feather="plus"></i></a>
                                </div>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="ageSelect" class="form-label">Quality</label>
                                <select class="form-control js-example-basic-single " name="quality">
                                    <option selected disabled>Select Quality</option>
                                    <option value="grade-a">Grade A</option>
                                    <option value="grade-b">Grade B</option>
                                    <option value="grade-c">Grade C</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="ageSelect" class="form-label">Current Stock</label>

                                <input type="number" class="form-control" name="current_stock">
                            </div>
                            @if (isset($manufacture_date) && $manufacture_date == 1)
                                <div class="mb-3 col-md-6" id="customDate">
                                    <label for="manufacture_date" class="form-label">Manufacture Date</label>
                                    {{-- <input type="date" class="form-control" name="manufacture_date" > --}}
                                    <input type="date" name="manufacture_date" data-input
                                        class="form-control bg-transparent border-primary "
                                        placeholder="Select Manufacture Date date">
                                </div>
                            @endif
                            @if (isset($expiry_date) && $expiry_date == 1)
                                <div class="mb-3 col-md-6">
                                    <label for="expiry_date" class="form-label">Expiry Date</label>
                                    <div class="input-group " id="customDate">
                                        <input type="date" name="expiry_date" data-input
                                            class="form-control bg-transparent border-primary "
                                            placeholder="Select Expiry date">
                                    </div>
                                </div>
                            @endif
                            @if ($product_set_low_stock == 1)
                                <div class="mb-3 col-md-12">
                                    <label class="form-label">Set low Stock Alert</label>
                                    <div class="input-group ">
                                        <input type="number" name="low_stock_alert" data-input
                                            class="form-control bg-transparent border-primary "
                                            placeholder="Enter qty for stock alert">
                                    </div>
                                </div>
                            @endif
                            <div class="mb-3 col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">Product Image</h6>
                                        <p class="mb-3 text-warning">Note: <span class="fst-italic">Image not
                                                required. If you
                                                add
                                                a category image
                                                please add a 400 X 400 size image.</span></p>
                                        <input type="file" class="categoryImage" name="image" id="myDropify" />
                                    </div>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <input class="btn btn-primary w-full save_product" type="submit" value="Submit">
                                <input class="btn btn-secondary  " type="btn" readonly value="Add Variation"
                                    id="toggleButton">
                            </div>
                            <div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    {{-- ///////////////////////////////////////////////Variation Create Code ///////////////////////////////// --}}
    <div class="controll-variation" style="display: none;">
        <div class="latest-product-container d-none">
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
                                                <p> Product Name : <span id="latestProductName"
                                                        class="text-success fs-5 fs-bold "> <input type="text"
                                                            value="" style="display: none"></span></p>
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

                                                                        {{-- <th class="dynamic-head" style="display: none;">Current Stock
                                                                    </th>
                                                                    <th class="dynamic-head" style="display: none;">Cost Price
                                                                    </th>
                                                                    <th class="dynamic-head" style="display: none;">B2B Price
                                                                    <th class="dynamic-head" style="display: none;">B2C Price
                                                                    </th>
                                                                    <th class="dynamic-head" style="display: none;">Size
                                                                    </th>
                                                                    <th class="dynamic-head" style="display: none;">Color
                                                                    </th>
                                                                    <th class="dynamic-head" style="display: none;">Model
                                                                        No</th>
                                                                    <th class="dynamic-head" style="display: none;">
                                                                        Quality</th>
                                                                    <th class="dynamic-head" style="display: none;">Image
                                                                    </th> --}}
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <input value="" type="hidden"
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



    {{-- //////////////////////////////////////////////// add extra field modal //////////////////////////////////////////////////////// --}}


    <div class="modal fade" id="addFieldModal" tabindex="-1" aria-labelledby="addFieldModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFieldModalLabel">Add New Field</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addFieldForm">
                        <!-- Field Name -->
                        <div class="mb-3">
                            <label for="field_name" class="form-label">Field Name</label>
                            <input type="text" class="form-control" id="field_name" name="field_name" required>
                        </div>



                        <div class="mb-3">
                            <label for="data_type" class="form-label">Data Type</label>
                            <select class="form-select p-2" id="data_type" name="data_type" required>
                                <option value="">Select Data Type</option>

                                <option value="longText">Text</option>
                                <option value="text">String</option>
                                <option value="int">Integer</option>

                                <option value="decimal">Decimal</option>
                                <option value="double">Double</option>
                                <option value="date">Date</option>
                                <option value="json">MultiSelect</option>
                            </select>
                        </div>
                        <div class="mb-3 multiInput" style="display: none;">

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="#" class="btn btn-success addFieldForm" form="addFieldForm" id="">Save
                        Field</a>
                </div>
            </div>
        </div>
    </div>


    <!---------------------------------------- Color Modal -------------------------------------------->
    <div class="modal fade" id="colorModal" tabindex="-1" aria-labelledby="exampleModalScrollableTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Add Color</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="colorForm" class="colorForm row">
                        <div class="mb-3 col-12">
                            <label for="name" class="form-label">Color Name</label>
                            <input class="form-control color_name" maxlength="39" name="name" type="text"
                                onkeyup="errorRemove(this);">
                            <span class="text-danger name_error"></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_color">Save</button>
                </div>
            </div>
        </div>
    </div>



    {{-- - //////////////////////////////////////////////// Variation Create Code //////////////////////////////////// -- --}}

    <script>
        ///////////////////////////////////////////show extra field ///////////////////////////////////



        function showExtraField() {
            $.ajax({
                url: "{{ url('get-extra-field/info/product/page/show') }}",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    // console.log("Response Data:", data);

                    if (data.status === 200) {
                        $('.extra_field').empty();
                        let extraField = data.extraField;
                        // console.log(extraField);
                        let option = `<option value="" selected disabled>Select Extra Field</option>`;

                        extraField.forEach(function(field) {
                            option +=
                                `<option value="${field.id}" data-id="${field.id}">${field.field_name}</option>`;
                        });

                        $('.extra_field').append(option);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching extra fields:", error);
                }
            });
        }



        showExtraField();







        let manufacture_date = '{{ $manufacture_date }}';
        let expiry_date = '{{ $expiry_date }}';
        let low_stock_alert = '{{ $product_set_low_stock }}';
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
        //variation controll
        // remove error
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

        $(document).ready(function() {
            // show error
            function showError(name, message) {
                $(name).css('border-color', 'red'); // Highlight input with red border
                $(name).focus(); // Set focus to the input field
                $(`${name}_error`).show().text(message); // Show error message
            }

            // when select category
            subCategory($('.category_id').val());
            $('.category_id').change(function() {
                let id = $(this).val();
                // alert(id);
                if (id) {
                    subCategory(id);
                }
            })

          $('.multiple_category').change(function() {
            let categoryIds = $(this).val(); // Returns an array of selected category IDs
            // console.log(categoryIds);
            if (categoryIds && categoryIds.length > 0) {
                fetchSubCategoriesAndSizes(categoryIds);
            } else {
                // Clear size dropdown if no categories are selected
                $('.multiple_size_id').html('<option selected disabled>Select a Size</option>');

            }
        });
function fetchSubCategoriesAndSizes(categoryIds) {

    $.ajax({
        url: '/multiple/category/find', // Update URL to handle multiple category IDs
        type: 'GET', // Use POST to send array of category IDs
        data: {
            category_ids: categoryIds, // Send array of category IDs
        },
        dataType: 'JSON',
        success: function(res) {
            // console.log(res);
            if (res.status == 200) {
        // Handle Sizes
        $('.multiple_size_id').empty();
        if (res.size && res.size.length > 0) {
            $('.multiple_size_id').html('<option selected disabled>Select a Size</option>');
            $.each(res.size, function(key, item) {
                $('.multiple_size_id').append(
                    `<option value="${item.id}">${item.size}</option>`
                );
            });
        } else {
            $('.multiple_size_id').html(
                '<option selected disabled>Please add Size</option>'
            );
        }
    }
        },
        error: function(xhr) {
            console.error('Error fetching data:', xhr.responseText);
        }
    });
}
            function subCategory(categoryId) {

                $.ajax({
                    url: '/subcategory/find/' + categoryId,
                    type: 'GET',

                    dataType: 'JSON',
                    success: function(res) {
                        if (res.status == 200) {
                            $('.subcategory_id').empty();
                            if (res.data.length > 0) {
                                $.each(res.data, function(key, item) {
                                    $('.subcategory_id').append(
                                        `<option value="${item.id}">${item.name}</option>`
                                    );
                                })
                            } else {
                                $('.subcategory_id').html(`
                                        <option selected disabled>Please add Subcategory</option>`)
                            }

                            // show Size
                            if (res.size.length > 0) {
                                // console.log(res.size);
                                $('.size_id').html(
                                    '<option selected disabled>Select a Size</option>'
                                );
                                $('.multiple_size_id').html(
                                    '<option selected disabled>Select a Size</option>'
                                );
                                $.each(res.size, function(key, item) {

                                    $('.size_id').append(
                                        `<option value="${item.id}">${item.size}</option>`
                                    );
                                    $('.multiple_size_id').append(
                                        `<option value="${item.id}">${item.size}</option>`
                                    );
                                })
                            } else {
                                $('.size_id').html(`
                                        <option selected disabled>Please add Size</option>`)
                            }

                        }
                    }
                });
            }

            // product save
            $('.save_product').click(function(e) {
                e.preventDefault();
                // alert('ok')
                showSpinner();
                let formData = new FormData($('.productForm')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/product/store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {

                        if (res.status == 200) {

                            // console.log(res);
                            latestProduct();
                            // latestSize();
                            hideSpinner()
                            $('.productForm')[0].reset();
                            toastr.success(res.message);
                            // window.location.href = "{{ route('product.all.view') }}";
                        } else {
                            // console.log(res.error);
                            hideSpinner()
                            const error = res.error;

                            // console.log(error)
                            if (error.name) {
                                showError('.name', error.name);
                            }
                            if (error.category_id) {
                                showError('.category_id', error.category_id);
                            }

                            if (error.cost_price) {
                                showError('.cost_price', error.cost_price);
                            }
                            if (error.unit) {
                                showError('.unit', error.unit);
                            }
                            if (error.size) {
                                showError('.size', error.size);
                            }
                            if (error.size) {
                                showError('.multiple_size', error.size);
                            }
                            if (error.b2b_price) {
                                showError('.b2b_price', error.b2b_price);
                            }
                            if (error.b2c_price) {
                                showError('.b2c_price', error.b2c_price);
                            }
                        }

                    }
                });
            })



            //////////////////////////////////////////////// color related ajax code  ////////////////////////////////////////////////
            $('.save_color').on('click', function(e) {
                e.preventDefault();
                let formData = new FormData($('.colorForm')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/color/add',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        // console.log(res);
                        if (res.status == 200) {
                            $('#colorModal').modal('hide');
                            $('.colorForm')[0].reset();
                            toastr.success(res.message);
                            showColor();
                        } else {
                            // toastr.warning(res.message);
                            if (res.errors) {
                                showError('.color_name', res.errors.name);
                            }
                        }
                    },
                    error: function(error) {
                        toastr.warning(error.message);
                    }
                })
            })

        });


        /////////////////////////////////////////////////Variation Create Code ///////////////////////////////////
        function latestProduct() {
            $.ajax({
                url: '/latest-product', // Your API endpoint
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.product) { // Check if a product is returned
                        let nameData = data.product.name;
                        let productId = data.product.id;
                        $('#latestProductName').text(nameData);
                        $('input[name="productId"]').val(productId);
                        $('.latest-product-container').removeClass('d-none'); // Show the containe
                    } else {
                        $('#latestProductName').text('No products available.'); // Handle empty data
                        $('.latest-product-container').addClass('d-none'); // Hide the container
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching latest product:', error);
                    $('#latestProductName').text('Error fetching product.');
                }
            });
        }
        latestProduct()

        function latestSize() {
            $.ajax({
                url: '/latest-product-size', // Your API endpoint
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

        document.getElementById('addVariationRowBtn').addEventListener('click', function() {
            let table = document.getElementById('variationTable');
            let tableHead = table.querySelectorAll('.dynamic-head');
            let tableBody = table.querySelector('tbody');
            let rowCount = tableBody.querySelectorAll('tr').length;
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
                        <label for="low_stock_input_${rowCount}">Set Low Stock QTY</label>
                        <input type="number" class="form-control" id="low_stock_input_${rowCount}" name="low_stock_alert[]">

                    </td>
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
            latestSize();
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

        ///End of Variation

        // show Color Function
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
                let priceVari = row.querySelector('input[name="cost_price[]"]').value.trim();
                let variation_name = row.querySelector('input[name="variation_name[]"]').value.trim();
                let b2b = row.querySelector('input[name="b2b_price[]"]').value.trim();
                const input = row.querySelector('input[name="low_stock_alert[]"]');
                let low_stock_alert = input ? input.value.trim() : '';
                let b2c = row.querySelector('input[name="b2c_price[]"]').value.trim();
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
        /////////////////////////////////////////////////Variation Create Code ///////////////////////////////////


        /////////////////////////////////////////////////Add Extra Field Create ////////////////////////////////////

        $(document).on('click', '.addFieldForm', function() {

            let fieldName = $('#field_name').val().trim();
            let dataType = $('#data_type').val();
            $('.error-message').remove();
            if (fieldName === '') {
                $('#field_name').after('<small class="text-danger error-message">Field Name is required.</small>');
                return;
            }

            if (dataType === '') {
                $('#data_type').after(
                    '<small class="text-danger error-message">Please select a Data Type.</small>');
                return;
            }
            let fieldForm = new FormData($('#addFieldForm')[0]);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ url('/store/extra/datatype/field') }}",
                type: "POST",
                data: fieldForm,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status == 200) {
                        $('#addFieldForm')[0].reset();
                        $('#addFieldModal').modal('hide');
                        showExtraField();
                        toastr.success("Extra Field Added Successfully");
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $('.error-message').remove(); // Remove previous errors

                        $.each(errors, function(key, value) {
                            let inputField = $('[name="' + key + '"]');
                            inputField.after('<div class="text-danger error-message">' + value[
                                0] + '</div>');
                        });
                    }
                }
            });

        });



        ///////////////////////////////////////////////////extra information field show code ///////////////////////////////////////////////
        //   $(document).on('click', '.extra_info', function() {
        //   $.ajax({
        //     url: "{{ url('/get/extra/info/field') }}",
        //     type: "GET",
        //     success: function(response) {
        //         if (response.status == 200) {
        //             let extraData = response.extraField;
        //             let container = $('#extra_info_field');
        //             container.empty();

        //             extraData.forEach(function(data) {
        //                 let fieldId = `extra_field_${data.id}`;

        //                 let dataType = data.data_type.toLowerCase().replace(/\s+/g, '');

        //                 let inputField = '';

        //                 if (dataType === "date") {
        //                     inputField = `<input type="date" class="form-control" name="extra_field_values[${data.id}]">`;
        //                 }
        //                 else if (dataType === "file") {
        //                     inputField = `<input type="file" class="form-control" name="extra_field_values[${data.id}]">`;
        //                 }
        //                 else if (dataType === "text" || dataType === "longtext") {
        //                     inputField = `<textarea class="form-control" name="extra_field_values[${data.id}]" placeholder="Enter ${data.field_name}"></textarea>`;
        //                 }
        //                 else {
        //                     inputField = `<input type="text" class="form-control" name="extra_field_values[${data.id}]" placeholder="Enter ${data.field_name}">`;
        //                 }

        //                 let fieldHtml = `
    //                     <div class="form-check mb-2">
    //                         <input type="checkbox" class="form-check-input extra-field-checkbox" id="${fieldId}" data-id="${data.id}">
    //                         <label class="form-check-label" for="${fieldId}">${data.field_name}</label>
    //                     </div>
    //                     <div class="mb-3 d-none" id="input_${data.id}">
    //                         ${inputField}
    //                     </div>
    //                 `;

        //                 container.append(fieldHtml);
        //             });

        //             // Show or hide input field based on checkbox selection
        //             $(".extra-field-checkbox").on("change", function() {
        //                 let inputId = "#input_" + $(this).data("id");
        //                 if ($(this).is(":checked")) {
        //                     $(inputId).removeClass("d-none");
        //                 } else {
        //                     $(inputId).addClass("d-none");
        //                 }
        //             });

        //         } else {
        //             toastr.warning("No Extra Field Added Yet.");
        //         }
        //     }
        //    });
        // });


        $(document).on('change', '#data_type', function() {
            let container = $('.multiInput');
            if ($(this).val() == 'json') {
                container.fadeIn();
                container.html(`
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" name="multi_input[]" placeholder="Enter Multi Input">
                        <button type="button" class="btn btn-success addInput">+</button>
                    </div>
                `);
            } else {
                container.fadeOut().empty();
            }
        });
        $(document).on('click', '.addInput', function() {
            $('.multiInput').append(`
                <div class="input-group mb-2">
                    <input type="text" class="form-control" name="multi_input[]" placeholder="Enter Multi Input">
                    <button type="button" class="btn btn-danger removeInput">-</button>
                </div>
            `);
        });
        $(document).on('click', '.removeInput', function() {
            $(this).closest('.input-group').remove();
        });





        ///////////////////////////////////////////////////extra information field show code ///////////////////////////////////////////////
        $(document).on('change', '.extra_field', function() {
            let selectedOption = $(this).find(':selected');

            let id = selectedOption.data('id');

            if (id) {
                $.ajax({
                    url: "{{ url('/get/extra/info/field/') }}" + "/" + id,
                    type: "GET",
                    success: function(response) {
                        if (response.status == 200) {
                            let extraData = response.extraField;
                            let container = $('#extra_info_field');

                            let hiddenInput = $('<input>')
                                .attr('type', 'hidden')
                                .attr('name', `extra_field_id[${id}]`)
                                .val(id);

                            container.append(hiddenInput);

                            if (extraData.data_type === "longtext") {
                                container.append(`
                            <div class="mb-3 col-md-6 extra-field-container">
                                <label for="name" class="form-label">${extraData.field_name}<span class="text-danger"></span></label>

                                <textarea class="form-control name" name="extra_field[${id}]" rows="3"
                                    onkeyup="errorRemove(this);" onchange="errorRemove(this);">{{ old('field_name') }}</textarea>
                                <span class="text-danger name_error"></span>
                                <button type="button" class="btn btn-danger btn-sm remove-field" style="margin-top: 5px;">-</button>

                            </div>
                        `);
                            } else if (extraData.data_type === "decimal" || extraData.data_type ===
                                "int" || extraData.data_type === "double") {
                                container.append(`
                            <div class="mb-3 col-md-6 extra-field-container">
                                <label for="name" class="form-label">${extraData.field_name}<span class="text-danger"></span></label>

                                <input class="form-control" type="number" name="extra_field[${id}]" rows="3"
                                    onkeyup="errorRemove(this);" onchange="errorRemove(this);">
                                <span class="text-danger name_error"></span>
                                <button type="button" class="btn btn-danger btn-sm remove-field" style="margin-top: 5px;">-</button>

                            </div>
                        `)
                            } else if (extraData.data_type === "text") {
                                container.append(`
                        <div class="mb-3 col-md-6 extra-field-container">
                            <label for="name" class="form-label">${extraData.field_name}<span class="text-danger"></span></label>

                            <input class="form-control" type="text" name="extra_field[${id}]" rows="3"
                                onkeyup="errorRemove(this);" onchange="errorRemove(this);">
                            <span class="text-danger name_error"></span>
                            <button type="button" class="btn btn-danger btn-sm remove-field" style="margin-top: 5px;">-</button>

                        </div>
                       `)
                            } else if (extraData.data_type === "date") {
                                container.append(`
                            <div class="mb-3 col-md-6 extra-field-container">
                                <label for="name" class="form-label">${extraData.field_name}<span class="text-danger"></span></label>

                                <input class="form-control" type="date" name="extra_field[${id}]" rows="3"
                                    onkeyup="errorRemove(this);" onchange="errorRemove(this);">
                                <span class="text-danger name_error"></span>
                                <button type="button" class="btn btn-danger btn-sm remove-field" style="margin-top: 5px;">-</button>

                            </div>
                        `)
                            } else if (extraData.data_type === "json") {
                                let options = JSON.parse(extraData
                                    .options); // Convert JSON string to array

                                let checkboxes = options.map(option => `
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="extra_field[${id}][]" value="${option}" id="checkbox_${id}_${option}">
                                <label class="form-check-label" for="checkbox_${id}_${option}">${option}</label>
                            </div>
                        `).join('');

                                container.append(`
                            <div class="mb-3 col-md-6 extra-field-container">
                                <label for="name" class="form-label">${extraData.field_name}<span class="text-danger"></span></label>
                                ${checkboxes}
                                <span class="text-danger name_error"></span>
                                <button type="button" class="btn btn-danger btn-sm remove-field" style="margin-top: 5px;">-</button>
                            </div>
                        `);
                            }
                        }
                    },

                });
            }
        });

        // $(document).on("click", ".remove-field", function() {
        //     $(this).closest(".extra-field-container").remove();
        // });


        $(document).on("click", ".remove-field", function() {
            $(this).closest(".extra-field-container").remove();

            // Reset the extra_field dropdown
            $(".extra_field").val("").trigger("change");
        });



        // add color in this field
    </script>
    <style>
        .form-control {
            width: 100%;
            box-sizing: border-box;
        }

        td {
            padding: 5px;
        }

        tr {
            display: flex;
            flex-wrap: wrap;
        }

        tr td {
            flex: 1 1 30%;

            margin: 5px;
        }


        @media (max-width: 1024px) {
            tr td {
                flex: 1 1 45%;

            }
        }


        @media (max-width: 767px) {
            tr td {
                flex: 1 1 100%;

            }


        }
    </style>
@endsection
