@extends('master')
@section('title', '| Product Bulk Variation Edit')
@section('admin')
<style>
    input[readonly] {
    border: none;
    background-color: transparent;
    box-shadow: none;
    outline: none;
}
</style>
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Bulk Variation Edit</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title">Bulk Variation Edit Table</h6>

                    </div>
                    <table id="bulkTable" class="table">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll">
                                    Select
                                </th>
                                <th>Product Name</th>
                                <th>Product Size</th>
                                <th>Product Color</th>
                                <th>Cost Price</th>
                                <th>B2B Price</th>
                                <th>B2C Price</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody class="showData">
                            <!-- Data will be loaded dynamically -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6"></td>
                                <td colspan="2" class="text-end">
                                    <button class="btn btn-primary updateData">Update</button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </div>





    <script>




$(document).ready(function () {
    let table = $("#bulkTable").DataTable({
        paging: true,
        searching: true,
        ordering: true,
        pageLength: 10, // Set page length
        columnDefs: [{ orderable: false, targets: [0] }] // Disable sorting for the checkbox column
    });
});
    let selectedItems = new Set();
// Load Data
function loadData() {
    $.ajax({
        url: '/bulk/variation/data',
        type: 'GET',
        success: function(response) {
            console.log(response);

            if (response.status == 200) {
                let data = response.product_variations;
                $('.showData').empty(); // Clear previous data

                data.forEach(function(variation, i) {
                    let totalStock = variation.stocks.reduce((acc, stock) => acc + stock.stock_quantity, 0);

                    $('.showData').append(`
                      <tr>
                        <td><input type="checkbox" name="product_variation_id[]" class="selectItem" value="${variation.id}"></td>
                        <td ><input type="hidden" value="${variation.product.id}" class="form-control product_id" data-id="${variation.id}">${variation.product.name}</td>

                     <td>${variation.variation_size ? variation.variation_size.size : ''}</td>
                        <td>${variation.color}</td>

                        <td><input type="number" value="${variation.cost_price}" class="form-control cost_price" data-id="${variation.id}"></td>
                        <td><input type="number" value="${variation.b2b_price}" class="form-control b2b_price" data-id="${variation.id}"></td>
                        <td><input type="number" value="${variation.b2c_price}" class="form-control b2c_price" data-id="${variation.id}"></td>
                        <td><input type="number" value="${totalStock}" class="form-control stock ${totalStock > 0 ? 'readonly-field' : ''}" data-id="${variation.id}" ${totalStock > 0 ? 'readonly' : ''}></td>
                      </tr>
                    `);
                });
                // table.clear().destroy(); // Destroy old DataTable
                    // $("#bulkTable").DataTable({
                    //     paging: true,
                    //     searching: true,
                    //     ordering: true,
                    //     pageLength: 10,
                    //     columnDefs: [{ orderable: false, targets: [0] }]
                    // });

                // Append the Update button inside .showData

            } else {
                toastr.warning('Something went wrong');
            }

        },
    });
}


        $(document).on('click', '.updateData', function () {
            let selectVariation = [];

            $('.selectItem:checked').each(function () {
                let variationId = $(this).val();
                let costPrice = $(`.cost_price[data-id='${variationId}']`).val();
                let b2bPrice = $(`.b2b_price[data-id='${variationId}']`).val();
                let b2cPrice = $(`.b2c_price[data-id='${variationId}']`).val();
                let productId = $(`.product_id[data-id='${variationId}']`).val();
                let stock = $(`.stock[data-id='${variationId}']`).val();

                selectVariation.push({
                    variationId: variationId,
                    costPrice: costPrice,
                    b2bPrice: b2bPrice,
                    b2cPrice: b2cPrice,
                    productId: productId,
                    stock: stock,
                });
            });


            if (selectVariation.length === 0) {
                toastr.warning("Please select at least one variation.");
                return;
                    }
                $.ajax({
                    url: '/bulk/variation/update',
                    type: 'POST',
                    data: {
                        selectVariation: selectVariation,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {

                        toastr.success("Update Successfully");
                        loadData();

                    },

                });

        });


 // Select All Checkbox
 $(document).on("change", "#selectAll", function () {
        $(".selectItem").prop("checked", this.checked);
        $(".selectItem").each(function () {
            let id = $(this).val();
            if (this.checked) {
                selectedItems.add(id);
            } else {
                selectedItems.delete(id);
            }
        });
    });

    // Individual Checkbox Selection
    $(document).on("change", ".selectItem", function () {
        let id = $(this).val();
        if (this.checked) {
            selectedItems.add(id);
        } else {
            selectedItems.delete(id);
        }

        if ($(".selectItem:checked").length === $(".selectItem").length) {
            $("#selectAll").prop("checked", true);
        } else {
            $("#selectAll").prop("checked", false);
        }
    });

    loadData();


    </script>






@endsection

