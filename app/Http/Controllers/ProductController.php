<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function __construct()
    {
        $this->middleware("auth:sanctum");
        $this->middleware("admin");
    }

    public function index()
    {
        //list all Products
        $products = Product::all();
        return response()->json([
            'status' => 'success',
            'data' => $products
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate data
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:products|min:3',
            'price' => 'required|integer|min:3',
            "category_id" => 'required',
            "photo" => 'required'
        ]);
        // check validator
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 200);
        } else {
            $product = new Product();
            // upload image
            if ($request->hasFile("photo")) {
                $file = $request->file('photo');
                $name = 'products/' . uniqid() . '.' . $file->extension();
                $file->storePubliclyAs('public', $name);
                $product->photo =  asset('storage/' . ($name));
            }

            $product->name = $request->name;
            $product->price = $request->price;
            $product->category_id = $request->category_id;
            return $product->save() ?
                response()->json(['status' => "success", 'data' => $product], 200) :
                response()->json(['status' => "error", 'message' => 'request failed'], 403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product->category = $product->category;
        return response()->json(["status" => "success", "data" => $product], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|min:3',
            'price' => 'integer|min:3',
            'isAvailable' => 'boolean'
        ]);
        // check validator
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 200);
        } else {
            if ($request->hasFile("photo")) {
                $file = $request->file('photo');
                $name = 'products/' . uniqid() . '.' . $file->extension();
                $file->storePubliclyAs('public', $name);
                $product->photo =  asset('storage/' . ($name));
            }
            $product->name = $request->name ? $request->name : $product->name;
            $product->price = $request->price ? $request->price : $product->price;
            $product->isAvailable = $request['isAvailable'] != null ? $request->isAvailable  : $product->isAvailable;
            return $product->update() ?
                response()->json(["status" => "success", "data" => $product], 200) :
                response()->json(['status' => "error", "message" => "request failed"], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        return $product->delete() ?
            response()->json(['status' => "success", "message" => "product deleted succssfully"], 200) :
            response()->json(['status' => "error", 'message' => 'request failed'], 403);
    }
}
