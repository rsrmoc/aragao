<?php

namespace App\Livewire\Pages\Chat;

use App\Models\Chat as ModelsChat;
use App\Models\ChatMessage;
use App\Models\ChatMessageView;
use App\Models\ChatUsuario;
use App\Models\Imagens;
use App\Models\ObrasUsuarios;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class Chat extends Component
{
    use WithFileUploads;

    public $idChatMessange = null;
    public $inputMessage = null;

    public $novoChat = false;
    public $novoChatUser = null;

    public $imagesChat = [];

    public function messageStore() {
        try {
            DB::beginTransaction();

            if ($this->novoChat) {
                $chat = ModelsChat::create(['tipo' => 'private']);
                $chat->usuario = User::find($this->novoChatUser);
                $this->dispatch('novo-chat-private-selected', $chat);

                $this->idChatMessange = $chat->id;

                ChatUsuario::create(['id_chat' => $chat->id, 'id_usuario' => Auth::user()->id]);
                ChatUsuario::create(['id_chat' => $chat->id, 'id_usuario' => $this->novoChatUser]);

                $this->reset('novoChat', 'novoChatUser');
            }

            if (count($this->imagesChat) > 0) {
                $messages = [];

                foreach($this->imagesChat as $image) {
                    $message = ChatMessage::create([
                        'id_chat' => $this->idChatMessange,
                        'id_usuario' => Auth::user()->id,
                        'mensagem' => $this->inputMessage
                    ]);
                    $message->load('usuario');
                    
                    $message->imagem = Imagens::create([
                        'tabela_id' => $message->id,
                        'tabela_type' => 'App\Models\ChatMessage',
                        'tipo' => $image->extension(),
                        'tamanho' => $image->getSize(),
                        'imagem' => base64_encode(file_get_contents($image->getRealPath()))
                    ]);

                    array_push($messages, $message);
                    
                    if ($this->inputMessage) $this->inputMessage = null;
                }

                $this->reset('imagesChat');

                DB::commit();

                return $messages;
            }

            $message = ChatMessage::create([
                'id_chat' => $this->idChatMessange,
                'id_usuario' => Auth::user()->id,
                'mensagem' => $this->inputMessage
            ]);
            $message->load('usuario');

            DB::commit();

            return $message;
        }
        catch(Exception $e) {
            DB::rollBack();

            $this->dispatch('toast-event', 'Não foi possivel enviar a mensagem. '.$e->getMessage(), 'error');
        }
    }

    public function messagesFromChat() {
        $chat = ModelsChat::with('unviewedMessages')->find($this->idChatMessange);
        foreach ($chat->unviewedMessages as $message) {
            ChatMessageView::create([
                'id_message' => $message->id,
                'id_usuario' => Auth::user()->id
            ]);
        }

        return ChatMessage::with(['usuario', 'visualizada', 'imagem'])->where('id_chat', $this->idChatMessange)->get();
    }

    public function getChats() {
        $authUserId = Auth::user()->id;
        $idsChats = ChatUsuario::where('id_usuario', $authUserId)->get('id_chat');

        $chats = Auth::user()->type !== 'admin' && !Auth::user()->engineer_admin
            ? ModelsChat::with('lastMessage')->withCount('unviewedMessages')->whereIn('id', $idsChats)->get()
            : ModelsChat::with('lastMessage')->withCount('unviewedMessages')->whereIn('id', $idsChats)->orWhere('tipo', 'group')->get();

        foreach($chats as $chat) {
            if ($chat->tipo == 'group') continue;

            $chatUsuario = ChatUsuario::firstWhere([
                'id_chat' => $chat->id,
                ['id_usuario', '<>', Auth::user()->id]
            ]);

            $chat->usuario = User::find($chatUsuario->id_usuario);
        }

        return $chats;
    }

    public function resetImagesChat() {
        $this->reset('imagesChat');
    }

    public function removeImagesChat($index) {
        unset($this->imagesChat[$index]);
    }

    #[Layout('components.layouts.dashboard')]
    public function render()
    {
        $authUserId = Auth::user()->id;

        if (Auth::user()->type !== 'admin' && !Auth::user()->engineer_admin) {
            $idsObras = ObrasUsuarios::where('id_usuario', $authUserId)->get('id_obra');
            $usuarios = User::whereIn('id', ObrasUsuarios::whereIn('id_obra', $idsObras)->get('id_usuario'))
                ->orWhere('type', 'admin')
                ->where('id', '<>', $authUserId)->get();
        }
        else {
            $usuarios = User::where('id', '<>', $authUserId)->get();
        }

        return view('livewire.pages.chat.chat', compact('usuarios'));
    }
}
