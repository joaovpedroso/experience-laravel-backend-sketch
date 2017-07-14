<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CamperRequest extends FormRequest
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
     //   $date = date('d/m/Y', strtotime($this->birth));
    //    $this['birth'] = $date;


        $rules = [
            'name' => 'required',
            'birth' => 'required|date_format:d/m/Y',
            'rg' => 'required',
            'cpf' => 'required|cpf|formato_cpf',
            'place' => 'required',
            'number' => 'required',
            'bairro' => 'required',
            'uf' => 'required',
            'city' => 'required',
        ];

        return $rules;
    }
}
