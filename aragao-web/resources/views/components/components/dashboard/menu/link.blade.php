<a href="{{ route($route) }}" @if (!isset($noNavigate)) wire:navigate @endif
    class="app-link-menu {{ request()->route()->getName() == $route ? 'active': '' }} hover:bg-zinc-800 hover:text-white">
    <div>
        <i class="{{ $icon }}"></i>
        <span>{{ $text }}</span>
    </div>

    <i class="fa-solid fa-arrow-right"></i>
</a>