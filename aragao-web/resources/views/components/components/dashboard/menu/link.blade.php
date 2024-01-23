<a href="{{ route($route) }}" onclick="initLoading()"
    class="app-link-menu {{ request()->route()->getName() == $route ? 'active': '' }} hover:bg-zinc-800 hover:text-white"
    @isset($blank) target="_blank" @endisset>
    <div>
        <i class="{{ $icon }}"></i>
        <span>{{ $text }}</span>
        @isset($badge)
            <span class="badge badge-success badge-sm font-bold">{{ $badge }}</span>
        @endisset
    </div>

    <i class="fa-solid fa-arrow-right"></i>
</a>