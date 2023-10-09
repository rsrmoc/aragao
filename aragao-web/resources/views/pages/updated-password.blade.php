<x-layouts.app>
    <div class="h-screen flex justify-center items-center p-3 bg-zinc-900">
        <div class="card bg-white shadow-lg w-full max-w-sm">
            <form method="POST" action="{{ route('password.update') }}" class="card-body">
                @csrf
                <input type="hidden" name="token" value="{{ request()->token }}" autocomplete="off" />
                <input type="hidden" name="email" value="{{ request()->email }}" autocomplete="off" />

                <img src="/images/big_logo.webp" alt="Aragão Construtora" class="w-40 mx-auto" />

                @if ($status)
                    <div class="alert alert-success">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ $message }}</span>
                    </div>
                    <a href="{{ route('login') }}" wire:navigate class="btn btn-sm btn-wide btn-primary">
                        <i class="fa-solid fa-arrow-left"></i> Entrar em minha conta
                    </a>
                @else    
                    <div class="alert alert-error">
                        <i class="fa-solid fa-bug"></i>
                        <span>{{ $message }}</span>
                    </div>
                @endif
            </form>
        </div>
    </div>
</x-layouts.app>