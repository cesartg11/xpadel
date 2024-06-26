<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use PHPUnit\Framework\Constraint\IsTrue;

class UpdateUserRequest extends FormRequest
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
                Rule::unique('users')->ignore($this->user),
            ],
            //'password' => 'required|min:6',
            // Añadir validaciones para los campos del perfil
            'name' => 'required|max:255',
            'surname' => 'required|max:255',
            'age' => 'required|integer|max:120|min:0',
            'telephone' => 'required',
            'profile_photo_path' => 'nullable|file|mimes:jpeg,jpg,png'
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
            'email.required' => 'Es necesario ingresar un correo electrónico.',
            'email.email' => 'Por favor, ingresa una dirección de correo electrónico válida.',
            'email.unique' => 'Este correo electrónico ya está en uso. Por favor, intenta con otro.',
            // 'password.required' => 'Es necesario ingresar una contraseña.',
            // 'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'name.required' => 'Por favor, ingresa tu nombre.',
            'name.max' => 'El nombre no debe exceder los 255 caracteres.',
            'surname.required' => 'Por favor, ingresa tu apellido.',
            'surname.max' => 'El apellido no debe exceder los 255 caracteres.',
            'age.required' => 'Por favor, ingresa tu edad.',
            'age.integer' => 'La edad debe ser un número entero.',
            'age.max' => 'La edad debe ser un número menor de 120.',
            'age.min' => 'La edad debe ser un número mayor de 0.',
            'telephone.required' => 'Es necesario ingresar un número de teléfono.',
            'profile_photo_path.file' => 'Debes subir un archivo válido para la foto de perfil.',
            'profile_photo_path.mimes' => 'La foto de perfil debe ser de tipo jpeg, jpg, o png.'
        ];
    }
}
