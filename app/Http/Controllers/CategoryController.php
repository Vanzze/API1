<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    //
    public function create(Request $request){
        $validasi = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if($validasi->fails()){
            return response()->json($validasi->errors(), 400);
        }

        $category = Category::create([
            'name' => $request->name,
        ]);
        return response()->json($category, 200);
    }

    public function getAllCategory(){
        $category = Category::get();

        return response()->json($category, 200);
    }

    public function getCategoryById($id){
        $category = Category::with("product")->find($id);

        if(!$category){
            return response()->json([
                'error' => 'Category Tidak Ditemukan',
            ]);
        }

        return response()->json($category, 200);
    }

    public function deleteCategory($id){
        $category = Category::find($id);

        if(!$category){
            return response()->json([
                'error' => 'Category Tidak Ditemukan',
            ]);
        }

        $category->delete();
         return response()->json([
            'message' => 'Category Berhasil Di Delete',
         ], 200);
    }

    public function updateCategory(Request $request, $id){
        $validasi = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if($validasi->fails()){
            return response()->json($validasi->errors(), 400);
        }

        $category = Category::find($id);
        if(!$category) {
            return response()->json([
                'error' => 'Category Tidak Ditemukan',
            ]);
        }

        if($request->has('name')){
            $category->name = $request->name;
            $category->save();


        }

        return response()->json($category, 200);
    }


}
