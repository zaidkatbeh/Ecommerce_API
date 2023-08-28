<?php

namespace App\Http\Controllers;

use App\Http\Requests\addAddress;
use App\Http\Requests\editAddress;
use App\Http\traits\responseTrait;
use App\Models\address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\address as addressResource;
use Illuminate\Support\Facades\DB;

class addressesController extends Controller
{
    use responseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(){
        $addresses=DB::table('addresses')->where('user_id',Auth::id())
            ->join('regions','addresses.region_id','regions.id')
            ->join('countries','regions.country_id','countries.id')
            ->select('addresses.id','countries.name as countryName','addresses.street_name as streetName','regions.name as regionName','addresses.phone_number as phoneNumber')
            ->get();
        if(!$addresses[0])
            return $this->errorResponse(message: 'you dont have addresses.');
        else
        return $this->successResponse($addresses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(addAddress $request)
    {
        $addressesCount=address::where('user_id',Auth::id())->count();
        if($addressesCount==10)
            return $this->errorResponse(statusCode: 429
                ,message: 'the maximum number of addresses is 10');
        address::create([
           'user_id'=>Auth::id(),
            'phone_number'=>$request['phoneNumber'],
            'street_name'=>$request['streetName'],
            'region_id'=>$request['regionID']
        ]);
        return $this->successResponse();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(editAddress $request, string $id)
    {
        if(address::where('phone_number',$request['phoneNumber'])->where('id','!=',$id)->count())
            return [
                'errors'=>[
            'phoneNumber'=>'phoneNumber is already used'
    ]];
        $address=address::where('id',$id)->where('user_id',Auth::id())->first();
        if(!$address)
            return $this->errorResponse(statusCode: 404,message: 'there is no address with this id'.Auth::id());
            $address->region_id=$request['regionID'];
            $address->phone_number=$request['phoneNumber'];
            $address->street_name=$request['streetName'];

        return $this->successResponse(['update_status'=>$address->save(),'address'=>new addressResource($address)]);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $address=address::where('user_id',Auth::id())->find($id);
        if($address)
            return $this->successResponse(['address'=>new addressResource($address)]);
        else
            return $this->errorResponse(statusCode: 404, message: 'you dont have an address with this id');

    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
