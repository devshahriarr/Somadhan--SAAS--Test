<!-- core:js -->
<script src="{{ asset('assets') }}/vendors/core/core.js"></script>
<!-- endinject -->
<script src="{{ asset('assets/jquery/jquery.min.js') }}"></script>
<!-- Plugin js for this page -->
{{-- //new --}}
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="{{ asset('assets') }}/vendors/flatpickr/flatpickr.min.js"></script>
{{-- <script src="{{ asset('assets') }}/vendors/apexcharts/apexcharts.min.js"></script> --}}
<script src="{{ asset('assets') }}/vendors/datatables.net/jquery.dataTables.js"></script>
<script src="{{ asset('assets') }}/vendors/datatables.net-bs5/dataTables.bootstrap5.js"></script>
<script src="{{ asset('assets') }}/vendors/prismjs/prism.js"></script>
<script src="{{ asset('assets') }}/vendors/clipboard/clipboard.min.js"></script>
<script src="{{ asset('assets') }}/vendors/jquery-validation/jquery.validate.min.js"></script>
<script src="{{ asset('assets') }}/vendors/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
<script src="{{ asset('assets') }}/vendors/inputmask/jquery.inputmask.min.js"></script>
<script src="{{ asset('assets') }}/vendors/select2/select2.min.js"></script>
<script src="{{ asset('assets') }}/vendors/typeahead.js/typeahead.bundle.min.js"></script>
<script src="{{ asset('assets') }}/vendors/jquery-tags-input/jquery.tagsinput.min.js"></script>
<script src="{{ asset('assets') }}/vendors/dropzone/dropzone.min.js"></script>
<script src="{{ asset('assets') }}/vendors/dropify/dist/dropify.min.js"></script>
<script src="{{ asset('assets') }}/vendors/pickr/pickr.min.js"></script>
<script src="{{ asset('assets') }}/vendors/moment/moment.min.js"></script>
<script src="{{ asset('assets') }}/vendors/sweetalert2/sweetalert2.min.js"></script>
<script src="{{ asset('assets') }}/vendors/tinymce/tinymce.min.js"></script>
<script src="{{ asset('assets') }}/vendors/easymde/easymde.min.js"></script>
<!-- End plugin js for this page -->

<!-- inject:js -->
<script src="{{ asset('assets') }}/vendors/feather-icons/feather.min.js"></script>
<script src="{{ asset('assets') }}/js/template.js"></script>
<!-- endinject -->

<!-- Custom js for this page -->
<script src="{{ asset('assets') }}/js/dashboard-dark.js"></script>
<!-- End custom js for this page -->

<!-- Custom js for this page -->
<script src="{{ asset('assets') }}/js/data-table.js"></script>
<script src="{{ asset('assets') }}/js/form-validation.js"></script>
<script src="{{ asset('assets') }}/js/bootstrap-maxlength.js"></script>
<script src="{{ asset('assets') }}/js/inputmask.js"></script>
<script src="{{ asset('assets') }}/js/select2.js"></script>
<script src="{{ asset('assets') }}/js/typeahead.js"></script>
<script src="{{ asset('assets') }}/js/tags-input.js"></script>
<script src="{{ asset('assets') }}/js/dropzone.js"></script>
<script src="{{ asset('assets') }}/js/dropify.js"></script>
<script src="{{ asset('assets') }}/js/pickr.js"></script>
<script src="{{ asset('assets') }}/js/flatpickr.js"></script>
<script src="{{ asset('assets') }}/js/sweet-alert.js"></script>
<script src="{{ asset('assets') }}/js/tinymce.js"></script>
<script src="{{ asset('assets') }}/js/apexcharts-dark.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    @if (Session::has('message'))
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }
        toastr.success("{{ session('message') }}");
    @endif
    @if (Session::has('error'))
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }
        toastr.error("{{ session('error') }}");
    @endif
    @if (Session::has('info'))
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }
        toastr.info("{{ session('info') }}");
    @endif
    @if (Session::has('warning'))
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }
        toastr.warning("{{ session('warning') }}");
    @endif
</script>
<script src="{{ asset('assets/js/codedeletesweet.js') }}"></script>

<script src="{{ asset('assets/js/myvalidate.min.js') }}"></script>
{{-- ///Export/// --}}

<script type="text/javascript" src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>
<!-- End custom js for this page --->

<script>
    $(document).ready(function() {
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

        function dyanamicTable(tableId) {
            $(tableId).DataTable({
                order: [
                    [1, 'desc']
                ],
                columnDefs: [{
                        "defaultContent": "-",
                        "targets": "_all"
                    },
                    {

                    }
                ],
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

        function ascDataTable(tableId) {
            $(tableId).DataTable({
                order: [
                    [1, 'asc']
                ],
                columnDefs: [{
                        "defaultContent": "-",
                        "targets": "_all"
                    },
                    {

                    }
                ],
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
        dyanamicTable('#productDataTable');
        dyanamicTable('#variationDataTable');
        ascDataTable('#lowStockVariationDataTable');
        ascDataTable('#lowStocProductkDataTable');

        // sale and purchase table data table
        var table = $('#saleAndPurchaseTableData').DataTable({
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
                        columns: function(idx, data, node) {
                            return idx < table.columns().nodes().length - 1; // শেষ কলাম বাদ
                        }
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
                        columns: function(idx, data, node) {
                            return idx < table.columns().nodes().length -
                                1; // ✅ শেষ কলাম বাদ দেবে
                        }
                    },
                    customize: function(doc) {
                        var columnCount = doc.content[1].table.body[0].length;

                        // Page Orientation Auto Adjust (More Columns = Landscape)
                        doc.pageOrientation = columnCount > 6 ? 'landscape' : 'portrait';

                        // Font Size ছোট করা, যাতে সব Data Fit হয়
                        doc.defaultStyle.fontSize = columnCount > 6 ? 7 :
                            8; // Make font size smaller

                        // Header Center Aligned
                        doc.content.unshift({
                            text: '{{ $header }}\nPhone: {{ $phone ?? '+880....' }}\nEmail: {{ $email }}\nAddress: {{ $address }}',
                            fontSize: 8, // Smaller font size for header
                            alignment: 'center',
                            margin: [0, 0, 0, 10]
                        });

                        if (doc.content.length > 1 && doc.content[1].table) {
                            let tableBody = doc.content[1].table.body;

                            // Column Width Adjust (ITEMS কলামের জন্য Fixed Width)
                            let columnWidths = Array(columnCount).fill('auto');
                            columnWidths[3] = '25%'; // ITEMS কলামের জন্য বেশি জায়গা
                            doc.content[1].table.widths = columnWidths;

                            // Row Alternating Colors & Text Alignment
                            for (var i = 1; i < tableBody.length; i++) {
                                let rowColor = (i % 2 === 0) ? '#f3f3f3' : '#ffffff';
                                tableBody[i].forEach(function(cell) {
                                    if (cell) {
                                        cell.fillColor = rowColor;
                                        cell.alignment = 'center';
                                        cell.margin = [2, 2, 2, 2]; // Cell Padding
                                    }
                                });
                            }

                            // Directly adjust the font size for each header cell
                            let headerRow = doc.content[1].table.body[0]; // First row (header)
                            headerRow.forEach(function(cell) {
                                cell.fontSize =
                                    5; // Directly set font size for header cells
                            });

                            // Table Body Styling (Auto Text Wrap)
                            doc.styles.tableBodyEven = {
                                alignment: 'center',
                                fontSize: 6, // Smaller font size for body (even rows)
                                wrap: true
                            };
                            doc.styles.tableBodyOdd = {
                                alignment: 'center',
                                fontSize: 6, // Smaller font size for body (odd rows)
                                wrap: true
                            };
                        }

                        // Footer Center Aligned
                        doc.content.push({
                            text: 'Thank you for using our service!',
                            fontSize: 8, // Smaller footer font size
                            alignment: 'center',
                            margin: [0, 10, 0, 0],
                            color: '#007BFF'
                        });

                        return doc;
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    exportOptions: {
                        columns: function(idx, data, node) {
                            return idx < table.columns().nodes().length - 1; // ✅ শেষ কলাম বাদ
                        }
                    },
                    customize: function(win) {
                        $(win.document.body).css({
                            'font-size': '10px',
                            'text-align': 'center',
                            'margin': '20px'
                        });

                        var table = $(win.document.body).find('table');
                        var columnCount = table.find('tr')[0].cells.length;

                        // ✅ Page Orientation Auto Adjust
                        var style = `<style>
                                @page { size: ${columnCount > 6 ? 'landscape' : 'portrait'}; }
                                table { width: 100%; border-collapse: collapse; }
                                th, td { padding: 6px; text-align: center; border: 1px solid #ddd; }
                                th:nth-child(4), td:nth-child(4) { width: 20%; word-wrap: break-word; } /* ✅ ITEMS Field Fixed Width */
                                th, td { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; } /* ✅ Text Overflow Fix */
                            </style>`;

                        $(win.document.head).append(style);

                        // ✅ Add Header
                        $(win.document.body).prepend(`
                                <div style="text-align: center; margin-bottom: 20px;">
                                    <h2 style="margin: 0;">{{ $header }}</h2>
                                    <p style="margin: 0;">Phone: {{ $phone ?? '+880....' }}</p>
                                    <p style="margin: 0;">Email: {{ $email }}</p>
                                    <p style="margin: 0;">Address: {{ $address }}</p>
                                    <hr style="border-top: 1px solid black;">
                                </div>
                            `);

                        // ✅ Add Footer
                        $(win.document.body).append(`
                                <div style="text-align: center; margin-top: 20px;">
                                    <hr style="border-top: 1px solid black;">
                                    <p style="font-size: 10px;">Thank you for using our service!</p>
                                </div>
                            `);
                    }
                }
            ]
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
            const elements = document.querySelectorAll('.js-choice');
            elements.forEach(element => {
                new Choices(element, {
                    searchEnabled: true,
                    placeholderValue: element.getAttribute('data-placeholder') || 'Select an option',
                    searchPlaceholderValue: 'Type to search',
                    itemSelectText: '',
                    shouldSort: false,
                    removeItemButton: true ,
                });

            });
        });

</script>
