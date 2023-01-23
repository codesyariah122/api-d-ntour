<?php

namespace App\Http\Controllers\Api\Products;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'price' => 'required',
                'description' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $check_product = Product::whereTitle($request->title)
                ->whereProductCategoryId($request->category_id)->first();

            if ($check_product) {
                return response()->json([
                    'success' => false,
                    'message' => "{$check_product->title}, is available from database"
                ]);
            }

            $product_category = ProductCategory::whereId($request->category_id)->first();

            $product = new Product;
            if ($request->file('image')) {
                $file = $request->file('image')->store(trim(preg_replace('/\s+/', '', 'products')) . "/{$product_category->slug}", 'public');
                $product->image = $file;
            }
            $product->title = $request->title;
            $product->price = $request->price;
            $product->product_category_id = $request->category_id;
            $product->description = htmlspecialchars($request->description);
            $product->save();

            return response()->json([
                'success' => true,
                'message' => 'Adding new product',
                'data' => $product
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
