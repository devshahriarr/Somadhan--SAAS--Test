@if (!empty($transactions))
    <div class="container-fluid mt-2 d-flex justify-content-center w-100">
        <div class="table-responsive w-100">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td>Account Of</td>
                        <td>{{ $supplier->name ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td>{{ $supplier->address ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Contact No.</td>
                        <td>{{ $supplier->phone ?? '' }}</td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <h4 class="my-3 text-center">Supplier Ledger</h4>
    <div class="container-fluid w-100">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="example" class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Particulars</th>
                                <th>Debit</th>
                                <th>Credit</th>
                                <th>Balance</th>
                            </tr>
                            @if ($transactions->count() > 0)
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transactions->date }}</td>
                                        <td>{{ $transactions->particulars }}</td>
                                        <td>{{ $transactions->debit }}</td>
                                        <td>{{ $transactions->credit }}</td>
                                        <td>{{ $transactions->balance }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">No Data Found</td>
                                </tr>
                            @endif
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
<div class="container-fluid w-100 btn_group">
    <a href="javascript:;" class="btn btn-outline-primary float-end mt-4" onclick="window.print();"><i
    data-feather="printer" class="me-2 icon-md"></i>Print</a>
</div>
