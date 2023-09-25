<?php

namespace App\Http\Controllers;

use App\Http\Resources\productsResource;
use App\Models\product;
use Illuminate\Http\Request;

class products extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $products=product::with('image');
        if($request['category']&& $request['category']>0)
        $products=$products->where('category_id',$request['category']);
        if($request['orderType'] &&$request['orderType']=='desc')
                $products=$products->orderBy('updated_at','desc');
        if($request['productsPerPage'] && !empty($request['productsPerPage']))
            $products=$products->paginate($request['productsPerPage']);
        else
            $products=$products->paginate(12);
        return productsResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
