<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\URL;

class UserRequest extends FormRequest
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
        // Pega a URL de onde veio.
        $url = URL::previous();

        // Define as regras
        $rules = [
            'name' => 'required|max:60',
            'email' => 'required|email'
        ];

        // Verifica se adiciona senha como obrigatória
        if (!str_contains($url, 'edit')) {
            $rules['password'] = 'required|min:3|max:30|confirmed';
        } else {
            // SE nao for mas ainda sim esteja preenchida
            if (isset($this->password)) {
                $rules['password'] = 'min:3|max:30|confirmed';
            }
        }

        return $rules;
    }
}
