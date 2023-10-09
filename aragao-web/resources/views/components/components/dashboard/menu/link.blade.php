<a href="{{ route($route) }}" wire:navigate
    class="app-link-menu {{ request()->route()->getName() == $route ? 'active': '' }} hover:bg-zinc-800 hover:text-white">
    <div>
        <i class="{{ $icon }}"></i>
        <span>{{ $text }}</span>
    </div>

    <i class="fa-solid fa-arrow-right"></i>
</a>