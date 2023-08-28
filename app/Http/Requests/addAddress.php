<?php

namespace App\Http\Requests;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class addAddress extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'regionID'=>['required',Rule::exists('regions','id')->where(function (Builder $qurey){
                return $qurey->where('country_id',$this->input('countryID'));
            })
            ],
            'countryID'=>['required','exists:countries,id'],
            'phoneNumber'=>['required','min:5','max:10',Rule::unique('addresses','phone_number')],
            'streetName'=>'required|string|max:50',
        ];
    }
}
