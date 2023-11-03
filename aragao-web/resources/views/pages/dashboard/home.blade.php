@if (!auth()->user()->password_user_set)
    <x-layouts.dashboard>
        <livewire:components.views.user-password-set />
    </x-layouts.dashboard>
@else
    <x-layouts.dashboard>
        
    </x-layouts.dashboard>
@endif