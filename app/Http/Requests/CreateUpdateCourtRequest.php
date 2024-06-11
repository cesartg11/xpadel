<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUpdateCourtRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validaciones para el request
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'number' => 'required|integer|min:0|max:999',
            'type' => 'required|in:Cristal,Muro',
        ];
    }

    /**
     * Mostrar mensajes personalizados de error
     *
     * @return array
     */
    public function messages()
    {
        return [
            'number.required' => 'El número es obligatorio.',
            'number.integer' => 'El número de la pista debe ser un entero.',
            'number.min' => 'El número debe ser mayor o igual a 0.',
            'number.max' => 'El número debe ser menor o igual a 999.',
            'type.required' => 'El tipo es obligatorio.',
            'type.in' => 'El tipo solo acepta los valores Muro y Cristal.',
        ];
    }
}
