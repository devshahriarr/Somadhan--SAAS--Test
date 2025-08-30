@extends('master')
@section('title', '| Product Sub-Category')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Sub Category</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title">Sub Category Table</h6>
                        @if (Auth::user()->can('subcategory.add'))
                        <button class="btn btn-rounded-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#exampleModalLongScollable"><i data-feather="plus"></i></button>
                        @endif
                    </div>
                    <div id="" class="table-responsive">
                        <table id="example" class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Sub Category Name</th>
                                    <th>Category Name</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="showData">
                                {{-- @include('pos.products.category-show-table'); --}}
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
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Add Sub Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="subcategoryForm" enctype="multipart/form-data">
                        <div class="mb-3 ">
                            <label for="ageSelect" class="form-label">Select Category</label>
                            <select class="form-select category_name" name="category_id">
                                <option selected disabled>Select Category </option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                                <span class="text-danger category_name_error"></span>
                            </select>
                            <span class="text-danger related_sign_error"></span>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Sub Category Name</label>
                            <input id="defaultconfig" class="form-control subcategory_name" maxlength="250" name="name"
                                type="text">
                            <span class="text-danger subcategory_name_error"></span>
                        </div>
                        <div class="mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Category Image</h6>
                                    <p class="mb-3 text-warning">Note: <span class="fst-italic">Image not
                                            required. If you
                                            add
                                            a subcategory image
                                            please add a 400 X 400 size image.</span></p>
                                    <input type="file" class="subcategoryImage" name="image" id="myDropify" />
                                </div>
                            </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_subcategory">Save</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!--Edit Modal -->
    <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Edit Sub Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="subcategoryFormEdit" enctype="multipart/form-data">
                        <div class="mb-3 ">
                            <label for="ageSelect" class="form-label">Select Category</label>
                            <select class="form-select category_name_edit" name="category_id">
                                <option selected disabled>Select Category </option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                                <span class="text-danger category_name_error"></span>
                            </select>
                            <span class="text-danger related_sign_error"></span>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Sub Category Name</label>
                            <input id="defaultconfig" class="form-control edit_subcategory_name" maxlength="250"
                                name="name" type="text">
                            <span class="text-danger edit_subcategory_name_error"></span>
                        </div>
                        <div class="mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Sub Category Image</h6>
                                    <div style="height:150px;position:relative">
                                        <button class="btn btn-info edit_upload_img"
                                            style="position: absolute;top:50%;left:50%;transform:translate(-50%,-50%)">Browse</button>
                                        <img class="img-fluid showEditImage" {{-- src="{{ asset('uploads/category/387707397.webp') }}" --}} src=""
                                            style="height:100%; object-fit:cover">
                                    </div>
                                    <input hidden type="file" class="subcategoryImage edit_image" name="image" />
                                </div>
                            </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary update_subcategory">Update</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // image onload when subcategory edit
            const edit_upload_img = document.querySelector('.edit_upload_img');
            const edit_image = document.querySelector('.edit_image');
            edit_upload_img.addEventListener('click', function(e) {
                e.preventDefault();
                edit_image.click();

                edit_image.addEventListener('change', function(e) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        document.querySelector('.showEditImage').src = e.target.result;
                    }
                    reader.readAsDataURL(this.files[0]);
                });
            });


            let protocol = window.location.protocol + "//";
            let host = window.location.host;
            let url = protocol + host;
            // error remove
            // $('.category_name').keyup(function() {
            //     $('.category_name_error').hide();
            //     $('.category_name').css('border-color', 'green');
            // });

            function errorRemove(element) {
                alert('ok')
                $(element).siblings('span').hide();
                $(element).css('border-color', 'green');
            }
            // show error
            function showError(name, message) {
                $(name).css('border-color', 'red'); // Highlight input with red border
                $(name).focus(); // Set focus to the input field
                $(`${name}_error`).show().text(message); // Show error message
            }

            // save category
            const saveSubCategory = document.querySelector('.save_subcategory');
            saveSubCategory.addEventListener('click', function(e) {
                e.preventDefault();
                let formData = new FormData($('.subcategoryForm')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/subcategory/store',
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
                            // console.log(res)
                            $('.category_name').css('border-color', 'red');
                            $('.category_name').focus();
                            $('.subcategory_name').css('border-color', 'red');
                            $('.subcategory_name').focus();
                            $('.subcategory_name_error').show();
                            $('.subcategory_name_error').text(res.error.name);
                            $('.category_name_error').show();
                            $('.category_name_error').text(res.error.name);

                        }
                    }
                });
            })


            // show category
            function categoryView() {
                $.ajax({
                    url: '/subcategory/view',
                    method: 'GET',
                    success: function(res) {
                        // console.log(res.data);
                        const subcategories = res.data;
                        $('.showData').empty();
                         // Destroy the existing DataTable instance if it exists
                         if ($.fn.DataTable.isDataTable('#example')) {
                                $('#example').DataTable().clear().destroy();
                            }
                        $.each(subcategories, function(index, subcategory) {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                            <td>
                                ${index+1}
                            </td>
                            <td>
                                ${subcategory.name ?? ""}
                            </td>
                            <td>
                                ${subcategory.category ? subcategory.category.name : ""}
                            </td>
                            <td>
                                <img src="${subcategory.image ? `${url}/uploads/subcategory/` + subcategory.image : `${url}/dummy/image.jpg`}" alt="cat Image">
                            </td>
                            <td>
                               <button id="subcategoryButton_${subcategory.id}" class="subcategoryButton btn ${subcategory.status != 0 ? 'btn-success' : 'btn-danger' } categoryButton"
                                data-id="${subcategory.id}">${subcategory.status != 0 ? 'Active' : 'Inactive'}</button>


                            </td>
                            <td>
                                @can('subcategory.edit')
                                <a href="#" class="btn btn-primary btn-icon subcategory_edit" data-id=${subcategory.id} data-bs-toggle="modal" data-bs-target="#edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                 @endcan
                                 @can('subcategory.delete')
                                <a href="#" class="btn btn-danger btn-icon subcategory_delete" data-id=${subcategory.id}>
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                                 @endcan
                            </td>
                            `;
                            $('.showData').append(tr);
                        })
                        $('#example').DataTable({
                                columnDefs: [{
                                    "defaultContent": "-",
                                    "targets": "_all"
                                }],
                                dom: 'Bfrtip',
                                buttons: [{
                                        extend: 'excelHtml5',
                                        text: 'Excel',
                                        exportOptions: {
                                            header: true,
                                            columns: ':visible'
                                        },
                                        customize: function(xlsx) {
                                            return '{{ $header ?? '' }}\n {{ $phone ?? '+880.....' }}\n {{ $email ?? '' }}\n{{ $address ?? '' }}\n\n' +
                                                xlsx + '\n\n';
                                        }
                                    },
                                    {
                                        extend: 'pdfHtml5',
                                        text: 'PDF',
                                        exportOptions: {
                                            header: true,
                                            columns: ':visible'
                                        },
                                        customize: function(doc) {
                                            doc.content.unshift({
                                                text: '{{ $header ?? '' }}\n {{ $phone ?? '+880.....' }}\n {{ $email ?? '' }}\n{{ $address ?? '' }}',
                                                fontSize: 14,
                                                alignment: 'center',
                                                margin: [0, 0, 0, 12]
                                            });
                                            doc.content.push({
                                                text: 'Thank you for using our service!',
                                                fontSize: 14,
                                                alignment: 'center',
                                                margin: [0, 12, 0, 0]
                                            });
                                            return doc;
                                        }
                                    },
                                    {
                                        extend: 'print',
                                        text: 'Print',
                                        exportOptions: {
                                            header: true,
                                            columns: ':visible'
                                        },
                                        customize: function(win) {
                                            $(win.document.body).prepend(
                                                '<h4>{{ $header }}</br>{{ $phone ?? '+880....' }}</br>Email:{{ $email }}</br>Address:{{ $address }}</h4>'
                                            );
                                            $(win.document.body).find('h1')
                                                .hide(); // Hide the title element
                                        }
                                    }
                                ]
                            });
                    }
                })
            }
            categoryView();
            //   edit category
            $(document).on('click', '.subcategory_edit', function(e) {
                e.preventDefault();
                // alert('ok');

                let id = this.getAttribute('data-id');
                // alert(id);
                // var selectedCategoryId = data.subcategory.category_id;
                // var categoryId = option.getAttribute('data-category-id');
                // selectedCategoryId ==categoryId ? 'selected'
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: `/subcategory/edit/${id}`,
                    type: 'GET',
                    success: function(data) {
                        // console.log(data.category.name);
                        if (data.subcategory && data.subcategory.category_id) {
                            $('.category_name_edit').val(data.subcategory.category_id);
                        } else {
                            console.log('Category ID not found');
                        }
                        $('.edit_subcategory_name').val(data.subcategory.name);
                        $('.update_subcategory').val(data.subcategory.id);
                        if (data.subcategory.image) {
                            $('.showEditImage').attr('src',
                                `${url}/uploads/subcategory/` + data
                                .subcategory
                                .image);
                        } else {
                            $('.showEditImage').attr('src',
                                `${url}/dummy/image.jpg`);
                        }
                    }
                });
            })
            // update category
            $('.update_subcategory').click(function(e) {
                e.preventDefault();
                // alert('ok');
                let id = $('.update_subcategory').val();
                // console.log(id);
                let formData = new FormData($('.subcategoryFormEdit')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: `/subcategory/update/${id}`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            $('#edit').modal('hide');
                            $('.subcategoryFormEdit')[0].reset();
                            categoryView();
                            toastr.success(res.message);
                        } else {
                            $('.category_name').css('border-color', 'red');
                            $('.category_name').focus();
                            $('.subcategory_name').css('border-color', 'red');
                            $('.subcategory_name').focus();
                            $('.subcategory_name_error').show();
                            $('.subcategory_name_error').text(res.error.name);
                            $('.category_name_error').show();
                            $('.category_name_error').text(res.error.name);
                        }
                    }
                });
            })
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
                            url: `/subcategory/destroy/${id}`,
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


            // category Status
            $(document).ready(function() {
                $('.showData').on('click', '.subcategoryButton', function() {
                    var subcategoryId = $(this).data('id');
                    // alert(subcategoryId);
                    $.ajax({
                        url: '/subcategory/status/' + subcategoryId,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                // var button = $('#categoryButton_' + categoryId);
                                if (response.status == 200) {
                                    var button = $('#subcategoryButton_' +
                                        subcategoryId);
                                    if (response.newStatus == 1) {
                                        button.removeClass('btn-danger').addClass(
                                            'btn-success').text('Active');
                                    } else {
                                        button.removeClass('btn-success').addClass(
                                            'btn-danger').text('Inactive');
                                    }
                                } else {
                                    button.removeClass('btn-success').addClass(
                                        'btn-danger').text(
                                        'Inactive');
                                }
                            }
                        }
                    });
                });
            });


        });
    </script>
@endsection
