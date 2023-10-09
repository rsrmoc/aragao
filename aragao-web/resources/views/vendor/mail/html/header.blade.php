@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Aragão Construtora')
<img src="{{ asset('/images/big_logo.webp') }}" class="logo" alt="Aragão Construtora Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
