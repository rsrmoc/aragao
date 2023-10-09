<x-layouts.app>
    <div class="h-screen flex justify-center items-center p-3 bg-zinc-900">
        <div class="card bg-white shadow-lg w-full max-w-sm">
            <form method="POST" action="{{ route('password.update') }}" class="card-body">
                @csrf
                <input type="hidden" name="token" value="{{ request()->token }}" autocomplete="off" />
                <input type="hidden" name="email" value="{{ request()->email }}" autocomplete="off" />

                <img src="/images/big_logo.webp" alt="Aragão Construtora" class="w-40 mx-auto" />

    
                <div>
                    <x-components.input type="password" label="Digite sua nova senha" placeholder="Nova senha" required
                        class="input-sm" icon="fa-solid fa-lock" name="password" />
                </div>
    
                <div class="mb-5">
                    <x-components.input type="password" label="Confirme a nova senha" placeholder="Confirme a nova senha" required
                        class="input-sm" min="8" icon="fa-solid fa-lock" name="password_confirmation" />
                </div>
    
                <button type="submit" class="btn btn-sm btn-primary mb-3">
                    <span>Alterar senha</span>
                </button>
            </form>
        </div>
    </div>
</x-layouts.app>