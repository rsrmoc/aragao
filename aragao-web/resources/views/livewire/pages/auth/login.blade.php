<div class="h-screen flex justify-center items-center p-3 bg-zinc-900">
    <div class="w-full max-w-sm">
        <div class="card bg-white shadow-lg">
            <form wire:submit.prevent="login" class="card-body">
                <img src="/images/big_logo.webp" alt="Aragão Construtora" class="w-40 mx-auto" />
    
                <div>
                    <x-components.input type="email" label="Digite seu email" placeholder="Email" required
                        class="input-sm" wire:model="email" icon="fa-solid fa-at" name="email" />
                </div>
    
                <div class="mb-5">
                    <x-components.input type="password" label="Digite sua senha" placeholder="Senha" required
                        class="input-sm" wire:model="password" min="8" icon="fa-solid fa-lock" name="password" />
                </div>
    
                <button type="submit" class="btn btn-sm btn-primary mb-3">
                    <div wire:loading wire:target="login">
                        <x-components.loading class="loading-xs" />
                    </div>
                    <span>Entrar</span>
                </button>
    
                <a href="{{ route('password.request') }}" class="link link-primary text-xs">Esqueceu sua senha?</a>
            </form>
        </div>
    
        <div class="p-2">
            <a href="{{ route('politica-privacidade') }}" target="_blank" class="link link-primary text-xs">Politica de privacidade</a>
        </div>
    </div>
</div>
