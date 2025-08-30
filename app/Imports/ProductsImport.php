<?php

namespace App\Imports;

use App\Models\Branch;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\PosSetting;
use App\Models\Product;
use App\Models\Psize;
use App\Models\Stock;
use App\Models\SubCategory;
use App\Models\Unit;
use App\Models\Variation;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToCollection, WithHeadingRow
{
    /**
     * @param  Collection  $collection
     */
    public function collection(Collection $rows)
    {

        function validateRow($row, $requiredFields)
        {
            $errors = [];
            foreach ($requiredFields as $field) {
                if (empty($row[$field])) {
                    $errors[] = "The '{$field}' field is required. this row not inserted .";
                }
            }

            return $errors;
        }
        // --- Use provided barcode or generate one ---//
        function generateUniqueBarcode($name)
        {
            // Extract the first two letters of the name, convert to uppercase
            $prefix = strtoupper(substr($name, 0, 2));

            do {
                $randomNumber = random_int(100000, 999999);
                $barcode = $prefix.'-'.$randomNumber; // Combine prefix with the random number and hyphen
            } while (Variation::where('barcode', $barcode)->exists()); // Ensure uniqueness

            return $barcode;
        }
        function convertExcelDate($excelDate)
        {
            try {
                return Carbon::create(1900, 1, 1)->addDays((int) $excelDate - 2)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }
        // dd($rows);
        $errors = [];
        $requiredFields = ['name', 'category', 'unit', 'size', 'brand'];
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 1; // Adjust for zero-based indexing
            Log::info("Row {$rowNumber} data: ", $row->toArray());

            $validationErrors = validateRow($row, $requiredFields);
            if (! empty($validationErrors)) {

                $errors[] = "Row {$rowNumber}: ".implode(',', $validationErrors);

                continue;
            }

            try {

                $manufactureDate = ! empty($row['manufacture_date']) ? convertExcelDate($row['manufacture_date']) : null;
                $expiryDate = ! empty($row['expiry_date']) ? convertExcelDate($row['expiry_date']) : null;
                $category = Category::firstOrCreate(
                    ['name' => $row['category']],
                    [
                        'slug' => Str::slug($row['category']), // Generate slug from category name
                    ]
                );
                // dd( $category);//
                $subcategory = null; // Default to null
                if (! empty($row['subcategory'])) { // Check if subcategory is provided
                    $subcategory = SubCategory::firstOrCreate(
                        [
                            'name' => $row['subcategory'],
                            'category_id' => $category->id, // Assuming category_id is required
                        ],
                        [
                            'slug' => Str::slug($row['subcategory']), // Generate slug from subcategory name
                        ]
                    );
                }

                $brand = null; // Default to null
                if (! empty($row['brand'])) { // Check if brand is provided
                    $brand = Brand::firstOrCreate(
                        ['name' => $row['brand']],
                        [
                            'slug' => Str::slug($row['brand']), // Generate slug from brand name
                        ]
                    );
                }
                // $branch = null; // Default to null
                // if (!empty($row['branch'])) { // Check if brand is provided
                //     $branch = Branch::firstOrCreate(
                //         ['name' => $row['branch']],
                //     );
                // }

                $unit = Unit::firstOrCreate(
                    ['name' => $row['unit'] ?? null],
                );

                $size = null; // Default to null
                if (! empty($row['size'])) { // Check if size is provided
                    $size = Psize::firstOrCreate(
                        [
                            'size' => $row['size'],
                            'category_id' => $category->id, // Assuming category_id is required
                        ],
                        [
                            // Add additional fields if your Psize model requires them, e.g., 'slug'
                        ]
                    );
                }
                $color = null; // Default to null
                if (! empty($row['color'])) { // Check if subcategory is provided
                    $color = Color::firstOrCreate(
                        [
                            'name' => $row['color'],
                        ],

                    );
                }
                $barcode = $row['barcode'] ?? generateUniqueBarcode($row['name']);

                $product = Product::firstOrCreate(
                    ['name' => $row['name']],
                    [
                        'category_id' => $category->id,
                        'subcategory_id' => $subcategory->id ?? null,
                        'brand_id' => $brand->id ?? null,
                        'unit' => $unit->id ?? null,
                        'description' => $row['description'] ?? null,
                    ]
                );
                $existingVariation = Variation::where('barcode', $row['barcode'])->first();
                $posSetting = PosSetting::first(); //
                $lowStockAlert = $row['low_stock_alert'] ? $row['low_stock_alert'] : $posSetting->low_stock ?? null;
                if (! $existingVariation) {
                    $existingVariations = Variation::where('product_id', $product->id)->count();

                    // dd( $row['barcode']);
                    $variation = Variation::firstOrCreate(
                        [
                            'barcode' => $row['barcode'] ?? $barcode,
                            // 'product_id' => $product->id,
                        ],
                        [

                            'product_id' => $product->id,
                            // 'barcode' => $row['barcode'] ?? $barcode,
                            'cost_price' => $row['cost_price'] ?? 0,
                            'b2b_price' => $row['b2b_price'] ?? 0,
                            'b2c_price' => $row['b2c_price'] ?? 0,
                            'size' => $size->id ?? null,
                            'color' => $color->id ?? null,
                            'model_no' => $row['model_no'],
                            'quality' => $row['quality'],
                            'origin' => $row['origin'],
                            'low_stock_alert' => $lowStockAlert,
                            'status' => $existingVariations === 0 ? 'default' : 'variant', // First variation as default, others as variant
                        ]
                    );

                    // dd($row['manufacture_date']);
                    if (($row['stock'] ?? 0) > 0) {
                        $existingStock = Stock::where('variation_id', $variation->id)
                            ->first();
                        // dd($existingStock);
                        Stock::create([
                            'branch_id' => $branch->id ?? 1,
                            'product_id' => $product->id,
                            'variation_id' => $variation->id,
                            'stock_quantity' => $row['stock'],
                            'is_current_stock' => $existingStock ? 0 : 1,
                            'manufacture_date' => $manufactureDate ?? null,
                            'expiry_date' => $expiryDate ?? null,
                        ]);
                    }
                } else {
                    // Skip if barcode already exists
                    continue;
                }
            } catch (\Exception $e) {
                $errors[] = "Row {$rowNumber}: ".$e->getMessage();
                Log::error("Error processing row {$rowNumber}: ".$e->getMessage(), ['exception' => $e->getTraceAsString()]);
            }
        }
        if (! empty($errors)) {
            Log::error('Errors during import: ', $errors);
            session()->flash('error', implode(' ', $errors));
        }

    }
}
