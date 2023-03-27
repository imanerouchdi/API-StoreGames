<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\jsonResponse
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json([
            '==========' => "============== Categories ==============",
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\jsonResponse
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->all());
        return response()->json([
            'Message' => "Category ({$category->name}) added successfully!",
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\jsonResponse
     */
    public function show($id)
    {
        $category = Category::find($id);
        if(!$category){
            return response()->json(['message' => "Category with (id:{$id}) doesn't exist!"]);
        }
        return response()->json([
            'Category' => $category->name,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\jsonResponse
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::find($id);

        if(!$category){
            return response()->json(['message' => "Category with (id:{$id}) doesn't exist!"]);
        }
        $oldCategory = $category->name;

        $category->update($request->all());

        return response()->json([
            '=======' => "============== Category Updated ==============",
            'Message' => "Category ({$oldCategory}) updated to ({$category->name}) successfully!",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\jsonResponse
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if(!$category){
            return response()->json(['message' => "Category with (id:{$id}) doesn't exist!"]);
        }

        $category->delete();

        return response()->json([
            '=======' => '============= Category Deleted =============',
            'message' => "Category ({$category->name}) deleted successfully!",
        ], 200);
    }
}
