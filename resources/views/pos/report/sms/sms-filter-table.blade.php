@if ($smsAll->count() > 0)
    @foreach ($smsAll as $key => $sms)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $sms->number ?? '-' }}</td>
            <td>{{ $sms->created_at->format('jS F, Y') ?? '-' }}</td>
            <td>{{ $sms->purpose ?? '-' }}</td>
            <td>{{ $sms->message ?? '-' }}</td>
        </tr>
    @endforeach
@else
<tr>
    <td colspan="12">
        <div class="text-center text-warning mb-2">Data Not Found</div>
    </td>
</tr>
@endif
