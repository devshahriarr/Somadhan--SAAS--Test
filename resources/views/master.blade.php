<!DOCTYPE html>
<html lang="en">

<head>
    @include('body.css')
</head>

<body>
    <div class="main-wrapper {{ Route::currentRouteName() === 'sale.new' ? 'no-sidebar' : '' }}">

        <!-- partial:partials/_sidebar.html -->
        @if (Route::currentRouteName() !== 'sale.new')
            @include('body.sidebar')
            <!-- partial -->
        @endif
        <div class="page-wrapper ">

            <!-- partial:partials/_navbar.html -->
            @include('body.navbar')
            <!-- partial -->

            <div class="page-content">
                {{-- <form action="{{ route('el-search') }}" method="GET" class="mb-4">
                    <input type="text" name="query" placeholder="Search products..." class="border p-2 rounded">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Search</button>
                </form> --}}
                @yield('admin')
            </div>

            <!-- partial:partials/_footer.html -->
            @include('body.footer')
            <!-- partial -->

        </div>
    </div>

    {{-- spinner  --}}
    <div class="spinner-container">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <style>
        .spinner-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1050;
            /* Ensure it's above all content */
            display: none;
            /* Initially hidden */
            background-color: rgba(0, 0, 0, 0.5);
            /* Optional: Semi-transparent background */
            width: 100vw;
            height: 100vh;
            justify-content: center;
            align-items: center;
        }

        .main-wrapper.no-sidebar .page-wrapper {
            width: 100%;
            /* Full width */
            margin: 0;
            /* No margin when sidebar is hidden */
        }

        .no-head {
            left: 0 !important;
            width: 100% !important
        }
    </style>
    @include('body.js')

    <script>
        const spinner = document.querySelector('.spinner-container');

        // Show the spinner
        function showSpinner() {
            spinner.style.display = 'flex';
        }

        // Hide the spinner
        function hideSpinner() {
            spinner.style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const flexSwitchCheckDefault = document.querySelector('.flexSwitchCheckDefault');
            const form = document.getElementById('darkModeForm');
            if (flexSwitchCheckDefault && form) {
                flexSwitchCheckDefault.addEventListener('change', function() {
                    form.submit();
                });
            }

            // nav links active
            const links = document.querySelectorAll('.nav-link');
            links.forEach(link => {
                link.addEventListener('click', function() {
                    links.forEach(l => l.classList.remove('active'));
                    this.classList.add('active');

                    // Handle collapse behavior
                    const parentMenu = this.closest('.collapse');
                    if (parentMenu) {
                        parentMenu.classList.add('show');
                        parentMenu.previousElementSibling.setAttribute('aria-expanded', 'true');
                        parentMenu.previousElementSibling.classList.remove('collapsed');
                    }
                });

                // Ensure parent menus stay open if a child link is active
                if (link.classList.contains('active')) {
                    const parentMenu = link.closest('.collapse');
                    if (parentMenu) {
                        parentMenu.classList.add('show');
                        parentMenu.previousElementSibling.setAttribute('aria-expanded', 'true');
                        parentMenu.previousElementSibling.classList.remove('collapsed');
                    }
                }
            });
        });
        $(function() {
            'use strict'

            if ($(".compose-multiple-select").length) {
                $(".compose-multiple-select").select2();
            }

            /*easymde editor*/
            if ($("#easyMdeEditor").length) {
                var easymde = new EasyMDE({
                    element: $("#easyMdeEditor")[0]
                });
            }

        });


        const global_search = document.querySelector("#global_search");
        const search_result = document.querySelector(".search_result");
        // console.log(global_search);
        // global_search.addEventListener('keyup', function() {
        //     // console.log(global_search.value);
        //     if (global_search.value != '') {
        //         $.ajax({
        //             url: '/search/' + global_search.value,
        //             type: 'GET',
        //             success: function(res) {
        //                 console.log(res);
        //                 let findData = '';
        //                 search_result.style.display = 'block';
        //                 if (res.products.length > 0) {
        //                     $.each(res.products, function(key, value) {

        //                         let displayPrice = 0;
        //                         if (value.variations && value.variations.length > 0) {
        //                             displayPrice = (res.pos_setting && res.pos_setting
        //                                     .sale_price_type === 'b2c_price') ?
        //                                 (value.variations[0].b2c_price ?? 0) :
        //                                 (value.variations[0].b2b_price ?? 0);
        //                         }

        //                         findData += `<tr>
        //                             <td>${value.name ?? ""}</td>
        //                             <td>${value.stock_quantity_sum_stock_quantity ?? 0}</td>
        //                              <td>${displayPrice}</td>
        //                         </tr>`
        //                     });

        //                     $('.findData').html(findData);
        //                 } else {
        //                     $('.table_header').hide();
        //                     findData += `<tr>
        //                             <td colspan = "3" class = "text-center">Data not Found</td>
        //                         </tr>`
        //                     $('.findData').html(findData);
        //                 }
        //             }
        //         });
        //     } else {
        //         search_result.style.display = 'none';
        //     }
        // })
        global_search.addEventListener('keyup', function () {
            if (global_search.value.trim() !== '') {
                $.ajax({
                    url: '/search/' + encodeURIComponent(global_search.value), // Encode search value
                    type: 'GET',
                    success: function (res) {
                        console.log(res); // Debug response
                        let findData = '';
                        search_result.style.display = 'block';
                        if (res.products && res.products.length > 0) {
                            $.each(res.products, function (key, value) {
                                let displayPrice = res.pos_setting && res.pos_setting.sale_price_type === 'b2c_price' ?
                                    (value.b2c_price || 0) : (value.b2b_price || 0);

                                findData += `<tr>
                                    <td>${value.name || 'N/A'}</td>
                                    <td>${value.totalVariationStock || 0}</td>
                                    <td>${displayPrice}</td>
                                    <td>${value.relevance_score || 'N/A'}</td> <!-- Debug relevance score -->
                                </tr>`;
                            });
                            $('.findData').html(findData);
                            $('.table_header').show();
                        } else {
                            $('.table_header').hide();
                            findData = `<tr>
                                <td colspan="4" class="text-center">Data not Found</td>
                            </tr>`;
                            $('.findData').html(findData);
                        }
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr.responseJSON);
                        $('.table_header').hide();
                        $('.findData').html('<tr><td colspan="4" class="text-center">Error fetching data</td></tr>');
                    }
                });
            } else {
                search_result.style.display = 'none';
                $('.findData').html('');
            }
        });

        global_search.addEventListener('click', function () {
            // Same logic as keyup
            if (global_search.value.trim() !== '') {
                $.ajax({
                    url: '/search/' + encodeURIComponent(global_search.value),
                    type: 'GET',
                    success: function (res) {
                        console.log(res);
                        let findData = '';
                        search_result.style.display = 'block';
                        if (res.products && res.products.length > 0) {
                            $.each(res.products, function (key, value) {
                                let displayPrice = res.pos_setting && res.pos_setting.sale_price_type === 'b2c_price' ?
                                    (value.b2c_price || 0) : (value.b2b_price || 0);

                                findData += `<tr>
                                    <td>${value.name || 'N/A'}</td>
                                    <td>${value.totalVariationStock || 0}</td>
                                    <td>${displayPrice}</td>
                                    <td>${value.relevance_score || 'N/A'}</td>
                                </tr>`;
                            });
                            $('.findData').html(findData);
                            $('.table_header').show();
                        } else {
                            $('.table_header').hide();
                            findData = `<tr>
                                <td colspan="4" class="text-center">Data not Found</td>
                            </tr>`;
                            $('.findData').html(findData);
                        }
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr.responseJSON);
                        $('.table_header').hide();
                        $('.findData').html('<tr><td colspan="4" class="text-center">Error fetching data</td></tr>');
                    }
                });
            }
        });

        global_search.addEventListener('blur', function () {
            search_result.style.display = 'none';
        });
        ///////////////////
        function printModalContent() {

            let printContents = document.getElementById('showDataModal').innerHTML; // Get the modal content
            let originalContents = document.body.innerHTML; // Save the current body content

            // Replace the body content with the modal content and open the print dialog
            document.body.innerHTML = '<html><head><title>Print Preview</title></head><body>' + printContents +
                '</body></html>';


            window.print(); // Trigger print dialog
            // $('#invoiceModal').modal('close')
            window.location.reload();
            // Restore the original body content after printing
            document.body.innerHTML = originalContents;
        }


        function initializeDataTable() {
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
                            // Center-aligned header content with styling
                            $(win.document.body).prepend(
                                '<h4 style="text-align: center; margin: 20px 0;">' +
                                '{{ $header ?? 'Default Header' }}' + '</br>' +
                                '{{ $phone ?? '+880....' }}' + '</br>' +
                                '{{ $email ?? 'No email' }}' + '</br>' +
                                '{{ $address ?? 'No address' }}</h4>'
                            );

                            // Hide the title element (if necessary)
                            $(win.document.body).find('h1').hide();
                        }
                    }
                ]
            });
        }
    </script>

</body>

</html>
