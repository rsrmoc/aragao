<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Aragão Construtora</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @livewireStyles
    @vite('resources/sass/app.scss')
    @stack('styles')
</head>
<body class="bg-zinc-100">
    <div class="app-loading">
        <div class="flex flex-col justify-center items-center">
            <img src="/images/big_logo.webp" alt="Logo Aragão Construtora" class="mb-3" />
            <progress class="progress w-56"></progress>
        </div>
    </div>

    {{ $slot }}

    @livewireScripts
    @vite('resources/js/app.js')
    @stack('scripts')

    <div x-data class="toast toast-top toast-end" style="z-index: 9999">
        <template x-for="toast, index in $store.toast.toasts">
            <div class="alert max-w-md" x-bind:class="`alert-${toast.type}`" x-bind:key="index">
                <template x-if="toast.type == 'info'">
                    <i class="fa-solid fa-circle-info"></i>
                </template>

                <template x-if="toast.type == 'success'">
                    <i class="fa-solid fa-circle-check"></i>
                </template>

                <template x-if="toast.type == 'warning'">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </template>
                
                <template x-if="toast.type == 'error'">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </template>

                <div class="w-full flex items-center justify-between gap-10">
                    <span x-text="toast.message" class="whitespace-normal"></span>
                    
                    <button x-on:click="$store.toast.remove(index)" class="btn btn-xs btn-ghost btn-circle">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>
        </template>
    </div>

    <dialog x-data id="appDialog" class="modal items-start pt-5">
        <div class="modal-box max-w-md">
            <h3 class="text-md font-bold mb-3" x-text="$store.dialog.title"></h3>
            <p class="text-sm" x-text="$store.dialog.message"></p>

            <div class="modal-action">
                <template x-if="$store.dialog.actions?.cancel">
                    <form method="dialog">
                        <button class="btn btn-sm" x-text="$store.dialog.actions?.cancel?.text ?? 'Cancelar'"></button>
                    </form>
                </template>

                <template x-if="$store.dialog.actions?.confirm">
                    <form method="dialog">
                        <button class="btn btn-sm btn-success" x-text="$store.dialog.actions?.confirm?.text ?? 'Ok'"
                            x-on:click="$store.dialog.actions?.confirm?.action"></button>
                    </form>
                </template>
            </div>
        </div>
    </dialog>

    <div class="alert alert-success hidden"></div>
    <div class="alert alert-info hidden"></div>
    <div class="alert alert-warning hidden"></div>
    <div class="alert alert-error hidden"></div>
</body>
</html>