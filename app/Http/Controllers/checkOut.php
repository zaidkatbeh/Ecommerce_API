<?php

namespace App\Http\Controllers;

use App\Http\traits\responseTrait;
use App\Models\address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class checkOut extends Controller
{
    use responseTrait;
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $address=address::where('user_id',Auth::id())->where('id',$request['address'])->first();
        if(!$address)
            return $this->errorResponse(statusCode: 400,message: "no address id provided");
        $cart=DB::table('carts')->where('user_id',Auth::id())
            ->where('carts.deleted_at',null)
            ->join('cart_items','carts.id','cart_items.cart_id')
            ->join('products','cart_items.product_id','products.id')
            ->select('cart_items.quantity','products.stripe_product_id as price')
            ->get();
        $items=[];
        foreach ($cart as $cart_item) {
            array_push($items,[
                'price'=>$cart_item->price,
                'quantity'=>$cart_item->quantity
            ]);
        }
        if(!$items)
            return  $this->errorResponse(statusCode: 400,message: "you dont have items in your cart");

        Log::info($cart);
        Stripe::setApiKey(getenv('stripe_secret'));
        $checkOutSession=Session::create([
            'line_items'=>$items,
            'metadata'=>[
                    'user_token'=>$request->bearerToken(),
                ],
            'mode' => 'payment',
            'success_url' => getenv('APP_URL') . '/api/payment?success=true',
            'cancel_url' => 'http://localhost:4200/cart',
        ]);
        return $this->successResponse(['sessionData'=>[
            'id'=>$checkOutSession->id
        ]
        ]);
    }
}
