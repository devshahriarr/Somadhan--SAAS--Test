@extends('master')
@section('title','| Brand')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Brand</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title">Brand Table</h6>
                        @if (Auth::user()->can('brand.add'))
                        <button class="btn btn-rounded-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#exampleModalLongScollable"><i data-feather="plus"></i></button>
                        @endif
                    </div>
                    <div id="" class="table-responsive">
                        <table id="example" class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Brand Name</th>
                                    <th>Description</th>
                                    <th>Image</th>
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
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Add Brand</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="brandForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Brand Name</label>
                            <input id="defaultconfig" class="form-control brand_name" maxlength="250" name="name"
                                type="text" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger brand_name_error"></span>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label description">Description</label>
                            <textarea class="form-control" id="defaultconfig-4" rows="5" placeholder="" name="description"></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Brand Image</h6>
                                    <p class="mb-3 text-warning">Note: <span class="fst-italic">Image not
                                            required. If you
                                            add
                                            a brand image
                                            please add a 400 X 400 pixel size image.</span></p>
                                    <input type="file" class="brandImage" name="image" id="myDropify" />
                                </div>
                            </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_brand">Save</button>
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
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Edit Brand</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="brandFormEdit" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Brand Name</label>
                            <input id="defaultconfig" class="form-control edit_brand_name" maxlength="250" name="name"
                                type="text" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger edit_brand_name_error"></span>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label ">Description</label>
                            <textarea class="form-control edit_description" id="defaultconfig-4" rows="5" placeholder=""
                                name="description"></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Brand Image</h6>
                                    <div style="height:150px;position:relative">
                                        <button class="btn btn-info edit_upload_img"
                                            style="position: absolute;top:50%;left:50%;transform:translate(-50%,-50%)">Browse</button>
                                        <img class="img-fluid showEditImage" src=""
                                            style="height:100%; object-fit:cover">
                                    </div>
                                    <input hidden type="file" class="brandImage edit_image" name="image" />
                                </div>
                            </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary update_brand">Update</button>
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
            // image onload when brand edit
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
                $(name).css('border-color', 'red');
                $(name).focus();
                $(`${name}_error`).show().text(message);
            }

            // save brand
            const saveBrand = document.querySelector('.save_brand');
            saveBrand.addEventListener('click', function(e) {
                e.preventDefault();
                let formData = new FormData($('.brandForm')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/brand/store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            $('#exampleModalLongScollable').modal('hide');
                            // formData.delete(entry[0]);
                            // alert('added successfully');
                            $('.brandForm')[0].reset();
                            brandView();
                            toastr.success(res.message);
                        } else {
                            showError('.brand_name', res.error.name);
                        }
                    }
                });
            })


            // show brand
            function brandView() {
                $.ajax({
                    url: '/brand/view',
                    method: 'GET',
                    success: function(res) {
                        // console.log(res.data);
                        const brands = res.data;
                        $('.showData').empty();
                         // Destroy the existing DataTable instance if it exists
                         if ($.fn.DataTable.isDataTable('#example')) {
                                $('#example').DataTable().clear().destroy();
                            }
                        if (brands.length > 0) {
                            $.each(brands, function(index, brand) {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                            <td>
                                ${index+1}
                            </td>
                            <td>
                                ${brand.name ?? ""}
                            </td>
                            <td>
                                ${brand.description ? brand.description.slice(0,15)  : ""}
                            </td>
                            <td>
                                <img src="${brand.image ? 'http://127.0.0.1:8000/uploads/brand/' + brand.image : 'http://127.0.0.1:8000/dummy/image.jpg'}" alt="Brand Image">
                            </td>
                            <td>
                                 @can('brand.edit')
                                <a href="#" class="btn btn-primary btn-icon brand_edit" data-id=${brand.id} data-bs-toggle="modal" data-bs-target="#edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                 @endcan
                                 @can('brand.delete')
                                <a href="#" class="btn btn-danger btn-icon brand_delete" data-id=${brand.id}>
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
                                         @can('brand.add')
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalLongScollable">Add
                                            Brand<i data-feather="plus"></i></button>
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
            brandView();

            // edit brand
            $(document).on('click', '.brand_edit', function(e) {
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
                    url: `/brand/edit/${id}`,
                    type: 'GET',
                    success: function(data) {
                        // console.log(data.brand.name);
                        $('.edit_brand_name').val(data.brand.name);

                        $('.update_brand').val(data.brand.id);
                        if (data.brand.description) {
                            $('.edit_description').val(data.brand.description);
                        } else {
                            $('.edit_description').val('');
                        }
                        if (data.brand.image) {
                            $('.showEditImage').attr('src',
                                'http://127.0.0.1:8000/uploads/brand/' + data.brand
                                .image);
                        } else {
                            $('.showEditImage').attr('src',
                                'http://127.0.0.1:8000/dummy/image.jpg');
                        }
                    }
                });
            })

            // update brand
            $('.update_brand').click(function(e) {
                e.preventDefault();
                // alert('ok');
                let id = $('.update_brand').val();
                // console.log(id);
                let formData = new FormData($('.brandFormEdit')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: `/brand/update/${id}`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            $('#edit').modal('hide');
                            $('.brandFormEdit')[0].reset();
                            brandView();
                            toastr.success(res.message);
                        } else {
                            showError('.edit_brand_name', res.error.name);
                        }
                    }
                });
            })


            // brand Delete
            $(document).on('click', '.brand_delete', function(e) {
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
                            url: `/brand/destroy/${id}`,
                            type: 'GET',
                            success: function(res) {
                                if (res.status == 200) {
                                    Swal.fire({
                                        title: "Deleted!",
                                        text: "Your file has been deleted.",
                                        icon: "success"
                                    });
                                    brandView();
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
