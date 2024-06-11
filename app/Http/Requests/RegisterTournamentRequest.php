<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterTournamentRequest extends FormRequest
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
            'emailPlayer1' => 'required|email|exists:users,email',
            'emailPlayer2' => 'required|email|exists:users,email',
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
            'emailPlayer1.required' => 'El correo electrónico del jugador 1 es obligatorio.',
            'emailPlayer1.email' => 'Debe proporcionar una dirección de correo electrónico válida para el jugador 1.',
            'emailPlayer1.exists' => 'El correo electrónico del jugador 1 no está registrado en la base de datos.',
            'emailPlayer2.required' => 'El correo electrónico del jugador 2 es obligatorio.',
            'emailPlayer2.email' => 'Debe proporcionar una dirección de correo electrónico válida para el jugador 2.',
            'emailPlayer2.exists' => 'El correo electrónico del jugador 2 no está registrado en la base de datos.',
        ];
    }
}
