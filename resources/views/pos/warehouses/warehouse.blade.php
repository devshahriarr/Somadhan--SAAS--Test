@extends('master')
@section('title', '| Warehouse')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Warehouse</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title">Warehouse Table</h6>

                        <button class="btn btn-rounded-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#warehouseModal"><i data-feather="plus"></i></button>

                    </div>
                    <div class="table-responsive">
                        <table id="example" class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Warehouse Name</th>
                                    <th>Location</th>
                                    <th>Contact Person Name</th>
                                    <th>Contact Number</th>
                                    <th class="action">Action</th>
                                </tr>
                            </thead>
                            <tbody class="showWarehouseData">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="warehouseModal" tabindex="-1" aria-labelledby="exampleModalScrollableTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Add Warehouse</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="warehouseForm">
                        <div class="mb-3">
                            <label for="name" class="form-label">Warehouse Name</label>
                            <input id="defaultconfig" class="form-control warehouse_name" maxlength="250" name="warehouse_name"
                                type="text" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger warehouse_name_error"></span>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Contact person Name</label>
                            <input id="defaultconfig" class="form-control " maxlength="250" name="contact_person"
                                type="text" >
                            {{-- <span class="text-danger edit_serial_number_error"></span> --}}
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Contact Number</label>
                            <input id="defaultconfig" class="form-control " maxlength="250" name="contact_number"
                                type="text" >
                            {{-- <span class="text-danger edit_serial_number_error"></span> --}}
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Location</label>
                                <textarea name="location" id="" cols="10" rows="3" class="form-control location" ></textarea>
                            {{-- <span class="text-danger location_error"></span> --}}
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_Warehouse">Save</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Edit Warehouse</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="warehouseEditForm">
                        <div class="mb-3">
                            <label for="name" class="form-label">Warehouse Name</label>
                            <input id="defaultconfig" class="form-control edit_warehouse_name" maxlength="250" name="warehouse_name"
                                type="text" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger edit_warehouse_name_error"></span>
                        </div>


                        <div class="mb-3">
                             <label for="name" class="form-label">Contact person Name</label>
                            <input id="defaultconfig" class="form-control edit_contact_person" maxlength="250" name="contact_person"
                                type="text" >
                            {{-- <span class="text-danger edit_serial_number_error"></span> --}}
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Contact Number</label>
                            <input id="defaultconfig" class="form-control edit_contact_number" maxlength="250" name="contact_number"
                                type="text" >
                            {{-- <span class="text-danger edit_serial_number_error"></span> --}}
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Location</label>
                                <textarea name="location" id="" cols="10" rows="3" class="form-control edit_location" onkeyup="errorRemove(this);" onblur="errorRemove(this);"></textarea>
                            <span class="text-danger edit_location_error"></span>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary update_Warehouse">Save</button>
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

            // show error
            function showError(name, message) {
                $(name).css('border-color', 'red'); // Highlight input with red border
                $(name).focus(); // Set focus to the input field
                $(`${name}_error`).show().text(message); // Show error message
            }

            // save Warehouse
            const saveWarehouse = document.querySelector('.save_Warehouse');
            saveWarehouse.addEventListener('click', function(e) {
                e.preventDefault();
                let formData = new FormData($('.warehouseForm')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/warehouse/store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            $('#warehouseModal').modal('hide');
                            // formData.delete(entry[0]);
                            // alert('added successfully');
                            $('.warehouseForm')[0].reset();
                            warehouseView();
                            toastr.success(res.message);
                        } else {
                            showError('.warehouse_name', res.error.warehouse_name);

                        }
                    }
                });
            })


            // show Warehouse
            function warehouseView() {
                $.ajax({
                    url: '/warehouse/view',
                    method: 'GET',
                    success: function(res) {

                        const warehouses = res.data;
                        $('.showWarehouseData').empty();
                         // Destroy the existing DataTable instance if it exists
                         if ($.fn.DataTable.isDataTable('#example')) {
                                $('#example').DataTable().clear().destroy();
                            }
                        if (warehouses.length > 0) {
                            $.each(warehouses, function(index, warehouse) {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                            <td>
                                ${index+1}
                            </td>
                            <td>
                                ${warehouse.warehouse_name ?? "N/A"}
                            </td>
                            <td>
                                ${warehouse.location ?? "N/A"}
                            </td>
                            <td>
                                ${warehouse.contact_person ?? "N/A"}
                            </td>
                            <td>
                                ${warehouse.contact_number ?? "N/A"}
                            </td>

                            <td>
                                <a href="#" class="btn btn-primary btn-icon warehouse_edit" data-id=${warehouse.id} data-bs-toggle="modal" data-bs-target="#edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-icon warehouse_delete" data-id=${warehouse.id}>
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            </td>
                            `;
                                $('.showWarehouseData').append(tr);
                            })
                        } else {
                            $('.showWarehouseData').html(`
                            <tr>
                                <td colspan='8'>
                                    <div class="text-center text-warning mb-2">Data Not Found</div>
                                    <div class="text-center">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#warehouseModal">Add
                                            Warehouse<i data-feather="plus"></i></button>

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
            warehouseView();

           // edit warehouse
            $(document).on('click', '.warehouse_edit', function(e) {
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
                    url: `/warehouse/edit/${id}`,
                    type: 'GET',
                    success: function(data) {
                        $('.edit_warehouse_name').val(data.warehouse.warehouse_name);
                        $('.edit_location').val(data.warehouse.location);
                        $('.edit_contact_person').val(data.warehouse.contact_person);
                        $('.edit_contact_number').val(data.warehouse.contact_number);
                        $('.update_Warehouse').val(data.warehouse.id);
                    }
                });
            })

            // update warehouse
            $('.update_Warehouse').click(function(e) {
                e.preventDefault();
                // alert('ok');
                let id = $('.update_Warehouse').val();
                console.log(id);
                let formData = new FormData($('.warehouseEditForm')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: `/warehouse/update/${id}`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            $('#edit').modal('hide');
                            $('.warehouseEditForm')[0].reset();
                            warehouseView();
                            toastr.success(res.message);
                        } else {
                            showError('.edit_warehouse_name', res.error.warehouse_name)
                            showError('.edit_location', res.error.location)
                            showError('.edit_serial_number', res.error.serial_number)
                        }
                    }
                });
            })


             // warehouse_delete
            $(document).on('click', '.warehouse_delete', function(e) {
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
                            url: `/warehouse/destroy/${id}`,
                            type: 'GET',
                            success: function(res) {
                                if (res.status == 200) {
                                    Swal.fire({
                                        title: "Deleted!",
                                        text: "Your file has been deleted.",
                                        icon: "success"
                                    });
                                    warehouseView();
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
