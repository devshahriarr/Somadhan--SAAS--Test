@extends('master')
@section('title', '| Product Info Report')
@section('admin')
    <div class="row">
        <table class="table table-bordered table-striped text-center  mb-4">
            <thead class="text-dark">
                <tr>
                    <th>Total Product</th>
                    <th>Total Variation</th>

                </tr>
            </thead>
            <tbody>
                <tr class="fw-bold">
                    <td>{{$productInfo->count() ?? 0}}</td>
                    <td>{{$variationInfo->count() ?? 0}}</td>

                </tr>
            </tbody>
        </table>
        <div class="col-md-12   grid-margin stretch-card filter_box">

            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            {{-- /// --}}
                            <input type="text" class="form-control filter-start-price" placeholder="Price">
                        </div>
                        @php
                            $category = App\Models\Category::all();
                            $subCategory = App\Models\SubCategory::all();
                            $brand = App\Models\Brand::all();
                        @endphp

                        <div class="col-md-3">
                            <div class="input-group flatpickr" id="flatpickr-date">
                                <select class="js-example-basic-single form-select filter-brand-name" data-width="100%"
                                    name="">
                                    @if ($brand->count() > 0)
                                        <option selected disabled>Select Brand</option>
                                        @foreach ($brand as $brands)
                                            <option value="{{ $brands->id }}">{{ $brands->name }}</option>
                                        @endforeach
                                    @else
                                        <option selected disabled>Please Add Brand</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group flatpickr" id="flatpickr-date">
                                <select class="js-example-basic-single form-select filter-category-name"
                                    name="product_category_id" data-width="100%">
                                    @if ($category->count() > 0)
                                        <option selected disabled>Select Category</option>
                                        @foreach ($category as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    @else
                                        <option selected disabled>Please Add Category</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group flatpickr" id="flatpickr-date">
                                <select class="js-example-basic-single form-select filter-subcategory-name"
                                    name="product_subcategory_id" data-width="100%">
                                    @if ($subCategory->count() > 0)
                                        <option selected disabled>Select Sub Category</option>
                                        @foreach ($subCategory as $subcat)
                                            <option value="{{ $subcat->id }}">{{ $subcat->name }}</option>
                                        @endforeach
                                    @else
                                        <option selected disabled>Please Add Category</option>
                                    @endif
                                </select>

                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="justify-content-left">
                                <button class="btn btn-sm bg-info text-dark mr-2" id="product-filter">Filter</button>
                                <button class="btn btn-sm bg-primary text-dark"
                                    onclick="window.location.reload();">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6>Product Info</h6> <br>
                        <div class="table-responsive">
                            <table id="example" class="table" style="padding-top:10px">
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Category</th>
                                        <th>Sub Category</th>
                                        <th>Brand</th>
                                        <th>Stock</th>
                                        <th>Unit</th>
                                    </tr>
                                </thead>
                                <tbody class="product-filter-table">
                                    @include('pos.report.products.product-info-filter-rander-table')
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {

            document.querySelector('#product-filter').addEventListener('click', function(e) {
                e.preventDefault();
                let filterStartPrice = document.querySelector('.filter-start-price').value;
                console.log(filterStartPrice);
                //  let filterEndPrice = document.querySelector('.filter-end-price').value;
                // alert(filterEndPrice);
                let filterBrand = document.querySelector('.filter-brand-name').value;
                let FilterCat = document.querySelector('.filter-category-name').value;
                let filterSubcat = document.querySelector('.filter-subcategory-name').value;
                // alert(filterSubcat);
                $.ajax({
                    url: "{{ route('product.info.filter.view') }}",
                    method: 'GET',
                    data: {
                        filterStartPrice,
                        filterBrand,
                        FilterCat,
                        filterSubcat,
                    },
                    success: function(res) {
                        jQuery('.product-filter-table').html(res);
                    }
                });
            });

        });
    </script>

@endsection
