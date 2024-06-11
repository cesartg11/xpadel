<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use PHPUnit\Framework\Constraint\IsTrue;

class CreateClubRequest extends FormRequest
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
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|string|min:6',
            // Validaciones para el perfil del club
            'name' => 'required|string|max:255',
            'telephone' => 'required|unique:club_profile|regex:/^[0-9]{9}$/',
            'address' => 'required|string',
            'province' => 'required|string',
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
            'name.required' => 'Es necesario proporcionar un nombre.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no debe exceder los 255 caracteres.',

            'email.required' => 'Es necesario proporcionar un correo electrónico.',
            'email.string' => 'El correo electrónico debe ser una cadena de texto.',
            'email.email' => 'El correo electrónico debe ser una dirección válida.',
            'email.max' => 'El correo electrónico no debe exceder los 255 caracteres.',
            'email.unique' => 'Este correo electrónico ya está en uso. Utiliza otro.',

            'password.required' => 'Es necesario proporcionar una contraseña.',
            'password.string' => 'La contraseña debe ser una cadena de texto.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',

            'password_confirmation.required' => 'Es necesario proporcionar una confirmación de contraseña.',
            'password_confirmation.string' => 'La confirmación de contraseña debe ser una cadena de texto.',
            'password_confirmation.min' => 'La confirmación de contraseña debe tener al menos 6 caracteres.',

            'telephone.required' => 'Es necesario proporcionar un número de teléfono.',
            'telephone.unique' => 'El número de teléfono ya existe.',
            'telephone.regex' => 'El número de teléfono debe contener exactamente 9 dígitos.',

            'address.required' => 'Es necesario proporcionar una dirección.',
            'address.string' => 'La dirección debe ser una cadena de texto.',

            'province.required' => 'Es necesario proporcionar una provincia.',
            'province.string' => 'La provincia debe ser una cadena de texto.',
        ];
    }
}
