<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SubcategoryImport implements ToCollection, WithHeadingRow
{
    /**
     * @param  Collection  $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $category = Category::firstOrCreate(
                ['name' => $row['category']],
                [
                    'slug' => Str::slug($row['category']),
                ]
            );

            if (isset($row['name'])) {
                $subcategory = SubCategory::where('name', $row['name'])->first();
                if ($subcategory) {
                    $subcategory->update([
                        // 'id' => $row['id'],
                        'category_id' => $category->id,
                        'name' => $row['name'],
                        'slug' => Str::slug($row['name']),
                    ]);
                } else {
                    SubCategory::create([
                        // 'id' => $row['id'],
                        'category_id' => $category->id,
                        'name' => $row['name'],
                        'slug' => Str::slug($row['name']),
                    ]);
                }
            } else {
                $notification = [
                    'warning' => 'Not Inserted',
                    'alert-type' => 'info',
                ];

                return redirect()->back()->with($notification);
            }
        }
    }
}
