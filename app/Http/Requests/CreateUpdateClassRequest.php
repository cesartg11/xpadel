<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUpdateClassRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // Asegúrate de que esta lógica cumple con tus políticas de autorización
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'court_id' => 'required|exists:courts,id',
            'level' => 'required|in:Pro,Principiante,Medio',
            'start_time' => 'required|date_format:"Y-m-d H:i"',
            'end_time' => 'required|date_format:"Y-m-d H:i"|after:start_time'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'court_id.required' => 'Debe seleccionar una pista obligatoriamente',
            'court_id.exists' => 'La pista seleccionada no existe.',

            'level.required' => 'El nivel de la clase es obligatorio.',
            'level.string' => 'El nivel debe ser una cadena de texto.',
            'level.max' => 'El nivel no puede tener más de 255 caracteres.',

            'start_time.required' => 'La hora de inicio es obligatoria.',
            'start_time.date_format' => 'La hora de inicio no tiene un formato válido. Use el formato aaaa-mm-dd hh:mm.',

            'end_time.required' => 'La hora de finalización es obligatoria.',
            'end_time.date_format' => 'La hora de finalización no tiene un formato válido. Use el formato aaaa-mm-dd hh:mm.',
            'end_time.after' => 'La hora de finalización debe ser posterior a la hora de inicio.'
        ];
    }
}
