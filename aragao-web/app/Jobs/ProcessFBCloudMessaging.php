<?php

namespace App\Jobs;

use App\Models\ChatMessage;
use App\Models\ChatUsuario;
use App\Services\Firebase\FBCloudMessagingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessFBCloudMessaging implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected ChatMessage $message)
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {    
        $messaging = new FBCloudMessagingService();

        $usersChat = ChatUsuario::with('usuario')
            ->where('id_chat', $this->message->id_chat)
            ->where('id_usuario', '<>', $this->message->id_usuario)
            ->get();

        foreach($usersChat as $userChat) {
            if ($userChat->usuario->notification_token_android) {
                $messaging->sendMessage(
                    deviceId: $userChat->usuario->notification_token_android,
                    title: "Mensagem de {$this->message->usuario->name}",
                    body: $this->message->imagem ? 'Enviou uma imagem': $this->message->mensagem
                );
            }

            if ($userChat->usuario->notification_token_ios) {
                $messaging->sendMessage(
                    deviceId: $userChat->usuario->notification_token_ios,
                    title: "Mensagem de {$this->message->usuario->name}",
                    body: $this->message->imagem ? 'Enviou uma imagem': $this->message->mensagem
                );
            }
        }
    }
}
