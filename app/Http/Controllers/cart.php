<?php

namespace App\Http\Controllers;

use App\Http\Requests\addToCartRequest;
use App\Http\Resources\cartResource;
use App\Http\traits\responseTrait;
use App\Models\cartItem;
use App\Models\product;
use Illuminate\Http\Request;
use App\Models\cart as cartModel;
use App\Models\cartItem as cartItemModel;
use Illuminate\Support\Facades\Auth;

class cart extends Controller
{


    use responseTrait;

    public function __construct(Request $request){
        if(!$request->isMethod('get')){
            $this->middleware('AccountVerified');
        }
    }
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $fullCart=cartModel::with('items')
            ->where('user_id',Auth::id())
            ->with('items.product')
            ->with('items.product.image')->with('items.product.category')
            ->first();
        if($fullCart==null)
            return $this->errorResponse(message: "you dont have a cart",statusCode: 418);
        return $this->successResponse(['cart'=>new cartResource($fullCart)]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(addToCartRequest $request)
    {

        $product=product::find($request['product']);
        if(!$product)
            return $this->errorResponse(statusCode: 400, message: 'there is no product with this number');
        $quantity=1;
        if($request['quantity'])
            $quantity=$request['quantity'];
        if($product->quantity-$quantity<0)
            return $this->errorResponse(statusCode: 422,message:"this product is out of stock");
       $cart= cartModel::firstOrCreate([
           'user_id'=>Auth::id(),
        ]);
        $cart_item=cartItemModel::where('cart_id',$cart->id)
            ->where('product_id',$request['product'])
            ->first();
        if(!$cart_item)
       cartItemModel::create([
           'cart_id'=>$cart->id,
           'product_id'=>$request['product'],
           'price'=>$product->price,
           'quantity'=>$quantity
       ]);
        else {
            $cart_item->quantity=+$cart_item->quantity+$quantity;
            $cart_item->save();
        }

            $fullCart=new cartResource(cartModel::with('items')->with('items.product')->with('items.product.image')->where('user_id',Auth::id())->find($cart->id));

           return $this->successResponse(['cart'=> $fullCart]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'increment'=>'required:number|in:-1,1',
        ],[
            'increment.required'=>'please enter the increment parameter',
            'increment.in'=>'the increment parameter should be 1 or -1',
        ]);
        $updateStatus=0;
            if($request['increment']==-1)
            {
                $updateStatus=cartItem::where('id',$id)->where('quantity','>',1)->whereHas('cart',function ($query){
                $query->where('user_id',Auth::id());
            })->decrement('quantity',1);
            }

            else if($request['increment']==1)
            {
                $updateStatus=cartItem::where('id',$id)->whereHas('cart',function ($query){
                    $query->where('user_id',Auth::id());
                })->increment('quantity',1);
            }
            if($updateStatus==1){
                $cart=new cartResource(cartModel::with('items')
                    ->with('items.product')
                    ->with('items.product.image')
                    ->where('user_id',Auth::id())
                    ->first());
            }
            else $cart=null;
            return $this->successResponse(['update_status'=>$updateStatus,'cart'=>$cart]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deletionStatus = cartItem::where('id', $id)->whereHas('cart', function ($query) {
            $query->where('user_id', Auth::id());
        })->delete();
        $cart=cartModel::with('items')->with('items.product')->where('user_id',Auth::id())->first();
        return $this->successResponse(['deletion_status'=>$deletionStatus,'cart'=>new cartResource($cart)]);
    }
}
