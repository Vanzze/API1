<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function create(Request $request){
        $validasi = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'category_id' => 'required|integer'
        ]);

        if ($validasi->fails()) {
            return response()->json($validasi->errors(), 400);
        }

        $category = Category::find($request->category_id);
        if (!$category) {
            return response()->json([
                'error' => 'Category tidak ditemukan'
            ], 404);
        }

        $product = Product::create([
            'name' => $request->name,
            'stock' => $request->stock,
            'price' => $request->price,
            'category_id' => $request->category_id
        ]);

        if($request->hasFile('image')){
            $image = $request->file('image');
            $imageName = Str::random(5) . '.' . $image->getClientOriginalExtension();
            $image->move('images/' , $imageName);

            $product->image = $imageName;
            $product->save();
        }

        return response()->json($product, 200);
    }

    public function getAllProducts(){
        $product = Product::get();

        return response()->json($product, 200);
    }

    public function getProductById($id) {
        $product = Product::with("category")->find($id);

        if(!$product) {
            return response()->json([
                'error' => 'Product tidak ditemukan'
            ], 404);
        }

        return response()->json($product, 200);
    }

    public function deleteProduct($id){
        $product = Product::find($id);

        if(!$product) {
            return response()->json([
                'error' => 'Product tidak ditemukan'
            ], 404);
        }

        unlink(public_path('images/'. $product->image));
        $product->delete();

        return response()->json([
            'message' => 'Product berhasil didelete'
        ], 200);
    }

    public function updateProduct(Request $request, $id){
        $validasi = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'category_id' => 'required|integer'
        ]);

        if ($validasi->fails()) {
            return response()->json($validasi->errors(), 400);
        }

        $product = Product::find($id);

        if(!$product) {
            return response()->json([
                'error' => 'Product tidak ditemukan'
            ], 404);
        }

        $product->name = $request->name;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->category_id = $request->category_id;
        $product->save();

        unlink(public_path('images/'. $product->image));

        if($request->hasFile('image')){
            $image = $request->file('image');
            $imageName = Str::random(5) . '.' . $image->getClientOriginalExtension();
            $image->move('images/' , $imageName);

            $product->image = $imageName;
            $product->save();
        }

        return response()->json($product, 200);
    }

    public function searchProduct(Request $request){
        $query = $request->input('search');

        if($query) {
            $products = Product::where('name', 'like', '%' . $query . '%')->get();
            return response()->json($products);
        } else {
            return response()->json([], 200);
        }
    }

}
