<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Psize;
use App\Models\SubCategory;
use App\Repositories\RepositoryInterfaces\SubCategoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use Validator;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    private $subCategory;

    public function __construct(SubCategoryInterface $subCategory)
    {
        $this->subCategory = $subCategory;
    }

    public function index()
    {
        $categories = Category::get();

        // return view('pos.products.category',compact('categories'));
        return view('pos.products.subcategory', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'category_id' => 'required|max:255',
        ]);

        if ($validator->passes()) {
            $data = $request->only(['name', 'category_id']);

            if ($request->hasFile('image')) {
                $imageName = rand().'.'.$request->image->extension();
                $request->image->move(public_path('uploads/subcategory'), $imageName);
                $data['image'] = $imageName;
            }
            $data['slug'] = Str::slug($request->name);

            $this->subCategory->create($data);

            return response()->json([
                'status' => 200,
                'message' => 'Sub Category Saved Successfully',
            ]);
        } else {
            return response()->json([
                'status' => '500',
                'error' => $validator->messages(),
            ]);
        } //
    }

    //
    public function view()
    {
        //   $subcategories = SubCategory::all();
        $subcategories = $this->subCategory->getAllSubCategory();
        $subcategories->load('category');

        return response()->json([
            'status' => 200,
            'data' => $subcategories,

        ]);
    }

    //
    public function edit($id)
    {
        //  $category = SubCategory::findOrFail($id);
        $subcategory = $this->subCategory->editData($id);
        // $categories = Category::get();
        if ($subcategory) {
            return response()->json([
                'status' => 200,
                'subcategory' => $subcategory,
                // 'categories' => $categories
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Data Not Found',
            ]);
        }
    }

    //
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'category_id' => 'required',
        ]);
        if ($validator->passes()) {
            $subcategory = SubCategory::findOrFail($id);
            $subcategory->name = $request->name;
            $subcategory->slug = Str::slug($request->name);
            $subcategory->category_id = $request->category_id;
            if ($request->image) {
                $imageName = rand().'.'.$request->image->extension();
                $request->image->move(public_path('uploads/subcategory'), $imageName);
                if ($subcategory->image) {
                    $previousImagePath = public_path('uploads/subcategory').$subcategory->image;
                    if (file_exists($previousImagePath)) {
                        unlink($previousImagePath);
                    }
                }
                $subcategory->image = $imageName;
            }

            $subcategory->save();

            return response()->json([
                'status' => 200,
                'message' => 'sub Category Update Successfully',
            ]);
        } else {
            return response()->json([
                'status' => '500',
                'error' => $validator->messages(),
            ]);
        }
    }

    //
    public function destroy($id)
    {
        $subcategory = SubCategory::findOrFail($id);
        if ($subcategory->image) {
            $previousImagePath = public_path('uploads/subcategory/').$subcategory->image;
            if (file_exists($previousImagePath)) {
                unlink($previousImagePath);
            }
        }
        $subcategory->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Sub Category Deleted Successfully',
        ]);
    }

    //
    public function status($id)
    {
        $subcategory = SubCategory::findOrFail($id);
        // dd($id);
        $newStatus = $subcategory->status == 0 ? 1 : 0;
        $subcategory->update([
            'status' => $newStatus,
        ]);

        return response()->json([
            'status' => 200,
            'newStatus' => $newStatus,
            'message' => 'Status Changed Successfully',
        ]);
    }

    public function find($id)
    {
        $subcategory = SubCategory::where('category_id', $id)->where('status', 1)->get();
        $size = Psize::where('category_id', $id)->get();

        return response()->json([
            'status' => 200,
            'data' => $subcategory,
            'size' => $size,
        ]);
    }

    public function findMultipleCategory(Request $request)
    {
        $categoryIds = $request->input('category_ids', []);
        if (empty($categoryIds)) {
            return response()->json([
                'status' => 200,
                'data' => [

                    'sizes' => [],
                ],
            ]);
        }
        $sizes = Psize::whereIn('category_id', $categoryIds)
            ->distinct() // Avoid duplicate sizes
            ->get();

        return response()->json([
            'status' => 200,
            'data' => [],
            'size' => $sizes,
        ]);
    }
}
