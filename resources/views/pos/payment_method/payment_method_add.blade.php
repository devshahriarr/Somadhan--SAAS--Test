@extends('master')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Payment Method</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title">Payment Method Table</h6>
                        <button class="btn btn-rounded-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#exampleModalLongScollable"><i data-feather="plus"></i></button>
                    </div>
                    <div id="" class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Name</th>
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
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Add Payement Method</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="paymentMethodForm row">
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Payement Method Name</label>
                            <input id="defaultconfig" class="form-control payment_method_name" maxlength="39" name="name"
                                type="text" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger payment_method_name_error"></span>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_payment_method">Save</button>
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
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Edit Payment Method</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="signupForm" class="editPaymentMethodForm">
                        <div class="mb-3">
                            <label for="name" class="form-label">Payment Method Name</label>
                            <input id="defaultconfig" class="form-control edit_payment_method_name" maxlength="39"
                                name="name" type="text" onkeyup="errorRemove(this);" onblur="errorRemove(this);">
                            <span class="text-danger edit_payment_method_name_error"></span>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary update_payment_method">Update</button>
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
            // save save Payment Method
            const savePayment = document.querySelector('.save_payment_method');
            savePayment.addEventListener('click', function(e) {
                e.preventDefault();
                let formData = new FormData($('.paymentMethodForm')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/payment/method/store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            $('#exampleModalLongScollable').modal('hide');
                            $('.paymentMethodForm')[0].reset();
                            paymentMethodView();
                            toastr.success(res.message);
                        } else {
                            if (res.error.name) {
                                showError('.payment_method_name', res.error.name);
                            }

                        }
                    }
                });
            });


            //    show Payment Method
            function paymentMethodView() {
                $.ajax({
                    url: '/payment/method/view',
                    method: 'GET',
                    success: function(res) {
                        const paymentMethod = res.data;
                        $('.showData').empty();

                        if (paymentMethod.length > 0) {
                            $.each(paymentMethod, function(index, paymentMethod) {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                            <td>
                                ${index+1}
                            </td>
                            <td>
                                ${paymentMethod.name ?? ""}
                            </td>

                            <td>
                                <a href="#" class="btn btn-primary btn-icon payment_method_edit" data-id=${paymentMethod.id} data-bs-toggle="modal" data-bs-target="#edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-icon payment_method_delete" data-id=${paymentMethod.id}>
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
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
                                            Payment Method<i data-feather="plus"></i></button>
                                    </div>
                                </td>
                            </tr>`)
                        }

                    }
                })
            }
            paymentMethodView();

            // edit Payment Method
            $(document).on('click', '.payment_method_edit', function(e) {
                e.preventDefault();
                // console.log('0k');
                let id = this.getAttribute('data-id');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: `/payment/method/edit/${id}`,
                    type: 'GET',
                    success: function(res) {
                        if (res.status == 200) {
                            $('.edit_payment_method_name').val(res.paymentMethod.name);
                            $('.update_payment_method').val(res.paymentMethod.id);
                        } else {
                            toastr.warning("No Data Found");
                        }
                    }
                });
            })

            //  update Payment Method
            $('.update_payment_method').click(function(e) {
                e.preventDefault();
                // alert('ok');
                let id = $(this).val();
                // console.log(id);
                let formData = new FormData($('.editPaymentMethodForm')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: `/payment/method/update/${id}`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 200) {
                            $('#edit').modal('hide');
                            $('.editPaymentMethodForm')[0].reset();
                            paymentMethodView();
                            toastr.success(res.message);
                        } else {
                            if (res.error.name) {
                                showError('.edit_payment_method_name', res.error.name);
                            }
                        }
                    }
                })
            });

            // Payment Method Delete
            $(document).on('click', '.payment_method_delete', function(e) {
                $('.payment_method_delete').click(function(e) {
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
                                url: `/payment/method/delete/${id}`,
                                type: 'GET',
                                success: function(data) {
                                    if (data.status == 200) {
                                        Swal.fire({
                                            title: "Deleted!",
                                            text: "Your file has been deleted.",
                                            icon: "success"
                                        });
                                        paymentMethodView();
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
