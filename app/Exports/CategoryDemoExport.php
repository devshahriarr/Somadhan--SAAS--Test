<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class CategoryDemoExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return new Collection([
            ['name'], // Column headings

        ]);
    }

    public function headings(): array
    {
        return ['name']; // Column headers for the demo file
    }
}
