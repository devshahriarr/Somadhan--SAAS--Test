@extends('master')
@section('title', '| SMS Marketing')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">SMS Marketing</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
            <div class="">
                <h4 class="text-right"><a href="{{ route('report.sms') }}" class="btn btn-info">SMS To Customer</a></h4>
            </div>
        </div>
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-info">SMS Marketing</h6>
                    <div class="row">
                        <div class="col-lg-6">
                            <h4>Choose Customer</h4>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="form-check-input select_all" name="check"
                                                id="">
                                            Select All
                                        </th>
                                        <th>
                                            Customer Name
                                        </th>
                                        <th>
                                            Phone
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="showCustomer">
                                    @php
                                        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
                                            $customers = App\Models\Customer::where('party_type', 'customer')->get();
                                        } else {
                                            $customers = App\Models\Customer::where('party_type', 'customer')
                                                ->where('branch_id', Auth::user()->branch_id)
                                                ->get();
                                        }
                                    @endphp
                                    @forelse ($customers as $customer)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="form-check-input" name="check"
                                                    id="">
                                            </td>
                                            <td>
                                                {{ $customer->name }}
                                            </td>
                                            <td>
                                                {{ $customer->phone }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center"> no data found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-6">
                            <h4>Send SMS</h4>
                            <form action="{{ route('sms.To.Customer') }}" method="POST">
                                @csrf
                                <div class="mb-3 form-valid-groups">
                                    <div class="row">
                                        <div class="col-lg-10">
                                            <label class="form-label">Purpose<span class="text-danger">*</span></label>
                                            <select name="purpose" id="" class="form-control">
                                                <option value="">-----Select Purpose-----</option>
                                                @php
                                                    $collection = App\Models\SmsCategory::all();
                                                @endphp
                                                @foreach ($collection as $item)
                                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-2 mt-4 ps-0">
                                            <a href="#" class="btn btn-primary submit mt-1 w-100"
                                                data-bs-toggle="modal" data-bs-target="#smsCategoryModal">Add</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3 form-valid-groups">
                                    <label class="form-label">Number<span class="text-danger">*</span></label>
                                    <textarea class="form-control field_required" name="number" id="" cols="30" rows="5"
                                        placeholder="01917344267,01744676725...."></textarea>
                                </div>
                                <div class="mb-3 form-valid-groups">
                                    <label class="form-label">SMS Body <span class="text-danger">*</span></label>
                                    <textarea class="form-control field_required" name="sms" id="" cols="30" rows="8"
                                        placeholder="Enter SMS Text"></textarea>
                                </div>
                                <div class="mb-3 form-valid-groups">
                                    <button type="submit" class="btn btn-primary submit w-25">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    {{-- SMS Category modal  --}}
    <div class="modal fade " id="smsCategoryModal" tabindex="-1" aria-labelledby="exampleModalScrollableTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">SMS Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form class="smsCategoryForm row">
                        <div class="mb-3 col-md-12">
                            <label for="name" class="form-label">Sms Category Name<span
                                    class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-md-8">
                                    <input id="defaultconfig" class="form-control category_name" maxlength="100"
                                        name="name" type="text" onkeyup="errorRemove(this);"
                                        onblur="errorRemove(this);">
                                    <span class="text-danger category_name_error"></span>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-primary w-100 catSave">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#SL</th>
                                        <th>
                                            Category Name
                                        </th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="showCategory">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .catUpdateInput:focus,
        .catUpdateInput {
            outline: 0;
            border: 0;
            color: white;
            background: transparent;
        }
    </style>
    <script>
        // remove error
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
            // show category
            function categoryView() {
                $.ajax({
                    url: '{{ route('sms.category.view') }}',
                    method: 'GET',
                    success: function(res) {
                        // console.log(res.data);
                        const categories = res.data;
                        $('.showCategory').empty();
                        if (categories.length > 0) {
                            $.each(categories, function(index, category) {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                            <td>
                                ${index+1}
                            </td>
                            <td>
                                <input type="text" class="cat_name_input catUpdateInput" name="name" value="${category.name ?? ""}" readonly/>
                            </td>
                            <td>
                                <a href="#" class="btn btn-primary btn-icon category_edit" data-id=${category.id}>
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-icon category_delete" data-id=${category.id}>
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                                <a href="#" class="btn btn-success btn-icon category_update" style="display: none" data-id=${category.id}>
                                    <i class="fa-solid fa-circle-check"></i>
                                </a>
                            </td>
                            `;
                                $('.showCategory').append(tr);
                            })
                        } else {
                            $('.showCategory').html(`
                            <tr>
                                <td colspan='8'>
                                    <div class="text-center text-warning mb-2">Data Not Found</div>
                                    <div class="text-center">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalLongScollable">Add
                                            Category<i data-feather="plus"></i></button>
                                    </div>
                                </td>
                            </tr>`)
                        }
                    }
                })
            }
            categoryView();
            // save category
            $(".catSave").click(function(e) {
                e.preventDefault();
                // alert("ok");
                let formData = new FormData($('.smsCategoryForm')[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '{{ route('sms.category.store') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        // console.log(res);
                        if (res.status == 200) {
                            $('.smsCategoryForm')[0].reset();
                            toastr.success(res.message);
                        } else {
                            // console.log(res);
                            showError('.category_name', res.error.name);
                        }
                    }
                });
            })


            // edit category
            $(document).on('click', '.category_edit', function(e) {
                e.preventDefault();
                // alert('ok');
                let id = this.getAttribute('data-id');
                // alert(id);
                let row = $(this).closest('tr');
                // Hide relevant buttons within all rows
                $('.category_delete').show();
                $('.category_edit').show();
                $('.category_update').hide();

                // Disable editing for all input fields
                $('.cat_name_input').attr('readonly', true).removeClass('form-control');

                row.find('.category_delete').hide();
                row.find('.category_edit').hide();
                row.find('.category_update').show();
                row.find('.cat_name_input').removeAttr('readonly').addClass('form-control').removeClass(
                    'catUpdateInput').focus();
            })

            // update category
            $(document).on('click', '.category_update', function(e) {
                e.preventDefault();
                let id = this.getAttribute('data-id');
                let name = $(this).closest('tr').find('.cat_name_input').val();
                // alert(name);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: `/sms/category/update/${id}`,
                    type: 'POST',
                    data: {
                        name: name
                    },
                    success: function(res) {
                        // console.log(res);
                        if (res.status == 200) {
                            // console.log(res);
                            $('#smsCategoryModal').modal('hide');
                            $('.smsCategoryForm')[0].reset();
                            categoryView();
                            toastr.success(res.message);
                        } else {
                            showError('.category_name', res.error.name);
                        }
                    }
                });
            })


            // delete category
            $(document).on('click', '.category_delete', function(e) {
                e.preventDefault();
                let id = this.getAttribute('data-id');


                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: `/sms/category/delete/${id}`,
                    type: 'GET',
                    success: function(res) {
                        if (res.status == 200) {
                            $('#smsCategoryModal').modal('hide');
                            categoryView();
                            toastr.success("Deleted Successfully");

                        } else {
                            toastr.warning("something went wrong");
                        }

                    }
                });
            })


            $('input[name="check"]').change(function() {
                // Initialize an empty array to store phone numbers
                var phoneNumbers = [];
                // Loop through all checked checkboxes
                $('input[name="check"]:checked').each(function() {
                    // Get the phone number from the corresponding row
                    var phoneNumber = $(this).closest('tr').find('td:eq(2)').text();
                    // Add the phone number to the array
                    phoneNumbers.push(phoneNumber.trim());
                });
                // Update the number field with the phone numbers joined by comma
                $('textarea[name="number"]').val(phoneNumbers.join(','));
            });

            // Click event for Select All checkbox
            $('.select_all').click(function() {
                // Toggle all checkboxes based on the Select All checkbox's status
                $('input[name="check"]').prop('checked', $(this).prop('checked')).change();
            });



        });
    </script>
@endsection
