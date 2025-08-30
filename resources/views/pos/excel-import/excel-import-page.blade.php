@extends('master')
@section('title', '| Product Excel Import')
@section('admin')
<style>
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>
    <div class="conatiner">

        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10  mt-2">
                <div class="card">
                    <div class="card-header">
                        <h3 style="font-size: 1.25rem; font-weight: bold;">
                            <span style="color: #6571ff;">Products</span> - Excel Data Import
                        </h3>

                        <!-- Right Side -->
                        <div>
                            <a href="{{ url('/products/exports/demo') }}" class="btn btn-sm btn-primary" >
                                Download Products Demo Excel File
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/products/imports/all') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group">
                                <input type="file" class="form-control" name="import_file">
                                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10  mt-2">
                <div class="card">

                        {{-- <h3>Import Category Excel Data into Database </h3> --}}
                        <div class="card-header">
                            <!-- Left Side -->
                            <h3 style="font-size: 1.25rem; font-weight: bold;">
                                <span style="color: #6571ff;">Category</span> - Excel Data Import
                            </h3>

                            <!-- Right Side -->
                            <div>
                                <a href="{{ url('/category/exports/demo') }}" class="btn btn-sm btn-primary" >
                                    Download Category  Demo Excel File
                                </a>
                            </div>

                    </div>
                    <div class="card-body">
                        <form action="{{ url('/category/imports/all') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group">
                                <input type="file" class="form-control" name="category-import_file">
                                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10  mt-2">
                <div class="card">
                    <div class="card-header">
                        {{-- <h3>Import Subcategory Excel Data into Database </h3> --}}
                             <!-- Left Side -->
                             <h3 style="font-size: 1.25rem; font-weight: bold;">
                                <span style="color: #6571ff;">Subcategory</span> - Excel Data Import
                            </h3>

                            <!-- Right Side -->
                            <div>
                                <a href="{{ url('/subcategory/exports/demo') }}" class="btn btn-sm btn-primary" >
                                    Download Subcategory  Demo Excel File
                                </a>
                            </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/subcategory/imports/all') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group">
                                <input type="file" class="form-control" name="subcategory-import_file">
                                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <br><br>
        <hr>
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10  mt-2">
                <div class="card">
                    <div class="card-header">
                        <!-- Left Side -->
                        <h3 style="font-size: 1.25rem; font-weight: bold;">
                            <span style="color: #6571ff;">Brand</span> - Excel Data Import
                        </h3>

                        <!-- Right Side -->
                        <div>
                            <a href="{{ url('/brand/exports/demo') }}" class="btn btn-sm btn-primary" >
                                Download Brand Demo Excel File
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ url('/brand/imports/all') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group">
                                <input type="file" class="form-control" name="brand-import_file">
                                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <br><br>
        <hr>
        {{-- <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10  mt-2">
                <div class="card">
                    <div class="card-header">
                        <!-- Left Side -->
                        <h3 style="font-size: 1.25rem; font-weight: bold;">
                            <span style="color: #6571ff;">Supplier</span> - Excel Data Import
                        </h3>

                        <!-- Right Side -->
                        <div>
                            <a href="{{ url('/supplier/exports/demo') }}" class="btn btn-sm btn-primary" >
                                Download Supplier Demo Excel File
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ url('/supplier/imports/all') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group">
                                <input type="file" class="form-control" name="supplier-import_file">
                                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <br><br> --}}
        <hr>
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10  mt-2">
                <div class="card">
                    <div class="card-header">
                        <!-- Left Side -->
                        <h3 style="font-size: 1.25rem; font-weight: bold;">
                            <span style="color: #6571ff;">Party</span> - Excel Data Import
                        </h3>

                        <!-- Right Side -->
                        <div>
                            <a href="{{ url('/customer/exports/demo') }}" class="btn btn-sm btn-primary" >
                                Download Customer Demo Excel File
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ url('/customer/imports/all') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group">
                                <input type="file" class="form-control" name="customer-import_file">
                                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <br><br>
        <hr>

    </div>
@endsection
