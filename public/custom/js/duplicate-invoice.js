// error remove
function errorRemove(element) {
    tag = element.tagName.toLowerCase();
    if (element.value != '') {
        // console.log('ok');
        if (tag == 'select') {
            $(element).closest('.mb-3').find('.text-danger').hide();
            $(element).css('border-color', 'green');
        } else {
            $(element).siblings('span').hide();
            $(element).css('border-color', 'green');
        }
    }
}

// define warranty period
$(document).on('click', '#warranty_status', function () {
    if ($(this).is(':checked')) {
        $(this).closest('div').next('.Warranty_duration').show();
    } else {
        $(this).closest('div').next('.Warranty_duration').hide();
    }
});


// jquery redy function
$(document).ready(function () {
    // Barcode Focused
    $('.barcode_input').focus();

    // showError Function
    function showError(name, message) {
        $(name).css('border-color', 'red');
        $(name).focus();
        $(`${name}_error`).show().text(message);
    }



    // Via Sell Product view function
    function viewProductSell() {
        $.ajax({
            url: '/product/view/sale',
            method: 'GET',
            success: function (res) {
                // console.log(res);
                const products = res.products;
                $('.view_product').empty();

                if (products.length > 0) {
                    $('.view_product').append(
                        `<option selected disabled>Select Product</option>`
                    );
                    $.each(products, function (index, product) {
                        const variations = product.defaultVariations || product.default_variations || {};

                        // const costPrice = variations.cost_price
                        // ? `  ৳ ${parseFloat(variations.cost_price).toFixed(2)}`
                        // : '  N/A';
                        // const b2b_price = variations.b2b_price
                        // ? `  ৳ ${parseFloat(variations.b2b_price).toFixed(2)}`
                        // : '  N/A';
                        // const b2c_price = variations.b2c_price
                        // ? `  ৳ ${parseFloat(variations.b2c_price).toFixed(2)}`
                        // : '  N/A';
                        // console.log()
                        $('.view_product').append(
                            `<option value="${product.id}">${product.name} (${product.stock_quantity_sum || 0} pc Available) </option>`
                        );
                    })
                } else {
                    $('.view_product').html(`
                 <option selected disable>Please add Product</option>`)
                }
            }

        })
    }
    viewProductSell();

    // add via Products
    $(document).on('click', '.save_via_product', function (e) {
        e.preventDefault();
        let formData = new FormData($('.viaSellForm')[0]);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/via/product/add',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                // console.log(res);
                if (res.status == 200) {
                    $('#viaSellModal').modal('hide');
                    $('.viaSellForm')[0].reset();
                    viewProductSell();
                    let products = res.products;
                    let quantity = products.stock;
                    // console.log(quantity);
                    showAddProduct(products, quantity);
                    // calculateProductTotal();
                    toastr.success(res.message);
                    $(window).on('beforeunload', function () {
                        return 'Are you sure you want to leave? because You Add a Via Sale Product?';
                    });
                } else if (res.status == 400) {
                    $('#viaSellModal').modal('hide');
                    toastr.warning(res.message);
                } else {
                    // console.log(res);
                    if (res.error.name) {
                        showError('.product_name', res.error.name);
                    }
                    if (res.error.cost) {
                        showError('.cost_price', res.error.cost);
                    }
                    if (res.error.price) {
                        showError('.sell_price', res.error.price);
                    }
                    if (res.error.stock) {
                        showError('.via_quantity', res.error.stock);
                    }
                    if (res.error.via_supplier_name) {
                        showError('.via_supplier_name', res.error.via_supplier_name);
                    }
                    if (res.error.transaction_account) {
                        showError('.transaction_account', res.error
                            .transaction_account);
                    }
                }
            }
        });
    })

    // Combined function for product calculation and due calculation
    function handleViaProductAndDueCalculation() {
        // Product Calculation
        let sellPrice = parseFloat($('.sell_price').val());
        let costPrice = parseFloat($('.cost_price').val()) || 1;
        let quantity = parseFloat($('.via_quantity').val()) || 1;

        // Check for negative values
        if (sellPrice < 0 || costPrice < 0 || quantity < 0) {
            toastr.warning("Warning: Negative values are not allowed!");

            // Empty the fields if negative value is found
            if (sellPrice < 0) $('.sell_price').val('');
            if (costPrice < 0) $('.cost_price').val('');
            if (quantity < 0) $('.via_quantity').val('');

            return; // Stop further execution
        }

        let total = sellPrice * quantity;
        let totalCost = costPrice * quantity;
        $('.via_product_total').val(total.toFixed(2));
        $('.via_total_pay').val(totalCost.toFixed(2));

        // Due Calculation
        let viaTotalPay = parseFloat($('.via_total_pay').val());
        let paid = parseFloat($('.via_paid').val()) || 0;

        // Check for negative paid value
        if (paid < 0) {
            toastr.warning("Warning: Paid amount cannot be negative!");

            // Empty the paid field if negative value is found
            $('.via_paid').val('');

            return; // Stop further execution
        }

        let due = viaTotalPay - paid;
        $('.via_due').val(due.toFixed(2));
    }

    // Event listeners for input fields
    $(document).on('keyup', '.sell_price, .via_quantity, .cost_price, .via_paid', function () {
        handleViaProductAndDueCalculation();
    });

    // customer view function
    function viewCustomer() {
        $.ajax({
            url: '/get/customer',
            method: 'GET',
            success: function (res) {
                // console.log(res);
                const customers = res.allData;
                $('.select-customer').empty();
                if (res.status == 404) {
                    $('.select-customer').html(`
                     <option selected disabled>No Customer available. Please add a customer.</option>`);
                }
                $.each(customers, function (index, customer) {
                    $('.select-customer').append(
                        `<option value="${customer.id}">${customer.name ?? ""} (${customer.phone ?? ""})</option>`
                    );
                });

                let customerId = $('.select-customer').val();
                if (customerId) {
                    customerDueShow(customerId);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // console.error('AJAX Error:', textStatus, errorThrown);
                toastr.warning(
                    'An error occurred while getting the customer.'
                );
            }

        });
    }
    viewCustomer();


    document.querySelector('.save_new_customer').addEventListener('click', function (e) {
        e.preventDefault();

        let formData = new FormData(document.querySelector('.customerForm'));

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/add/customer',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                // console.log(res);
                if (res.status === 400) {
                    // Display validation errors
                    if (res.error.name) {
                        showError('.customer_name', res.error.name);
                    }
                    if (res.error.phone) {
                        showError('.phone', res.error.phone);
                    }
                    // Handle other potential validation errors
                    if (res.error.email) {
                        showError('.email', res.error.email);
                    }
                    if (res.error.address) {
                        showError('.address', res.error.address);
                    }
                    if (res.error.opening_payable) {
                        showError('.opening_payable', res.error.opening_payable);
                    }
                    if (res.error.opening_receivable) {
                        showError('.opening_receivable', res.error.opening_receivable);
                    }
                } else if (res.status === 500) {
                    toastr.warning("Internerl Server Error");
                } else {
                    $('#customerModal').modal('hide');
                    document.querySelector('.customerForm').reset();
                    viewCustomer();
                    toastr.success(res.message);

                    if (res.customer.id) {
                        customerDueShow(res.customer.id);
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // console.error('AJAX Error:', textStatus, errorThrown);
                toastr.warning(
                    'An error occurred while adding the customer. Please try again later.'
                );
            }
        });
    });



    // calculate quantity
    let totalQuantity = 0;

    function updateTotalQuantity() {
        totalQuantity = 0;
        $('.quantity').each(function () {
            let quantity = parseFloat($(this).val());
            if (!isNaN(quantity)) {
                totalQuantity += quantity;
            }
        });
    }

    $(document).ready(function () {

        // Configure Toastr options
        toastr.options = {
            positionClass: "toast-top-right",
            timeOut: 3000,
            closeButton: true
        };
        if (typeof sale_without_stock !== "undefined" && sale_without_stock == 1) {
            $(document).on("keyup change", ".quantity", function () {

                const input = this;
                const productId = $(input).attr("product-id");

                const totalStock = parseInt($(`.sumQuantity${productId}`).val()) || 0;
                const enteredQuantity = parseInt(input.value) || 0;

                if (totalStock <= 0  ) {
                    toastr.info("No stock available for this product.");
                    // input.value = 0;

                }
                if (enteredQuantity > totalStock) {
                    toastr.info(`Quantity exceed available stock (${totalStock}).`);

                    $(input).css("border", "2px solid red");
                    setTimeout(() => $(input).css("border", ""), 2000);
                }
            });
        }
        if (typeof sale_without_stock !== "undefined" && sale_without_stock == 0) {

            $(document).on("keyup change", ".quantity", function () {

            const input = this;
            const productId = $(input).attr("product-id");
            const totalStock = parseInt($(`.sumQuantity${productId}`).val()) || 0;
            const enteredQuantity = parseInt(input.value) || 0;

            if (totalStock <= 0  ) {
                toastr.warning("No stock available for this product.");
                // input.value = 0;
                // input.disabled = true;
                return;
            }

            if (enteredQuantity > totalStock) {
                toastr.warning(`Quantity cannot exceed available stock (${totalStock}).`);
                input.value = totalStock;
                $(input).css("border", "2px solid red");
                setTimeout(() => $(input).css("border", ""), 2000);
            }

        });
    }
    });

    // show Product function
    function showAddProduct(product, quantity, promotion,isSelectedProduct = false) {
        toastr.options = {
            positionClass: "toast-top-right",
            timeOut: 3000,
            closeButton: true
        };
        const quantity1 = quantity || 1;
        const allStock = product.stocks || [];
        const totalStock = Array.isArray(allStock)
        ? allStock.reduce((sum, stock) => sum + (stock.stock_quantity || 0), 0)
        : 0;

        // Check if a row with the same product ID already exists
        const existingRow = $(`.data_row${product.id}`);
         //negetive sale
         if (!isSelectedProduct) {
         if (typeof sale_without_stock !== "undefined" && sale_without_stock == 0) {
            if (totalStock <= 0) {
                toastr.warning("No stock available for this product.");
                quantityInput.val(0);
                return;
            }
             }
            if (typeof sale_without_stock !== "undefined" && sale_without_stock == 1) {
            if (totalStock <= 0) {
                toastr.info("No stock available for this product.");
                // quantityInput.val(0);

            }
             }
            }
        if (existingRow.length > 0) {
            // If the row exists, update the quantity
            const quantityInput = existingRow.find('.quantity');
            const currentQuantity = parseInt(quantityInput.val());
            const newQuantity = currentQuantity + 1;
             //negetive sale
            if (newQuantity > totalStock) {
                toastr.warning(`Quantity cannot exceed available stock (${totalStock}).`);
                quantityInput.val(totalStock);
            } else {
                quantityInput.val(newQuantity);
            }
            return;
        }

        // If the row doesn't exist, add a new row
        const rowHtml = `
            <tr class="data_row${product.id}">
                <td  class="duplicate_invoices">
                    <span type="text" class="form-control product_name${
                product.id
            } border-0" name="product_name[]" readonly
            " style="width:100%; margin: 0; padding: 0; font-size: 12px; white-space: normal; word-wrap: break-word; max-height: 50px; overflow-y: auto;">${
                product?.product?.name ?? ""
            }</span>
                </td>
                <td>
                    <input type="text" class="form-control product_color${product.id} border-0" name="product_color[]" readonly value="${product?.color_name.name ?? ""}" style="width:60px;margin:0;padding:3px;font-size:12px"/>
                </td>
                <td>
                    <input type="text" class="form-control product_size${product.id} border-0" name="product_size[]" readonly value="${product?.variation_size?.size ?? ""}" style="width:60px;margin:0;padding:3px;font-size:12px"/>
                </td>
                <td>
                    <input type="hidden" class="product_id" name="product_id[]" readonly value="${product.id ?? 0}" />
                    <input type="hidden" class="variant_cost_price${product.id}" name="variant_cost_price[]" readonly value="${product.cost_price ?? 0}" />
                    <input type="hidden" class="variant_sale_price${product.id}" name="variant_sale_price[]" readonly value="${priceType == "b2b_price" ? product.b2b_price : product.b2c_price ?? 0}" style="width:60px;margin:0;padding:3px;font-size:12px"/>
                    <input type="hidden" class="promotion_type${product.id}" name="promotion_type[]" readonly value="${promotion?.discount_type}" />
                    <input type="hidden" class="discount_value${product.id}" name="discount_value[]" readonly value="${promotion?.discount_value}" />
                    <input type="number" product-id="${product.id}" class="form-control unit_price product_price${product.id} ${checkSellEdit == 0 ? 'border-0' : ''}" id="product_price" name="unit_price[]" ${checkSellEdit == 0 ? 'readonly' : ''} value="${priceType == "b2b_price" ? product.b2b_price : product.b2c_price}" style="width:60px;margin:0;padding:3px;font-size:12px"/>
                </td>
                <td>
                <input type="hidden" variant-id="${
                        product.id
                    }" class=" totalSumQuantity sumQuantity${
                    product.id
                }" name="sumQuantity[]" value="${totalStock}" />

                    <input type="number" product-id="${product.id}" class="form-control quantity productQuantity${product.id}" name="quantity[]" value="${quantity1}" style="width:60px;margin:0;padding:3px;font-size:12px"/>
                </td>
                ${generateWarrantySection(product.id)}
                ${sale_hands_on_discount == 1 ? `<td style="padding-top: 20px;">
                    ${generateDiscountSection(product, promotion)}
                </td>` : ""}
                <td>
                    ${generateSubtotalSection(product, promotion, quantity1)}
                </td>
                <td style="padding-top: 20px;">
                    <a href="#" class="btn btn-sm btn-danger btn-icon purchase_delete" style="font-size: 8px; height: 25px; width: 25px;" data-id=${product.id}>
                        <i class="fa-solid fa-trash-can" style="font-size: 0.8rem; margin-top: 2px;"></i>
                    </a>
                </td>
            </tr>
        `;

        $('.showData').append(rowHtml);
    }

    // Generate Warranty Section HTML
    function generateWarrantySection(productId) {
        if (checkWarranty != 1) return "";

        return `
            <td class="d-flex align-items-center">
                <div class="form-check form-switch mb-2">
                    <input type="checkbox" class="form-check-input warranty_status${productId}" id="warranty_status">
                </div>
                <div class="Warranty_duration" style="display: none;">
                    <select class="form-select wa_duration${productId}" onclick="errorRemove(this);">
                        <option selected disabled>Select Warranty</option>
                        <option value="6 Month">6 Month</option>
                        <option value="1 Year">1 Year</option>
                        <option value="2 Year">2 Year</option>
                        <option value="3 Year">3 Year</option>
                        <option value="4 Year">4 Year</option>
                        <option value="5 Year">5 Year</option>
                        <option selected disabled>No Warranty</option>
                    </select>
                    <span class="text-danger product_select_error"></span>
                </div>
            </td>
        `;
    }

    // Generate Discount Section HTML
    function generateDiscountSection(product, promotion) {
        if (sale_hands_on_discount == 0) {
            if (!promotion) return `<span class="mt-2">00</span>`;

            return promotion.discount_type == 'percentage' ?
                `<span class="discount_percentage${product.id} mt-2">${promotion.discount_value}</span>%` :
                `<span class="discount_amount${product.id} mt-2">${promotion.discount_value}</span>Tk`;
        }

        return `
                <input type="number" product-id="${product.id}" class="form-control product_discount${product.id} discountProduct" name="product_discount" value="" />
            `;
    }

    // Generate Subtotal Section HTML
    function generateSubtotalSection(product, promotion, quantity) {
        if (!promotion) {
            let price = priceType == "b2b_price" ? product.b2b_price * quantity : product.b2c_price * quantity;
            return `<input type="number" class="form-control product_subtotal${product.id} border-0" name="total_price[]" id="productTotal" readonly value="${price.toFixed(2) ?? 0}" />`;
        }

        const discountValue = promotion.discount_value;
        const subtotal = promotion.discount_type == 'percentage' ?
            product.price - (product.price * discountValue / 100) :
            product.price - discountValue;

        return `<input type="number" class="form-control product_subtotal${product.id} border-0" name="total_price[]" id="productTotal" readonly value="${subtotal.toFixed(2) ?? 0}" />`;
    }



    function calculateProductTotal() {
        $('.quantity').each(function () {
            let $quantityInput = $(this);
            let productId = $quantityInput.attr('product-id');
            let quantity = parseInt($quantityInput.val()) || 0;
            let price = parseFloat($('.product_price' + productId).val()) || 0;
            let unitPriceField = $('.product_price' + productId);
            let discount = parseFloat($('.product_discount' + productId).val()) || 0;
            let productSubtotal = $('.product_subtotal' + productId);
            let discountField = $('.product_discount' + productId);
            let subtotal = (quantity * price) - discount;


            let costPrice = parseFloat($('.variant_cost_price' + productId).val()) || 0;
            let salePrice = parseFloat($('.variant_sale_price' + productId).val()) || 0;
            let promotion = $('.promotion_type' + productId).val() || null;
            let discountValue = parseFloat($('.discount_value' + productId).val()) || 0;

            if (promotion) {
                if (promotion == 'percentage') {
                    subtotal -= (subtotal * discountValue) / 100;
                } else {
                    subtotal -= discountValue;
                }
            }

            if (price < costPrice) {
                if(sale_with_low_price ==1 ){
                Swal.fire({
                    title: 'Are you sure?',
                    text: `You want to sell this product at a lower price? The product cost price is ${costPrice}.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, I want!',
                }).then((result) => {
                    if (!result.isConfirmed) {
                        unitPriceField.val(salePrice);
                        subtotal = (salePrice * quantity) -
                            discount;
                    }
                    productSubtotal.val(subtotal.toFixed(2));
                });
            }else{
                toastr.warning("Cannot sell at a lower price.");
                unitPriceField.val(salePrice);
                subtotal = salePrice * quantity - discount;
            }
           } else {
                productSubtotal.val(subtotal.toFixed(2));
            }

            if (subtotal < costPrice) {
                if(sale_with_low_price ==1 ){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "you want to sell this product at a lower price?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, I want!',
                }).then((result) => {
                    if (!result.isConfirmed) {
                        discountField.val(0);
                        unitPriceField.val(salePrice);
                        subtotal = salePrice * quantity;
                    }
                    productSubtotal.val(subtotal.toFixed(2));
                });
            }else{
                toastr.warning("Cannot sell at a lower price.");
                unitPriceField.val(salePrice);
                subtotal = salePrice * quantity - discount;
            }
         } else {
                productSubtotal.val(subtotal.toFixed(2));
            }

        });
        calculateInvoiceTotal();
    }


    $(".duplicate_sale_discount_type").on("change", function () {
        calculateInvoiceTotal();
    });
    // Prevent negative values directly in input fields
    $(document).on('input', '.unit_price, .quantity, .discountProduct', function () {
        let value = parseFloat($(this).val()) || 0;
        if (value < 0) {
            $(this).val(0);
            toastr.warning("Negetive Value are not allowed");
        };
        calculateInvoiceTotal();
    });

    // Trigger calculation when price, quantity, or discount changes
    $(document).on('change', '.unit_price, .quantity, .discountProduct', function () {
        calculateProductTotal();
        calculateInvoiceTotal();
        updateTotalQuantity();
    });

    // // Handle Enter key press for unit_price, quantity, handsOnDiscount, and total_payable fields
    // $(document).on('keydown', '.unit_price, .quantity, .handsOnDiscount, .total_payable', function (e) {
    //     if (e.key === 'Enter') {
    //         e.preventDefault(); // Prevent form submission

    //         const currentField = $(this);
    //         const currentFieldClass = currentField.attr('class');
    //         const productId = currentField.attr('product-id'); // Get product ID if applicable

    //         if (currentFieldClass.includes('unit_price')) {
    //             // Move to the corresponding quantity field
    //             const nextField = $(`.quantity[product-id="${productId}"]`);
    //             nextField.val(''); // Clear value
    //             nextField.focus();
    //         } else if (currentFieldClass.includes('quantity')) {
    //             if (checkSellEdit == 1) {
    //                 // Move to the next unit_price field
    //                 const nextUnitPrice = $(`.unit_price[product-id="${productId}"]`).closest('tr').next().find('.unit_price');
    //                 if (nextUnitPrice.length > 0) {
    //                     nextUnitPrice.val(''); // Clear value
    //                     nextUnitPrice.focus();
    //                 } else {
    //                     // If no next unit_price, move to handsOnDiscount
    //                     $('.handsOnDiscount').val('').focus();
    //                 }
    //             } else {
    //                 // If checkSellEdit is off, move to the next quantity field
    //                 const nextQuantity = $(`.quantity[product-id="${productId}"]`).closest('tr').next().find('.quantity');
    //                 if (nextQuantity.length > 0) {
    //                     nextQuantity.val(''); // Clear value
    //                     nextQuantity.focus();
    //                 } else {
    //                     // If no next quantity, move to handsOnDiscount
    //                     $('.handsOnDiscount').val('').focus();
    //                 }
    //             }
    //         } else if (currentFieldClass.includes('handsOnDiscount')) {
    //             // Move to total_payable field
    //             $('.total_payable').val('').focus();
    //         } else if (currentFieldClass.includes('total_payable')) {
    //             // If in total_payable, do nothing or submit the form
    //             // Example: $('form').submit();
    //         }
    //     }
    // });

    // Function to calculate the Invoice total//
    function calculateInvoiceTotal() {
        let productTotal = $('.total');
        let handsOnDiscount = parseFloat($('.handsOnDiscount').val()) || 0;
        // console.log(checkTax == 1);
        let tax = (checkTax == 1 ? parseFloat("{{ $taxPercentage }}") : 0) || 0;
        let invoiceTotalField = $('.invoice_total');
        let previousDue = invoicePayment == 0 ? (parseFloat($('.previous_due').val()) || 0) : 0;
        let grandTotalField = $('.grandTotal');
        let totalPayable = parseFloat($('.total_payable').val()) || 0;
        let totalPayableField = $('.total_payable');
        let total_due = $('.total_due');
        let taxField = $('.tax');

        let allProductTotal = document.querySelectorAll('#productTotal');
        let allTotal = 0;
        allProductTotal.forEach(product => {
            let productValue = parseFloat(product.value);
            if (!isNaN(productValue)) {
                allTotal += productValue;
            }
        });

        productTotal.val(allTotal.toFixed(2));
        //
        const discountType = discountCheck === "1" ? $('.duplicate_sale_discount_type').val() : null;
        const discountValue = discountCheck === "1"
        ? parseFloat($('.handsOnDiscount').val()) || 0
        : 0;
        // console.log(discountValue)
        // console.log(discountType)
        let discountTotal = allTotal;
        if (discountType === 'fixed') {
              discountTotal  -= discountValue;
        }
        else if (discountValue > 0) {
            if (discountValue > 100) {
                toastr.warning('Please select a number between 1 and 100');
                $('.handsOnDiscount').val(0);
            } else {
                // console.log('worked')
                discountTotal -= (allTotal * discountValue) / 100;
            }
        }
        // let discountTotal = allTotal - handsOnDiscount;

        // if tax option is on
        let taxTotal = checkTax == 1 ? (discountTotal * tax) / 100 : 0;
        taxField.val(taxTotal.toFixed(2));

        let invoiceTotal = discountTotal + taxTotal;
        invoiceTotalField.val(invoiceTotal.toFixed(2));
        const grandTotal = previousDue + invoiceTotal;
        grandTotalField.val(grandTotal.toFixed(2));
        let due = grandTotal - totalPayable;

        if (due > 0) {
            $('.total_due').val(due.toFixed(2));
            $('.due_text').text('Due');
        } else {
            $('.total_due').val(-(due.toFixed(2)));
            $('.due_text').text('Return');
        }
    }

    $('.handsOnDiscount, .total_payable').on('keyup', function () {
        let value = parseFloat($(this).val()) || 0;
        if (value < 0) {
            $(this).val(0);
            toastr.warning("Negative values are not allowed");
        }
        calculateInvoiceTotal();
        // handsonDiscountField();
    });



    function showSelectedProducts() {
        $.ajax({
            url: '/sale/product/find/' + sale_id,
            type: 'GET',
            dataType: 'JSON',
            success: function (res) {
                if (res.status == 200) {
                    const saleItems = res.saleItems;
                    saleItems.forEach(item => {
                        const variant = item?.variant;
                        const promotion = item?.promotion;
                        const quantity = item?.quantity;
                        showAddProduct(variant, quantity, promotion,true);
                        // console.log(quantity);
                    });
                    calculateProductTotal();
                    updateTotalQuantity();
                } else {
                    toastr.warning(res.error);
                }
            }
        });
    }
    showSelectedProducts();

    // Product add with barcode
    $('.barcode_input').change(function () {
        let barcode = $(this).val();
        $.ajax({
            url: '/variant/barcode/find/' + barcode,
            type: 'GET',
            dataType: 'JSON',
            success: function (res) {
                if (res.status == 200) {
                    showAddProduct(res.variant, 1);
                    calculateProductTotal();
                    updateTotalQuantity();
                    calculateInvoiceTotal();
                    $('.barcode_input').val('');
                } else {
                    toastr.warning(res.error);
                    $('.barcode_input').val('');
                }
            }
        });
    });


    // Select product
    $('.product_select').change(function () {
        selectedCustomerId = $(".select-customer").val();
        if (!selectedCustomerId) {
            // console.log("No customer selected");
            alert("Please select a customer first.");
            return;
        }
        // showSpinner();
        let id = $(this).val();
        $.ajax({
            url: '/product/find/' + id,
            type: 'GET',
            dataType: 'JSON',
            success: function (res) {
                // hideSpinner();
                const product = res.data;
                selectedCustomerId = $(".select-customer").val();
                // console.log(product);
                if (product.variations.length > 1) {
                    $('#varientModal').modal('show');
                    showVariants(product.variations);
                } else {
                    fetchVariantDetails(
                        product.variations[0].id,
                        selectedCustomerId
                    );
                    calculateProductTotal();
                    updateTotalQuantity();
                }
            },

        });
    });


    // show variants
    function showVariants(variants) {
        // Clear previous variants (if any)
        $(".show_variants").empty();
        if (!variants || variants.length === 0) {
            $(".show_variants").append('<p class="text-center">No active variants available.</p>');
            return;
        }
        // $('.show_variants').empty();
        // console.log(variants);
        // Loop through each variant and append to the modal
        variants.forEach(variant => {
            // console.log(variant);
            const imageUrl = variant?.image ? `/uploads/products/${variant.image}` :
                '/dummy/image.jpg';
            // const imageUrl = variant.image || '/dummy/image.jpg';
            $('.show_variants').append(`
                <div class="col-md-4 text-center p-2 cursor-pointer addVariant" data-active="false" data-id="${variant.id}">
                    <div class="rounded border pb-2 overflow-hidden">
                        <img src="${imageUrl}" class="img-fluid"
                            style="object-fit: cover; height: 120px; width: 100%" alt="variant image">
                        <div class="mt-2">
                            <p class="">${priceType === "b2b_price" ? variant.b2b_price : variant.b2c_price ?? 0}</p>
                            <p class="">${variant?.variation_size?.size ?? ""}</p>
                            <p class="">${variant?.color_name.name ?? ""}</p>
                        </div>
                    </div>
                </div>
            `);
        });
    }

    // function showVariants(variants) {
    //     // Clear previous variants (if any)
    //     $('.show_variants').empty();

    //     // Loop through each variant and append to the modal
    //     variants.forEach(variant => {
    //         let imageUrl = variant?.image ? `/uploads/products/${variant.image}` : '/dummy/image.jpg';

    //         $.ajax({
    //             url: imageUrl,
    //             type: 'HEAD',
    //             error: function () {
    //                 // dummy ইমেজ ব্যবহার করুন
    //                 imageUrl = '/dummy/image.jpg';
    //             },
    //             success: function () {
    //                 // ডাটাবেস থেকে প্রাপ্ত ইমেজ ব্যবহার করুন
    //                 imageUrl = `/uploads/products/${variant.image}`;
    //             },
    //             complete: function () {
    //                 // Append the variant to the modal
    //                 $('.show_variants').append(
    //                     `<div class="col-md-4 text-center p-2 cursor-pointer addVariant" data-active="false" data-id="${variant.id}">
    //             <div class="rounded border pb-2 overflow-hidden">
    //                 <img src="${imageUrl}" class="img-fluid"
    //                     style="object-fit: cover; height: 120px; width: 100%" alt="variant image">
    //                 <div class="mt-2">
    //                     <p class="">${priceType === "b2c_price" ? variant.b2b_price : variant.b2c_price ?? 0}</p>
    //                     <p class="">${variant?.variation_size?.size ?? ""}</p>
    //                     <p class="">${variant?.color ?? ""}</p>
    //                 </div>
    //             </div>
    //         </div>`
    //                 );
    //             }
    //         });
    //     });
    // }


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
        selectedCustomerId = $(".select-customer").val();
        // console.log('selectedCustomerId')
        if (!selectedCustomerId) {
            // console.log("No customer selected");
            alert("Please select a customer first.");
            return;
        }
        // Loop through each active variant and fetch details
        activeVariants.each(function () {
            const variantId = $(this).attr('data-id'); // Get the data-id of the active variant
            fetchVariantDetails(variantId,selectedCustomerId); // Call fetchVariantDetails for each active variant
        });

        // Hide the modal after processing
        $('#varientModal').modal('hide');
    });



    // Fetch variant details via AJAX
    function fetchVariantDetails(variantId,selectedCustomerId, isProduct) {
        $.ajax({
            url: `/variant/find/${variantId}`, // Create this endpoint in your backend
            type: 'GET',
            data: {
                isProduct: isProduct,
                selectedCustomerId:selectedCustomerId,
            },
            dataType: 'JSON',
            success: function (res) {
                const variant = res.variant;
                // console.log(variant);
                // const promotion = res.promotion;
                showAddProduct(variant, 1); // Add the selected variant to the product list
                calculateProductTotal();
                updateTotalQuantity();
            },
            error: function (error) {
                console.error('Error fetching variant details:', error);
            }
        });
    }

    // Purchase delete
    $(document).on('click', '.purchase_delete', function (e) {
        let id = $(this).attr('data-id');
        let dataRow = $('.data_row' + id);
        dataRow.remove();
        calculateProductTotal();
        updateTotalQuantity();
    });


    // view Customer Details
    function viewCustomerDetails(customer) {
        $('.show_customer_details').html(`
            <div class="col-lg-12 d-flex justify-content-between align-items-center">
                <label for="exampleInputUsername2" class="col-form-label">Customer Name</label>
                <span>:</span>
                <label for="exampleInputUsername2" class="col-form-label">${customer.name ?? ""}</label>
            </div>
            <div class="col-lg-12 d-flex justify-content-between align-items-center">
                <label for="exampleInputUsername2" class="col-form-label">Customer Phone</label>
                <span>:</span>
                <label for="exampleInputUsername2" class="col-form-label">${customer?.phone ?? ""}</label>
            </div>
            <div class="col-lg-12 d-flex justify-content-between align-items-center">
                <label for="exampleInputUsername2" class="col-form-label">Previous Due</label>
                <span>:</span>
                <label for="exampleInputUsername2" class="col-form-label">${customer?.wallet_balance ?? "00"}</label>
            </div>
         `)
    }

    // Customer previous Due
    $(document).on('change', '.select-customer', function () {
        let id = $(this).val();
        if (id) {
            customerDueShow(id);
        }

    })

    function customerDueShow(id) {
        $.ajax({
            url: `/sale/customer/due/${id}`,
            type: 'GET',
            dataType: 'JSON',
            success: function (res) {
                const customer = res.customer;
                if (invoicePayment == 1) {
                    viewCustomerDetails(customer);
                } else {
                    $('.previous_due').val(customer.wallet_balance ?? 0);
                    calculateProductTotal();
                }
            }
        })
    }



    // console.log(checkPrintType);

    function saveInvoice() {
        let customer_id = $('.select-customer').val();
        let sale_date = $('.purchase_date').val();
        let formattedSaleDate = moment(sale_date, 'DD-MMM-YYYY').format('YYYY-MM-DD HH:mm:ss');
        let quantity = totalQuantity;
        let product_total = parseFloat($('.total').val()) || 0;
        let actual_discount = parseFloat($('.handsOnDiscount').val()) || 0;
        let discount = parseFloat($(".handsOnDiscount").val()) || 0;
        let tax = (checkTax == 1 ? parseFloat("{{ $taxPercentage }}") : 0) || 0;
        let invoice_total = parseFloat($('.invoice_total').val()) || 0;
        let previous_due = $('.previous_due').val() || 0;
        let grand_total = parseFloat($('.grandTotal').val());
        let paid = parseFloat($('.total_payable').val()) || 0;
        let due = grand_total - paid;
        let note = $('.note').val();
        let payment_method = $('.payment_method').val();
        let sale_discount_type = $('.duplicate_sale_discount_type').val();
        let invoice_number = $('.generate_invoice').val();
        // let product_id = $('.product_id').val();
        // console.log(total_quantity);

        let variants = [];

        $('tr[class^="data_row"]').each(function () {
            let row = $(this);
            // Get values from the current row's elements
            let variant_id = row.find('.product_id').val();
            let quantity = row.find('input[name="quantity[]"]').val();
            let unit_price = row.find('input[name="unit_price[]"]').val();
            let wa_status = row.find(`.warranty_status${variant_id}`).is(':checked') ? 1 : 0;
            let wa_duration = row.find(`.wa_duration${variant_id}`).val();
            let discount_amount = row.find(`.discount_amount${variant_id}`).text().replace('Tk',
                '') || 0;
            let discount_percentage = row.find(`.discount_percentage${variant_id}`).text().replace(
                '%', '') || 0;
            let productDiscount = row.find(`.product_discount${variant_id}`).val();
            let total_price = row.find('input[name="total_price[]"]').val();

            let product_discount = discount_amount || discount_percentage ? (discount_amount ?
                discount_amount : discount_percentage) : (productDiscount ? productDiscount : 0);
            let variant = {
                variant_id,
                quantity,
                unit_price,
                wa_status,
                wa_duration,
                product_discount,
                total_price
            };

            // Push the object into the products array
            variants.push(variant);
        });

        let allData = {
            // for purchase table
            customer_id,
            sale_date: formattedSaleDate,
            quantity,
            product_total,
            actual_discount,
            invoice_total,
            grand_total,
            tax,
            paid,
            due,
            note,
            payment_method,
            variants,
            previous_due,
            invoice_number,
            sale_discount_type,
            discount,
        }

        // console.log(allData);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/sale/store',
            type: 'POST',
            data: allData,
            success: function (res) {
                if (res.status == 201) {
                    toastr.success(res.message);
                    let id = res.saleId;
                    let printFrame = $('#printFrame')[0];

                    function handlePrintAndRedirect(url) {
                        $('#printFrame').attr('src', url);
                        printFrame.onload = function () {
                            printFrame.contentWindow.focus();
                            printFrame.contentWindow.print();
                            hideSpinner();
                            printFrame.contentWindow.onafterprint = function () {
                                window.location.href = "/sale";
                            };
                        };
                    }

                    switch (checkPrintType) {
                        case 'a4':
                        case 'a5':
                            handlePrintAndRedirect('/sale/invoice/' + id);
                            break;
                        default:
                            handlePrintAndRedirect('/sale/print/' + id);
                            break;
                    }

                    $(window).off('beforeunload');
                } else {
                    hideSpinner();
                    // console.log(res);
                    if (res.error.customer_id) {
                        showError('.select-customer', res.error.customer_id);
                    }
                    if (res.error.sale_date) {
                        showError('.purchase_date', res.error.sale_date);
                    }
                    if (res.error.payment_method) {
                        showError('.payment_method', res.error.payment_method);
                    }
                    if (res.error.paid) {
                        showError('.total_payable', res.error.paid);
                    }
                    if (res.error.variants) {
                        toastr.warning("Please Select a Variant to sell");
                    }
                }
            },
            error: function (xhr, status, error) {
                // console.log(`xhr: ${xhr}, status: ${status}, error: ${error}`);
                hideSpinner();
                if (xhr.status === 404) {
                    toastr.warning('Error: Variant not found.');
                } else if (xhr.status === 500) {
                    toastr.warning('Error: An unexpected error occurred.');
                } else {
                    toastr.warning('Error: ' + xhr.statusText);
                }
            }
        });
    }

    const total_payable = document.querySelector('.total_payable');
    total_payable.addEventListener('keydown',
        function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                showSpinner();
                saveInvoice();
            }
        })
    // order btn
    $('.payment_btn').click(function (e) {
        e.preventDefault();
        showSpinner();
        saveInvoice();
    })
})
