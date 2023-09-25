<?php

namespace App\Http\Controllers;

use App\Http\traits\responseTrait;
use App\Models\product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;
use Stripe\Stripe;
use Stripe\Webhook;

class handleSuccessfulPayment extends Controller
{
    use responseTrait;
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $payload = $request->getContent();
        $event = null;
        $userToken = '';
        try {
            $event = Webhook::constructEvent($payload, $request->header('stripe-signature'),secret: getenv('stripe_webhook_secret'));
        } catch (\Exception $e) {
            // Handle invalid webhook signature
            return $this->errorResponse(statusCode: 400, message: "Invalid webhook signature");
        }
        \Illuminate\Support\Facades\Log::info("-----------------------------------------------
        Event Type $event->type
        ");
        // Handle specific event types, e.g., payment succeeded
        if ($event->type === 'checkout.session.completed') {
            $userToken = $event->data->object->metadata->user_token;

            $user=PersonalAccessToken::findToken($userToken)->tokenable()->first('id');
            Log::info('the user id is : '.$user->id);
            $cart=\App\Models\cart::where('user_id',$user->id)
                ->whereNull('deleted_at')
                ->first();
            Log::info('--------------------------------------------the cart');
            Log::info($cart);

            $cartItems=DB::table('cart_items')
                ->where('cart_id',$cart->id)
                ->select('quantity','product_id')
                ->get();
            Log::info('----------cart items');
            Log::info($cartItems);
            foreach ($cartItems as $cartItem){
                DB::table('products')
                    ->where('id',$cartItem->product_id)
                    ->decrement('quantity',+$cartItem->quantity);
            }
//            DB::table('products')
//                ->join('cart_items', 'products.id', '=', 'cart_items.product_id')
//                ->join('carts', 'cart_items.cart_id', '=', 'carts.id')
//                ->where('carts.id',$cart->id)
//                ->decrement('products.quantity', amount: 'cart_items.quantity');
            Log::info("_____________________________________________");
            $cart->delete();
            Log::info($cart);
        }

        return response()->json(['success' => true]);
    }
}
