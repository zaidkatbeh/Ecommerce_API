<?php

namespace App\Http\Controllers;

use App\Http\Resources\categoryResource;
use App\Models\category;
use Illuminate\Http\Request;
use App\Http\traits\responseTrait;

class categories extends Controller
{
    /**
     * Handle the incoming request.
     */
    use responseTrait;
    public function __invoke(Request $request)
    {
        try {
            $categories=category::get();
            return  $this->successResponse(categoryResource::collection($categories));
        }catch (\Exception $exception){
            return $this->errorResponse(exception:null,message:"an Error accorded when trying to get the categories ");
        }

    }
}
