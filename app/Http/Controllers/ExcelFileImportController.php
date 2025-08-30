<?php

namespace App\Http\Controllers;

use App\Imports\BrandImport;
use App\Imports\CategoryImport;
use App\Imports\CustomerImport;
use App\Imports\ProductsImport;
// use Validator;
use App\Imports\SubcategoryImport;
use App\Imports\SupplierImport;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class ExcelFileImportController extends Controller
{
    public function importProductPage()
    {
        return view('pos.excel-import.excel-import-page');
    }

    // ///////////////////// Products Import Data //////////////////////

    // public function productImportExcelData(Request $request)
    // {
    //     // dd($request);
    //     $request->validate([
    //         'import_file' => [
    //             'required',
    //             'file'
    //         ]
    //     ]);

    //     //    try {
    //         // Attempt to import the Excel file
    //         Excel::import(new ProductsImport, $request->file('import_file'));

    //         // Success notification
    //         $notification = array(
    //             'message' => 'Products imported successfully.',
    //             'alert-type' => 'info'
    //         );
    //          return redirect()->back()->with($notification);
    //     // } catch (ValidationException $e) {
    //     //         return redirect()->back()->withErrors($e->errors());
    //     //     } catch (\Exception $e) {
    //     //         return redirect()->back()->with('error', $e->getMessage());
    //     //     }

    //     // return redirect()->back()->with($notification);
    // }

    // new code

    public function productImportExcelData(Request $request)
    {
        $request->validate([
            'import_file' => ['required', 'file'],
        ]);

        try {
            // Disable observers to prevent individual Elasticsearch updates
            Product::withoutEvents(function () use ($request) {
                Excel::import(new ProductsImport, $request->file('import_file'));
            });

            // Run bulk indexing after import
            Artisan::call('products:index');
            Log::info('Bulk product indexing completed after Excel import.');

            $notification = [
                'message' => 'Products imported and indexed successfully.',
                'alert-type' => 'info',
            ];

            return redirect()->back()->with($notification);
        } catch (\Exception $e) {
            Log::error('Product import failed: '.$e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Import failed: '.$e->getMessage()]);
        }
    }

    // /////////////////////Brand Import Data //////////////////////

    public function importBrandExcelData(Request $request)
    {
        $request->validate([
            'brand-import_file' => [
                'required',
                'file',
            ],
        ]);

        Excel::import(new BrandImport, $request->file('brand-import_file'));
        $notification = [
            'message' => 'Brand imported successfully.',
            'alert-type' => 'info',
        ];

        return redirect()->back()->with($notification);
    }

    // ///////////////////// Category Import Data //////////////////////

    public function importCategoryExcelData(Request $request)
    {
        $request->validate([
            'category-import_file' => [
                'required',
                'file',
            ],
        ]);
        Excel::import(new CategoryImport, $request->file('category-import_file'));
        $notification = [
            'message' => 'Category imported successfully.',
            'alert-type' => 'info',
        ];

        return redirect()->back()->with($notification);
    }

    // ///////////////////// Sub Category Import Data //////////////////////

    public function importSubcategoryExcelData(Request $request)
    {
        Excel::import(new SubcategoryImport, $request->file('subcategory-import_file'));
        $notification = [
            'message' => 'SubCategory imported successfully.',
            'alert-type' => 'info',
        ];

        return redirect()->back()->with($notification);
    }

    // ///////////////////// Supplier Import Data //////////////////////
    public function importSupplierExcelData(Request $request)
    {
        $request->validate([
            'supplier-import_file' => [
                'required',
                'file',
            ],
        ]);

        // Attempt to import the Excel file
        Excel::import(new SupplierImport, $request->file('supplier-import_file'));

        // Success notification
        $notification = [
            'message' => 'Supplier imported successfully.',
            'alert-type' => 'info',
        ];

        return redirect()->back()->with($notification);
    }

    // ///////////////////// Customer Import Data //////////////////////
    public function importCustomerExcelData(Request $request)
    {
        $request->validate([
            'customer-import_file' => [
                'required',
                'file',
            ],
        ]);

        // Attempt to import the Excel file
        Excel::import(new CustomerImport, $request->file('customer-import_file'));

        // Success notification
        $notification = [
            'message' => 'Customer imported successfully.',
            'alert-type' => 'info',
        ];

        return redirect()->back()->with($notification);
    }
}
