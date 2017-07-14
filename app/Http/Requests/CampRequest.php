<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class CampRequest extends FormRequest
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
        // pegar o dia, mes e ano.
        $parts = explode('/', $this->start_date);

        // Formatar
        $this['start_date'] = date('Y-m-d', strtotime(Carbon::createFromDate($parts[2], $parts[1], $parts[0])));

        // pegar o dia, mes e ano.
        $parts = explode('/', $this->end_date);

        // Formatar
        $this['end_date'] = date('Y-m-d', strtotime(Carbon::createFromDate($parts[2], $parts[1], $parts[0])));

        return [
            'name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'type_id' => 'required',
        ];
    }
}
