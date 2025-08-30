<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class SupplierDemoExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return new Collection([
            ['name', 'branch_id', 'email', 'phone', 'address', 'wallet_balance'],
        ]);
    }

    public function headings(): array
    {
        return ['name', 'branch_id', 'email', 'phone', 'address', 'wallet_balance'];
    }
}
