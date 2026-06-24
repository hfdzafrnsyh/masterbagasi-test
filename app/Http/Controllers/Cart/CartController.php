<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    //



    public function getCart(){
        try{
               
        $userId = Auth::user()->id;
        $cart = Cart::where('user_id' , $userId)->paginate(10);

        return response()->json([
            'data' => $cart
        ],200);

        }catch(Exception $e){
            return response()->json([
                        'error' => 'Internal Server Error'
                    ],500);
        }
    }

    public function addToCart(Request $request){
        
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|max:100'
        ]);

        try{

        $userId = Auth::user()->id;
        
        $cart = Cart::where('product_id' , $request->product_id)->where('user_id' , $userId)->first();

        if($cart){
            $quantity = $cart->quantity + $request->quantity;
            $cart->update([
                'quantity' => $quantity 
            ]);

            $cart->save();


            return response()->json([
                'data' => $cart
            ],200);

        }else{
            $newCart = Cart::create([
                'product_id' => $request->product_id,
                'user_id' => $userId,
                'quantity' => $request->quantity
            ]);

            $newCart->save();

            return response()->json([
                'data' => $newCart
            ],201);
        }

        }catch(Exception $e){
            return response()->json([
                'error' => 'Internal Server Error'
            ],500);
        }

    }


    public function reducedProduct($id){
        

    try{

        $cart = Cart::where('id',$id)->first();

        if(!$cart){
            return response()->json([
                'error' => 'Not found'
            ],     404);
    
        }

        $quantity = $cart->quantity - 1;

        if($cart->quantity == 1){
            return response()->json([
                'error' => 'Bad Request'
            ],     400);
        }

        $cart->update([
            'quantity' => $quantity
        ]);

        $cart->save();

        return response()->json([
            'data' => $cart
        ],     200);

     
    }catch(Exception $e){
        return response()->json([
            'error' => 'Internal Server Error'
        ],500);
    }



    }


    public function addedProduct($id){

    try{

        $cart = Cart::where('id',$id)->first();

        if(!$cart){
            return response()->json([
                'error' => 'Not found'
            ],     404);
    
        }

        $quantity = $cart->quantity + 1;

        $cart->update([
            'quantity' => $quantity
        ]);

        $cart->save();

        return response()->json([
            'data' => $cart
        ],     200);

     
    }catch(Exception $e){
        return response()->json([
            'error' => 'Internal Server Error'
        ],500);
    }


    }



    public function checkedStatus($id){


        try{
            $cart = Cart::find($id);

            if(!$cart){
                return response()->json([
                    'error' => 'Cart not found'
                ],404);
            }


            if($cart->checked === true){

                $cart->update([
                    'checked' => 0
                ]);

                return response()->json([
                    'data' => $cart
                ]);
            }else{

                $cart->update([
                    'checked' => 1
                ]);

                return response()->json([
                    'data' => $cart
                ]);
            }

        }catch(Exception $e){
            return response()->json([
                'error' => 'Internal Server Error'
            ],500);
        }


    }



    public function cartCheckout(){

        $qcart = request()->query('cart_id');

        $userId = Auth::user()->id;

        if($qcart != ''){
            $cart = Cart::whereIn('id' , $qcart)->with('product')->where('user_id' , $userId)->get();
            
            if(sizeof($cart)){
      
                return response()->json([
                    'data' => $cart,
                ],200);

            }else{
                return response()->json([
                    'error' => 'Cart not found'
                ],404);
            }
        }

        return response()->json([
            'error' => 'Bad Request'
        ],400);
    }

    
    public function delete($id){

        
        try{
            $cart = Cart::find($id);

            if(!$cart){
                return response()->json([
                    'error' => 'Cart Not found'
                ],404);
            }
    
            Cart::destroy($id);
        
        
            return response()->json([
                'status' => 'sucess'
            ],200);
    
        }catch(Exception $e){
            return response()->json([
                'error' => 'Internal Server Error'
            ],500);
        }
    }

}
