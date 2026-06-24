<?php

namespace App\Http\Controllers\Voucher;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    //


    public function addVoucher(Request $request){


        $qActive = request()->query('active');

        try{

        if($qActive != '' && $qActive == true){

            $request->validate([
                'code' => 'required|string|max:8|min:8',
                'discount' => 'required|integer|max:100',
                'active' => 'required|boolean',
                'expired_at' => 'required|string' 
            ]);


            $carbon = Carbon::now();
            $actived = date('Y-m-d H:i:s' , strtotime($carbon));
            $expired = date('Y-m-d H:i:s' , strtotime($request->expired_at));

            $voucher = Voucher::create([
                'code' => $request->code,
                'discount' => $request->discount,
                'active' => 1,
                'actived_at' => $actived,
                'expired_at' => $expired
            ]);

            return response()->json([
                'data' => $voucher
            ],201);

        }else{

            $request->validate([
                'code' => 'required|string|max:8|min:8',
                'discount' => 'required|integer|max:100',
                'actived_at' => 'required|string',
                'expired_at' => 'required|string' 
            ]);

         

            $actived = date('Y-m-d H:i:s' , strtotime($request->actived_at));
            $expired = date('Y-m-d H:i:s' , strtotime($request->expired_at));


            $voucher = Voucher::create([
                'code' => $request->code,
                'discount' => $request->discount,
                'active' => 0,
                'actived_at' => $actived,
                'expired_at' => $expired
            ]);

            return response()->json([
                'data' => $voucher
            ],201);

        }

        }catch(Exception $e){

            return response()->json([
                'error' => 'Internal server error'
            ],500);
        }
    }
}
