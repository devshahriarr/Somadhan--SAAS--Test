@extends('master')
@section('title', '| Money Receipt')
@section('admin')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card filter_box">
            <div class="card">
                <div class="card-body">
                    <div class="show_doc">
                        @if ($purchase->document)
                            @php
                                // Get file extension and convert it to lowercase
                                $fileExtension = strtolower(pathinfo($purchase->document, PATHINFO_EXTENSION));
                            @endphp

                            @if ($fileExtension !== 'pdf')
                                <!-- If the document is an image -->
                                <img id="printableImage" src="{{ asset('uploads/purchase/' . $purchase->document) }}"
                                    width="100%" height="500px" alt="Image" />
                                <button class="btn btn-primary mt-3" id="printImageBtn">
                                    <i class="fa-solid fa-print"></i> Print
                                </button>
                            @else
                                <!-- If the document is a PDF -->
                                <iframe src="{{ asset('uploads/purchase/' . $purchase->document) }}" width="100%"
                                    height="500px"></iframe>
                            @endif
                        @else
                            <p>No document available</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .page-content {
                margin-top: 0 !important;
                padding-top: 0 !important;
            }



            nav,
            .footer {
                display: none !important;
            }

            /* Hide elements that are not needed for printing */
            button {
                display: none !important;
            }

            /* Ensure the image takes up the full width on print */
            img {
                max-width: 100% !important;
                height: auto !important;
            }

            /* Ensure the image's container is not hidden */
            .show_doc {
                display: block !important;
            }
        }
    </style>

    <script>
        document.getElementById('printImageBtn')?.addEventListener('click', function() {
            let id = '{{ $purchase->id }}';
            // console.log(id);
            $.ajax({
                url: "/purchase/image/" + id,
                method: 'GET',
                success: function(res) {
                    // console.log(res);
                    if (res.status == 200) {
                        window.print();
                    } else {
                        toastr.warning('Something went Wrong');
                    }
                }
            });

        });
    </script>
@endsection
