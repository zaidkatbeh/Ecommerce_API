<?php

namespace App\Http\Controllers;

use App\Http\traits\responseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            Log::info($user);
            $cart=\App\Models\cart::where('user_id',$user->id)->delete();
            Log::info("_____________________________________________");
            Log::info($cart);
        }

        return response()->json(['success' => true]);
    }
}
