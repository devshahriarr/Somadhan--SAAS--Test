// error remove
function errorRemove(element) {
    if (element.value != '') {
        $(element).siblings('span').hide();
        $(element).css('border-color', 'green');
    }
}
// Function to recalculate total
function calculateTotal() {
    let total = 0;
    $('.quantity').each(function () {
        let variantId = $(this).attr('variant-id');
        let qty = parseFloat($(this).val()) || 1;
        let costPrice = parseFloat($('.cost_price' + variantId).val());
        $('.variant_subtotal' + variantId).val((qty * costPrice).toFixed(2));
        total += qty * costPrice;
        // console.log(total);
    });
    // console.log(total);
    $('.total').val(total.toFixed(2));
    let extraCost = 0
    $('.carrying_cost').each(function () {
        const cost = parseFloat($(this).val()) || 0;
        extraCost += cost;
    });
    // let carrying_cost = parseFloat($('.carrying_cost').val()) || 0;
    $('.extra_cost_total').val((extraCost).toFixed(2));
    $('.grand_total').val((extraCost + total).toFixed(2));
}

// payFunc
function payFunc() {
    let pay = parseFloat($('.total_payable').val()) || 0;
    let grandTotal = parseFloat($('.grandTotal').val()) || 0;
    let due = (grandTotal - pay).toFixed(2);
    if (due > 0) {
        $('.final_due').val(due);
    } else {
        $('.final_due').val(0);
    }
}
$(document).ready(function () {
    // show error
    function showError(name, message) {
        $(name).css('border-color', 'red');
        $(name).focus();
        $(`${name}_error`).show().text(message);
    }

    const saveSupplier = document.querySelector('.save_supplier');
    saveSupplier.addEventListener('click', function (e) {
        e.preventDefault();
        let formData = new FormData($('.supplierForm')[0]);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/supplier/store',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                if (res.status == 200) {
                    $('#supplierModal').modal('hide');
                    $('.supplierForm')[0].reset();
                    supplierView();
                    toastr.success(res.message);
                } else {
                    if (res.error.name) {
                        showError('.supplier_name', res.error.name);
                    }
                    if (res.error.phone) {
                        showError('.phone', res.error.phone);
                    }
                }
            }
        });
    })
    // show supplier
    function supplierView() {
        // console.log('hello')
        $.ajax({
            url: '/get/supplier/view',
            method: 'GET',
            success: function (res) {
                const suppliers = res.data;
                // console.log(suppliers);
                $('.select-supplier').empty();
                if (suppliers.length > 0) {
                    $('.select-supplier').html(
                        `<option  disabled>Select a Supplier</option>`);
                    $.each(suppliers, function (index, supplier) {
                        $('.select-supplier').append(
                            `<option value="${supplier.id}">${supplier.name}</option>`
                        );
                    })
                    let supplierId = $('.select-supplier').val();
                    fetchSupplierDetails(supplierId);
                } else {
                    $('.select-supplier').html(`
                    <option selected disabled>Please add supplier</option>`)
                }
            }
        })
    }
    supplierView();

    //Supplier Data find
    function fetchSupplierDetails(supplierId) {
        $.ajax({
            url: `/purchase/supplier/${supplierId}`,
            method: 'GET',
            success: function (res) {
                const supplier = res.supplier;
                // console.log(supplier);
                if (supplier.wallet_balance > 0) {
                    $('.previous_due').text(supplier.wallet_balance);
                } else {
                    $('.previous_due').text(0);
                }
            }
        });
    } //

    // select supplier
    $('.select-supplier').on('change', function () {
        const selectedSupplierId = $(this).val();
        if (selectedSupplierId) {
            fetchSupplierDetails(selectedSupplierId);
        }
    });

    // total quantity
    let totalQuantity = 0;

    // Function to update total quantity
    function updateTotalQuantity() {
        totalQuantity = 0;
        $('.quantity').each(function () {
            let quantity = parseFloat($(this).val());
            if (!isNaN(quantity)) {
                totalQuantity += quantity;
            }
        });
        // console.log(totalQuantity);
    }
    // Function to update SL numbers
    function updateSLNumbers() {
        $('.showData > tr').each(function (index) {
            $(this).find('td:first').text(index + 1);
        });
    }

    // select product
    $('.product_select').change(function () {
        let id = $(this).val();

        if ($(`.data_row${id}`).length === 0 && id) {
            $.ajax({
                url: '/product/find/' + id,
                type: 'GET',
                dataType: 'JSON',
                success: function (res) {
                    if (res.status == 200) {
                        const product = res.data;
                        const unit = res.unit;
                        if (product.variations.length > 1) {
                            $('#varientModal').modal('show');
                            showVariants(product.variations);
                            updateSLNumbers();
                        } else {
                            addVariants(product.variations[0]);
                            updateSLNumbers();
                            calculateTotal();
                        }
                        updateSLNumbers();
                        calculateTotal();
                        updateTotalQuantity();
                    } else {
                        toastr.warning(res.message);
                    }
                }
            })
        }
    })

    function addVariants(variant) {
        $('.showData').append(
            `<tr class="data_row${variant.id}">
              <td>${serialNumber++}</td>
            
            <td class="pruchase_bars2">
                <input type="text" class="form-control product_name${variant.id} border-0 "  name="product_name[]" readonly value="${variant?.product?.name ?? ""}" style="width:60px;margin:0;padding:0;font-size:12px" />
            </td>
            <td>
                <input type="text" class="form-control color${variant.id} border-0 "  name="color[]" readonly value="${variant?.color_name.name ?? ""}" style="width:60px;margin:0;padding:0;font-size:12px"/>
            </td>
            <td>
                <input type="text" class="form-control size${variant.id} border-0 "  name="size[]" readonly value="${variant?.variation_size?.size ?? ""}" style="width:60px;margin:0;padding:0;font-size:12px"/>
            </td>
            <td>
                <input type="hidden" class="variant_id" name="variant_id[]" readonly value="${variant.id ?? 0}" />
                <input type="number" class="form-control cost_price${variant.id} ${checkPurchaseEdit == 0 ? 'border-0' : ''}" ${checkPurchaseEdit == 0 ? 'readonly' : ''}  name="cost_price[]" onkeyup="calculateTotal();"  value="${variant.cost_price ?? 0}" style="width:60px;margin:0;padding:3px;font-size:12px"/>
            </td>
            <td class="text-start">
               <div class="d-flex justify-content-center align-items-center ">
                 <input type="number" variant-id="${variant.id}" class="form-control input-small quantity me-3" onkeyup="calculateTotal();" name="quantity[]"  value="1"   style="width:60px;margin:0;padding:3px;font-size:12px"/> <span>${variant?.product?.product_unit?.name ?? "pc"}</span>
                <div class="validation-message text-danger" style="display: none;">Please enter a quantity of at least 1.</div>
                </div>
            </td>
            <td>
                <input type="number" class="form-control variant_subtotal${variant.id} border-0 "  name="total_price[]" readonly value="${variant?.cost_price ?? 0}"  style="width:60px;margin:0;padding:0;font-size:12px"/>
            </td>
            <td>
                <a href="#" class="btn btn-danger btn-icon purchase_delete" data-id=${variant.id}>
                    <i class="fa-solid fa-trash-can"></i>
                </a>
            </td>
        </tr>`
        );

    }

    // show variants
    function showVariants(variants) {
        // console.log(variants);
        $('.show_variants').empty();
        variants.forEach(variant => {
            // console.log(variant);
            const imageUrl = variant?.image ? `/uploads/products/${variant.image}` :
                '/dummy/image.jpg';
            // console.log(imageUrl);
            $('.show_variants').append(`
                <div class="col-md-4 text-center p-2 cursor-pointer addVariant" data-active="false" data-id="${variant.id}">
                    <div class="rounded border pb-2 overflow-hidden">
                        <img src="${imageUrl}" class="img-fluid"
                            style="object-fit: cover; height: 120px; width: 100%;" alt="variant image">
                        <div class="mt-2">
                            <p class="">${variant?.cost_price ?? 0}</p>
                            <p class="">${variant?.variation_size?.size ?? ""}</p>
                            <p class="">${variant?.color_name.name ?? ""}</p>
                        </div>
                    </div>
                </div>
            `);
        });
    }

    // Handle variant selection
    $(document).on('click', '.addVariant', function () {
        // Toggle the 'active' class and data-active attribute for the clicked variant
        $(this).toggleClass('active');
        const isActive = $(this).hasClass('active');
        $(this).attr('data-active', isActive ? 'true' : 'false');
    });

    // Add variant selection
    $(document).on('click', '.add_all_variants', function () {
        // Find all active variants
        const activeVariants = $('.addVariant[data-active="true"]');

        // Check if any variant is selected
        if (activeVariants.length === 0) {
            alert('Please select at least one variant.');
            return;
        }

        // Loop through each active variant and fetch details
        activeVariants.each(function () {
            const variantId = $(this).attr('data-id'); // Get the data-id of the active variant
            fetchVariantDetails(variantId);
            updateSLNumbers(); // Call fetchVariantDetails for each active variant
        });

        // Hide the modal after processing
        $('#varientModal').modal('hide');
    });


    // Fetch variant details via AJAX
    function fetchVariantDetails(variantId, isProduct) {
        $.ajax({
            url: `/variant/find/${variantId}`, // Create this endpoint in your backend
            type: 'GET',
            data: {
                isProduct: isProduct
            },
            dataType: 'JSON',
            success: function (res) {
                const variant = res.variant;
                // console.log(variant);
                // const promotion = res.promotion;
                addVariants(variant);
                updateSLNumbers();
                calculateTotal();
            },
            error: function (error) {
                console.error('Error fetching variant details:', error);
            }
        });
    }



    // purchase Delete
    $(document).on('click', '.purchase_delete', function (e) {
        // alert('ok');
        let id = $(this).attr('data-id');
        let dataRow = $('.data_row' + id);
        dataRow.remove();
        // Recalculate grand total
        calculateTotal();
        updateSLNumbers();
        updateTotalQuantity();
    });

    // payment button click event
    $('.payment_btn').click(function (e) {
        e.preventDefault();
        let supplier = $('.select-supplier').val();
        $('.order_status').val('pre_order');
        if (supplier) {
            $('#paymentModal').modal('show');

            updateTotalQuantity();
            let cumtomer_due = parseFloat($('.previous_due').text()) || 0;
            let subtotal = parseFloat($('.grand_total').val()) || 0;
            $('.subTotal').val(subtotal);
            let grandTotal = cumtomer_due + subtotal;
            $('.grandTotal').val(grandTotal);
            $('.paying_items').text(totalQuantity);
            var isValid = true;
            //Quantity Message
            $('.quantity').each(function () {
                var quantity = $(this).val();
                if (!quantity || quantity < 1) {
                    isValid = false;
                    return false;
                }
            });
            if (!isValid) {
                event.preventDefault();
                // alert('Please enter a quantity of at least 1 for all products.');
                toastr.error('Please enter a quantity of at least 1.)');
                $('#paymentModal').modal('hide');
            }
        } else {
            toastr.warning('Please Select Supplier');
        }
    })

    // paid amount
    $('.paid_btn').click(function (e) {
        e.preventDefault();
        // alert('ok');
        let grandTotal = $('.grandTotal').val();
        $('.total_payable').val(grandTotal);
        payFunc();
    })


    $('#purchaseForm').submit(function (event) {
        event.preventDefault();
        showSpinner();
        $('#paymentModal').modal('hide');
        let formData = new FormData($('#purchaseForm')[0]);
        createInvoice('store', formData);
    });


    $('.save_purchase_invoice').on('click', function (e) {
        e.preventDefault();
        showSpinner();
        let formData = new FormData($('#purchaseForm')[0]);
        createInvoice('draft', formData);
    })

    function createInvoice(url, formData) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: `/purchase/${url}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                if (res.status == 200) {
                    hideSpinner();
                    if (url === "store") {
                        $('#paymentModal').modal('hide');
                        toastr.success(res.message);
                        let id = res.purchaseId;

                        window.location.href = '/purchase/invoice/' + id;
                    } else {
                        toastr.success(res.message);
                        window.location.reload();
                    }
                } else if (res.status == 400) {
                    hideSpinner();
                    toastr.warning(res.message);
                    showError('.payment_method',
                        'please Select Another Payment Method');
                } else {
                    console.log(res);
                    hideSpinner();
                    toastr.warning(res.message);
                    if (res.error.payment_method || res.error.total_payable) {
                        if (res.error.total_payable) {
                            showError('.total_payable', res.error.total_payable);
                        }
                        if (res.error.payment_method) {
                            showError('.payment_method', res.error.payment_method);
                        }
                    } else {
                        $('#paymentModal').modal('hide');
                        if (res.error.supplier_id) {
                            showError('.supplier_id', res.error.supplier_id);
                        }
                        if (res.error.purchase_date) {
                            showError('.purchase_date', res.error.purchase_date);
                        }
                        if (res.error.document) {
                            showError('.document_file', res.error.document);
                        }
                    }
                }
            }
        });
    }


    $('.add_extra_cost').on('click', function (e) {
        e.preventDefault();

        const costViewContainer = $('.extra_cost_view');

        const view = `
        <div class="row align-items-center mb-2">
        <div class="col-md-6" >
       <input type="text" name="purpose[]"
           class="form-control">
            </div>
            <div class="col-md-6" >
                <input type="number" class="form-control carrying_cost"
                    name="amount[]" onkeyup="calculateTotal();"
                    value="0.00" />
            </div>
        </div>
        `;

        costViewContainer.append(view);
    })
});
