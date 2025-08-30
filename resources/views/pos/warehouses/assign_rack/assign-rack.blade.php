@extends('master')
@section('title', '| Warehouse')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Racks Assign</li>
        </ol>
    </nav>
    {{-- //-----------------------Assign Racks---------------------// --}}
    <div class="card mb-4">
        <form id="signupForm" class="assignRacksForm">
            <div class="card-body row">
                <div class="col-md-6">
                    <div class="mb-3 ">
                        <label for="ageSelect" class="form-label">Select Warehouse <span
                                class="text-danger">*</span></label>
                        <select class="form-select store_warehouse_name" id="warehouseSelect"  onkeyup="errorRemove(this);" 
                             name="warehouse_id">
                            <option selected disabled>Select Warehouse </option>
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->warehouse_name }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger store_warehouse_name_error"></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3 ">
                        <label for="ageSelect" class="form-label">Select Racks </label>
                        <select class="form-select assign_racks_name"
                           id="racksSelect" name="racks_id">
                            <option selected disabled>Select Racks </option>
                        </select>
                        {{-- <span class="text-danger assign_racks_name_error"></span> --}}
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3 ">
                        <label for="ageSelect" class="form-label">Select Product Stock</label>
                        <select class="js-example-basic-single2 form-control form-select stock_product_name"
                            onkeyup="errorRemove(this);" onblur="errorRemove(this);" data-width="100%" data-loaded="false"
                            id="stockProduct" name="stock_id">
                            <option selected disabled>Select Stocks </option>

                        </select>
                        <span class="text-danger stock_product_name_error"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer px-5 pb-4">
                <button type="button" class="btn btn-primary save_assign_racks">Save</button>
            </div>

        </form>
    </div>
    {{-- ---------------------Table --------------------------- --}}

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title">Racks Assign Table</h6>
                    </div>
                    <div class="table-responsive">
                        <table id="example" class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Product Name</th>
                                    <th>Warehouse Name</th>
                                    <th>Rack Name</th>

                                    {{-- <th class="action">Action</th> --}}
                                </tr>
                            </thead>
                            <tbody class="showAssignRackseData">

                            </tbody>
                        </table>
                    </div>
                </div>
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
                            <label for="ageSelect" class="form-label">Select Warehouse <span
                                    class="text-danger">*</span></label>
                            <select class="form-select edit_warehouse_name " name="warehouse_id">
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
                            <input id="defaultconfig" class="form-control edit_max_capacity" maxlength="250"
                                name="max_capacity" type="text">
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

            $('#warehouseSelect').on('change', function() {
                var warehouseId = $(this).val(); // Get the selected warehouse ID

                // Clear the racks dropdown
                $('#racksSelect').empty().append('<option selected disabled>Select Racks</option>');

                if (warehouseId) {
                    // Make an AJAX request to fetch racks
                    $.ajax({
                        url: '/get-warehouse-racks', // Replace with your server endpoint
                        type: 'GET',
                        data: {
                            warehouse_id: warehouseId
                        },
                        success: function(response) {
                            // Populate the racks dropdown with the received data
                            if (response.length > 0) {
                                $.each(response, function(index, rack) {
                                    $('#racksSelect').append('<option value="' + rack
                                        .id + '">' + rack.rack_name + '</option>');
                                });
                            } else {
                                $('#racksSelect').append(
                                    '<option disabled>No racks found</option>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching racks:', error);
                        }
                    });
                }
            });

            $('#stockProduct').on('focus', function() {
                // console.log('Stock Product')
                //   let warehouseId = $('#warehouseSelect').val();
                // Check if the dropdown already has options loaded (optional)
                if ($(this).data('loaded') === "true") {
                    return;
                }
                $.ajax({
                    url: '/stock-already-exists',
                    //   data: { warehouse_id: warehouseId },
                    method: 'GET',
                    success: function(data) {
                        //    console.log(data)

                        var stockDropdown = $('#stockProduct');
                        stockDropdown.empty();
                        stockDropdown.append(
                            '<option selected disabled>Select Stock</option>');

                        $.each(data, function(index, rackStocks) {
                            // console.log(rackStocks.variation?.variationSize.size);
                            stockDropdown.append(
                                '<option value="' + rackStocks.id + '">' +
                                rackStocks.product.name +
                                '| Size: ' + (rackStocks.variation?.variation_size.size ??
                                    'N/A') +
                                '| Color: ' + (rackStocks.variation?.color_name?.name ??
                                'N/A') +
                                ' | Stocks: ' + rackStocks.stock_quantity +
                                '</option>'
                            );
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching Stock Data:', error);
                    }
                });
            });
            //     // save Assign Racks
            const saveAssignRacks = document.querySelector('.save_assign_racks');

            saveAssignRacks.addEventListener('click', function(e) {
                e.preventDefault();
                const stockId = document.getElementById('stockProduct').value;
                // console.log('id',stockId);
                let formData = new FormData($('.assignRacksForm')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: `/assign/racks/store/${stockId}`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            $('.assignRacksForm')[0].reset();
                            racksAssignView();
                            toastr.success(res.message);
                        } else {
                            showError('.store_warehouse_name', res.error.warehouse_id);
                            // showError('.assign_racks_name', res.error.racks_id);
                            showError('.stock_product_name', res.error.stock_id);

                        }
                    }
                });
            })

            // show Racks
            function racksAssignView() {
                $.ajax({
                    url: '/racks/assign/view',
                    method: 'GET',
                    success: function(res) {

                        const racks = res.data;
                        // console.log(racks)
                        $('.showAssignRackseData').empty();
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
                                ${rack.product.name ?? "N/A"} | ${rack.variation?.variation_size?.size ?? "N/A"}|
                                ${rack.variation.color ?? "N/A"}
                            </td>
                            <td>
                                ${rack.warehouse?.warehouse_name ?? "N/A"}
                            </td>
                            <td>
                                ${rack.racks?.rack_name ?? "N/A"}
                            </td>

                            `;
                                $('.showAssignRackseData').append(tr);
                            })
                        } else {
                            $('.showAssignRackseData').html(`
                            <tr>
                                <td colspan='8'>
                                    <div class="text-center text-warning mb-2">Data Not Found</div>

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
            racksAssignView();
            // <td>
            //                     <a href="#" class="btn btn-primary btn-icon racks_edit" data-id=${rack.id} data-bs-toggle="modal" data-bs-target="#edit">
            //                         <i class="fa-solid fa-pen-to-square"></i>
            //                     </a>
            //                     <a href="#" class="btn btn-danger btn-icon racks_delete" data-id=${rack.id}>
            //                         <i class="fa-solid fa-trash-can"></i>
            //                     </a>
            //                 </td>
            //   // edit Racks
            //     $(document).on('click', '.racks_edit', function(e) {
            //         e.preventDefault();
            //         // alert('ok');
            //         let id = this.getAttribute('data-id');
            //         // alert(id);
            //         $.ajaxSetup({
            //             headers: {
            //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //             }
            //         });
            //         $.ajax({
            //             url: `/racks/edit/${id}`,
            //             type: 'GET',
            //             success: function(data) {
            //                 $('.edit_warehouse_name').val(data.racks.warehouse_id );
            //                 $('.edit_rack_name').val(data.racks.rack_name);
            //                 $('.edit_max_capacity').val(data.racks.max_capacity);
            //                 $('.update_racks').val(data.racks.id);
            //             }
            //         });
            //     })

            //     // update Racks
            //     $('.update_racks').click(function(e) {
            //         e.preventDefault();
            //         // alert('ok');
            //         let id = $('.update_racks').val();
            //         // console.log(id);
            //         let formData = new FormData($('.racksEditForm')[0]);
            //         $.ajaxSetup({
            //             headers: {
            //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //             }
            //         });
            //         $.ajax({
            //             url: `/racks/update/${id}`,
            //             type: 'POST',
            //             data: formData,
            //             processData: false,
            //             contentType: false,
            //             success: function(res) {
            //                 if (res.status == 200) {
            //                     $('#edit').modal('hide');
            //                     $('.racksEditForm')[0].reset();
            //                     racksView();
            //                     toastr.success(res.message);
            //                 } else {
            //                     showError('.edit_warehouse_name', res.error.warehouse_id)
            //                     showError('.edit_rack_name', res.error.rack_name)

            //                 }
            //             }
            //         });
            //     })


            //     // racks  delete
            //     $(document).on('click', '.racks_delete', function(e) {
            //         e.preventDefault();
            //         // alert("ok")
            //         let id = this.getAttribute('data-id');

            //         Swal.fire({
            //             title: "Are you sure?",
            //             text: "You won't be able to Delete this!",
            //             icon: "warning",
            //             showCancelButton: true,
            //             confirmButtonColor: "#3085d6",
            //             cancelButtonColor: "#d33",
            //             confirmButtonText: "Yes, delete it!"
            //         }).then((result) => {
            //             if (result.isConfirmed) {
            //                 $.ajaxSetup({
            //                     headers: {
            //                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //                     }
            //                 });
            //                 $.ajax({
            //                     url: `/racks/destroy/${id}`,
            //                     type: 'GET',
            //                     success: function(res) {
            //                         if (res.status == 200) {
            //                             Swal.fire({
            //                                 title: "Deleted!",
            //                                 text: "Your file has been deleted.",
            //                                 icon: "success"
            //                             });
            //                             racksView();
            //                         } else {
            //                             Swal.fire({
            //                                 position: "top-end",
            //                                 icon: "warning",
            //                                 title: "File Delete Unsuccessful",
            //                                 showConfirmButton: false,
            //                                 timer: 1500
            //                             });
            //                         }

            //                     }
            //                 });
            //             }
            //         });
            //     })


        });
    </script>
@endsection
