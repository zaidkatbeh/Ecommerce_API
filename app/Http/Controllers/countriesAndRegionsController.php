<?php

namespace App\Http\Controllers;

use App\Http\traits\responseTrait;
use App\Models\country;
use App\Models\region;
use App\Http\Resources\country as countryResource;
use App\Http\Resources\regions as regionResource;

class countriesAndRegionsController extends Controller
{
    use responseTrait;
    public function countries(){
        return $this->successResponse(countryResource::collection(country::get()));
    }
    public function regions($countryID){
        return  $this->successResponse(regionResource::collection(region::whereHas('country',function ($query) use ($countryID) {
           return $query->where('id',$countryID);
        })->get()));
    }
}
