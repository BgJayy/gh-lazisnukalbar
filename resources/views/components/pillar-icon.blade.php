@props(['name'])

<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="h-5 w-5">
    @switch($name)
        @case('cerdas')
            <path d="M3 10.5 12 6.5l9 4-9 4-9-4z" />
            <path d="M7.2 12.2v3.2c1.4 1.3 8.2 1.3 9.6 0v-3.2" />
            <path d="M21 10.5v5.5" />
            @break
        @case('berdaya')
            <path d="M12 3.2a1.3 1.3 0 0 1 1.3 1.3c0 .8-.7 1.2-1.3 2-.6-.8-1.3-1.2-1.3-2A1.3 1.3 0 0 1 12 3.2z" />
            <path d="M4 12.2 12 7l8 5.2" />
            <path d="M6.2 11.6V19h11.6v-7.4" />
            <path d="M10 19v-3.2h4V19" />
            @break
        @case('sehat')
            <path d="M12 16.2c-3.2-1.8-5.2-3.6-5.2-6a2.6 2.6 0 0 1 4.4-1.8L12 9.2l.8-.8a2.6 2.6 0 0 1 4.4 1.8c0 2.4-2 4.2-5.2 6z" />
            <path d="M12 10.6v2.6" />
            <path d="M10.7 11.9h2.6" />
            <path d="M6.5 16.8c1.6 1.4 4 2.2 5.5 2.2s3.9-.8 5.5-2.2" />
            @break
        @case('damai')
            <path d="M12 9.2c.5-1.2 2.1-1.4 2.7-.3.5.9-.2 1.8-1.2 2.6L12 12.6l-1.5-1.1c-1-.8-1.7-1.7-1.2-2.6.6-1.1 2.2-.9 2.7.3z" />
            <path d="M4.5 14.8c2.2 1.4 4.6 2.2 7.5 2.2s5.3-.8 7.5-2.2" />
            <path d="M6.2 15.2 4.2 18.4" />
            <path d="M17.8 15.2 19.8 18.4" />
            <path d="M8.2 18.4h7.6" />
            @break
        @case('hijau')
            <path d="M5 18.5C5 11 10.2 5.5 19 5.2 18.6 14.2 12.8 18.8 5 18.5z" />
            <path d="M8.5 15.2c2.2-2.4 4.6-5.2 7-7.6" />
            @break
    @endswitch
</svg>
