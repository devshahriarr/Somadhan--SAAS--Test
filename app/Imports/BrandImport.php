<?php

namespace App\Imports;

use App\Models\Brand;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BrandImport implements ToCollection, WithHeadingRow
{
    /**
     * @param  Collection  $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (isset($row['name'])) {
                $brand = Brand::where('name', $row['name'])->first();
                if ($brand) {
                    $brand->update([
                        // 'id' => $row['id'],
                        'name' => $row['name'],
                        'slug' => Str::slug($row['name']),
                        'description' => $row['description'],
                    ]);

                } else {
                    Brand::create([
                        // 'id' => $row['id'],
                        'name' => $row['name'],
                        'slug' => Str::slug($row['name']),
                        'description' => $row['description'],
                    ]);

                }
            } else {
                $notification = [
                    'warning' => 'Brand File Not Inserted',
                    'alert-type' => 'info',
                ];

                return redirect()->back()->with($notification);
            }
        }
    }
}
