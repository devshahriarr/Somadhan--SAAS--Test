<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class CustomerDemoExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return new Collection([
            ['name', 'branch_id', 'email', 'phone', 'address', 'opening_payable', 'opening_receivable', 'wallet_balance', 'total_receivable', 'total_payable', 'party_type'],
        ]);
    }

    public function headings(): array
    {
        return ['name', 'branch_id', 'email', 'phone', 'address', 'opening_payable', 'opening_receivable', 'wallet_balance', 'total_receivable', 'total_payable', 'party_type'];
    }
}
