@extends('master')
@section('title', '| Product List')
@section('admin')

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">View Products</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title">Product Table</h6>
                        <div>

                            <button id="copyButton" class="btn btn-primary">📋 Copy</button>
                            <button id="excelButton" class="btn btn-success">📊 Excel</button>
                            <button id="csvButton" class="btn btn-info">📜 CSV</button>
                            <button id="pdfButton" class="btn btn-danger">📄 PDF</button>
                            <button id="printButton" class="btn btn-warning">🖨️ Print</button>

                            @if (Auth::user()->can('products.add'))
                                <a href="{{ route('product') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-plus"></i> Add Product
                                </a>
                            @endif

                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="products-table" class="display table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>SL No</th>
                                    <th>Name</th>
                                    <th>Image</th>
                                    <th>Product Code</th>
                                    <th>Category</th>
                                    <th>Subcategory</th>
                                    <th>Brand</th>
                                    <th>Color</th>
                                    <th>Cost Price </th>
                                    <th>B2B Price </th>
                                    <th>B2C Price </th>
                                    <th>Size</th>
                                    <th>Unit </th>
                                    <th>Quantity</th>
                                    <th>Product Type</th>
                                    <th>Action</th> <!-- This should be sufficient for your actions -->
                                </tr>
                            </thead>

                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Barcode Modal -->
    <div class="modal fade" id="barcodeModal" tabindex="-1" aria-labelledby="exampleModalScrollableTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" id="exampleModalScrollableTitle">Print Barcode</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Size</th>
                                    <th>Color</th>
                                    <th>Stock</th>
                                    <th>Price</th>
                                    <th>Barcode</th>
                                    <th>Quantity to Print</th>
                                </tr>
                            </thead>
                            <tbody class="show_variant">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary print_barcode" value="">Print Barcode</button>
                </div>
            </div>
        </div>
    </div>

    <iframe id="printFrame" name="printFrame" src="" width="0" height="0"></iframe>

    <style>
        thead,
        tbody,
        tfoot,
        tr,
        td,
        th {
            vertical-align: middle;
        }
    </style>



    <script>
        let priceType = "{{ $sale_price_type }}";
        // print barcode
        $(document).on('click', '.barcode-print-btn', function(e) {
            e.preventDefault();
            let id = $(this).attr('data-id');
            // alert(id);
            $.ajax({
                url: `/product/find/${id}`,
                type: 'GET',
                dataType: 'JSON',
                success: function(res) {
                    // console.log(res);
                    const variantions = res?.data?.variations ?? [];
                    $('.modal-title').html(`${res?.data?.name ?? ""}`)
                    showVariant(variantions);
                    $('#barcodeModal').modal('show');
                },
                error: function(error) {
                    console.error('Error fetching variant details:', error);
                }
            });
        });

        function showVariant(variants) {
            // console.log(variants);

            $('.show_variant').empty();
            // console.log(variant?.stocks);
            // Calculate total stock quantity
            variants.forEach(variant => {
                const totalStockQuantity = variant?.stocks?.reduce((total, stock) => {
                    return total + (stock.stock_quantity || 0); // Ensure stock_quantity is a number
                }, 0) || 0; // Default to 0 if stocks array is empty or undefined

                $('.show_variant').append(`
                    <tr>
                        <td>${variant?.variation_size?.size ?? "N/A"}</td>
                        <td>${variant?.color_name?.name ?? "N/A"}</td>
                        <td>${totalStockQuantity ?? 0}</td>
                        <td>${priceType === "b2c_price" ? variant.b2b_price : variant.b2c_price ?? 0}</td>
                        <td>${variant.barcode ?? "N/A"}</td>
                        <td>
                            <input type="hidden" class="form-control variantId" variantId="${variant.id}"  value="1" min="1">
                            <input type="number" class="form-control barcodeQuantity${variant.id}"  value="1" min="1">
                        </td>
                    </tr>
                  `)
            })
        }


        $(document).on('click', '.print_barcode', function(e) {
            e.preventDefault();

            // Collect all variant IDs and quantities
            let variantData = [];
            $('.show_variant tr').each(function() {
                let variantId = $(this).find('.variantId').attr('variantId');
                let quantity = $(this).find(`.barcodeQuantity${variantId}`).val();
                // console.log(variantId, quantity)
                if (variantId && quantity) {
                    variantData.push({
                        id: variantId,
                        quantity: quantity
                    });
                }
            });

            // Hide the modal
            $('#barcodeModal').modal('hide');

            // console.log(variantData);

            // Pass the variant data to the backend
            handlePrintAndRedirect('/variant/barcode/print-all', variantData);
        });


        function handlePrintAndRedirect(url, data) {
            // Create a form dynamically to submit the data
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.style.display = 'none';
            form.target = 'printFrame'; // Set the form target to the iframe

            // Add CSRF token (if using Laravel)
            let csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = $('meta[name="csrf-token"]').attr('content');
            form.appendChild(csrfToken);

            // Add variant data as hidden inputs
            data.forEach((item, index) => {
                let idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = `variants[${index}][id]`; // Ensure the key matches the backend expectation
                idInput.value = item.id;
                form.appendChild(idInput);

                let quantityInput = document.createElement('input');
                quantityInput.type = 'hidden';
                quantityInput.name =
                    `variants[${index}][quantity]`; // Ensure the key matches the backend expectation
                quantityInput.value = item.quantity;
                form.appendChild(quantityInput);
            });

            // Append the form to the body and submit it
            document.body.appendChild(form);
            form.submit();

            // Wait for the iframe to load
            const printFrame = document.getElementById('printFrame');
            printFrame.onload = function() {
                // Focus the iframe and trigger the print dialog
                printFrame.contentWindow.focus();
                printFrame.contentWindow.print();

                // Reload the page after printing (optional)
                printFrame.contentWindow.onafterprint = function() {
                    window.location.reload();
                };

                // Fallback: Reload the page after 1 second if onafterprint is not supported
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            };
        }


        // print modal Content
        function printModalContent(modalId) {
            var modalBodyContent = document.getElementById(modalId).getElementsByClassName('modal-body')[0].innerHTML;
            var printWindow = window.open('', '_blank');
            printWindow.document.write(
                '<html><head><title>Print</title><link rel="stylesheet" type="text/css" href="print.css" /></head><body>' +
                modalBodyContent + '</body></html>');
            printWindow.document.close();
            printWindow.print();

        }

        $(document).ready(function() {
            $('#copyButton').click(function() {
                $('.buttons-copy').click();
            });
            $('#excelButton').click(function() {
                $('.buttons-excel').click();
            });
            $('#csvButton').click(function() {
                $('.buttons-csv').click();
            });
            $('#pdfButton').click(function() {
                $('.buttons-pdf').click();
            });
            $('#printButton').click(function() {
                $('.buttons-print').click();
            });

            let table = $('#products-table').DataTable({

                processing: true,
                serverSide: true,
                ajax: '{{ route('product.view') }}',
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'copyHtml5',
                        text: '📋 Copy',
                        className: 'btn btn-primary mb-5 d-none',
                        exportOptions: {
                            columns: [0, 1, 3, 4, 5, 6, 7, 8, 9, 10, 11,
                                12
                            ],
                            modifier: {
                                page: 'all'
                            }
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '📊 Excel',
                        className: 'btn btn-success mb-5 d-none',
                        exportOptions: {
                            columns: [0, 1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
                            modifier: {
                                page: 'all'
                            }
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        text: '📜 CSV',
                        className: 'btn btn-info mb-5 d-none',
                        exportOptions: {
                            columns: [0, 1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
                            modifier: {
                                page: 'all'
                            }
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '📄 PDF',
                        className: 'btn btn-danger mb-5 d-none',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: [0, 1, 3, 4, 5, 6, 7, 8, 9],
                            modifier: {
                                page: 'all'
                            }
                        },
                        customize: function(doc) {
                            // Adjust column widths to fit the page
                            doc.content[1].table.widths = Array(doc.content[1].table.body[0].length)
                                .fill('*');
                            // Center align headers and body content
                            doc.styles.tableHeader.alignment = 'center';
                            doc.content[1].table.body.forEach(row => {
                                row.forEach(cell => {
                                    cell.alignment = 'center';
                                });
                            });
                        }
                    },
                    {
                        extend: 'print',
                        text: '🖨️ Print',
                        className: 'btn btn-warning mb-5 d-none',
                        exportOptions: {
                            columns: [0, 1, 3, 4, 5, 6, 7, 8, 9],
                            modifier: {
                                search: 'applied',
                                order: 'applied',
                                page: 'all'
                            }
                        },
                        customize: function(win) {
                            // Adjust print layout
                            $(win.document.body).css('font-size', '10pt');
                            $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit')
                                .css('width', '100%');
                            // Center align images in print view
                            $(win.document.body).find('img').css({
                                'display': 'block',
                                'margin': '0 auto'
                            });
                        }
                    }
                ],
                columns: [{
                        data: null,
                        name: 'SL No',
                        render: function(data, type, row, meta) {
                            // console.log(data);
                            let pageInfo = $('#products-table').DataTable().page.info();
                            return pageInfo.start + meta.row + 1;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'product_name',
                        name: 'product_name'
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'barcode',
                        name: 'barcode'
                    },
                    {
                        data: 'category.name',
                        name: 'category'
                    },
                    {
                        data: 'subcategory_name',
                        name: 'subcategory_name'
                    },
                    {
                        data: 'brand_name',
                        name: 'brand_name'
                    },
                    {
                        data: 'color',
                        name: 'color'
                    },
                    {
                        data: 'cost_price',
                        name: 'cost_price'
                    },
                    {
                        data: 'b2b_price',
                        name: 'b2b_price'
                    },
                    {
                        data: 'b2c_price',
                        name: 'b2c_price'
                    },
                    {
                        data: 'size_name',
                        name: 'size_name'
                    },
                    {
                        data: 'unit_name',
                        name: 'unit_name'

                    },
                    {
                        data: 'quantity',
                        name: 'quantity'

                    },
                    {
                        data: 'product_type',
                        name: 'product_type',
                      render: function(data, type, row) {
                        if (data === 'via_goods') {
                            return '<span class="badge bg-warning">Via Goods</span>';
                        } else if (data === 'own_goods') {
                            return '<span class="badge bg-success">Own Goods</span>';
                        } else {
                            return '<span class="badge bg-secondary">Unknown</span>';
                        }
                    }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
            // console.log('Hello',table);
        });
        $('#products-table').on('click', '.toggle-status-btn', function () {
                var button = $(this);
                var productId = button.data('id');
                var currentStatus = button.data('status');

                $.ajax({
                    url: '{{ route("product.status", ":id") }}'.replace(':id', productId),
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        if (response.success) {
                            var newStatus = response.newStatus;
                            button.text(newStatus);
                            button.data('status', newStatus);
                            button.removeClass('btn-success btn-danger');
                            button.addClass(newStatus === 'active' ? 'btn-success' : 'btn-danger');
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function (xhr) {
                        alert('Failed to update status. Error: ' + (xhr.responseJSON?.message || 'Unknown error'));
                    }
                });
            });
        //Sweet Alert
        function confirmDelete(productId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/product/destroy/' + productId,
                        type: 'GET',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            Swal.fire('Deleted!', 'Your product has been deleted.', 'success');
                            location.reload();

                        },
                        error: function(response) {
                            Swal.fire('Error!', 'There was a problem deleting the product.', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endsection
