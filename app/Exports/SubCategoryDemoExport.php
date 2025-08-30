<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class SubCategoryDemoExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return new Collection([
            ['category', 'name'],

        ]);
    }

    public function headings(): array
    {
        return ['category', 'name']; // Column headers for the demo file
    }
}
