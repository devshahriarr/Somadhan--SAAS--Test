@extends('master')
@section('title', '| Unit')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Unit</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title">Unit Table</h6>
                        @if (Auth::user()->can('unit.add'))
                            <button class="btn btn-rounded-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#exampleModalLongScollable"><i data-feather="plus"></i></button>
                        @endif
                    </div>
                    <div id="" class="table-responsive">
                        <table id="example" class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Unit Name</th>
                                    <th>Related To Unit</th>
                                    <th>Operator</th>
                                    <th>Related By Value</th>
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
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Add Unit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="unitForm row">
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Unit Name</label>
                            <input id="defaultconfig" class="form-control unit_name" maxlength="39" name="name"
                                type="text" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger unit_name_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Related To Unit</label>
                            <input id="defaultconfig" class="form-control related_to_unit" maxlength="39"
                                name="related_to_unit" type="text" onkeyup="errorRemove(this);"
                                onblur="errorRemove(this);">
                            <span class="text-danger related_to_unit_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="ageSelect" class="form-label">Operator</label>
                            <select class="form-select related_sign" name="related_sign" onclick="errorRemove(this);"
                                onblur="errorRemove(this);">
                                <option selected disabled>Select Operator Sign</option>
                                <option value="+">(+)addition operator</option>
                                <option value="-">(-)subtraction operator</option>
                                <option value="*">(*)multiplication operator</option>
                                <option value="/">(/)Division operator</option>
                            </select>
                            <span class="text-danger related_sign_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Related By Value</label>
                            <input id="defaultconfig" class="form-control related_by" maxlength="10" name="related_by"
                                type="number" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger related_by_error"></span>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_unit">Save</button>
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
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Edit Unit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="editUnitForm">
                        <div class="mb-3">
                            <label for="name" class="form-label">Unit Name</label>
                            <input id="defaultconfig" class="form-control edit_unit_name" maxlength="39" name="name"
                                type="text" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger edit_unit_name_error"></span>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Related To Unit</label>
                            <input id="defaultconfig" class="form-control edit_related_to_unit" maxlength="39"
                                name="related_to_unit" type="text" onkeyup="errorRemove(this);"
                                onblur="errorRemove(this);">
                            <span class="text-danger edit_related_to_unit_error"></span>
                        </div>
                        <div class="mb-3">
                            <label for="ageSelect" class="form-label">Operator</label>
                            <select class="form-select edit_related_sign" name="related_sign"
                                onclick="errorRemove(this);" onblur="errorRemove(this);">
                                <option selected disabled>Select Operator Sign</option>
                                <option value="+">(+)addition operator</option>
                                <option value="-">(-)subtraction operator</option>
                                <option value="*">(*)multiplication operator</option>
                                <option value="/">(/)Division operator</option>
                            </select>
                            <span class="text-danger edit_related_sign_error"></span>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Related By Value</label>
                            <input id="defaultconfig" class="form-control edit_related_by" maxlength="10"
                                name="related_by" type="number" onkeyup="errorRemove(this);"
                                onblur="errorRemove(this);">
                            <span class="text-danger edit_related_by_error"></span>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary update_unit">Update</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // error remove
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
            // save unit
            const saveUnit = document.querySelector('.save_unit');
            saveUnit.addEventListener('click', function(e) {
                e.preventDefault();
                let formData = new FormData($('.unitForm')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/unit/store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            $('#exampleModalLongScollable').modal('hide');
                            $('.unitForm')[0].reset();
                            unitView();
                            toastr.success(res.message);
                        } else {
                            if (res.error.name) {
                                showError('.unit_name', res.error.name);
                            }
                            if (res.error.related_to_unit) {
                                showError('.related_to_unit', res.error.related_to_unit);
                            }
                            if (res.error.related_sign) {
                                showError('.related_sign', res.error.related_sign);
                            }
                            if (res.error.related_by) {
                                showError('.related_by', res.error.related_by);
                            }
                        }
                    }
                });
            })


            // show Unit
            function unitView() {
                $.ajax({
                    url: '/unit/view',
                    method: 'GET',
                    success: function(res) {
                        const units = res.data;
                        $('.showData').empty();
                        // Destroy the existing DataTable instance if it exists
                        if ($.fn.DataTable.isDataTable('#example')) {
                            $('#example').DataTable().clear().destroy();
                        }
                        if (units.length > 0) {
                            $.each(units, function(index, unit) {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                            <td>
                                ${index+1}
                            </td>
                            <td>
                                ${unit.name ?? ""}
                            </td>
                            <td>
                                ${unit.related_to_unit ?? ""}
                            </td>
                            <td>
                                ${unit.related_sign ?? ""}
                            </td>
                            <td>
                                ${unit.related_by ?? 0 }
                            </td>
                            <td>
                                @can('unit.edit')
                                <a href="#" class="btn btn-primary btn-icon unit_edit" data-id=${unit.id} data-bs-toggle="modal" data-bs-target="#edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                @endcan
                                @can('unit.delete')
                                <a href="#" class="btn btn-danger btn-icon unit_delete" data-id=${unit.id}>
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
                                         @can('unit.add')
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalLongScollable">Add
                                            Unit<i data-feather="plus"></i></button>
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
            unitView();

            // edit Unit
            $(document).on('click', '.unit_edit', function(e) {
                e.preventDefault();
                // console.log('0k');
                let id = this.getAttribute('data-id');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: `/unit/edit/${id}`,
                    type: 'GET',
                    success: function(res) {
                        if (res.status == 200) {
                            $('.edit_unit_name').val(res.unit.name);
                            $('.edit_related_to_unit').val(res.unit.related_to_unit);
                            $('.edit_related_sign').val(res.unit.related_sign);
                            $('.edit_related_by').val(res.unit.related_by);
                            $('.update_unit').val(res.unit.id);
                        } else {
                            toastr.warning("No Data Found");
                        }
                    }
                });
            })

            // update unit
            $('.update_unit').click(function(e) {
                e.preventDefault();
                // alert('ok');
                let id = $(this).val();
                // console.log(id);
                let formData = new FormData($('.editUnitForm')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: `/unit/update/${id}`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            $('#edit').modal('hide');
                            $('.editUnitForm')[0].reset();
                            unitView();
                            toastr.success(res.message);
                        } else {
                            if (res.error.name) {
                                showError('.edit_unit_name', res.error.name);
                            }
                            if (res.error.related_to_unit) {
                                showError('.edit_related_to_unit', res.error.related_to_unit);
                            }
                            if (res.error.related_sign) {
                                showError('.edit_related_sign', res.error.related_sign);
                            }
                            if (res.error.related_by) {
                                showError('.edit_related_by', res.error.related_by);
                            }
                        }
                    }
                });
            })

            // unit Delete
            $(document).on('click', '.unit_delete', function(e) {
                // $('.unit_delete').click(function(e) {
                e.preventDefault();
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
                            url: `/unit/destroy/${id}`,
                            type: 'GET',
                            success: function(data) {
                                if (data.status == 200) {
                                    Swal.fire({
                                        title: "Deleted!",
                                        text: "Your file has been deleted.",
                                        icon: "success"
                                    });
                                    unitView();
                                } else {
                                    Swal.fire({
                                        position: "top-end",
                                        icon: "warning",
                                        title: "Deleted Unsuccessful!",
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
