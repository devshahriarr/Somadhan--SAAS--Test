@extends('master')
@section('title', '| Services Name')
@section('admin')

<style>
    .highlighted {
        background-color: #e5e7eb;
    }
    #search-results-table tbody tr {
        cursor: pointer;
    }
</style>

    <div class="container mx-auto p-4">
        <h1 class="text-2xl mb-4">Search Products</h1>
        <div class="mb-4">
            <input type="text" id="search-input" class="w-full p-2 border rounded" placeholder="Search products..." autocomplete="off">
        </div>
        <div id="search-results" class="mb-4">
            <table id="search-results-table" class="w-full border-collapse border border-gray-300 hidden">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2">Product Name</th>
                        <th class="border p-2">Variation Name</th>
                        <th class="border p-2">Size</th>
                        <th class="border p-2">Color</th>
                        <th class="border p-2">Stock</th>
                        <th class="border p-2">Price</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <p id="no-results" class="hidden text-red-500">No results found.</p>
        </div>
        <div id="selected-item-details" class="hidden bg-white p-4 border rounded">
            <h2 class="text-xl mb-2">Selected Item Details</h2>
            <p><strong>Product Name:</strong> <span id="selected-product-name"></span></p>
            <p><strong>Variation Name:</strong> <span id="selected-variation-name"></span></p>
            <p><strong>Barcode:</strong> <span id="selected-barcode"></span></p>
            <p><strong>Price:</strong> $<span id="selected-price"></span></p>
            <p><strong>Stock:</strong> <span id="selected-stock"></span></p>
            <p><strong>Size:</strong> <span id="selected-size"></span></p>
            <p><strong>Color:</strong> <span id="selected-color"></span></p>
        </div>
        <a href="{{ url('/') }}" class="mt-4 inline-block text-blue-600">Back to Home</a>
    </div>

    <script>
        $(document).ready(function() {
            let selectedIndex = -1;
            let results = [];

            // Debounce function to limit AJAX requests
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            // Perform search
            const performSearch = debounce(function(query) {
                if (query.length === 0) {
                    $('#search-results-table').addClass('hidden');
                    $('#no-results').addClass('hidden');
                    $('#selected-item-details').addClass('hidden');
                    $('#search-results-table tbody').empty();
                    selectedIndex = -1;
                    results = [];
                    return;
                }

                $.ajax({
                    url: '{{ route("search.ajax") }}',
                    type: 'GET',
                    data: { query: query },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#search-results-table tbody').empty();
                        results = [];
                        if (response.results.length === 0) {
                            $('#search-results-table').addClass('hidden');
                            $('#no-results').removeClass('hidden');
                            $('#selected-item-details').addClass('hidden');
                            selectedIndex = -1;
                            return;
                        }

                        response.results.forEach(function(product) {
                            product.variations.forEach(function(variation) {
                                results.push({
                                    product_id: product.product_id,
                                    product_name: product.product_name,
                                    variation_id: variation.variation_id,
                                    variation_name: variation.variation_name,
                                    barcode: variation.barcode,
                                    b2c_price: variation.b2c_price,
                                    stock_quantity: variation.stock_quantity,
                                    size: variation.size,
                                    color: variation.color
                                });

                                $('#search-results-table tbody').append(`
                                    <tr data-index="${results.length - 1}">
                                        <td class="border p-2">${product.product_name}</td>
                                        <td class="border p-2">${variation.variation_name}</td>
                                        <td class="border p-2">${variation.size}</td>
                                        <td class="border p-2">${variation.color}</td>
                                        <td class="border p-2">${variation.stock_quantity}</td>
                                        <td class="border p-2">$${variation.b2c_price}</td>
                                    </tr>
                                `);
                            });
                        });

                        $('#search-results-table').removeClass('hidden');
                        $('#no-results').addClass('hidden');
                        selectedIndex = -1;
                    },
                    error: function(xhr) {
                        console.error('Search error:', xhr.responseText);
                        $('#search-results-table').addClass('hidden');
                        $('#no-results').removeClass('hidden').text('Search failed. Please try again.');
                        $('#selected-item-details').addClass('hidden');
                    }
                });
            }, 300);

            // Handle keyup event
            $('#search-input').on('keyup', function(e) {
                const query = $(this).val().trim();
                performSearch(query);
            });

            // Handle keyboard navigation
            $('#search-input').on('keydown', function(e) {
                if (results.length === 0) return;

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (selectedIndex < results.length - 1) {
                        selectedIndex++;
                        updateSelection();
                    }
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (selectedIndex > 0) {
                        selectedIndex--;
                        updateSelection();
                    }
                } else if (e.key === 'Enter' && selectedIndex >= 0) {
                    e.preventDefault();
                    selectItem();
                }
            });

            // Handle row click
            $('#search-results-table tbody').on('click', 'tr', function() {
                selectedIndex = $(this).data('index');
                updateSelection();
                selectItem();
            });

            // Update row highlighting
            function updateSelection() {
                $('#search-results-table tbody tr').removeClass('highlighted');
                if (selectedIndex >= 0) {
                    $(`#search-results-table tbody tr[data-index="${selectedIndex}"]`).addClass('highlighted');
                }
            }

            // Display selected item details
            function selectItem() {
                if (selectedIndex >= 0) {
                    const item = results[selectedIndex];
                    $('#selected-product-name').text(item.product_name);
                    $('#selected-variation-name').text(item.variation_name);
                    $('#selected-barcode').text(item.barcode);
                    $('#selected-price').text(item.b2c_price);
                    $('#selected-stock').text(item.stock_quantity);
                    $('#selected-size').text(item.size);
                    $('#selected-color').text(item.color);
                    $('#selected-item-details').removeClass('hidden');
                }
            }
        });
    </script>
@endsection
