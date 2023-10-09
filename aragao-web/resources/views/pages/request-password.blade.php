<x-layouts.app>
    <div class="h-screen flex justify-center items-center p-3 bg-zinc-900">
        <div class="card bg-white shadow-lg w-full max-w-sm">
            <form method="POST" action="{{ route('password.email') }}" class="card-body">
                @csrf

                <img src="/images/big_logo.webp" alt="Aragão Construtora" class="w-40 mx-auto" />

                <h4 class="text-center font-bold">Redefinição de senha</h4>
                <p class="text-xs text-gray-600 mb-5">Você receberá um e-mail com instruções para redefinir sua senha e
                    garantir a segurança da sua conta. Por favor, fique atento à sua caixa de entrada.</p>

                @isset($status)
                    @if ($status)
                        <div class="alert alert-success">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @else
                        <div class="alert alert-error">
                            <i class="fa-solid fa-bug"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @endif
                @else
                    <div class="mb-5">
                        <x-components.input type="email" label="Digite seu email" placeholder="Email" required
                            class="input-sm" icon="fa-solid fa-at" name="email" />
                    </div>

                    <button type="submit" class="btn btn-sm btn-primary mb-3">
                        <span>Enviar email</span>
                    </button>
                @endisset
            </form>
        </div>
    </div>
</x-layouts.app>
