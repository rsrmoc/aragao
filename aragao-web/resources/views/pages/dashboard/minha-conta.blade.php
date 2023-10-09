<x-layouts.dashboard>
    <x-components.dashboard.navbar.navbar title="Minha conta" />

    <div x-data="{
        tabActive: 1
    }">
        <div class="tabs mb-5">
            <div class="tab tab-lifted" x-bind:class="{ 'tab-active': tabActive == 1 }" x-on:click="tabActive = 1">Meus dados</div>
            <div class="tab tab-lifted" x-bind:class="{ 'tab-active': tabActive == 2 }" x-on:click="tabActive = 2">Segurança</div>
        </div>

        <div class="px-3">
            <div x-bind:class="{ 'hidden': tabActive != 1 }">
                <livewire:components.views.minha-conta.meus-dados />
            </div>

            <div x-bind:class="{ 'hidden': tabActive != 2 }">
                <livewire:components.views.minha-conta.alterar-senha />
            </div>
        </div>
    </div>
</x-layouts.dashboard>