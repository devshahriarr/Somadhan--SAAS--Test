// error remove
function errorRemove(element) {
    tag = element.tagName.toLowerCase();
    if (element.value != "") {
        // console.log('ok');
        if (tag == "select") {
            $(element).closest(".mb-3").find(".text-danger").hide();
            $(element).css("border-color", "green");
        } else {
            $(element).siblings("span").hide();
            $(element).css("border-color", "green");
        }
    }
}

// define warranty period
$(document).on("click", "#warranty_status", function () {
    if ($(this).is(":checked")) {
        $(this).closest("div").next(".Warranty_duration").show();
    } else {
        $(this).closest("div").next(".Warranty_duration").hide();
    }
});

$(document).ready(function () {
    // Barcode Focused
    $(".barcode_input").focus();

    // showError Function
    function showError(name, message) {
        $(name).css("border-color", "red");
        $(name).focus();
        $(`${name}_error`).show().text(message);
    }

    // Via Sell Product view function
    function viewProductSell() {
        $.ajax({
            url: "/product/view/sale",
            method: "GET",
            success: function (res) {
                // console.log(res);
                const products = res.products;
                const userRole = res.user_role;
                // console.log(products);
                $(".view_product").empty();

                if (products.length > 0) {
                    $(".view_product").append(
                        `<option selected disabled>Select Product</option>`
                    );
                    $.each(products, function (index, product) {
                        const variations =
                            product.defaultVariations ||
                            product.default_variations ||
                            {};

                        const costPrice = variations.cost_price
                            ? `  ৳ ${parseFloat(variations.cost_price).toFixed(
                                  2
                              )}`
                            : "  N/A";
                        const b2b_price = variations.b2b_price
                            ? `  ৳ ${parseFloat(variations.b2b_price).toFixed(
                                  2
                              )}`
                            : "  N/A";
                        const b2c_price = variations.b2c_price
                            ? `  ৳ ${parseFloat(variations.b2c_price).toFixed(
                                  2
                              )}`
                            : "  N/A";
                        $(".view_product").append(
                            `<option value="${product.id}">${product.name} (${
                                product.stock_quantity_sum || 0
                            } pc Available)
                            | ${
                                ["admin", "superadmin"].includes(userRole)
                                    ? ` | Cost Price: <i>${costPrice}</i>`
                                    : ""
                            }${
                                ["admin", "superadmin", "salesman"].includes(
                                    userRole
                                )
                                    ? ` | B2B Price: <i>${b2b_price}</i>`
                                    : ""
                            }${
                                ["admin", "superadmin", "salesman"].includes(
                                    userRole
                                )
                                    ? ` | B2C Price: <i>${b2c_price}</i>`
                                    : ""
                            }|
                            </option>`
                        );
                    });
                } else {
                    $(".view_product").html(`
                 <option selected disable>Please add Product</option>`);
                }
            },
        });
    }
    viewProductSell();

    // add via Products
    $(document).on("click", ".save_via_product", function (e) {
        e.preventDefault();
        let formData = new FormData($(".viaSellForm")[0]);
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        $.ajax({
            url: "/via/product/add",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                // console.log("via product add", res);
                if (res.status == 200) {
                    $("#viaSellModal").modal("hide");
                    $(".viaSellForm")[0].reset();
                    viewProductSell();
                    let products = res.products;
                    let quantity = res.quantity;
                    // let totalStock = res
                    // console.log('total stock :',quantity);
                    showAddProduct(products, quantity);
                    calculateProductTotal();
                    updateTotalQuantity();
                    toastr.success(res.message);
                    $(window).on("beforeunload", function () {
                        return "Are you sure you want to leave? because You Add a Via Sale Product?";
                    });
                } else if (res.status == 400) {
                    $("#viaSellModal").modal("hide");
                    toastr.warning(res.message);
                } else {
                    // console.log(res);
                    if (res.error.name) {
                        showError(".product_name", res.error.name);
                    }
                    if (res.error.cost) {
                        showError(".cost_price", res.error.cost);
                    }
                    if (res.error.price) {
                        showError(".sell_price", res.error.price);
                    }
                    if (res.error.stock) {
                        showError(".via_quantity", res.error.stock);
                    }
                    if (res.error.category_id) {
                        showError(".via_category", res.error.category_id);
                    }
                    if (res.error.color) {
                        showError(".via_color", res.error.color);
                    }
                    if (res.error.size) {
                        showError(".via_size", res.error.size);
                    }
                    if (res.error.via_supplier_name) {
                        showError(
                            ".via_supplier_name",
                            res.error.via_supplier_name
                        );
                    }
                    if (res.error.transaction_account) {
                        showError(
                            ".transaction_account",
                            res.error.transaction_account
                        );
                    }
                }
            },
        });
    });

    // Combined function for product calculation and due calculation
    function handleViaProductAndDueCalculation() {
        // Product Calculation
        let sellPrice = parseFloat($(".sell_price").val());
        let costPrice = parseFloat($(".cost_price").val()) || 1;
        let quantity = parseFloat($(".via_quantity").val()) || 1;

        // Check for negative values
        if (sellPrice < 0 || costPrice < 0 || quantity < 0) {
            toastr.warning("Warning: Negative values are not allowed!");

            // Empty the fields if negative value is found
            if (sellPrice < 0) $(".sell_price").val("");
            if (costPrice < 0) $(".cost_price").val("");
            if (quantity < 0) $(".via_quantity").val("");

            return; // Stop further execution
        }

        let total = sellPrice * quantity;
        let totalCost = costPrice * quantity;
        $(".via_product_total").val(total.toFixed(2));
        $(".via_total_pay").val(totalCost.toFixed(2));

        // Due Calculation
        let viaTotalPay = parseFloat($(".via_total_pay").val());
        let paid = parseFloat($(".via_paid").val()) || 0;

        // Check for negative paid value
        if (paid < 0) {
            toastr.warning("Warning: Paid amount cannot be negative!");

            // Empty the paid field if negative value is found
            $(".via_paid").val("");

            return; // Stop further execution
        }

        let due = viaTotalPay - paid;
        $(".via_due").val(due.toFixed(2));
    }

    // Event listeners for input fields
    $(document).on(
        "keyup",
        ".sell_price, .via_quantity, .cost_price, .via_paid",
        function () {
            handleViaProductAndDueCalculation();
        }
    );

    // customer view function

    function viewCustomer() {
        $.ajax({
            url: "/get/customer",
            method: "GET",
            success: function (res) {
                // console.log(res);
                const customers = res.allData;
                $(".select-customer").empty();
                if (res.status == 404) {
                    $(".select-customer").html(`
                     <option selected disabled>Please add a customer.</option>`);
                }
                $.each(customers, function (index, customer) {
                    $(".select-customer").append(
                        `<option value="${customer.id}">${
                            customer.name ?? ""
                        } (${customer.phone ?? ""})</option>`
                    );
                });

                $(".select-customer option:last").prop("selected", true);
                let customerId = $(".select-customer").val();
                if (customerId) {
                    customerDueShow(customerId);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // console.error('AJAX Error:', textStatus, errorThrown);
                toastr.warning("An error occurred while getting the customer.");
            },
        });
    }
    viewCustomer2();
    function viewCustomer2() {
        $.ajax({
            url: "/get/customer2",
            method: "GET",
            success: function (res) {
                // console.log(res);
                const customers = res.allData;
                $(".select-customer").empty();
                if (res.status == 404) {
                    $(".select-customer").html(`
                     <option selected disabled>Please add a customer.</option>`);
                }
                $.each(customers, function (index, customer) {
                    $(".select-customer").append(
                        `<option value="${customer.id}">${
                            customer.name ?? ""
                        } (${customer.phone ?? ""})</option>`
                    );
                });

                let customerId = $(".select-customer").val();
                if (customerId) {
                    customerDueShow(customerId);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // console.error('AJAX Error:', textStatus, errorThrown);
                toastr.warning("An error occurred while getting the customer.");
            },
        });
    }
    ///
    document
        .querySelector(".save_new_customer")
        .addEventListener("click", function (e) {
            e.preventDefault();

            let formData = new FormData(
                document.querySelector(".customerForm")
            );

            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            });

            $.ajax({
                url: "/add/customer",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {
                    // console.log(res);
                    if (res.status === 400) {
                        // Display validation errors
                        if (res.error.name) {
                            showError(".customer_name", res.error.name);
                        }
                        if (res.error.phone) {
                            showError(".phone", res.error.phone);
                        }
                        // Handle other potential validation errors
                        if (res.error.email) {
                            showError(".email", res.error.email);
                        }
                        if (res.error.address) {
                            showError(".address", res.error.address);
                        }
                        if (res.error.opening_payable) {
                            showError(
                                ".opening_payable",
                                res.error.opening_payable
                            );
                        }
                        if (res.error.opening_receivable) {
                            showError(
                                ".opening_receivable",
                                res.error.opening_receivable
                            );
                        }
                    } else if (res.status === 500) {
                        toastr.warning("Internerl Server Error");
                    } else {
                        $("#customerModal").modal("hide");
                        document.querySelector(".customerForm").reset();
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
                        "An error occurred while adding the customer. Please try again later."
                    );
                },
            });
        });

    // calculate quantity
    let totalQuantity = 0;

    function updateTotalQuantity() {
        totalQuantity = 0;
        $(".quantity").each(function () {
            let quantity = parseFloat($(this).val());
            if (!isNaN(quantity)) {
                totalQuantity += quantity;
            }
        });
    }

    let rowCount = 0;

    // Function to add a new row//
    function addRow() {
        rowCount++; // Increment row count for unique IDs
        const newRow = `
            <tr id="row-${rowCount}"  class="data_row${rowCount}">
                <td style="padding: 0!important; > data-row="${rowCount}" class="serial-number"></td>
                <td  style="padding:  0!important; text-align: left;">
                    <div style="position: relative;">
                        <input type="text"  style="border: 1px solid #176AF3;font-size:13px;font-weight:bold"
                            class="form-control py-1 global-search"
                            id="global_search_${rowCount}"
                            data-row="${rowCount}"
                            placeholder="Search here..."
                            autocomplete="off">
                        <div class="search_result m-0" id="search_result_${rowCount}"
                            style="display: none; position: absolute; background: #fff; font-weight: semibold;font-size:13px; border: 1px solid #ddd; z-index: 10; left: 0; width: calc(50vw); max-height: 200px; overflow-y: auto;"  >
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Stock</th>
                                       ${
                                           window.authUserSize
                                               ? `<th>Size</th>`
                                               : ""
                                       }
                                       ${
                                           window.authUserColor
                                               ? `<th>Color</th>`
                                               : ""
                                       }
                                       ${
                                           window.authUserCanCostPrice
                                               ? `<th>Cost Price</th>`
                                               : ""
                                       }
                                       ${
                                           window.authUserB2BPrice
                                               ? `<th>B2B Price</th>`
                                               : ""
                                       }
                                       ${
                                           window.authUserB2CPrice
                                               ? `<th>B2C Price</th>`
                                               : ""
                                       }
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="findData"></tbody>
                            </table>
                        </div>
                    </div>
                </td>
              ${
                  color_view == 1
                      ? `<td style="padding: 0px  10px!important;  text-align: center;">
                <input style="width:60px;margin:0;padding:0;font-size:12px ;margin-top:5px;font-size:12px"  type="text"  class="form-control border-0 color-input product_color${rowCount}" name="product_color[]" data-row="${rowCount}" readonly />
                 </td>`
                      : ""
              }

               ${
                   size_view == 1
                       ? ` <td style="padding: 0 10px!important;  text-align: center;"><input style="width:60px;margin:0;padding:0;font-size:14px ;margin-top:5px ;font-size:12px" name="product_size[]"  type="text" class="form-control  product_size${rowCount}  border-0   size-input" data-row="${rowCount}" readonly /></td>`
                       : ""
               }


                <td class="fa-barss" style="padding: 0!important;  text-align: center;">
            <input type="hidden" class="product_id" name="product_id[]" readonly value="" />
            <input type="hidden" class="variant_cost_price  variant_cost_price${rowCount}"  name="variant_cost_price[]" readonly value="" data-row="${rowCount}"/>
            <input type="hidden" class="variant_sale_price variant_sale_price${rowCount}" name="variant_sale_price[]" readonly value="" data-row="${rowCount}"/>
            <input type="hidden" class="total_stock" name="totalStock[]" value="" data-row="${rowCount}">
            <div style="display: flex; align-items: center; justify-content: center; gap: 5px;">
    <input
        type="number"
        class="form-control unit_price product_price${rowCount} price-input"
        name="unit_price[]"
        data-row="${rowCount}"
        ${checkSellEdit == 0 ? "readonly" : ""}
        ${checkSellEdit == 0 ? 'style="width:120px; margin:0; padding:3px; font-size:12px; border: 1px solid #176AF3; border-0;"' : 'style="width:120px; margin:0; padding:3px; font-size:12px; border: 1px solid #176AF3;"'}
    />
</div>
                </td>
                <td style="padding: 0!important;  text-align: center;"><input type="number" product-id="${rowCount}" class="form-control qty-input quantity productQuantity${rowCount}" name="quantity[]" style="width:60px;margin:0; padding:3px;font-size:12px;border: 1px solid #176AF3;" data-row="${rowCount}" value="1" /></td>
                 ${
                     sale_hands_on_discount == 1
                         ? `<td style="padding: 0!important; text-align: center">
                <input type="number" product-id="${rowCount}" class="form-control discountProduct product_discount${rowCount}" name="product_discount[]" value="" data-row="${rowCount}" style="width:60px;margin:0;padding:3px;font-size:12px;border: 1px solid #176AF3;"/>
                 </td>`
                         : ""
                 }
                <td style="padding: 0!important;  text-align: center;"><input type="number" id="productTotal" class="form-control border-0 subtotal-input product_subtotal${rowCount}" name="total_price[]" data-row="${rowCount}" readonly style="width:60px;margin:5px;padding:0;font-size:12px" /></td>
                <td style="padding: 0!important;  text-align: center;">
                    <button style="padding: 5px; text-align: center;" type="button" class="btn btn-danger btn-sm purchase_delete remove-rows" data-id=${rowCount} data-row="${rowCount}">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </td>
            </tr>
        `;
        $(".salesTable tbody.showData").append(newRow); // Append to specific tbody //
        calculateProductTotal();
        regenerateSerialNumbers();
    }
    // Check if a row with the same product ID already exists //
    // Add three rows initially
    $(document).ready(function () {
        for (let i = 0; i < 1; i++) {
            addRow();
        }
        regenerateSerialNumbers();
    });

    // Add a new row on button click
    $("#addRow").on("click", addRow);

    // Search functionality
    $(document).on("keyup", ".global-search", function (e) {
        const searchQuery = $(this).val().trim();
        const rowId = $(this).data("row");
        const searchResultContainer = $(`#search_result_${rowId}`);
        const findDataContainer = searchResultContainer.find(".findData");

        const items = findDataContainer.find("tr");

        // Keyboard navigation
        if (items.length > 0) {
            let current = findDataContainer.find(".highlight");

            if (e.keyCode === 40) {
                // Down arrow
                e.preventDefault(); // Prevent default scrolling
                current.removeClass("highlight");
                if (current.length === 0 || current.next().length === 0) {
                    items.first().addClass("highlight");
                } else {
                    current.next().addClass("highlight");
                }
                // Scroll to the highlighted item
                const highlighted = findDataContainer.find(".highlight")[0];
                if (highlighted) {
                    highlighted.scrollIntoView({
                        behavior: "smooth",
                        block: "nearest",
                    });
                }
                return;
            }

            if (e.keyCode === 38) {
                e.preventDefault(); // Prevent default scrolling
                current.removeClass("highlight");
                if (current.length === 0 || current.prev().length === 0) {
                    items.last().addClass("highlight");
                } else {
                    current.prev().addClass("highlight");
                }
                // Scroll to the highlighted item
                const highlighted = findDataContainer.find(".highlight")[0];
                if (highlighted) {
                    highlighted.scrollIntoView({
                        behavior: "smooth",
                        block: "nearest",
                    });
                }
                return;
            }

            if (e.keyCode === 13) {
                // Enter key
                e.preventDefault();
                const selectedProduct = findDataContainer.find(
                    ".highlight .select-product"
                );

                if (selectedProduct.length > 0) {
                    selectedProduct.trigger("click"); // Trigger the click event for the selected product
                }
                return;
            }
        }
        // Clear previous results
        findDataContainer.empty();

        if (searchQuery.length > 1) {
            let customerId = $(".select-customer").val();

            $.ajax({
                url: `/search2/${encodeURIComponent(searchQuery)}`,
                method: "GET",
                data: {
                    customerId: customerId,
                },
                success: function (response) {
                    // console.log(response);
                    if (
                        response.products &&
                        Array.isArray(response.products) &&
                        response.products.length > 0
                    ) {
                        findDataContainer.empty();
                        response.products.forEach((item) => {
                            findDataContainer.append(`
                                <tr>
                                    <td class="select-product" data-row="${rowId}"
                                        data-name="${
                                            item.name
                                        }" data-variant_id="${
                                item.variant_id
                            }" data-sizeid="${item.size}" data-colorid="${
                                item.color
                            }" data-color="${item.variation_color}"
                                   data-variation="${item}" data-variation_stock = "${
                                item.totalVariationStock
                            }"  data-praty_kit_price='${JSON.stringify(
                                item.salePartyKitPrice
                            )}'   data-variation_cost_price="${
                                item.cost_price
                            }"  data-size="${
                                item.variation_size
                            }" data-price="${
                                item.price
                            }" style="background-color: #f4f4f4;">
                                        ${item.name}
                                    </td>
                                    <td style="background-color: #ffcccc;">${
                                        item.totalVariationStock
                                    }</td>
                                     ${
                                         window.authUserSize
                                             ? `<td style="background-color: #ccffcc;">${item.variation_size}</td>`
                                             : ""
                                     }
                                     ${
                                         window.authUserColor
                                             ? `<td style="background-color: #ccccff;">${item.variation_color}</td>`
                                             : ""
                                     }
                                     ${
                                         window.authUserCanCostPrice
                                             ? `<td style="background-color: #ffffcc;">${item.cost_price}</td>`
                                             : ""
                                     }
                                     ${
                                         window.authUserB2BPrice
                                             ? `<td style="background-color: #ffccff;">${item.b2b_price}</td>`
                                             : ""
                                     }
                                     ${
                                         window.authUserB2CPrice
                                             ? `<td style="background-color: #ccffff;">${item.b2c_price}</td>`
                                             : ""
                                     }
                                </tr>
                            `);
                        });
                    } else if (
                        !findDataContainer.children(".no-products").length
                    ) {
                        // Append "No products found" only once
                        findDataContainer.append(`
                        <tr class="no-products">
                            <td colspan="3">No products found</td>
                        </tr>
                    `);
                    }
                    searchResultContainer.show();
                },
            });
        } else {
            searchResultContainer.hide();
        }
    });

    // Event listener for selecting a product
    $(document).on("click", ".select-product", function () {
        const rowId = $(this).data("row");
        const productName = $(this).data("name") || "";
        const productColor = $(this).data("color") || "";
        const productSize = $(this).data("size") || "";
        const productPrice = $(this).data("price") || 0;
        const variation = $(this).data("variation");
        const variant_id = $(this).data("variant_id");
        const variation_cost_price = $(this).data("variation_cost_price");
        const pratyKitPrice = $(this).data("praty_kit_price");
        const totalStock = $(this).data("variation_stock");
  let customerId = $(".select-customer").val();
$.ajax({
    url: "/rate-kit-price-get", // Replace with your actual endpoint
    type: "GET",
    dataType: "json",
    data: {
        variant_id: variant_id,
        customer_id: customerId,
        _token: $('meta[name="csrf-token"]').attr("content"), // CSRF token for Laravel
    },
    success: function (response) {
        if (response.success && response.data) {
            const pratyKitDatas = response.data.sale_item;
            const priceInput = $(`#row-${rowId} .product_price${rowId}`);

            if (priceInput.length) {
                // Set the initial price in the input if data exists
                if (pratyKitDatas.length > 0) {
                    // priceInput.val(pratyKitDatas[0].rate); // Set the first price as default
                }

                // Initialize popover only if party_ways_rate_kit == 1 and checkSellEdit != 0
                if (party_ways_rate_kit == 1 && checkSellEdit != 0) {
                    // Generate popover content with clickable prices
                    let popoverContent = Array.isArray(pratyKitDatas) && pratyKitDatas.length > 0
                        ? pratyKitDatas
                              .map((price, index) => {
                                  const saleDate = price.sale_date
                                      ? new Date(price.sale_date).toLocaleDateString()
                                      : "N/A";
                                  return `<div style="font-size: 12px; cursor: pointer;" class="price-option" data-price="${price.rate}" data-index="${index}">
                                              Rate: ${price.rate} | Qty: ${price.qty} | Date: ${saleDate}
                                          </div>`;
                              })
                              .join("")
                        : '<div style="font-size: 12px;">No previous prices</div>';

                    // Initialize Bootstrap popover
                    priceInput.attr({
                        'data-bs-toggle': 'popover',
                        'data-bs-trigger': 'focus', // Trigger on focus
                        'data-bs-content': popoverContent,
                        'data-bs-html': true // Allow HTML content
                    }).popover();

                    // Ensure popover shows on any focus event (manual or programmatic)
                    priceInput.on('focus', function () {
                        if (party_ways_rate_kit == 1 && checkSellEdit != 0) {
                            $(this).popover('show');
                        }
                    });

                    // Hide popover on blur
                    priceInput.on('blur', function () {
                        $(this).popover('hide');
                    });

                    // Handle click on price options in the popover
                    $(document).on('click', `#row-${rowId} .price-option`, function () {
                        const selectedPrice = $(this).data('price');
                        priceInput.val(selectedPrice); // Update the input field with the selected price
                        priceInput.popover('hide'); // Hide the popover after selection
                    });
                }

                // If the input is programmatically focused, ensure popover shows
                if (party_ways_rate_kit == 1 && checkSellEdit != 0 && priceInput.is(':focus')) {
                    priceInput.popover('show');
                }
            } else {
                console.error("Price input element not found");
            }
        }
    },
    error: function (xhr, status, error) {
        console.error("Error:", error);
    },
});

        // const priceOptions =
        //     Array.isArray(pratyKitPrice) && pratyKitPrice.length > 0
        //         ? pratyKitPrice
        //             .map(
        //                 (price) =>
        //                     `<option value="${price.rate}">${price.rate} | Qty :${price.qty} | Date: ${price.sale_date || "N/A"} </option>`
        //             )
        //             .join("")
        //         : "";
        // const selectElement = $(`#row-${rowId} select.variant_sale_price${rowId}`);

        // ///Party kit price//
        // $(`.variant_sale_price${rowId}`).on("change", function () {
        //     const selectedPrice = $(this).val();
        //     $(`.product_price${rowId}`).val(selectedPrice);
        // });
        // if (selectElement.length) {
        //     // Clear previous options except the first disabled one
        //     selectElement.find("option:not(:first)").remove();
        //     selectElement.append(priceOptions);
        // }
        const quantity = $(`#row-${rowId} .qty-input`).val() || 1;
        // console.log(variant_id);/
        // Set values in the row
        $(`#global_search_${rowId}`).val(productName);
        $(`#row-${rowId} .color-input`).val(productColor);
        $(`#row-${rowId} .size-input`).val(productSize);
        $(`#row-${rowId} .price-input`).val(productPrice);
        $(`#row-${rowId} .qty-input`).val(1);
        $(`#row-${rowId} .subtotal-input`).val(productPrice);
        $(`#row-${rowId} .product_id`).val(variant_id);
        $(`#row-${rowId} .variant_cost_price`).val(variation_cost_price);
        $(`#row-${rowId} .variant_sale_price`).val(productPrice);
        $(`#row-${rowId} .total_stock`).val(totalStock);
        const promotion = null;

        // Hide search results
        $(`#search_result_${rowId}`).hide();
        if (checkSellEdit == 0) {
            $(`#row-${rowId} .qty-input`).focus();
        } else {
            $(`#row-${rowId} .price-input`).focus();
        }

        ///////////////////////--sale without Stock--////////////////////
        if (sale_without_stock !== "undefined" && sale_without_stock === 0) {
            if (totalStock < 1) {
                toastr.warning("No stock available for this product.");
                $(`#row-${rowId} .qty-input`).val(0);
                return;
            }
        }
        if (sale_without_stock !== "undefined" && sale_without_stock === 1) {
            if (totalStock < 1) {
                toastr.info("No stock available for this product.");
            }
        }
        //////////////////sale without Stock ////////////////////
        calculateProductTotal();
    });
    // Keyboard enter Code Start
    $(document).on("keyup", ".price-input", function (e) {
        if (e.keyCode === 13) {
            // Enter key
            e.preventDefault();
            const rowId = $(this).data("row");
            const $row = $(`#row-${rowId}`);
            const $qtyInput = $row.find(".qty-input");
            $qtyInput.focus();
        }
    });
    //     $(document).on("keyup", ".price-input", function (e) {
    //     if (e.keyCode === 13) { // Enter key
    //         e.preventDefault();
    //         const rowId = $(this).data("row");
    //         const $row = $(`#row-${rowId}`);
    //         const $select = $row.find(`select.variant_sale_price${rowId}`);
    //         $select.focus(); // Focus the dropdown
    //         $select[0].size = $select.find('option').length; // Expand dropdown for navigation
    //     }
    // });

    // // Dropdown navigation and selection
    // $(document).on("keydown", "select", function (e) {
    //     if (e.key === "Enter") { // Enter key
    //         e.preventDefault();
    //         const rowId = $(this).data("row");
    //         const $row = $(`#row-${rowId}`);
    //         const $qtyInput = $row.find(".qty-input");
    //         $qtyInput.focus(); // Focus the qty-input field
    //         this.size = 0; // Collapse dropdown
    //     }
    // });

    // $(document).on("keyup", ".qty-input", function(e) {
    //     if (e.keyCode === 13) { // Enter key
    //         e.preventDefault();
    //         const rowId = $(this).data("row");
    //         const $row = $(`#row-${rowId}`);
    //         // const $discountInput = $row.find(".discountProduct"); // Check for discount input

    //         // if ($discountInput.length) {
    //         //     // If discount input exists, focus on it
    //         //     $discountInput.focus();
    //         // } else {
    //             // If no discount input, move to the global-search of the next row
    //             const nextRowId = parseInt(rowId) + 1;
    //             const $nextSearch = $(`#global_search_${nextRowId}`);
    //             if ($nextSearch.length) {
    //                 $nextSearch.focus();
    //             }
    //         // }
    //     }
    $(document).on("keyup", ".qty-input", function (e) {
        if (e.keyCode === 13) {
            // Enter key
            e.preventDefault();

            const rowId = $(this).data("row");
            const $row = $(`#row-${rowId}`);

            // Check if this is the last row
            const isLastRow = $row.is(":last-child");

            if (isLastRow) {
                // Add a new row
                addRow();

                // Calculate the next row ID
                const nextRowId = parseInt(rowId) + 1;

                // Wait for the row to render before focusing the new search input
                setTimeout(() => {
                    const $newSearch = $(`#global_search_${nextRowId}`);
                    if ($newSearch.length) {
                        $newSearch.focus();
                    }
                }, 100); //-Adjust the timeout if necessary-//
            } else {
                // Calculate the next row ID
                const nextRowId = parseInt(rowId) + 1;
                const $nextSearch = $(`#global_search_${nextRowId}`);
                // Focus the next row's search input if it exists
                if ($nextSearch.length) {
                    $nextSearch.focus();
                }
            }
        }
    });

    // Handle Enter key navigation for discountProduct (if exists)
    // $(document).on("keyup", ".discountProduct", function(e) {
    //     if (e.keyCode === 13) { // Enter key
    //         e.preventDefault();
    //         const rowId = $(this).data("row");
    //         // Move to the global-search of the next row
    //         const nextRowId = parseInt(rowId) + 1;
    //         const $nextSearch = $(`#global_search_${nextRowId}`);
    //         if ($nextSearch.length) {
    //             $nextSearch.focus();
    //         }
    //     }
    // });
    //////////////////sale without stock //////////////
    $(document).on("input", ".qty-input", function () {
        const rowId = $(this).data("row");
        const qty = parseFloat($(`#row-${rowId} .qty-input`).val()) || 0;
        // console.log(qty);
        const totalStock = parseInt($(`#row-${rowId} .total_stock`).val()) || 0; // Get totalStock from the input field

        if ($(this).hasClass("qty-input")) {
            // Only check stock when qty-input changes
            if (
                typeof sale_without_stock !== "undefined" &&
                sale_without_stock == 0
            ) {
                if (qty > totalStock) {
                    toastr.warning(
                        `Quantity cannot exceed available stock (${totalStock}).`
                    );
                    $(`#row-${rowId} .qty-input`).val(totalStock);
                    return;
                }
            }
            if (
                typeof sale_without_stock !== "undefined" &&
                sale_without_stock == 1
            ) {
                if (totalStock < qty) {
                    toastr.info(
                        `No stock available for this product.Avialabe Stock(${totalStock})`
                    );
                }
            }
        }
    });

    //////////////////sale without stock //////////////
    //Keyboard enter Code end
    // Hide search results on click outside
    $(document).on("click", function (event) {
        if (!$(event.target).closest(".global-search, .search_result").length) {
            $(".search_result").hide();
        }
    });

    // Remove row
    $(document).on("click", ".remove-rows", function () {
        const rowId = $(this).data("row");
        $(`#row-${rowId}`).remove();
        regenerateSerialNumbers();
        calculateProductTotal();
    });
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

            return promotion.discount_type == "percentage"
                ? `<span class="discount_percentage${product.id} mt-2">${promotion.discount_value}</span>%`
                : `<span class="discount_amount${product.id} mt-2">${promotion.discount_value}</span>Tk`;
        }

        return `
                <input type="number" product-id="${product.id}" class="form-control discountProduct product_discount${product.id}" name="product_discount[]" value="" />
            `;
    }

    // Generate Subtotal Section HTML
    function generateSubtotalSection(product, promotion, quantity) {
        if (!promotion) {
            let price =
                priceType == "b2b_price"
                    ? product.b2b_price * quantity
                    : product.b2c_price * quantity;
            return `<input type="number" class="form-control product_subtotal${
                product.id
            } border-0" name="total_price[]" id="productTotal" readonly value="${
                price.toFixed(2) ?? 0
            }" style="width:60px;margin:0;padding:0;font-size:12px"  />`;
        }

        const discountValue = promotion.discount_value;
        const subtotal =
            promotion.discount_type == "percentage"
                ? product.price - (product.price * discountValue) / 100
                : product.price - discountValue;

        return `<input type="number" class="form-control product_subtotal${
            product.id
        } border-0" name="total_price[]" id="productTotal" readonly value="${subtotal.toFixed(2) ?? 0}"   style="width:60px;margin:0;padding:0;font-size:12px" />`;
    }

    function calculateProductTotal() {
        $(".quantity").each(function () {
            let $quantityInput = $(this);
            let productId = $quantityInput.attr("product-id");
            let quantity = parseInt($quantityInput.val()) || 0;
            let price = parseFloat($(".product_price" + productId).val()) || 0;
            // console.log(price);
            let unitPriceField = $(".product_price" + productId);
            let discount =
                parseFloat($(".product_discount" + productId).val()) || 0;
            let productSubtotal = $(".product_subtotal" + productId);
            let discountField = $(".product_discount" + productId);
            let subtotal = quantity * price - discount;

            let costPrice =
                parseFloat($(".variant_cost_price" + productId).val()) || 0;
            let salePrice =
                parseFloat($(".variant_sale_price" + productId).val()) || 0;
            // console.log(salePrice);
            let promotion = $(".promotion_type" + productId).val() || null;
            let discountValue =
                parseFloat($(".discount_value" + productId).val()) || 0;

            if (promotion) {
                if (promotion == "percentage") {
                    subtotal -= (subtotal * discountValue) / 100;
                } else {
                    subtotal -= discountValue;
                }
            }
            // console.log('sale price',price)
            // console.log('Cost Price',costPrice)
            console.log(price < costPrice);
            if (price < costPrice) {
                if (sale_with_low_price == 1) {
                    // Swal.fire({
                    //     title: "Are you sure?",
                    //     text: `You want to sell this product at a lower price? The product cost price is ${costPrice}.`,
                    //     icon: "warning",
                    //     showCancelButton: true,
                    //     confirmButtonText: "Yes, I want!",
                    // }).then((result) => {
                    //     if (!result.isConfirmed) {
                    // unitPriceField.val(salePrice);
                    subtotal = price * quantity - discount;
                    // }
                    productSubtotal.val(subtotal.toFixed(2));
                    // });
                } else {
                    toastr.warning("Cannot sell at a lower price.");
                    // unitPriceField.val(salePrice);
                    subtotal = price * quantity - discount;
                }
            } else {
                productSubtotal.val(subtotal.toFixed(2));
            }

            if (subtotal < costPrice) {
                if (sale_with_low_price == 1) {
                    // Swal.fire({
                    //     title: "Are you sure?",
                    //     text: "you want to sell this product at a lower price?",
                    //     icon: "warning",
                    //     showCancelButton: true,
                    //     confirmButtonText: "Yes, I want!",
                    // }).then((result) => {
                    //     if (!result.isConfirmed) {
                    // discountField.val(0);
                    // unitPriceField.val(salePrice);
                    subtotal = price * quantity - discount;

                    productSubtotal.val(subtotal.toFixed(2));
                } else {
                    toastr.warning("Cannot sell at a lower price.");
                    // unitPriceField.val(salePrice);
                    subtotal = price * quantity - discount;
                }
            } else {
                productSubtotal.val(subtotal.toFixed(2));
            }
        });
        calculateInvoiceTotal();
    }

    // Prevent negative values directly in input fields //
    $(document).on(
        "input",
        ".unit_price, .quantity, .discountProduct",
        function () {
            let value = parseFloat($(this).val()) || 0;

            if (value < 0) {
                $(this).val(1);
                toastr.warning("Negetive Value are not allowed");
            }
        }
    );

    // Trigger calculation when price, quantity, or discount changes
    $(document).on(
        "keyup",
        ".unit_price, .quantity, .discountProduct",
        function () {
            calculateProductTotal();
            calculateInvoiceTotal();
            updateTotalQuantity();
        }
    );
    // Function to calculate the Invoice total
    function calculateInvoiceTotal() {
        // alert("Ok");
        let productTotal = $(".total");
        let handsOnDiscount = parseFloat($(".handsOnDiscount").val()) || 0;
        // console.log(checkTax == 1);
        let tax = checkTax == 1 ? taxPercentage : 0;
        let invoiceTotalField = $(".invoice_total");
        let previousDue =
            invoicePayment == 0 ? parseFloat($(".previous_due").val()) || 0 : 0;
        let grandTotalField = $(".grandTotal");
        // console.log(grandTotalField);
        let totalPayable = parseFloat($(".total_payable").val()) || 0;
        let totalPayableField = $(".total_payable");
        let total_due = $(".total_due");
        let taxField = $(".tax");

        let allProductTotal = document.querySelectorAll("#productTotal");
        let allTotal = 0;
        allProductTotal.forEach((product) => {
            let productValue = parseFloat(product.value);
            if (!isNaN(productValue)) {
                allTotal += productValue;
            }
        });

        productTotal.val(allTotal.toFixed(2));
        //handson discount percentage and fiexed
        // console.log()
        const discountType =
            discountCheck === "1" ? $(".sale_discount_type").val() : null;
        const discountValue =
            discountCheck === "1"
                ? parseFloat($(".handsOnDiscount").val()) || 0
                : 0;
        // console.log(discountValue)
        // console.log(discountType)
        let discountTotal = allTotal;
        if (discountType === "fixed") {
            discountTotal -= discountValue;
        } else if (discountValue > 0) {
            if (discountValue > 100) {
                toastr.warning("Please select a number between 1 and 100");
                $(".handsOnDiscount").val(0);
            } else {
                // console.log("worked");
                discountTotal -= (allTotal * discountValue) / 100;
            }
        }
        //handson discount percentage and fiexed
        // let discountTotal = allTotal - handsOnDiscount;

        // if tax option is on
        let taxTotal = checkTax == 1 ? (discountTotal * tax) / 100 : 0;
        taxField.val(taxTotal.toFixed(2));

        let invoiceTotal = discountTotal + taxTotal;
        invoiceTotalField.val(invoiceTotal.toFixed(2));
        const grandTotal = previousDue + invoiceTotal;
        grandTotalField.val(grandTotal.toFixed(2));
        let due = grandTotal - totalPayable;

        if (invoicePayment === "1") {
            if (due > 0) {
                $(".total_due").val(due.toFixed(2));
                $(".due_text").text("Due Amount");
            } else {
                $(".total_due").val(-due.toFixed(2));
                $(".due_text").text("Return Amount");
            }
        } else {
            if (due > 0) {
                $(".total_due").val(due.toFixed(2));
                $(".due_text").text("Due Amount");
            } else {
                $(".total_due").val(-due.toFixed(2));
                $(".due_text").text("Advance Amount");
            }
        }
    }

    $(".sale_discount_type").on("change", function () {
        calculateInvoiceTotal();
    });
    $(".handsOnDiscount, .total_payable").on("keyup", function () {
        let value = parseFloat($(this).val()) || 0;
        // console.log(value);
        if (value < 0) {
            $(this).val(0);
            toastr.warning("Negative values are not allowed");
        }
        calculateInvoiceTotal();
        // handsonDiscountField();
    });

    // Product add with barcode
    $(".barcode_input").change(function () {
        selectedCustomerId = $(".select-customer").val();
        if (!selectedCustomerId) {
            // console.log("No customer selected");
            alert("Please select a customer first.");
            return;
        }
        let barcode = $(this).val();
        $.ajax({
            url: "/variant/barcode/find/" + barcode,
            type: "GET",
            data: {
                selectedCustomerId: selectedCustomerId,
            },
            dataType: "JSON",
            success: function (res) {
                saleItemsPrice = res.saleItemsPrice;
                if (res.status == 200) {
                    showAddProduct(res.variant, 1, saleItemsPrice);
                    calculateProductTotal();
                    updateTotalQuantity();
                    calculateInvoiceTotal();
                    $(".barcode_input").val("");
                } else {
                    toastr.warning(res.error);
                    $(".barcode_input").val("");
                }
            },
        });
    });

    // Select Product
    $(".product_select").change(function () {
        selectedCustomerId = $(".select-customer").val();
        if (!selectedCustomerId) {
            // console.log("No customer selected");
            alert("Please select a customer first.");
            return;
        }
        // showSpinner();
        let id = $(this).val();
        $.ajax({
            url: "/product/find/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (res) {
                // hideSpinner();
                const product = res.data;
                // console.log(product);
                selectedCustomerId = $(".select-customer").val();

                if (product.variations.length > 1) {
                    $("#varientModal").modal("show");
                    showVariants(product.variations);
                } else {
                    // console.log('product',product);
                    // console.log('variation',product.variations[0].id);
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
        // Clear previous variants (if any
        //update for active status
        // console.log(variants)

        $(".show_variants").empty();
        if (!variants || variants.length === 0) {
            $(".show_variants").append(
                '<p class="text-center">No active variants available.</p>'
            );
            return;
        }
        // console.log(variants);
        // Loop through each variant and append to the modal
        variants.forEach((variant) => {
            // console.log(variant.stocks);
            const allStock = variant.stocks;
            // console.log(allStock);
            const totalStock = Array.isArray(allStock)
                ? allStock.reduce(
                      (sum, stock) => sum + (stock.stock_quantity || 0),
                      0
                  )
                : 0;
            const imageUrl = variant?.image
                ? `/uploads/products/${variant.image}`
                : "/dummy/image.jpg";
            // const imageUrl = variant.image || '/dummy/image.jpg';
            $(".show_variants").append(`
                <div class="col-md-4 p-2 text-center addVariant cursor-pointer" data-active="false" data-id="${
                    variant.id
                }">
                    <div class="border rounded overflow-hidden pb-2">
                        <img src="${imageUrl}" class="img-fluid"
                            style="object-fit: cover; height: 120px; width: 100%" alt="variant image">
                        <div class="mt-2">
                            <p class="">${
                                priceType === "b2b_price"
                                    ? variant.b2b_price
                                    : variant.b2c_price ?? 0
                            } <span> (${totalStock} pc )</span></p>
                            <p class="">${
                                variant?.variation_size?.size ?? ""
                            }</p>
                            <p class="">${variant?.color_name?.name ?? ""}</p>
                        </div>
                    </div>
                </div>
            `);
        });
    }

    // Handle variant selection
    $(document).on("click", ".addVariant", function () {
        // Toggle the 'active' class and data-active attribute for the clicked variant
        $(this).toggleClass("active");
        const isActive = $(this).hasClass("active");
        $(this).attr("data-active", isActive ? "true" : "false");
    });

    // Add variant selection
    $(document).on("click", ".add_all_variants", function () {
        // Find all active variants
        const activeVariants = $('.addVariant[data-active="true"]');

        // Check if any variant is selected
        if (activeVariants.length === 0) {
            alert("Please select at least one variant.");
            return;
        }

        selectedCustomerId = $(".select-customer").val();
        if (!selectedCustomerId) {
            // console.log("No customer selected");
            alert("Please select a customer first.");
            return;
        }
        activeVariants.each(function () {
            const variantId = $(this).attr("data-id");
            fetchVariantDetails(variantId, selectedCustomerId); // Call fetchVariantDetails for each active variant
        });

        // Hide the modal after processing
        $("#varientModal").modal("hide");
    });

    // let customerId = $(".select-customer").val();
    // console.log(customerId);
    // Fetch variant details via AJAX
    function fetchVariantDetails(variantId, selectedCustomerId, isProduct) {
        // console.log(selectedCustomerId)
        $.ajax({
            url: `/variant/find/${variantId}`, // Create this endpoint in your backend
            type: "GET",
            data: {
                isProduct: isProduct,
                selectedCustomerId: selectedCustomerId,
            },
            dataType: "JSON",
            success: function (res) {
                const variant = res.variant;
                // console.log(variant);

                const saleItemsPrice = res.saleItemsPrice;
                // console.log(saleItemsPrice);
                // const promotion = res.promotion;
                showAddProduct(variant, 1, saleItemsPrice); // Add the selected variant to the product list
                calculateProductTotal();
                updateTotalQuantity();
            },
            error: function (error) {
                console.error("Error fetching variant details:", error);
            },
        });
    }

    // Purchase delete
    $(document).on("click", ".purchase_delete", function (e) {
        let id = $(this).attr("data-id");
        let dataRow = $(".data_row" + id);
        dataRow.remove();
        calculateProductTotal();
        updateTotalQuantity();
    });

    // view Customer Details
    function viewCustomerDetails(customer) {
        $(".show_customer_details").html(`

            <div class="col-lg-6 d-flex m-0 p-0 align-items-center ">
                <label for="exampleInputUsername2" class="col-form-label"> Previous Due</label>
                <span> : </span>
                <label for="exampleInputUsername2" class="col-form-label px-4"> ${
                    customer?.wallet_balance ?? "00"
                }</label>
            </div>
         `);
    }

    // Customer previous Due
    $(document).on("change", ".select-customer", function () {
        let id = $(this).val();
        if (id) {
            customerDueShow(id);
        }
    });

    function customerDueShow(id) {
        $.ajax({
            url: `/sale/customer/due/${id}`,
            type: "GET",
            dataType: "JSON",
            success: function (res) {
                const customer = res.customer;
                if (invoicePayment == 1) {
                    viewCustomerDetails(customer);
                } else {
                    $(".previous_due").val(customer.wallet_balance ?? 0);
                    calculateProductTotal();
                }
            },
        });
    }

    // console.log(checkPrintType);

    function saveInvoice() {
        let customer_id = $(".select-customer").val();
        let affiliator_id = $(".affiliator_select").val();
        let commission_state = $(".commission_state").val();
        let sale_date = $(".purchase_date").val();
        let formattedSaleDate = moment(sale_date, "DD-MMM-YYYY").format(
            "YYYY-MM-DD HH:mm:ss"
        );
        let quantity = totalQuantity;
        let product_total = parseFloat($(".total").val()) || 0;
        let actual_discount = parseFloat($(".handsOnDiscount").val()) || 0;
        let discount = parseFloat($(".handsOnDiscount").val()) || 0;
        let tax = (checkTax == 1 ? parseFloat("{{ $taxPercentage }}") : 0) || 0;
        let invoice_total = parseFloat($(".invoice_total").val()) || 0;
        let previous_due = $(".previous_due").val() || 0;
        let grand_total = parseFloat($(".grandTotal").val());
        let paid = parseFloat($(".total_payable").val()) || 0;
        let due = grand_total - paid;
        let note = $(".note").val();
        let payment_method = $(".payment_method").val();

        let invoice_number = $(".generate_invoice").val();
        // let product_id = $('.product_id').val();
        // console.log(total_quantity);
        let sale_discount_type = $(".sale_discount_type").val();
        // console.log()
        let variants = [];
        let isValid = true;
        let rowNumber = 0;

        $('tr[class^="data_row"]').each(function () {
            rowNumber++;
            let row = $(this);

            // Get values from the current row's elements
            let variant_id = row.find(".product_id").val();
            // console.log(variant_id);
            let quantity = row.find('input[name="quantity[]"]').val();
            let unit_price =
                parseFloat(row.find('input[name="unit_price[]"]').val()) || 0;
            let variant_cost_price =
                parseFloat(
                    row.find('input[name="variant_cost_price[]"]').val()
                ) || 0;

            if (!quantity || quantity <= 0) {
                toastr.warning("Quantity cannot be empty or zero.");
                row.find('input[name="quantity[]"]').focus();
                return false; // Breaks out of the loop
            }
            if (sale_with_low_price == 0) {
                if (unit_price < variant_cost_price) {
                    toastr.warning(
                        `Cannot sell at a lower price than the cost price. price in row ${rowNumber}.`
                    );
                    row.find('input[name="unit_price[]"]').focus();
                    isValid = false;
                    return false; // Break the loop
                }
            }

            // Prevent form submission if validation fails

            let product_discount =
                row.find('input[name="product_discount[]"]').val() || 0;
            // let wa_status = row
            //     .find(`.warranty_status${variant_id}`)
            //     .is(":checked")
            //     ? 1
            //     : 0;
            // let wa_duration = row.find(`.wa_duration${variant_id}`).val();
            // let discount_amount =
            //     row
            //         .find(`.discount_amount${variant_id}`)
            //         .text()
            //         .replace("Tk", "") || 0;
            //     console.log(discount_amount);
            //  let discount_percentage =
            //     row
            //         .find(`.discount_percentage${variant_id}`)
            //         .text()
            //         .replace("%", "") || 0;
            // let productDiscount = row
            //     .find(`.product_discount${variant_id}`)
            //     .val();
            let total_price = row.find('input[name="total_price[]"]').val();

            // let product_discount =
            //     discount_amount || discount_percentage
            //         ? discount_amount
            //             ? discount_amount
            //             : discount_percentage
            //         : productDiscount
            //             ? productDiscount
            //             : 0;
            let variant = {
                variant_id,
                quantity,
                unit_price,
                // wa_status,
                // wa_duration,
                product_discount,
                total_price,
            };

            // Push the object into the products array
            variants.push(variant);
        });
        if (!isValid) {
            hideSpinner(); // Hide spinner if validation fails
            return false; // Stop further processing
        }
        let allData = {
            // for purchase table
            customer_id,
            affiliator_id,
            commission_state,
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
        };

        // console.log(allData);
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            url: "/sale/store",
            type: "POST",
            data: allData,
            success: function (res) {
                // console.log(res);
                if (res.status == 201) {
                    toastr.success(res.message);

                    let id = res.saleId;
                    let printFrame = $("#printFrame")[0];

                    function handlePrintAndRedirect(url) {
                        $("#printFrame").attr("src", url);
                        printFrame.onload = function () {
                            printFrame.contentWindow.focus();
                            printFrame.contentWindow.print();
                            hideSpinner();
                            if (checkalertStock == 1) {
                                if (
                                    res.lowStockVariants &&
                                    res.lowStockVariants.length > 0
                                ) {
                                    let lowStockMessage = "<ul>";
                                    res.lowStockVariants.forEach(function (
                                        variant
                                    ) {
                                        const productName = variant.product
                                            ? variant.product.name
                                            : "";
                                        const colorName = variant.color
                                            ? variant.color.name
                                            : "";
                                        const sizeName = variant.size
                                            ? variant.size.name
                                            : "";
                                        lowStockMessage += `<li>${productName} (Color: ${colorName}, Size: ${sizeName}): Stock (${variant.stock_quantity}) below alert (${variant.low_stock_alert})</li>`;
                                    });
                                    lowStockMessage += "</ul>";

                                    // Show SweetAlert and set onafterprint after "OK" is clicked
                                    Swal.fire({
                                        icon: "warning",
                                        title: "Low Stock Alert",
                                        html: lowStockMessage,
                                        confirmButtonText: "OK",
                                    }).then((result) => {
                                        // When "OK" is clicked
                                        if (result.isConfirmed) {
                                            // Set the onafterprint event handler to redirect after printing
                                            printFrame.contentWindow.onafterprint =
                                                function () {
                                                    window.location.href =
                                                        "/sale/pharmacy";
                                                };

                                            if (
                                                printFrame.contentWindow
                                                    .document.readyState ===
                                                "complete"
                                            ) {
                                                window.location.href =
                                                    "/sale/pharmacy";
                                            }
                                        }
                                    });
                                } else {
                                    // If no low stock variants, set onafterprint immediately
                                    printFrame.contentWindow.onafterprint =
                                        function () {
                                            window.location.href =
                                                "/sale/pharmacy";
                                        };
                                }
                            } else {
                                // If checkalertStock != 1, set onafterprint immediately
                                printFrame.contentWindow.onafterprint =
                                    function () {
                                        window.location.href = "/sale/pharmacy";
                                    };
                            }
                        };
                    }

                    if (make_invoice_print == 1) {
                        switch (checkPrintType) {
                            case "a4":
                            case "a5":
                                handlePrintAndRedirect("/sale/invoice/" + id);
                                break;
                            default:
                                handlePrintAndRedirect("/sale/print/" + id);
                                break;
                        }
                    } else {
                        hideSpinner();
                        window.location.href = "/sale/pharmacy";
                    }
                    // $(window).off('beforeunload');
                } else {
                    hideSpinner();
                    // console.log(res);
                    if (res.error.customer_id) {
                        showError(".select-customer", res.error.customer_id);
                    }
                    if (res.error.sale_date) {
                        showError(".purchase_date", res.error.sale_date);
                    }
                    if (res.error.payment_method) {
                        showError(".payment_method", res.error.payment_method);
                    }
                    if (res.error.paid) {
                        showError(".total_payable", res.error.paid);
                    }
                    if (res.error.variants) {
                        toastr.warning("Please Select a Variant to sell");
                    }
                    // if (res.error.quantity) {
                    //     toastr.warning("Please Enter a minimum quantity of 1.");
                    // }
                    if (res.error.invoice_number) {
                        showError(
                            ".generate_invoice",
                            res.error.invoice_number
                        );
                    }
                }
            },
            error: function (xhr, status, error) {
                // console.log(`xhr: ${xhr}, status: ${status}, error: ${error}`);
                hideSpinner();
                if (xhr.status === 404) {
                    toastr.warning("Error: Variant not found.");
                } else if (xhr.status === 500) {
                    toastr.warning("Error: An unexpected error occurred.");
                } else {
                    toastr.warning("Error: " + xhr.statusText);
                }
            },
        });
    }

    const total_payable = document.querySelector(".total_payable");
    total_payable.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            showSpinner();
            saveInvoice();
        }
    });
    // order btn
    $(".payment_btn").click(function (e) {
        e.preventDefault();
        showSpinner();
        saveInvoice();

        // alert("hi");
    });
    // draft_invoice
    $(".draft_invoice").click(function (e) {
        e.preventDefault();
        showSpinner();
        saveDraftInvoice();
    });

    function saveDraftInvoice() {
        let customer_id = $(".select-customer").val();
        let sale_date = $(".purchase_date").val();
        let formattedSaleDate = moment(sale_date, "DD-MMM-YYYY").format(
            "YYYY-MM-DD HH:mm:ss"
        );
        let quantity = totalQuantity;
        let product_total = parseFloat($(".total").val()) || 0;
        let actual_discount = parseFloat($(".handsOnDiscount").val()) || 0;
        let discount = parseFloat($(".handsOnDiscount").val()) || 0;
        let tax = (checkTax == 1 ? parseFloat("{{ $taxPercentage }}") : 0) || 0;
        let invoice_total = parseFloat($(".invoice_total").val()) || 0;
        let previous_due = $(".previous_due").val() || 0;
        let grand_total = parseFloat($(".grandTotal").val());
        let paid = parseFloat($(".total_payable").val()) || 0;
        let due = grand_total - paid;
        let note = $(".note").val();
        let payment_method = $(".payment_method").val();
        let sale_discount_type = $(".sale_discount_type").val();
        let invoice_number = $(".generate_invoice").val();
        // let product_id = $('.product_id').val();
        // console.log(total_quantity);

        let variants = [];
        let isValid = true;
        let rowNumber = 0;
        $('tr[class^="data_row"]').each(function () {
            rowNumber++;
            let row = $(this);
            // Get values from the current row's elements
            let variant_id = row.find(".product_id").val();
            let quantity = row.find('input[name="quantity[]"]').val();
            let unit_price =
                parseFloat(row.find('input[name="unit_price[]"]').val()) || 0;
            let variant_cost_price =
                parseFloat(
                    row.find('input[name="variant_cost_price[]"]').val()
                ) || 0;
            // let wa_status = row
            //     .find(`.warranty_status${variant_id}`)
            //     .is(":checked")
            //     ? 1
            //     : 0;
            if (sale_with_low_price == 0) {
                if (unit_price < variant_cost_price) {
                    toastr.warning(
                        `Cannot sell at a lower price than the cost price. Error in row ${rowNumber}.`
                    );
                    row.find('input[name="unit_price[]"]').focus();
                    isValid = false;
                    return false; // Break the loop
                }
            }
            let product_discount =
                row.find('input[name="product_discount[]"]').val() || 0;
            // let wa_duration = row.find(`.wa_duration${variant_id}`).val();
            // let discount_amount =
            //     row
            //         .find(`.discount_amount${variant_id}`)
            //         .text()
            //         .replace("Tk", "") || 0;
            // let discount_percentage =
            //     row
            //         .find(`.discount_percentage${variant_id}`)
            //         .text()
            //         .replace("%", "") || 0;
            // let productDiscount = row
            //     .find(`.product_discount${variant_id}`)
            //     .val();
            let total_price = row.find('input[name="total_price[]"]').val();

            // let product_discount =
            //     discount_amount || discount_percentage
            //         ? discount_amount
            //             ? discount_amount
            //             : discount_percentage
            //         : productDiscount
            //             ? productDiscount
            //             : 0;
            let variant = {
                variant_id,
                quantity,
                unit_price,
                // wa_status,
                // wa_duration,
                product_discount,
                total_price,
            };

            // Push the object into the products array
            variants.push(variant);
        });
        if (!isValid) {
            hideSpinner(); // Hide spinner if validation fails
            return false; // Stop further processing
        }
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
        };

        // console.log(allData);
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            url: "/sale/draft",
            type: "POST",
            data: allData,
            success: function (res) {
                // console.log(res);
                if (res.status == 201) {
                    toastr.success(res.message);
                    hideSpinner();
                    window.location.reload();
                } else {
                    hideSpinner();
                    console.log(res);
                    if (res.error.customer_id) {
                        showError(".select-customer", res.error.customer_id);
                    }
                    if (res.error.sale_date) {
                        showError(".purchase_date", res.error.sale_date);
                    }
                    if (res.error.variants) {
                        toastr.warning("Please Select a Variant to sell");
                    }
                    if (res.error.invoice_number) {
                        showError(
                            ".generate_invoice",
                            res.error.invoice_number
                        );
                    }
                }
            },
            error: function (xhr, status, error) {
                // console.log(`xhr: ${xhr}, status: ${status}, error: ${error}`);
                hideSpinner();
                if (xhr.status === 404) {
                    toastr.warning("Error: Variant not found.");
                } else if (xhr.status === 500) {
                    toastr.warning("Error: An unexpected error occurred.");
                } else {
                    toastr.warning("Error: " + xhr.statusText);
                }
            },
        });
    }
});
