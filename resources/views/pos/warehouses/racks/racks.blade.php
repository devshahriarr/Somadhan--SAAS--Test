@extends('master')
@section('title', '| Warehouse')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Warehouse/Racks</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title">Racks Table</h6>

                        <button class="btn btn-rounded-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#racksModal"><i data-feather="plus"></i></button>
                    </div>
                    <div class="table-responsive">
                        <table id="example" class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Warehouse Name</th>
                                    <th>Rack Name</th>
                                    <th>Max Capacity</th>
                                    <th class="action">Action</th>
                                </tr>
                            </thead>
                            <tbody class="showRackseData">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="racksModal" tabindex="-1" aria-labelledby="exampleModalScrollableTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Add Warehouse </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="racksForm">
                        <div class="mb-3 ">
                            <label for="ageSelect" class="form-label">Select Warehouse <span class="text-danger">*</span></label>
                            <select class="form-select warehouse_name" id="warehouseSelect" name="warehouse_id"  >
                                <option selected disabled>Select Warehouse </option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->warehouse_name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger warehouse_name_error"></span>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Racks Name <span class="text-danger">*</span></label>
                            <input id="defaultconfig" class="form-control racks_name" maxlength="250" name="rack_name"
                                type="text" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger racks_name_error"></span>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Max Capacity</label>
                            <input id="defaultconfig" class="form-control " maxlength="250" name="max_capacity"
                                type="text" >
                            {{-- <span class="text-danger edit_serial_number_error"></span> --}}
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_racks">Save</button>
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
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Edit Warehouse Racks</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="racksEditForm">
                        <div class="mb-3 ">
                            <label for="ageSelect" class="form-label">Select Warehouse <span class="text-danger">*</span></label>
                            <select class="form-select edit_warehouse_name " name="warehouse_id"  >
                                <option selected disabled>Select Warehouse </option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->warehouse_name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger edit_warehouse_name_error"></span>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Racks Name <span class="text-danger">*</span></label>
                            <input id="defaultconfig" class="form-control edit_rack_name" maxlength="250" name="rack_name"
                                type="text" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger edit_racks_name_error"></span>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Max Capacity</label>
                            <input id="defaultconfig" class="form-control edit_max_capacity" maxlength="250" name="max_capacity"
                                type="text" >
                            {{-- <span class="text-danger edit_serial_number_error"></span> --}}
                        </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary update_racks">Save</button>
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

            // save Racks
            const saveRacks = document.querySelector('.save_racks');

            saveRacks.addEventListener('click', function(e) {
                e.preventDefault();

                let formData = new FormData($('.racksForm')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/racks/store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            $('#racksModal').modal('hide');
                            // formData.delete(entry[0]);
                            // alert('added successfully');
                            $('.racksForm')[0].reset();
                            racksView();
                            toastr.success(res.message);
                        } else {
                            showError('.warehouse_name', res.error.warehouse_id);
                            showError('.racks_name', res.error.rack_name);

                        }
                    }
                });
            })

            // show Racks
            function racksView() {
                $.ajax({
                    url: '/warehouse/racks/view',
                    method: 'GET',
                    success: function(res) {

                        const racks = res.data;
                        $('.showRackseData').empty();
                         // Destroy the existing DataTable instance if it exists
                         if ($.fn.DataTable.isDataTable('#example')) {
                                $('#example').DataTable().clear().destroy();
                            }
                        if (racks.length > 0) {
                            $.each(racks, function(index, rack) {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                            <td>
                                ${index+1}
                            </td>
                            <td>
                                ${rack.warehouse.warehouse_name ?? "N/A"}
                            </td>
                            <td>
                                ${rack.rack_name ?? "N/A"}
                            </td>
                            <td>
                                ${rack.max_capacity ?? "N/A"}
                            </td>

                            <td>
                                <a href="#" class="btn btn-primary btn-icon racks_edit" data-id=${rack.id} data-bs-toggle="modal" data-bs-target="#edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-icon racks_delete" data-id=${rack.id}>
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            </td>
                            `;
                                $('.showRackseData').append(tr);
                            })
                        } else {
                            $('.showRackseData').html(`
                            <tr>
                                <td colspan='8'>
                                    <div class="text-center text-warning mb-2">Data Not Found</div>
                                    <div class="text-center">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#racksModal">Add
                                           Warehouse Racks<i data-feather="plus"></i></button>

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
            racksView();

          // edit Racks
            $(document).on('click', '.racks_edit', function(e) {
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
                    url: `/racks/edit/${id}`,
                    type: 'GET',
                    success: function(data) {
                        $('.edit_warehouse_name').val(data.racks.warehouse_id );
                        $('.edit_rack_name').val(data.racks.rack_name);
                        $('.edit_max_capacity').val(data.racks.max_capacity);
                        $('.update_racks').val(data.racks.id);
                    }
                });
            })

            // update Racks
            $('.update_racks').click(function(e) {
                e.preventDefault();
                // alert('ok');
                let id = $('.update_racks').val();
                // console.log(id);
                let formData = new FormData($('.racksEditForm')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: `/racks/update/${id}`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            $('#edit').modal('hide');
                            $('.racksEditForm')[0].reset();
                            racksView();
                            toastr.success(res.message);
                        } else {
                            showError('.edit_warehouse_name', res.error.warehouse_id)
                            showError('.edit_rack_name', res.error.rack_name)

                        }
                    }
                });
            })


            // racks  delete
            $(document).on('click', '.racks_delete', function(e) {
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
                            url: `/racks/destroy/${id}`,
                            type: 'GET',
                            success: function(res) {
                                if (res.status == 200) {
                                    Swal.fire({
                                        title: "Deleted!",
                                        text: "Your file has been deleted.",
                                        icon: "success"
                                    });
                                    racksView();
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
