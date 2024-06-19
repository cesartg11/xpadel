<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use PHPUnit\Framework\Constraint\IsTrue;

class CreateUserRequest extends FormRequest
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
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|string|min:6',
            // Añadir validaciones para los campos del perfil
            'name' => 'required|max:255',
            'surname' => 'required|max:255',
            'age' => 'required|integer|min:0|max:120',
            'telephone' => 'required|unique:user_profile|regex:/^[0-9]{9}$/',
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
            'email.required' => 'El campo correo electrónico es obligatorio.',
            'email.email' => 'Debe proporcionar una dirección de correo electrónico válida.',
            'email.unique' => 'El correo electrónico ya está registrado. Por favor, use otro.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.string' => 'La contraseña debe ser una cadena de texto.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password_confirmation.required' => 'Es necesario proporcionar una confirmación de contraseña.',
            'password_confirmation.string' => 'La confirmación de contraseña debe ser una cadena de texto.',
            'password_confirmation.min' => 'La confirmación de contraseña debe tener al menos 6 caracteres.',
            'name.required' => 'El campo nombre es obligatorio.',
            'name.max' => 'El nombre no debe exceder los 255 caracteres.',
            'surname.required' => 'El campo apellido es obligatorio.',
            'surname.max' => 'El apellido no debe exceder los 255 caracteres.',
            'age.required' => 'El campo edad es obligatorio.',
            'age.integer' => 'La edad debe ser un número entero.',
            'age.min' => 'La edad debe ser un numero mayor de 0.',
            'age.max' => 'La edad debe ser un número menor de 120.',
            'telephone.required' => 'El campo teléfono es obligatorio.',
            'telephone.unique' => 'El número de teléfono ya existe.',
            'telephone.regex' => 'El número de teléfono debe contener exactamente 9 dígitos.',
        ];
    }
}
