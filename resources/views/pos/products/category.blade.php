@extends('master')
@section('title', '| Product Category')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Category</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title">Category Table</h6>
                        @if (Auth::user()->can('category.add'))
                        <button class="btn btn-rounded-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#exampleModalLongScollable"><i data-feather="plus"></i></button>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table id="example" class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Category Name</th>
                                    <th>Product Count</th>
                                    <th>Image</th>
                                    <th>Status</th>
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
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="categoryForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name</label>
                            <input id="defaultconfig" class="form-control category_name" maxlength="250" name="name"
                                type="text" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger category_name_error"></span>
                        </div>
                        <div class="mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Category Image</h6>
                                    <p class="mb-3 text-warning">Note: <span class="fst-italic">Image not
                                            required. If you
                                            add
                                            a category image
                                            please add a 400 X 400 size image.</span></p>
                                    <input type="file" class="categoryImage" name="image" id="myDropify" />
                                </div>
                            </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_category">Save</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="categoryFormEdit" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name</label>
                            <input id="defaultconfig" class="form-control edit_category_name" maxlength="250" name="name"
                                type="text" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger edit_category_name_error"></span>
                        </div>
                        <div class="mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Category Image</h6>
                                    <div style="height:150px;position:relative">
                                        <button class="btn btn-info edit_upload_img"
                                            style="position: absolute;top:50%;left:50%;transform:translate(-50%,-50%)">Browse</button>
                                        <img class="img-fluid showEditImage" src=""
                                            style="height:100%; object-fit:cover">
                                    </div>
                                    <input hidden type="file" class="categoryImage edit_image" name="image" />
                                </div>
                            </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary update_category">Update</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function errorRemove(element) {
            if (element.value != '') {
                $(element).siblings('span').hide();
                $(element).css('border-color', 'green');
            }
        }

        $(document).ready(function() {

            let protocol = window.location.protocol + "//";
            let host = window.location.host;
            let url = protocol + host;
            // image onload when category edit
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

            // show error
            function showError(name, message) {
                $(name).css('border-color', 'red'); // Highlight input with red border
                $(name).focus(); // Set focus to the input field
                $(`${name}_error`).show().text(message); // Show error message
            }

            // save category
            const saveCategory = document.querySelector('.save_category');
            saveCategory.addEventListener('click', function(e) {
                e.preventDefault();
                let formData = new FormData($('.categoryForm')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/category/store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            $('#exampleModalLongScollable').modal('hide');
                            // formData.delete(entry[0]);
                            // alert('added successfully');
                            $('.categoryForm')[0].reset();
                            categoryView();
                            toastr.success(res.message);
                        } else {
                            showError('.category_name', res.error.name);
                        }
                    }
                });
            })


            // show category
            function categoryView() {
                $.ajax({
                    url: '/category/view',
                    method: 'GET',
                    success: function(res) {
                        // console.log(res.data);
                        const categories = res.data;
                        $('.showData').empty();
                         // Destroy the existing DataTable instance if it exists
                         if ($.fn.DataTable.isDataTable('#example')) {
                                $('#example').DataTable().clear().destroy();
                            }
                        if (categories.length > 0) {
                            $.each(categories, function(index, category) {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                            <td>
                                ${index+1}
                            </td>
                            <td>
                                ${category.name ?? ""}
                            </td>
                               <td>${category.product_count ?? 0}</td>
                            <td>
                                <img src="${category.image ? `${url}/uploads/category/` + category.image : `${url}/dummy/image.jpg`}" alt="cat Image">
                            </td>
                            <td>
                                <button id="categoryButton_${category.id}" class="btn ${category.status != 0 ? 'btn-success' : 'btn-danger' } categoryButton"
                                data-id="${category.id}">${category.status != 0 ? 'Active' : 'Inactive'}</button>
                            </td>
                            <td>
                                @can('category.edit')
                                <a href="#" class="btn btn-primary btn-icon category_edit" data-id=${category.id} data-bs-toggle="modal" data-bs-target="#edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                @endcan
                                @can('category.delete')
                                <a href="#" class="btn btn-danger btn-icon category_delete" data-id=${category.id}>
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                             @endcan
                            </td>
                            `;
                                $('.showData').append(tr);
                            })
                        } else {
                            $('.showData').html(`
                            <tr>
                                <td colspan='8'>
                                    <div class="text-center text-warning mb-2">Data Not Found</div>
                                    <div class="text-center">
                                    @can('category.add')
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalLongScollable">Add
                                            Category<i data-feather="plus"></i></button>
                                    @endcan
                                    </div>
                                </td>
                            </tr>`)
                        }
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

            // edit category
            $(document).on('click', '.category_edit', function(e) {
                e.preventDefault();
                // alert('ok');
                let id = this.getAttribute('data-id');
                // alert(id);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: `/category/edit/${id}`,
                    type: 'GET',
                    success: function(data) {
                        // console.log(data.category.name);
                        $('.edit_category_name').val(data.category.name);
                        $('.update_category').val(data.category.id);
                        if (data.category.image) {
                            $('.showEditImage').attr('src',
                                `${url}/uploads/category/` + data.category
                                .image);
                        } else {
                            $('.showEditImage').attr('src',
                                `${url}/dummy/image.jpg`);
                        }
                    }
                });
            })

            // update category
            $('.update_category').click(function(e) {
                e.preventDefault();
                // alert('ok');
                let id = $('.update_category').val();
                // console.log(id);
                let formData = new FormData($('.categoryFormEdit')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: `/category/update/${id}`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            $('#edit').modal('hide');
                            $('.categoryFormEdit')[0].reset();
                            categoryView();
                            toastr.success(res.message);
                        } else {
                            showError('.edit_category_name', res.error.name)
                            // $('.edit_category_name').css('border-color', 'red');
                            // $('.edit_category_name').focus();
                            // $('.edit_category_name_error').show();
                            // $('.edit_category_name_error').text(res.error.name);
                        }
                    }
                });
            })


            // category Delete
            $(document).on('click', '.category_delete', function(e) {
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
                            url: `/category/destroy/${id}`,
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
                $('.showData').on('click', '.categoryButton', function() {
                    var categoryId = $(this).data('id');
                    // alert(categoryId);
                    $.ajax({
                        url: '/category/status/' + categoryId,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                // var button = $('#categoryButton_' + categoryId);
                                if (response.status == 200) {
                                    var button = $('#categoryButton_' + categoryId);
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
