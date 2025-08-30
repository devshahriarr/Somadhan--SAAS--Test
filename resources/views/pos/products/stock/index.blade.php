@extends('master')
@section('title', '| Stock Managment')
@section('admin')

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Stock Managment</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title">Stock Managment</h6>

                        <button class="btn btn-rounded-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#exampleModalLongScollable"><i data-feather="plus"></i></button>
                    </div>
                    <div id="" class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Product Name</th>
                                    <th>Stock Quantity</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="showData">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModalLongScollable" tabindex="-1" aria-labelledby="exampleModalScrollableTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Add Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="subcategoryForm">
                        <div class="mb-3 ">
                            <label for="ageSelect" class="form-label">Select Product</label>
                            <select class="form-select product_id" name="product_id">
                                <option selected disabled>Select Product </option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name ?? '' }}</option>
                                @endforeach

                            </select>
                            <span class="text-danger product_id_error"></span>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Stock Quantity</label>
                            <input class="form-control stock_quantity" name="stock_quantity" type="number">
                            <span class="text-danger stock_quantity_error"></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_subcategory">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!--Edit Modal -->
    <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Edit Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="subcategoryFormEdit" enctype="multipart/form-data">
                        <div class="mb-3 ">
                            <label for="ageSelect" class="form-label">Select Product</label>
                            <select class="form-select product_id_edit" name="product_id">
                                <option selected disabled>Select Product </option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name ?? '' }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger product_id_error"></span>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Stock Quantity</label>
                            <input class="form-control stock_quantity_edit" name="stock_quantity" type="number">
                            <span class="text-danger stock_quantity_error_edit"></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary update_subcategory">Update</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        function errorRemove(element) {
            alert('ok')
            $(element).siblings('span').hide();
            $(element).css('border-color', 'green');
        }


        $(document).ready(function() {

            // show error
            function showError(name, message) {
                $(name).css('border-color', 'red'); // Highlight input with red border
                $(name).focus(); // Set focus to the input field
                $(`${name}_error`).show().text(message); // Show error message
            }

            // save category
            const saveSubCategory = document.querySelector('.save_subcategory');
            // console.log(saveSubCategory);
            saveSubCategory.addEventListener('click', function(e) {
                e.preventDefault();
                // alert('hello world!');
                let formData = new FormData($('.subcategoryForm')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/stock/store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            $('#exampleModalLongScollable').modal('hide');
                            // formData.delete(entry[0]);
                            // alert('added successfully');
                            $('.subcategoryForm')[0].reset();
                            categoryView();
                            toastr.success(res.message);
                        } else {
                            if (res.error.product_id) {
                                showError('.product_id', res.error.product_id);
                            }
                            if (res.error.stock_quantity) {
                                showError('.stock_quantity', res.error.stock_quantity);
                            }
                        }
                    }
                });
            })


            // show category
            function categoryView() {
                $.ajax({
                    url: '/stock/view',
                    method: 'GET',
                    success: function(res) {
                        // console.log(res.data);
                        const subcategories = res.data;
                        $('.showData').empty();
                        $.each(subcategories, function(index, subcategory) {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td>
                                    ${index+1}
                                </td>
                                <td>
                                    <a href="/product/ledger/${subcategory?.product?.id}" >
                                                ${subcategory?.product?.name ?? ""}
                                            </a>

                                </td>
                                <td>
                                    ${subcategory.stock_quantity ?? ""}
                                </td>
                                <td>
                                    <a href="#" class="btn btn-danger btn-icon subcategory_delete" data-id=${subcategory.id}>
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </td>
                            `;
                            $('.showData').append(tr);
                        })

                    }
                })
            }
            categoryView();




            // category Delete
            $(document).on('click', '.subcategory_delete', function(e) {
                e.preventDefault();
                // alert("ok")
                let id = this.getAttribute('data-id');

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to Delete this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: `/stock/destroy/${id}`,
                            type: 'GET',
                            success: function(res) {
                                if (res.status == 200) {
                                    Swal.fire({
                                        title: "Deleted!",
                                        text: "Your file has been deleted.",
                                        icon: "success"
                                    });
                                    categoryView();
                                } else {
                                    Swal.fire({
                                        position: "top-end",
                                        icon: "warning",
                                        title: "File Delete Unsuccessful",
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }

                            }
                        });
                    }
                });
            })
        });
    </script>


@endsection
