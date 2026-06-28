<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Caixa de Entrada
     */
    public function index()
    {
        $messages = Message::where('receiver_id', Auth::id())
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->get();

        $unread = $messages->where('read', false)->count();

        return view('admin.messages.index', compact('messages', 'unread'));
    }

    /**
     * Mensagens Enviadas
     */
    public function sent()
    {
        $messages = Message::where('sender_id', Auth::id())
            ->with('receiver')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.messages.sent', compact('messages'));
    }

    /**
     * Formulário para escrever mensagem
     */
    public function create()
    {
        // Busca todos os utilizadores para enviar mensagem (exceto o próprio)
        $users = User::with('roles')->where('id', '!=', Auth::id())->get();

        return view('admin.messages.create', compact('users'));
    }

    /**
     * Enviar mensagem
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:usuarios,id',
            'subject'     => 'required|string|max:255',
            'body'        => 'required|string',
        ], [
            'receiver_id.required' => 'Selecione o destinatário',
            'subject.required'     => 'O assunto é obrigatório',
            'body.required'        => 'O corpo da mensagem é obrigatório',
        ]);

        Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'subject'     => $request->subject,
            'body'        => $request->body,
            'read'        => false,
        ]);

        return redirect()->route('admin.messages.sent')->with('create', '1');
    }

    /**
     * Ver detalhes de uma mensagem
     */
    public function show($id)
    {
        $message = Message::findOrFail($id);

        // Só o remetente ou destinatário pode ver a mensagem
        if ($message->sender_id !== Auth::id() && $message->receiver_id !== Auth::id()) {
            abort(403, 'Sem permissão para ver esta mensagem.');
        }

        // Marcar como lida se o utilizador atual é o destinatário
        if ($message->receiver_id === Auth::id() && !$message->read) {
            $message->update([
                'read'    => true,
                'read_at' => now(),
            ]);
        }

        return view('admin.messages.show', compact('message'));
    }

    /**
     * Apagar mensagem
     */
    public function destroy($id)
    {
        $message = Message::findOrFail($id);

        if ($message->sender_id !== Auth::id() && $message->receiver_id !== Auth::id()) {
            abort(403);
        }

        $message->delete();

        return redirect()->route('admin.messages.index')->with('destroy', '1');
    }

    /**
     * Contador de mensagens não lidas (para o menu)
     */
    public static function unreadCount()
    {
        return Message::where('receiver_id', Auth::id())->where('read', false)->count();
    }
}
