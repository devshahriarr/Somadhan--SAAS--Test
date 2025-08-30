<?php

namespace App\Http\Controllers;

use App\Exports\BrandDemoExport;
use App\Exports\CategoryDemoExport;
use App\Exports\CustomerDemoExport;
use App\Exports\ProductsDemoExport;
use App\Exports\SubCategoryDemoExport;
use App\Exports\SupplierDemoExport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelDemoFileController extends Controller
{
    public function brandDemoExcel()
    {
        // This will create and download the demo Excel file
        return Excel::download(new BrandDemoExport, 'demo-brands.xlsx');
    }

    public function categoryDemoExcel()
    {
        // This will create and download the demo Excel file
        return Excel::download(new CategoryDemoExport, 'demo-Category.xlsx');
    }

    public function subCategoryDemoExcel()
    {
        // This will create and download the demo Excel file
        return Excel::download(new SubCategoryDemoExport, 'demo-SubCategory.xlsx');
    }

    public function productsDemoExcel()
    {
        // This will create and download the demo Excel file
        return Excel::download(new ProductsDemoExport, 'demo-products.xlsx');
    }

    public function supplierDemoExcel()
    {
        // This will create and download the demo Excel file
        return Excel::download(new SupplierDemoExport, 'demo-supplier.xlsx');
    }

    public function customerDemoExcel()
    {
        // This will create and download the demo Excel file
        return Excel::download(new CustomerDemoExport, 'demo-customer.xlsx');
    }
}
