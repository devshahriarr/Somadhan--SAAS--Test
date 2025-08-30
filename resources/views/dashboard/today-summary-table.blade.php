<div class="col-md-12 col-xl-6 col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">{{ $branch['name'] }} Today Summary</h6>
            <table class="table summary_table">
                <thead>
                    <tr>
                        <th colspan="2">Incoming</th>
                        <th colspan="2">Outgoing</th>
                    </tr>
                    <tr>
                        <th>Purpose</th>
                        <th class="text-end">TK</th>
                        <th>Purpose</th>
                        <th class="text-end">TK</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($summary as $item)
                        <tr>
                            <td>{{ $item['incoming']['purpose'] }}</td>
                            <td class="text-end">{{ number_format($item['incoming']['amount'], 2) }}</td>
                            <td>{{ $item['outgoing']['purpose'] }}</td>
                            <td class="text-end">{{ number_format($item['outgoing']['amount'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">Total Balance</th>
                        <th class="text-end">{{ number_format($totalIngoing - $totalOutgoing, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
