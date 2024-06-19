<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use PHPUnit\Framework\Constraint\IsTrue;

class UpdateClubRequest extends FormRequest
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
            'email' => [
                'required',
                'email',
                //Rule::unique('users', 'email')->ignore($this->user),
            ],
            'name' => 'required|string|max:255',
            'telephone' => 'required|string',
            'address' => 'required|string',
            'province' => 'required|string',
            'Lunes_apertura' => 'required|date_format:H:i',
            'Lunes_cierre' => 'required|date_format:H:i',
            'Martes_apertura' => 'required|date_format:H:i',
            'Martes_cierre' => 'required|date_format:H:i',
            'Miércoles_apertura' => 'required|date_format:H:i',
            'Miércoles_cierre' => 'required|date_format:H:i',
            'Jueves_apertura' => 'required|date_format:H:i',
            'Jueves_cierre' => 'required|date_format:H:i',
            'Viernes_apertura' => 'required|date_format:H:i',
            'Viernes_cierre' => 'required|date_format:H:i',
            'Sábado_apertura' => 'required|date_format:H:i',
            'Sábado_cierre' => 'required|date_format:H:i',
            'Domingo_apertura' => 'required|date_format:H:i',
            'Domingo_cierre' => 'required|date_format:H:i',
            'photo_url' => 'nullable|mimes:jpeg,jpg,png',
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
            'email.required' => 'Es necesario proporcionar un correo electrónico.',
            'email.email' => 'El correo electrónico debe ser una dirección válida.',
            //'email.unique' => 'Este correo electrónico ya está en uso. Utiliza otro.',
            'name.required' => 'Es necesario proporcionar un nombre.',
            'telephone.required' => 'Es necesario proporcionar un número de teléfono.',
            'address.required' => 'Es necesario proporcionar una dirección.',
            'province.required' => 'Es necesario proporcionar una provincia.',
            'date_format' => 'La hora debe estar en el formato HH:mm.',
            'photo_url.mimes' => 'El archivo debe ser una imagen de tipo: jpeg, jpg o png.',
        ];
    }
}
