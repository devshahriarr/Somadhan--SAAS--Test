@extends('master')
@section('title','| Tax')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Taxes</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title">Tax Table</h6>
                        @if (Auth::user()->can('tax.add'))
                        <button class="btn btn-rounded-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#exampleModalLongScollable"><i data-feather="plus"></i></button>
                        @endif
                    </div>
                    <div id="" class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Tax Name</th>
                                    <th>Percentage</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="showData">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModalLongScollable" tabindex="-1" aria-labelledby="exampleModalScrollableTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Add Tax</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="taxForm row">
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Tax Name</label>
                            <input id="defaultconfig" class="form-control tax_name" maxlength="39" name="name"
                                type="text" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger tax_name_error"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Percentage</label>
                            <input id="defaultconfig" class="form-control tax_percentage" maxlength="39" name="percentage"
                                type="number" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger tax_percentage_error"></span>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_tax">Save</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Edit Tax</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="editTaxForm">
                        <div class="mb-3">
                            <label for="name" class="form-label">Tax Name</label>
                            <input id="defaultconfig" class="form-control edit_tax_name" maxlength="39" name="name"
                                type="text" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger edit_tax_name_error"></span>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Percentage</label>
                            <input id="defaultconfig" class="form-control edit_tax_percentage" maxlength="39"
                                name="percentage" type="number" onkeyup="errorRemove(this);"
                                onblur="errorRemove(this);">
                            <span class="text-danger edit_tax_percentage_error"></span>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary update_tax">Update</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // error remove
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
            // save tax
            const saveTax = document.querySelector('.save_tax');
            saveTax.addEventListener('click', function(e) {
                e.preventDefault();
                let formData = new FormData($('.taxForm')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/tax/store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            $('#exampleModalLongScollable').modal('hide');
                            $('.taxForm')[0].reset();
                            taxView();
                            toastr.success(res.message);
                        } else {
                            if (res.error.name) {
                                showError('.tax_name', res.error.name);
                            }
                            if (res.error.percentage) {
                                showError('.tax_percentage', res.error.percentage);
                            }

                        }
                    }
                });
            });


            // show tax
            function taxView() {
                $.ajax({
                    url: '/tax/view',
                    method: 'GET',
                    success: function(res) {
                        const tax = res.data;
                        $('.showData').empty();

                        if (tax.length > 0) {
                            $.each(tax, function(index, tax) {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                            <td>
                                ${index+1}
                            </td>
                            <td>
                                ${tax.name ?? ""}
                            </td>
                            <td>
                                ${tax.percentage ?? ""}<span> %</span>
                            </td>

                            <td>
                                @can('tax.edit')
                                <a href="#" class="btn btn-primary btn-icon tax_edit" data-id=${tax.id} data-bs-toggle="modal" data-bs-target="#edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                  @endcan
                                  @can('tax.delete')
                                <a href="#" class="btn btn-danger btn-icon tax_delete" data-id=${tax.id}>
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                                @endcan
                            </td>
                            `;
                                $('.showData').append(tr);
                            })
                        } else {
                            $('.showData').html(`
                            <tr>
                                <td colspan='8'>
                                    <div class="text-center text-warning mb-2">Data Not Found</div>
                                    <div class="text-center">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalLongScollable">Add
                                            Tax<i data-feather="plus"></i></button>
                                    </div>
                                </td>
                            </tr>`)
                        }

                    }
                })
            }
            taxView();

            // edit Tax
            $(document).on('click', '.tax_edit', function(e) {
                e.preventDefault();
                // console.log('0k');
                let id = this.getAttribute('data-id');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: `/tax/edit/${id}`,
                    type: 'GET',
                    success: function(res) {
                        if (res.status == 200) {
                            $('.edit_tax_name').val(res.tax.name);
                            $('.edit_tax_percentage').val(res.tax.percentage);
                            $('.update_tax').val(res.tax.id);
                        } else {
                            toastr.warning("No Data Found");
                        }
                    }
                });
            })

            //  update tax
            $('.update_tax').click(function(e) {
                e.preventDefault();
                // alert('ok');
                let id = $(this).val();
                // console.log(id);
                let formData = new FormData($('.editTaxForm')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: `/tax/update/${id}`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            $('#edit').modal('hide');
                            $('.editTaxForm')[0].reset();
                            taxView();
                            toastr.success(res.message);
                        } else {
                            if (res.error.name) {
                                showError('.edit_tax_name', res.error.name);
                            }
                            if (res.error.percentage) {
                                showError('.edit_tax_percentage', res.error.percentage);
                            }
                        }
                    }
                })
            });

            // tax Delete
            $(document).on('click', '.tax_delete', function(e) {
                $('.tax_delete').click(function(e) {
                    e.preventDefault();
                    let id = this.getAttribute('data-id');

                    Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to Delete this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                        .attr('content')
                                }
                            });
                            $.ajax({
                                url: `/tax/delete/${id}`,
                                type: 'GET',
                                success: function(data) {
                                    if (data.status == 200) {
                                        Swal.fire({
                                            title: "Deleted!",
                                            text: "Your file has been deleted.",
                                            icon: "success"
                                        });
                                        taxView();
                                    } else {
                                        Swal.fire({
                                            position: "top-end",
                                            icon: "warning",
                                            title: "Deleted Unsuccessful!",
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                    }


                                }
                            });
                        }
                    });
                })
            });
        });
    </script>
@endsection
