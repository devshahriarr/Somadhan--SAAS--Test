@extends('master')
@section('title', '| Product Edit')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Update Product</li>
        </ol>
    </nav>
    <form class="productForm" enctype="multipart/form-data">
        <div class="row">
            <div class="col-lg-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="card-title">Update Product</h6>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">Product Name <span
                                        class="text-danger">*</span></label>
                                <input class="form-control name" name="name" type="text" onkeyup="errorRemove(this);"
                                    onblur="errorRemove(this);" value="{{ $product->name ?? '' }}">
                                <span class="text-danger name_error"></span>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">Variation Name(Optional)</label>
                                <input class="form-control " name="variation_name"
                                    value="{{ $product->defaultVariations->variation_name ?? '' }}" type="text">
                            </div>
                            <div class="mb-3 col-md-6">
                                @php
                                    $categories = App\Models\Category::get();
                                @endphp
                                <label for="ageSelect" class="form-label">Category <span
                                        class="text-danger">*</span></label>
                                <select class="form-select js-example-basic-single category_id" id="category_name"
                                    name="category_id" onchange="errorRemove(this);"
                                    value="{{ $product->category->name ?? '' }}">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $category->id == $product->category_id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger category_id_error"></span>
                            </div>
                            <div class="mb-3 col-md-4">
                                @php
                                    $subcategories = App\Models\SubCategory::get();
                                @endphp
                                <label for="ageSelect" class="form-label">Subcategory <span
                                        class="text-danger">*</span></label>
                                <select class="form-select js-example-basic-single subcategory_id" name="subcategory_id"
                                    onchange="errorRemove(this);">
                                    @foreach ($subcategories as $subcategory)
                                        <option value="{{ $subcategory->id }}"
                                            {{ $subcategory->id == $product->subcategory_id ? 'selected' : '' }}>
                                            {{ $subcategory->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-4">
                                @php
                                    $brands = App\Models\Brand::get();
                                @endphp
                                <label for="ageSelect" class="form-label">Brand <span class="text-danger">*</span></label>
                                <select class="form-select js-example-basic-single brand_id" name="brand_id"
                                    onchange="errorRemove(this);">
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            {{ $brand->id == $product->brand_id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger brand_id_error"></span>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="ageSelect" class="form-label">Model No </label>
                                <input type="text" class="form-control"
                                    value="{{ $product->defaultVariationsEdit->model_no }}" name="model_no">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="password" class="form-label">Cost Price</label>
                                <input class="form-control" name="cost_price" type='number' placeholder="00.00"
                                    value="{{ $product->defaultVariationsEdit->cost_price ?? 0 }}" />
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="password" class="form-label">B2C Price <span
                                        class="text-danger">*</span></label>
                                <input class="form-control b2c_price" name="b2c_price" type='number' placeholder="00.00"
                                    value="{{ $product->defaultVariationsEdit->b2c_price ?? 0 }}"
                                    onkeyup="errorRemove(this);" onblur="errorRemove(this);" />
                                <span class="text-danger b2c_price_error"></span>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="password" class="form-label">B2B Price <span
                                        class="text-danger">*</span></label>
                                <input class="form-control b2b__price" name="b2b_price" type='number' placeholder="00.00"
                                    value="{{ $product->defaultVariationsEdit->b2b_price ?? 0 }}"
                                    onkeyup="errorRemove(this);" onblur="errorRemove(this);" />
                                <span class="text-danger b2b_price_error"></span>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Origin</label>
                                <select class="js-example-basic-single form-select" name="origin"
                                    onchange="errorRemove(this);">
                                    <option selected disabled>Select Origin</option>
                                    <option value="cn"
                                        {{ old('origin', $product->defaultVariationsEdit->origin) == 'cn' ? 'selected' : '' }}>
                                        China</option>
                                    <option value="in"
                                        {{ old('origin', $product->defaultVariationsEdit->origin) == 'in' ? 'selected' : '' }}>
                                        India</option>
                                    <option value="us"
                                        {{ old('origin', $product->defaultVariationsEdit->origin) == 'us' ? 'selected' : '' }}>
                                        United States</option>
                                    <option value="id"
                                        {{ old('origin', $product->defaultVariationsEdit->origin) == 'id' ? 'selected' : '' }}>
                                        Indonesia</option>
                                    <option value="pk"
                                        {{ old('origin', $product->defaultVariationsEdit->origin) == 'pk' ? 'selected' : '' }}>
                                        Pakistan</option>
                                    <option value="br"
                                        {{ old('origin', $product->defaultVariationsEdit->origin) == 'br' ? 'selected' : '' }}>
                                        Brazil</option>
                                    <option value="ng"
                                        {{ old('origin', $product->defaultVariationsEdit->origin) == 'ng' ? 'selected' : '' }}>
                                        Nigeria</option>
                                    <option value="bd"
                                        {{ old('origin', $product->defaultVariationsEdit->origin) == 'bd' ? 'selected' : '' }}>
                                        Bangladesh</option>
                                    <option value="ru"
                                        {{ old('origin', $product->defaultVariationsEdit->origin) == 'ru' ? 'selected' : '' }}>
                                        Russia</option>
                                    <option value="mx"
                                        {{ old('origin', $product->defaultVariationsEdit->origin) == 'mx' ? 'selected' : '' }}>
                                        Mexico</option>
                                </select>
                                <span class="text-danger origin_error"></span>
                            </div>

                            <div class="mb-3 col-12">
                                <label for="" class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="5">{{ $product->description }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row ">
                            <div class="col-md-12 d-flex align-items-center">
                                <div class="mb-3 col-md-10">
                                    <label for="ageSelect" class="form-label">Color</label>
                                    {{-- <div id="pickr_1"></div> --}}

                                    <select class="form-control js-example-basic-single show_color" name="color"
                                        data-selected-color-id="{{ $product->defaultVariationsEdit->color }}">
                                        <option selected disabled>Select Color</option>

                                    </select>

                                </div>
                                <div class="col-2 ps-2 pt-1">
                                    <a href="#" class="btn btn-rounded-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#colorModal"><i data-feather="plus"></i></a>
                                </div>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="qualitySelect" class="form-label">Quality</label>
                                <select class="form-control js-example-basic-single" name="quality">
                                    <option selected disabled>Select Quality</option>
                                    <option value="grade-a"
                                        {{ old('quality', $product->defaultVariationsEdit->quality) == 'grade-a' ? 'selected' : '' }}>
                                        Grade A</option>
                                    <option value="grade-b"
                                        {{ old('quality', $product->defaultVariationsEdit->quality) == 'grade-b' ? 'selected' : '' }}>
                                        Grade B</option>
                                    <option value="grade-c"
                                        {{ old('quality', $product->defaultVariationsEdit->quality) == 'grade-c' ? 'selected' : '' }}>
                                        Grade C</option>
                                </select>
                            </div>
                           <div class="mb-3 col-md-6">
                                <label class="form-label">Product Type</label>
                                <select class="form-control" name="product_type">
                                    <option value="via_goods" {{ $product->product_type == 'via_goods' ? 'selected' : '' }}>Via Goods</option>
                                    <option value="own_goods" {{ $product->product_type == 'own_goods' ? 'selected' : '' }}>Own Goods</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-12">
                                @php
                                    $sizes = App\Models\Psize::get();
                                @endphp
                                <label for="ageSelect" class="form-label">Size </label>
                                <select class="form-select size js-example-basic-single" name="size">
                                    @foreach ($sizes as $size)
                                        <option value="{{ $size->id }}"
                                            {{ $size->id == $product->defaultVariationsEdit->size ? 'selected' : '' }}>
                                            {{ $size->size }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger size_error"></span>
                            </div>
                            <div class="mb-3 col-md-12">
                                @php
                                    $units = App\Models\Unit::get();
                                @endphp
                                <label for="ageSelect" class="form-label">Unit <span class="text-danger">*</span></label>
                                <select class="form-select unit js-example-basic-single" name="unit"
                                    onchange="errorRemove(this);">
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ $unit->id == $product->unit ? 'selected' : '' }}>
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger unit_error"></span>
                            </div>
                            <div class="mb-3 col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">Product Image</h6>
                                        <input type="file" class="productImage"
                                            data-default-file="{{ $product->defaultVariationsEdit->image ? asset('uploads/products/' . $product->defaultVariationsEdit->image) : asset('dummy/image.jpg') }}"
                                            name="image" id="myDropify" />
                                    </div>
                                </div>
                            </div>
                            <div>
                                <button class="btn btn-primary w-full update_product" type="submit"
                                    value="{{ $product->id }}">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
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
    {{-- ////////////////Edit Variations ////////////////// --}}

    <!-- Edit Variations Section -->
    <div class="latest-product-container">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form id="variationForm">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="card-title">Product Variations Update</h6>
                                <button type="button" class="btn btn-primary" id="addVariationRowBtn">Add New
                                    Variation</button>
                            </div>
                            <div class="table-responsive">
                                <table id="variationTable" class="table ">
                                    <thead>
                                        <tr>
                                            <th>Action</th>
                                            <th>Variation Name</th>
                                            <th>Current Stock</th>
                                            <th>Cost Price</th>
                                            <th>B2B Price</th>
                                            <th>B2C Price</th>
                                            <th>Size</th>
                                            <th>Color</th>
                                            <th>Model No</th>
                                            <th>Quality</th>
                                            <th>Image</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($product->variations as $variation)
                                            <tr data-variation-id="{{ $variation->id }}">
                                                <td>
                                                    <button type="button"
                                                        class="btn btn-danger btn-sm deleteVariationBtn"
                                                        data-variation-id="{{ $variation->id }}">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="variation_name[]"
                                                        value="{{ $variation->variation_name }}"
                                                        placeholder="Enter Variation Name">
                                                </td>
                                                <td>
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <input type="hidden" name="variation_id[]"
                                                        value="{{ $variation->id }}">
                                                    <input type="number" class="form-control" name="current_stock[]"
                                                        value="{{ $variation->stocks->first()->stock_quantity ?? 0 }}">
                                                </td>
                                                <td><input type="number" class="form-control" name="cost_price[]"
                                                        value="{{ $variation->cost_price }}"></td>
                                                <td><input type="number" class="form-control" name="b2b_price[]"
                                                        value="{{ $variation->b2b_price }}"></td>
                                                <td><input type="number" class="form-control" name="b2c_price[]"
                                                        value="{{ $variation->b2c_price }}"></td>
                                                <td style="width: 150px;">
                                                    <select class="form-control js-example-basic-single" name="size[]">
                                                        @foreach ($sizes as $size)
                                                            <option value="{{ $size->id }}"
                                                                {{ $size->id == $variation->size ? 'selected' : '' }}>
                                                                {{ $size->size }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td style="width: 150px;">
                                                    <select class="form-control show_color js-example-basic-single"
                                                        name="color[]" data-selected-color-id="{{ $variation->color }}">

                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control" name="model_no[]"
                                                        value="{{ $variation->model_no }}"></td>
                                                <td>
                                                    <select class="form-control" name="quality[]">
                                                        <option value="grade-a"
                                                            {{ $variation->quality == 'grade-a' ? 'selected' : '' }}>Grade
                                                            A</option>
                                                        <option value="grade-b"
                                                            {{ $variation->quality == 'grade-b' ? 'selected' : '' }}>Grade
                                                            B</option>
                                                        <option value="grade-c"
                                                            {{ $variation->quality == 'grade-c' ? 'selected' : '' }}>Grade
                                                            C</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="file" class="form-control" name="image[]">
                                                    @if ($variation->image)
                                                        <img src="{{ asset('uploads/products/' . $variation->image) }}"
                                                            width="50" height="50">
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" class="btn btn-success mt-3 variationStoreUpdate">Update
                                Variations</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
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
            const category = document.querySelector('#category_name');
            category.addEventListener('change', function() {
                let category_id = $(this).val();
                // alert(category_id);
                // console.log(category_id);
                if (category_id) {
                    $.ajax({
                        url: '/subcategory/find/' + category_id,
                        type: 'GET',
                        dataType: 'JSON',
                        success: function(res) {
                            if (res.status == 200) {
                                // console.log(res.data)
                                // subcategory data
                                $('select[name="subcategory_id"]').html(
                                    '<option selected disabled>Select a Sub-Category</option>'
                                );
                                $.each(res.data, function(key, item) {
                                    $('select[name="subcategory_id"]').append(
                                        '<option myid="' + item.id +
                                        '" value="' + item.id +
                                        '">' + item
                                        .name + '</option>');
                                })

                                // size selcet
                                $('select[name="size_id"]').html(
                                    '<option selected disabled>Select a Size</option>');
                                $.each(res.size, function(key, item) {
                                    $('select[name="size_id"]').append(
                                        '<option myid="' + item.id +
                                        '" value="' + item.id +
                                        '">' + item
                                        .size + '</option>');
                                })

                            }
                        }
                    });
                }
            });


            // update_product
            $('.update_product').click(function(e) {
                e.preventDefault();
                let id = $(this).val();
                let formData = new FormData($('.productForm')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/product/update/' + id,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            // console.log(res);
                            $('.productForm')[0].reset();
                            toastr.success(res.message);
                            window.location.href = "{{ route('product.view') }}";
                        } else {
                            // console.log(res.error);
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
            // variation_size
            function EditProducttSize() {
                id = {{ $product->id }}
                $.ajax({
                    url: `/edit-product-size/${id}`, // Your API endpoint
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        let sizes = response.sizes;
                        // console.log(sizes);

                        document.querySelectorAll('select[name="size[]"]').forEach(function(
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

                // Create a new row
                let newRow = document.createElement('tr');
                newRow.innerHTML = `
                <td>
                    <button type="button" class="removeVariationRowBtnUpdate form-control text-danger btn-xs btn-danger">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </td>

                <td><input type="text" class="form-control" name="variation_name[]" placeholder="Enter Variation Name"></td>
                <td><input type="number" class="form-control" name="current_stock[]" placeholder="Stock"></td>
                <td><input type="number" class="form-control" name="cost_price[]" placeholder="Price"></td>
                <td><input type="number" class="form-control" name="b2b_price[]" placeholder="b2b Price"></td>
                <td><input type="number" class="form-control" name="b2c_price[]" placeholder=" b2c Price"></td>
                <td>
                <select class="form-control js-example-basic-single" id="variation_size" name="size[]">
                        <option selected disabled value=''>Select Size</option>
                </select>

                </td>
                <td>
                    <select class="form-control js-example-basic-single show_color" name="color[]">

                    </select>
                </td>
                <td><input type="text" class="form-control" name="model_no[]" placeholder="Model No"></td>
                <td>
                    <select class="form-control" name="quality[]">
                        <option selected disabled>Select Quality</option>
                        <option value="grade-a">Grade A</option>
                        <option value="grade-b">Grade B</option>
                        <option value="grade-c">Grade C</option>
                    </select>
                </td>
                <td><input type="file" class="form-control" name="image[]"></td>
            `;
                // Append the new row to the table body
                tableBody.appendChild(newRow);
                $('.js-example-basic-single').select2();
                EditProducttSize()
                showColor();
                tableHead.forEach(function(head) {
                    head.style.display = 'table-cell';
                });

                // Add event listener for the remove button in the new row
                newRow.querySelector('.removeVariationRowBtnUpdate').addEventListener('click', function() {
                    newRow.remove();
                    if (tableBody.querySelectorAll('tr').length === 0) {
                        tableHead.forEach(function(head) {
                            head.style.display = 'none';
                        });
                    }
                });

            });
            const variationStoreUpdate = document.querySelector('.variationStoreUpdate');
            const variationForm = document.getElementById('variationForm');
            variationStoreUpdate.addEventListener('click', function(e) {
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
                    let variation_name = row.querySelector('input[name="variation_name[]"]').value
                        .trim();
                    let b2b = row.querySelector('input[name="b2b_price[]"]').value.trim();
                    let b2c = row.querySelector('input[name="b2c_price[]"]').value.trim();
                    let sizeVari = row.querySelector('select[name="size[]"]').value;

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
                        url: '/update-variation',
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.status === 200) {
                                variationForm.reset();

                                // $('#variationTable tbody').empty();
                                toastr.success(response.message);
                                // Optionally reload the page
                                window.location.reload();
                                // window.location.href = "{{ route('product.all.view') }}";
                            } else {

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
            ////////////////////DeleteFrom Database ariation ///
            $(document).on('click', '.deleteVariationBtn', function() {
                const variationId = $(this).data('variation-id');

                // Display SweetAlert confirmation dialog
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You won’t be able to undo this action!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show a loading spinner
                        Swal.fire({
                            title: 'Deleting...',
                            text: 'Please wait a moment.',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            willOpen: () => Swal.showLoading()
                        });

                        // Proceed with deletion if confirmed
                        $.ajax({
                            url: `/variation/delete/${variationId}`,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.status === 200) {
                                    Swal.fire(
                                        'Deleted!',
                                        response.message,
                                        'success'
                                    );
                                    $(`tr[data-variation-id="${variationId}"]`)
                                .remove();
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        response.message ||
                                        'Unable to delete the variation.',
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    xhr.responseJSON?.message ||
                                    'An error occurred while deleting the variation.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });


        });

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
                        let selectedColorId = $select.data('selected-color-id');
                        // console.log(selectedColorId);
                        $select.empty();

                        if (colors.length > 0) {
                            $select.html(`<option selected disabled>Select Colors</option>`);
                            $.each(colors, function(index, color) {
                                let selected = (color.id == selectedColorId) ? 'selected' : '';
                                $select.append(
                                    `<option value="${color.id}" ${selected}>${color.name}</option>`
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
    </script>
@endsection
