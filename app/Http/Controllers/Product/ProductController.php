<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //


    public function getProduct(){

        $product = Product::paginate(10);

        return response()->json([
            'data' => $product
        ],200);

    }


    public function addProduct(Request $request){

         $request->validate([
            'name' => 'required|string',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'description' => 'required|string|max:400',
            'image' => 'required|array|max:4',
            'image.*' => 'image'
        ]);


        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description
        ]);

        if($request->hasFile('image')){

            $images = $request->image;

            foreach($images as  $key => $img){
                
          
            $img->move('storage/asset/product/image/' , $img->getClientOriginalName());
            
            $image = ProductImage::create([
                'product_id' => $product->id,
                'image' => $img->getClientOriginalName()
            ]);
       
            $image->save();
           
             }
        }


        $newProduct = Product::where('id' , $product->id)->with('product_image')->first();

        return response()->json([
            'data' => $newProduct
        ],201);



    }



    public function detailProduct($id){

        $product = Product::where('id' , $id)->with('product_image')->first();

        if(!$product){
            return response()->json([
                'error' => 'Product Not Found'
            ],404);
        }


        return response()->json([
            'data' => $product
        ],200);
    }


    
}
