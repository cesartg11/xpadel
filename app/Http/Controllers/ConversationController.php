<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    /**
     * Muestra una lista de todas las conversaciones del usuario.
     */
    public function index()
    {
        $conversations = Auth::user()->conversations;  // Asume que hay una relación 'conversations' en el modelo User
        return view('conversations.index', compact('conversations'));
    }

    /**
     * Muestra el formulario para crear una nueva conversación.
     */
    public function create()
    {
        $users = User::where('id', '!=', auth()->user()->id)->get();
        return view('conversations.create', compact('users'));
    }

    /**
     * Almacena una nueva conversación en la base de datos.
     */
    public function store(Request $request)
    {
        $conversation = Conversation::create(); // detalles adicionales según sea necesario

        // Asignar participantes, que pueden ser User o Club
        $participants = collect($request->participants_ids); // IDs de participantes
        foreach ($participants as $participantId) {
            $participant = $this->findParticipant($participantId, $request->participant_type);
            $conversation->participants()->attach($participant);
        }

        // Añadir mensaje inicial, si existe
        if ($request->filled('message')) {
            $message = new Message([
                'body' => $request->message,
                'conversation_id' => $conversation->id,
                'sender_id' => $request->user()->id,
                'sender_type' => $request->user,
            ]);
            $message->save();
        }

        return redirect()->back()->with('success', 'Conversación creada correctamente.');
    }

    /**
     * Envía un mensaje a una conversación específica.
     */
    public function sendMessage(Request $request, $conversationId)
    {
        $request->validate([
            'body' => 'required|string',  // Asegúrate de validar adecuadamente los datos entrantes
        ]);

        $conversation = Conversation::findOrFail($conversationId);
        // Asegúrate de que el usuario tiene permiso para enviar mensajes en esta conversación
        if (!$this->userCanParticipate($conversation, Auth::user())) {
            return redirect()->back()->with('error', 'No tienes permiso para enviar mensajes en esta conversación.');
        }

        try {
            // Crear y guardar el mensaje
            $message = new Message([
                'body' => $request->body,
                'sender_id' => Auth::id(),
                'sender_type' => get_class(Auth::user()),  // Esto asume que el usuario puede ser de diferentes clases
                'conversation_id' => $conversation->id,
            ]);
            $message->save();

            return redirect()->route('conversations.show', $conversation->id)->with('success', 'Mensaje enviado con éxito.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al enviar el mensaje: ' . $e->getMessage());
        }
    }

    /**
     * Verifica si el usuario puede participar en la conversación.
     *
     * @param Conversation $conversation
     * @param mixed $user
     * @return bool
     */
    private function userCanParticipate(Conversation $conversation, $user)
    {
        // Asegúrate de implementar esta función según las reglas de tu aplicación.
        return $conversation->participants()->where('id', $user->id)->exists();
    }


    /**
     * Muestra una conversación específica.
     */
    public function show(Conversation $conversation)
    {
        return view('conversations.show', compact('conversation'));
    }

    /**
     * Elimina una conversación.
     */
    public function destroy(Conversation $conversation)
    {
        try {
            $conversation->delete();
            return redirect()->route('conversations.index')->with('success', 'Conversación eliminada con éxito.');
        } catch (\Exception $e) {
            return back()->withErrors('Error al eliminar la conversación: ' . $e->getMessage());
        }
    }
}
