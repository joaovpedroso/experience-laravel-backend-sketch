<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CamperCampRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rule = [
            'camper_id' => 'required|int',
            'camp_id' => 'required',
            'has_worked' => 'required'
        ];

        if ($this->has_worked === "Sim") {
            $rule['func_id'] = 'required';
        } else {
            $rule['angels'] = 'required';
        }

        return $rule;
    }
}
