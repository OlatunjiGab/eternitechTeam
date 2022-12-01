<tr>
    <td class="header">
        <a href="{{ $url }}">
            @if (trim($slot) == "CRM")
                <center><img src="{{ url('/la-assets/eterni_logo.png') }}"></center>
            @else
                {{ $slot }}
            @endif
        </a>
    </td>
</tr>
