<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
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
            'email' => 'required|string|email|max:255|unique:clubs',
            //'password' => 'required|string',
            // Validaciones para el perfil del club
            'name' => 'required|string|max:255',
            'telephone' => 'required|string',
            'address' => 'required|string',
            'province' => 'required|string',
            'description' => 'nullable|string',
            // Validaciones para el horario del club
            'day_of_week' => 'required|array',
            'day_of_week.*' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'opening_time' => 'required|array',
            'opening_time.*' => 'required|date_format:H:i',
            'closing_time' => 'required|array',
            'closing_time.*' => 'required|date_format:H:i',
            // Validaciones para la foto de club (perfil)
            'photo_url' => 'nullable|mimes:jpeg,jpg,png,gif',
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

            // 'password.required' => 'Es necesario proporcionar una contraseña.',
            // 'password.string' => 'La contraseña debe ser una cadena de texto.',

            'telephone.required' => 'Es necesario proporcionar un número de teléfono.',
            'telephone.string' => 'El número de teléfono debe ser una cadena de texto.',

            'address.required' => 'Es necesario proporcionar una dirección.',
            'address.string' => 'La dirección debe ser una cadena de texto.',

            'province.required' => 'Es necesario proporcionar una provincia.',
            'province.string' => 'La provincia debe ser una cadena de texto.',

            'description.string' => 'La descripción debe ser una cadena de texto.',

            'day_of_week.required' => 'Es necesario especificar los días de la semana.',
            'day_of_week.array' => 'Los días de la semana deben ser proporcionados como un arreglo.',
            'day_of_week.*.required' => 'Cada día de la semana es obligatorio.',
            'day_of_week.*.string' => 'Cada día de la semana debe ser una cadena de texto.',
            'day_of_week.*.in' => 'Cada día de la semana debe ser uno de los siguientes: Lunes, Martes, Miércoles, Jueves, Viernes, Sábado, Domingo.',

            'opening_time.required' => 'Es necesario especificar los horarios de apertura.',
            'opening_time.array' => 'Los horarios de apertura deben ser proporcionados como un arreglo.',
            'opening_time.*.required' => 'Cada horario de apertura es obligatorio.',
            'opening_time.*.date_format' => 'El formato de la hora de apertura debe ser HH:mm.',

            'closing_time.required' => 'Es necesario especificar los horarios de cierre.',
            'closing_time.array' => 'Los horarios de cierre deben ser proporcionados como un arreglo.',
            'closing_time.*.required' => 'Cada horario de cierre es obligatorio.',
            'closing_time.*.date_format' => 'El formato de la hora de cierre debe ser HH:mm.',

            'photo_url.mimes' => 'El archivo debe ser una imagen de tipo: jpeg, jpg o png.',
        ];
    }
}
