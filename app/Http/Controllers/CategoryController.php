<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MultipleCategoryProduct;
use App\Repositories\RepositoryInterfaces\CategoryInterface;
// use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    private $CategoryRepo;

    public function __construct(CategoryInterface $CategoryRepo)
    {
        $this->CategoryRepo = $CategoryRepo;
    }

    public function index()
    {
        return view('pos.products.category');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);
        if ($validator->passes()) {
            $category = new Category;
            if ($request->image) {
                $imageName = rand().'.'.$request->image->extension();
                $request->image->move(public_path('uploads/category/'), $imageName);
                $category->image = $imageName;
            }
            $category->name = $request->name;
            $category->slug = Str::slug($request->name);
            $category->status = 1;
            $category->save();

            return response()->json([
                'status' => 200,
                'message' => 'Category Save Successfully',
            ]);
        } else {
            return response()->json([
                'status' => '500',
                'error' => $validator->messages(),
            ]);
        }
    }

    public function view()
    {
        // Fetch all categories
        $categories = $this->CategoryRepo->getAllCategory();

        // Fetch the count of products for each category
        $categoriesWithProductCount = $categories->map(function ($category) {
            $productCount = MultipleCategoryProduct::where('category_id', $category->id)->count();

            return [
                'id' => $category->id,
                'name' => $category->name, // Assuming 'name' is a column in your Category model
                'product_count' => $productCount,
            ];
        });

        return response()->json([
            'status' => 200,
            'data' => $categoriesWithProductCount,
        ]);
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        if ($category) {
            return response()->json([
                'status' => 200,
                'category' => $category,
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Data Not Found',
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);
        if ($validator->passes()) {
            $category = Category::findOrFail($id);
            $category->name = $request->name;
            $category->slug = Str::slug($request->name);
            if ($request->image) {
                $imageName = rand().'.'.$request->image->extension();
                $request->image->move(public_path('uploads/category/'), $imageName);
                if ($category->image) {
                    $previousImagePath = public_path('uploads/category/').$category->image;
                    if (file_exists($previousImagePath)) {
                        unlink($previousImagePath);
                    }
                }
                $category->image = $imageName;
            }

            $category->save();

            return response()->json([
                'status' => 200,
                'message' => 'Category Update Successfully',
            ]);
        } else {
            return response()->json([
                'status' => '500',
                'error' => $validator->messages(),
            ]);
        }
    }

    public function status($id)
    {
        // dd($id);
        $category = Category::findOrFail($id);
        $newStatus = $category->status == 0 ? 1 : 0;
        $category->update([
            'status' => $newStatus,
        ]);

        return response()->json([
            'status' => 200,
            'newStatus' => $newStatus,
            'message' => 'Status Changed Successfully',
        ]);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        if ($category->image) {
            $previousImagePath = public_path('uploads/category/').$category->image;
            if (file_exists($previousImagePath)) {
                unlink($previousImagePath);
            }
        }
        $category->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Category Deleted Successfully',
        ]);
    }

    // public function categoryAll()
    // {
    //     $categories = Category::all();
    //     return  response()->json([
    //         'status' => 200,
    //         'categories' =>  $categories,
    //     ]);
    // }
}
