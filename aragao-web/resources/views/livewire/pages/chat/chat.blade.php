<div x-data="pageChat" class="h-full" x-on:novo-chat-private-selected="eventNovoChatSelected">
    <div class="flex h-full">
        <div class="w-full flex-shrink-0 lg:w-80 lg:border-r flex flex-col">
            <div class="navbar border-b px-3">
                <div class="navbar-start">
                    <h3 class="text-lg font-bold">Conversas</h3>
                </div>
                <div class="navbar-end">
                    <button class="btn btn-xs btn-primary" x-on:click="modalUsuarios = true">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>

            <div wire:loading wire:target="getChats">
                <div class="flex justify-center p-3">
                    <x-components.loading />
                </div>
            </div>

            <template x-if="chats.length == 0">
                <p wire:loading.remove wire:target="getChats" class="p-5 text-center text-xs">Não há conversas</p>
            </template>

            <div wire:loading.remove wire:target="getChats" class="h-full overflow-auto p-3">
                <template x-for="(chat,index) in chats" x-bind:key="index">
                    <div class="border-b last:border-0 px-2 cursor-pointer active:bg-zinc-200 hover:bg-zinc-200"
                        x-on:click="initChat(chat)" x-bind:class="{ 'bg-zinc-300': chatSelected?.id == chat.id }">
                        <div class="flex pt-4 pb-2 gap-3 relative">
                            <div>
                                <div class="avatar placeholder">
                                    <div class="w-12 h-12 rounded-full bg-primary border shadow-inner">
                                        <span class="text-lg text-white"
                                            x-text="initials(chat?.usuario ? chat?.usuario?.name : chat.nome)"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col justify-center overflow-hidden w-full">
                                <h4 class="font-semibold text-base mb-1 whitespace-nowrap text-ellipsis overflow-hidden"
                                    x-text="chat?.usuario ? `${chat?.usuario?.name} (${typeUsuarios[chat?.usuario?.type]})` : `${chat.nome} (Obra)`"></h4>

                                <template x-if="chat.last_message">
                                    <span class="text-xs whitespace-nowrap text-ellipsis overflow-hidden">
                                        <template x-if="chat.last_message.id_usuario == {{ auth()->user()->id }}">
                                            <strong>Eu:</strong>
                                        </template>
                                        <template x-if="chat.last_message.id_usuario != {{ auth()->user()->id }}">
                                            <strong x-text="`${chat.last_message.usuario.name} (${typeUsuarios[chat.last_message.usuario.type]}):`"></strong>
                                        </template>

                                        <span>
                                            <template x-if="chat.last_message.imagem">
                                                <i class="fa-regular fa-image"></i>
                                            </template>
                                            <span x-text="chat.last_message.mensagem"></span>
                                        </span>
                                    </span>
                                </template>
                            </div>

                            <div class="absolute right-0 top-0 p-1" style="font-size: 0.6rem">
                                <span
                                    x-text="$store.helpers.formatDateFromNow((chat.last_message ? chat.last_message.created_at : chat.created_at), 'LLL')"></span>
                                <template x-if="chat.unviewed_messages_count">
                                    <span class="badge badge-success badge-sm font-bold text-white"
                                        x-text="chat.unviewed_messages_count"></span>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="w-full fixed top-0 left-0 h-full bg-white z-50 lg:static" x-bind:class="{ 'hidden': !activeChat }">
            <div class="h-full flex flex-col">
                <div>
                    <div class="navbar border-b px-5">
                        <div class="navbar-start gap-2 w-full">
                            <button class="btn btn-sm btn-ghost lg:hidden" x-on:click="closeChat">
                                <i class="fa-solid fa-arrow-left"></i>
                            </button>

                            <h3 class="font-bold text-lg whitespace-nowrap text-ellipsis overflow-hidden">
                                <span
                                    x-text="chatSelected?.usuario ? chatSelected?.usuario?.name : chatSelected?.nome"></span>
                            </h3>
                        </div>

                        <div class="navbar-end w-max">
                            <x-components.dashboard.dropdown.dropdown-table class="dropdown-end">
                                <x-components.dashboard.dropdown.dropdown-item text="Fechar conversa"
                                    icon="fa-solid fa-xmark" x-on:click="closeChat" />
                            </x-components.dashboard.dropdown.dropdown-table>
                        </div>
                    </div>
                </div>

                <div id="container-messages" style="background-image: url({{ asset('/images/chat_bg.webp') }});"
                    class="h-full overflow-hidden relative">
                    <div class="h-max max-h-full flex flex-col-reverse overflow-auto p-2">
                        <div wire:loading wire:target="messagesFromChat" class="absolute w-full top-0 left-0">
                            <div class="flex justify-center p-3">
                                <x-components.loading />
                            </div>
                        </div>
    
                        <template x-for="message, index in messages">
                            <div class="chat"
                                x-bind:class="message.id_usuario == {{ auth()->user()->id }} ? 'chat-end' : 'chat-start'">
                                <div class="chat-image avatar placeholder">
                                    <div class="w-10 rounded-full bg-primary text-white border">
                                        <span x-text="initials(message?.usuario?.name)"></span>
                                    </div>
                                </div>
                                <template x-if="message.id_usuario !== {{ auth()->user()->id }}">
                                    <div class="chat-header">
                                        <span class="badge badge-primary badge-sm font-bold"
                                            x-text="`${message?.usuario?.name} (${typeUsuarios[message?.usuario?.type]})`"></span>
                                    </div>
                                </template>
                                <div class="chat-bubble flex flex-col gap-1">
                                    <template x-if="message?.imagem">
                                        <img x-bind:src="message?.imagem?.url" class="rounded-lg max-w-xs cursor-pointer"
                                            loading="lazy" x-on:click="setModalImage(message?.imagem?.url)" />
                                    </template>
                                    <span x-text="message?.mensagem" class="text-sm"></span>
                                    <div class="text-xs text-right opacity-50" x-text="$store.helpers.formatDateFromNow(message?.created_at, 'LLL')"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div>
                    <div class="navbar gap-2 border-t">
                        <div class="navbar-start w-auto">
                            <button class="btn btn-ghost" x-on:click="$refs.imagesChat.click()">
                                <i class="fa-regular fa-image"></i>
                            </button>
                        </div>
                        <div class="navbar-center w-full flex-shrink">
                            <x-components.input placeholder="Mensagem" x-on:keydown="inputSendMessage"
                                wire:model="inputMessage" />
                        </div>
                        <div class="navbar-end w-auto">
                            <div wire:loading.remove wire:target="messageStore">
                                <button class="btn btn-ghost" x-on:click="sendMessage">
                                    <i class="fa-solid fa-paper-plane"></i>
                                </button>
                            </div>

                            <div wire:loading wire:target="messageStore">
                                <x-components.loading class="loading-sm" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input id="images-chat" type="file" multiple accept=".jpg,.png,.webp,.jpeg,.gif" x-ref="imagesChat"
        class="hidden" wire:model="imagesChat" />

    <div class="modal" x-bind:class="{ 'modal-open': modalUsuarios }">
        <div class="modal-box max-w-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-lg">Iniciar conversa</h3>

                <button type="button" class="btn btn-sm btn-circle" x-on:click="modalUsuarios = false">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div>
                @foreach ($usuarios as $usuario)
                    <div class="border-b last:border-0 px-2">
                        <div class="flex py-2 gap-3 relative justify-between items-center">
                            <div class="flex items-center gap-2">
                                <div class="avatar placeholder">
                                    <div class="w-10 h-10 rounded-full bg-primary border shadow-inner">
                                        <span class="text-xl text-white">
                                            {{ App\Services\Helpers\StringService::initials($usuario->name) }}
                                        </span>
                                    </div>
                                </div>
                                <h4 class="font-bold text-base mb-1">{{ $usuario->name }}
                                    {{ $usuario->type == 'admin' ? '(Admin)' : '' }}</h4>
                            </div>

                            <button class="btn btn-sm btn-outline" x-on:click="initChatUser({{ $usuario }})">
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="modal" x-bind:class="{'modal-open': $wire.imagesChat.length > 0}">
        <div class="modal-box">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-lg">Enviar imagens</h3>

                <button type="button" class="btn btn-sm btn-circle" x-on:click="closeModalImagesChat">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div>
                <div class="flex overflow-x-auto p-2 gap-3">
                    @foreach ($imagesChat as $index => $item)
                        <div class="w-28 h-28 relative">
                            <button type="button" class="btn btn-sm btn-ghost btn-circle absolute"
                                wire:click="removeImagesChat({{ $index }})"
                                wire:loading.attr="disabled"
                                wire:target="removeImagesChat({{ $index }})">
                                <div wire:loading wire:target="removeImagesChat({{ $index }})">
                                    <x-components.loading class="loading-sm" />
                                </div>
                                <i wire:loading.remove wire:target="removeImagesChat({{ $index }})"
                                    class="fa-solid fa-trash text-red-600"></i>
                            </button>

                            <img src="{{ $item?->temporaryUrl() }}" class="w-full h-full object-cover rounded-md cursor-pointer"
                                x-on:click="setModalImage('{{ $item?->temporaryUrl() }}')" />
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="modal-action">
                <x-components.input placeholder="Mensagem" wire:model="inputMessage" />

                <div wire:loading.remove wire:target="messageStore">
                    <button class="btn btn-primary" x-on:click="sendMessage">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </div>

                <div wire:loading wire:target="messageStore">
                    <x-components.loading class="loading-sm" />
                </div>
            </div>
        </div>
    </div>

    <div class="modal" x-bind:class="{'modal-open': modalImage}">
        <div class="modal-box p-0 relative max-w-4xl">
            <button type="button" class="btn btn-sm btn-ghost btn-circle text-white absolute top-3 right-3" x-on:click="modalImage = false">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <img x-bind:src="modalImageSrc" class="w-full" />
        </div>
    </div>
</div>

@push('styles')
    @vite('resources/sass/chat.scss')
@endpush

@push('scripts')
    @vite('resources/js/pages/dashboard/chat.js')
@endpush
