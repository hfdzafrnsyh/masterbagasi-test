<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Type\Integer;

class OrderController extends Controller
{
    //



    public function getOrder(){

        $userId = Auth::user()->id;
        $order = Order::where('user_id' , $userId)->get();

        return response()->json([
            'data' => $order
        ],200);

    }

    public function checkoutProduct(){

        $qproduct = request()->query('product_id');


        if($qproduct != ''){

            $product = Product::where('id' , $qproduct)->first();
            
            if($product){
                return response()->json([
                    'data' => $product
                ],200);
            }else{
                return response()->json([
                    'error' => 'Product not found'
                ],404);
            }
        }

        return response()->json([
            'error' => 'Bad Request'
        ],400);


    }

    public function addOrder(Request $request){

        $request->validate([
            'product_id' => 'required|integer'
        ]);

        $product = Product::where('id' , $request->product_id)->first();

        $userId = Auth::user()->id;

        if(!$product){
            return response()->json([
                'error' => 'Product not found'
            ],404);
        }

        $qvoucher = request()->query('voucher');

        if($qvoucher != ''){
            
            $voucher = Voucher::where('code' ,   $qvoucher)->first();
            
            if(!$voucher){
                return response()->json([
                    'error' => 'Voucher not found'
                ],404);
            }

            $expired = date('Y-m-d H:i:s' , strtotime($voucher->expired_at));
            $now = date('Y-m-d H:i:s' , strtotime(Carbon::now()));

            if($voucher->active == 0 ){
                return response()->json([
                    'error' => 'Bad Request',
                    'message' => 'Vouchers not active'
                ],400);
             }else if($expired <= $now){
                return response()->json([
                   'error' => 'Bad Request',
                   'message' => 'Vouchers has Expired'
                ],400);
            }


                $vd = $voucher->discount;
                
                $discount  =  ( $vd / 100 ) * $product->price;

                $resultPrice = $product->price - $discount;
            
                $order = Order::create([
                    'user_id'  => $userId, 
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'status' => 'Progress',
                    'price' => $product->price,
                    'total_price' => $resultPrice,
                    'voucher_id' => $voucher->id
                ]);

                $order->save();  


                return response()->json([
                    'status' => 'success',
                    'data' => $order
                ],200);

        }

            $order = Order::create([
                'user_id'  => $userId, 
                'product_id' => $product->id,
                'name' => $product->name,
                'status' => 'Progress',
                'price' => $product->price,
                'total_price' => $product->price,
            ]);

            $order->save();  
                

            return response()->json([
                'status' => 'success',
                'data' => $order
            ],200);


    }


    public function addOrderWithCart(Request $request){

        $request->validate([
            'cart_id' => 'required|array',
            'cart_id.*' => 'required|integer'
        ]);


        $cartRequest = $request->cart_id;
        $cart = Cart::whereIn('id' , $cartRequest)->with('product')->get();
        $qvoucher = request()->query('voucher');

        $userId = Auth::user()->id;
        
       if(sizeof($cart)){
     
          if($qvoucher != ''){

              $voucher = Voucher::where('code' ,   $qvoucher)->first();

                if(!$voucher){
                    return response()->json([
                        'error' => 'Bad Request',
                        'message' => 'Voucher not Found'
                    ]);
                }

          
            $expired = date('Y-m-d H:i:s' , strtotime($voucher->expired_at));
            $now = date('Y-m-d H:i:s' , strtotime(Carbon::now()));

            if($voucher->active == 0 ){
                return response()->json([
                    'error' => 'Bad Request',
                    'message' => 'Vouchers not active'
                ],400);
             }else if($expired <= $now){
                return response()->json([
                   'error' => 'Bad Request',
                   'message' => 'Vouchers has Expired'
                ],400);
            }



            foreach($cart as $ct){

                $vd = $voucher->discount;
                
                $discount  =  ( $vd / 100 ) * $ct->product->price;

                $resultPrice = $ct->product->price - $discount;
            
                $order = Order::create([
                    'user_id'  => $userId, 
                    'product_id' => $ct->product->id,
                    'name' => $ct->product->name,
                    'status' => 'Progress',
                    'price' => $ct->product->price,
                    'total_price' => $resultPrice,
                    'voucher_id' => $voucher->id
                ]);

                $order->save();     
                
                //destroy cart
                Cart::destroy($ct->id);


            }

                return response()->json([
                    'status' => 'success'
                ],200);

            }  

            
      
            foreach($cart as $ct){
 
                $order = Order::create([
                    'user_id'  => $userId, 
                    'product_id' => $ct->product->id,
                    'name' => $ct->product->name,
                    'status' => 'Progress',
                    'price' => $ct->product->price,
                    'total_price' => $ct->product->price,
                ]);

                $order->save();

                 //destroy cart
                 Cart::destroy($ct->id);


            }
 

                return response()->json([
                    'status' => 'success'
                ],200);

       
            
        }else{
            return response()->json([
                'error' => 'Cart not found'
            ],404);

        }
  
    
    }
}
