<div class="form-control w-full">
    @isset ($label)
        <label class="label">
            <span class="label-text">{{ $label }} @isset($required) <span class="text-red-600">*</span> @endisset</span>
        </label>
    @endisset

    <div class="{{ isset($icon) || isset($iconEnd) ? 'input-group': '' }}">
        @isset($icon)
            <span>
                <i class="{{ $icon }}"></i>
            </span>
        @endisset
        
        @if(isset($type) && $type == 'textarea')
            <textarea {{ $attributes->merge(['class' => 'textarea textarea-bordered w-full']) }}></textarea>
        @elseif(isset($type) && $type == 'file')
            <input {{ $attributes->merge(['class' => 'file-input file-input-bordered w-full']) }} />
        @else
            <input {{ $attributes->merge(['class' => 'input input-bordered w-full']) }} />
        @endif

        @isset($iconEnd)
            <span>
                <i class="{{ $iconEnd }}"></i>
            </span>
        @endisset
    </div>

    @error($name ?? "")
        <label class="label">
            <span class="label-text-alt text-red-600">{{ $message }}</span>
        </label>
    @enderror
</div>
