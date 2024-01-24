<?php

namespace App\Http\Controllers\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subcategories = SubCategory::all();
        return view('admin.backend.subcategory.subcategories',['subcategories'=>$subcategories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.backend.subcategory.add_subcategory',['categories'=>$categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        SubCategory::insert([
            'category_id'=>$request->category_id,
            'subcategory_name'=>$request->subcategory_name,
            'subcategory_slug'=>strtolower(str_replace([' ', '/'],'-',$request->subcategory_name))
        ]);

        $notification = array(
            'message' => 'SubCategory Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.subcategory')->with($notification);
        //dd($save_url);
    }

    /**
     * Display the specified resource.
     */
    public function show(SubCategory $subCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubCategory $subCategory, Category $Category, string $id)
    {
        $categories = $Category::all();
        $subcategory_obj = $subCategory::find($id);
        return view('admin.backend.subcategory.edit_subcategory',['subcategory'=>$subcategory_obj,'categories'=>$categories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubCategory $subCategory)
    {
        $subcategory_id = $request->id;
        $subCategory::find($subcategory_id)->update([
            'category_id'=>$request->category_id,
            'subcategory_name'=>$request->subcategory_name,
            'subcategory_slug'=>strtolower(str_replace([' ', '/'],'-',$request->subcategory_name))
        ]);

        $notification = array(
            'message' => 'SubCategory Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.subcategory')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubCategory $subCategory, string $id)
    {
        $subCategory::find($id)->delete();

        $notification = array(
            'message' => 'SubCategory deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
}
