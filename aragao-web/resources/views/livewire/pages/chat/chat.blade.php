<div class="h-full">
    <div class="flex h-full">
        <div class="w-full flex-shrink-0 lg:w-80 lg:border-r flex flex-col">
            <div class="navbar border-b px-3">
                <div class="navbar-start">
                    <h3 class="text-lg font-bold">Conversas</h3>
                </div>
                <div class="navbar-end">

                    <button class="btn btn-xs btn-primary">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>

            <div class="h-full overflow-auto p-3">
                @foreach (range(0, 12) as $item)    
                    <div class="border-b last:border-0 px-2 cursor-pointer active:bg-zinc-200 hover:bg-zinc-50">
                        <div class="flex py-2 gap-3 relative">
                            <div>
                                <div class="avatar placeholder">
                                    <div class="w-14 h-14 rounded-full bg-primary border shadow-inner">
                                        <span class="text-xl text-white">GL</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col justify-center">
                                <h4 class="font-bold text-xl mb-1">Teste</h4>
                                <span class="text-xs"><strong>João:</strong> Oi, tudo bem?</span>
                            </div>

                            <div class="absolute right-0 top-4 text-xs">
                                <span>12:00</span>
                                <span class="badge badge-success badge-sm">2</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- <p class="p-5 text-center text-xs">Não há conversas</p> --}}
        </div>

        <div class=" w-full fixed top-0 left-0 h-full bg-white z-50 lg:static">
            <div class="h-full flex flex-col">
                <div>
                    <div class="navbar">
                        <div class="navbar-start gap-2">
                            <button class="btn btn-sm btn-ghost lg:hidden">
                                <i class="fa-solid fa-arrow-left"></i>
                            </button>

                            <div class="avatar placeholder">
                                <div class="w-10 h-10 rounded-full bg-primary border shadow-inner">
                                    <span class="text-lg text-white">GL</span>
                                </div>
                            </div>

                            <h3 class="font-bold text-lg">Gean Lima</h3>
                        </div>

                        <div class="navbar-end">
                            <x-components.dashboard.dropdown.dropdown-table class="dropdown-end">

                            </x-components.dashboard.dropdown.dropdown-table>
                        </div>
                    </div>
                </div>

                <div id="container-messages" style="background-image: url({{ asset('/images/chat_bg.webp') }});"
                    class="h-full overflow-auto p-2">
                    @foreach (range(0, 2) as $item)    
                        <div class="chat chat-start">
                            <div class="chat-image avatar placeholder">
                                <div class="w-10 rounded-full bg-primary text-white border">
                                    <span>GL</span>
                                </div>
                            </div>
                            <div class="chat-bubble">It was said that you would.</div>
                        </div>

                        <div class="chat chat-end">
                            <div class="chat-image avatar placeholder">
                                <div class="w-10 rounded-full bg-primary text-white border">
                                    <span>GL</span>
                                </div>
                            </div>
                            <div class="chat-header">
                                <span class="font-bold text-zinc-800">Obi-Wan Kenobi</span>
                                <time class="text-xs opacity-75">12:45</time>
                            </div>
                            <div class="chat-bubble">It was you who.</div>
                        </div>

                        <div class="chat chat-start">
                            <div class="chat-image avatar placeholder">
                                <div class="w-10 rounded-full bg-primary text-white border">
                                    <span>GL</span>
                                </div>
                            </div>
                            <div class="chat-header">
                                Obi-Wan Kenobi
                                <time class="text-xs opacity-50">12:45</time>
                            </div>
                            <div class="chat-bubble">It was said that you would, destroy the Sith, not
                                join them.</div>
                        </div>

                        <div class="chat chat-end">
                            <div class="chat-image avatar placeholder">
                                <div class="w-10 rounded-full bg-primary text-white border">
                                    <span>GL</span>
                                </div>
                            </div>
                            <div class="chat-header">
                                Obi-Wan Kenobi
                                <time class="text-xs opacity-50">12:45</time>
                            </div>
                            <div class="chat-bubble">It was you who would bring balance to the Force</div>
                        </div>

                        <div class="chat chat-start">
                            <div class="chat-image avatar placeholder">
                                <div class="w-10 rounded-full bg-primary text-white border">
                                    <span>GL</span>
                                </div>
                            </div>
                            <div class="chat-header">
                                Obi-Wan Kenobi
                                <time class="text-xs opacity-50">12:45</time>
                            </div>
                            <div class="chat-bubble">It was </div>
                        </div>

                        <div class="chat chat-end">
                            <div class="chat-image avatar placeholder">
                                <div class="w-10 rounded-full bg-primary text-white border">
                                    <span>GL</span>
                                </div>
                            </div>
                            <div class="chat-header">
                                Obi-Wan Kenobi
                                <time class="text-xs opacity-50">12:45</time>
                            </div>
                            <div class="chat-bubble">It was you who</div>
                        </div>
                    @endforeach
                </div>

                <div>
                    <div class="navbar gap-2">
                        <div class="navbar-start w-auto">
                            <button class="btn btn-ghost">
                                <i class="fa-regular fa-image"></i>
                            </button>
                        </div>
                        <div class="navbar-center w-full flex-shrink">
                            <x-components.input placeholder="Mensagem" />
                        </div>
                        <div class="navbar-end w-auto">
                            <button class="btn btn-ghost">
                                <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    @vite('resources/sass/chat.scss')
@endpush
