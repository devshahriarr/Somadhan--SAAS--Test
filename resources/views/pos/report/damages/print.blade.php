<!DOCTYPE html>
<html>
<head>
<title>Damage Report</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    @php
        $branch = App\Models\Branch::findOrFail(Auth::user()->branch_id);
    @endphp
    <div class=”sheet-outer A4">
        <section class=”sheet”>
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="container-fluid d-flex justify-content-between">
                                <div class="col-lg-3 ps-0">
                                    <a href="#" class="noble-ui-logo logo-light d-block mt-3">EIL<span>POS</span></a>
                                    <p class="mt-1 mb-1 show_branch_name"><b>{{ $branch->name ?? '' }}</b></p>
                                    <p class="show_branch_address">{{ $branch->address ?? 'accordion ' }}</p>
                                    <p class="show_branch_email">{{ $branch->email ?? '' }}</p>
                                    <p class="show_branch_phone">{{ $branch->phone ?? '' }}</p>


                                </div>
                            </div>
                            <div id="" class="table-responsive">
                                <table id="dataTableExample" class="table">
                                    <thead>
                                        <tr>
                                            <th>SN#</th>
                                            <th>Date</th>
                                            <th>Product Name</th>
                                            <th>Branch Name</th>
                                            <th>Quantity</th>
                                            <th>Note</th>

                                        </tr>
                                    </thead>
                                    <tbody class="showData">
                                        @if ($damageItem->count() > 0)
                                            @foreach ($damageItem as $key => $damage)
                                                {{-- @dd($damage); --}}
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $damage->date ?? '' }}</td>
                                                    <td>{{ $damage['product']['name'] ?? '' }}</td>
                                                    <td>{{ $damage['branch']['name'] ?? '' }}</td>
                                                    {{-- <td>{{ $damage->branch_id ?? ''}}</td>
                                                            <td>{{ $damage->product_id ?? ''}}</td> --}}
                                                    <td>{{ $damage->qty ?? '' }}</td>
                                                    <td>{{ $damage->note ?? '' }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="12">
                                                    <div class="text-center text-warning mb-2">Data Not Found</div>
                                                </td>
                                            </tr>
                                        @endif

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
<style>
body { margin: 0 }
.sheet-outer {
    margin: 0;
}
.sheet {
    margin: 0;
    overflow: hidden;
    position: relative;
    box-sizing: border-box;
    page-break-after: always;
}
@media screen {
    body {
        background: #e0e0e0
    }

    .sheet {
        background: white;
        box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);
        margin: 5mm auto;
    }
}
.sheet-outer.A4 .sheet {
    width: 210mm;
    height: 296mm
}
.sheet.padding-5mm { padding: 5mm }
@page {
    size: A4;
    margin: 0
}
@media print {
    .sheet-outer.A4, .sheet-outer.A5.landscape {
        width: 210mm
    }
}
</style>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
