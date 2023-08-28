<?php

namespace App\Http\Controllers;

use App\Http\traits\responseTrait;
use App\Models\country;
use Illuminate\Http\Request;

class ValidatePhoneNumber extends Controller
{
    use responseTrait;
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'phoneNumber'=>'required|unique:addresses,phone_number',
            'countryID'=>'required|exists:countries,id'
        ]);
        $country=country::find($request['countryID']);
        if($country->phoneNumberLength==strlen($request['phoneNumber']))
            return $this->successResponse('phone number is valid');
        else
            return $this->errorResponse(statusCode: 400, message: 'phone number is not valid');
    }
}
