<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Psy\CodeCleaner\AssignThisVariablePass;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\jsonResponse
     */
    public function index()
    {
        $products = Product::all();

        return response()->json([
            '========' => '================== Display Products : ==================',
            'Products' => $products,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\jsonResponse
     */
    public function store(StoreProductRequest $request)
    {
        $user = Auth::user();
        $product = Product::create($request->all() + ['user_id' => $user->id]);

        return response()->json([
            'Message' => 'Product added successfully!',
            'Product' => $product,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\jsonResponse
     */
    public function show($id)
    {
        $product = Product::find($id);
        $response = ($product) ? response()->json($product, 200) : response()->json(['message' => "Product with (id : {$id}) doesn't exist!",]);
        return $response;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\jsonResponse
     */


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\jsonResponse
     */
    function destroy($id)
    {
        $user = Auth::user();
        $product = Product::find($id);

        if(!$product){
            return response()->json(['message' => "Product with (id : {$id} doesn't exist!)"]);
        }
        if (!$user->can('delete every product') && $user->id != $product->user_id){
            return response()->json(['message' => "Product with this (id : {$id} doesn't exist!)"], 403);
        }

        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product delete successfully!',
        ], 200);
    }

    public function filterByCategory($category_name)
    {
        $category = Category::where('name', $category_name)->firstOrFail();
        $products = Product::where('category_id', $category->id)->get();
        return response()->json($products);
    }
    public function update(UpdateProductRequest $request, $id)
    {
        $user = Auth::user();
        $product = Product::find($id);

        if(!$product){
            return response()->json(['message' => "Product with (id : {$id} doesn't exist!)"]);
        }
        if(!$user->can('edit every product') && $user->id != $product->user_id){
            return response()->json(['message' => "Can't update a product that isn't yours!"]);
        }

        $product->update($request->all());

        return response()->json([
            '***' => '** Product updated successfully! **',
            'product' => $product,
        ], 200);
    }
}
