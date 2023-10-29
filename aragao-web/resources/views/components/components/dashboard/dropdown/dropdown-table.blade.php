<div {{ $attributes->merge(['class' => 'dropdown dropdown-left dropdown-top']) }}>
    <label tabindex="0" class="btn btn-sm btn-ghost">
        <i class="fa-solid fa-ellipsis-vertical"></i>
    </label>

    <ul tabindex="0"
        class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
        {{ $slot }}
    </ul>
</div>