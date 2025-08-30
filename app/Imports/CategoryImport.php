<?php

namespace App\Imports;

use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CategoryImport implements ToCollection, WithHeadingRow
{
    /**
     * @param  Collection  $collection
     */
    public function collection(Collection $rows)
    {

        foreach ($rows as $row) {
            if (isset($row['name'])) {
                $cat = Category::where('name', $row['name'])->first();
                // dd($cat);
                if ($cat) {
                    $cat->update([
                        // 'id' => $row['id'],
                        'name' => $row['name'],
                        'slug' => Str::slug($row['name']),
                    ]);

                } else {
                    Category::create([
                        // 'id' => $row['id'],
                        'name' => $row['name'],
                        'slug' => Str::slug($row['name']),
                    ]);

                }
            } else {
                $notification = [
                    'warning' => 'Category Not Inserted',
                    'alert-type' => 'info',
                ];

                return redirect()->back()->with($notification);
            }
        }
    }
}
