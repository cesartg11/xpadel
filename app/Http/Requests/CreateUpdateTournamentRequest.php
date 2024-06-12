<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUpdateTournamentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validaciones para el request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:tournaments',
            'status' => 'required|string|in:Abierto,Cerrado,En progreso',
            'description' => 'required|string|max:5000',
            'photo_url' => 'nullable|file|mimes:jpeg,jpg,png',
            'start_date' => 'required|date_format:"Y-m-d"',
            'end_date' => 'required|date_format:"Y-m-d"|after:start_date',
        ];
    }

    /**
     * Mostrar mensajes personalizados de error.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'El nombre del torneo es obligatorio.',
            'name.string' => 'El nombre del torneo debe ser una cadena de texto.',
            'name.max' => 'El nombre del torneo no puede superar los 255 caracteres.',
            'name.unique' => 'Ya existe un torneo con este nombre.',
            'status.required' => 'El estado del torneo es obligatorio.',
            'status.string' => 'El estado del torneo debe ser una cadena de texto.',
            'status.in' => 'El estado del torneo no es válido.',
            'description.required' => 'La descripción del torneo es obligatoria.',
            'description.string' => 'La descripción del torneo debe ser una cadena de texto.',
            'photo_url.file' => 'El archivo debe ser una imagen.',
            'photo_url.mimes' => 'El archivo debe ser de tipo jpeg, jpg, o png.',
            'start_date.required' => 'La fecha de inicio del torneo es obligatoria.',
            'start_date.date_format' => 'La fecha de inicio debe estar en el formato debido.',
            'end_date.required' => 'La fecha de fin del torneo es obligatoria.',
            'end_date.date_format' => 'La fecha de fin debe estar en el formato debido.',
            'end_date.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.'
        ];
    }
}
