@props(['name'])

<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="h-5 w-5">
    @switch($name)
        @case('home')
            <path d="M4 10.5 12 4l8 6.5" />
            <path d="M6.5 9.8V20h11V9.8" />
            @break
        @case('campaign')
            <path d="M15.2 5.4c1.1-1.2 2.9-1 3.6.4.7 1.3.2 2.8-1.1 3.8L15.2 12l-2.5-2.4c-1.3-1-1.8-2.5-1.1-3.8.7-1.4 2.5-1.6 3.6-.4z" />
            <path d="M4.5 14.2c.6 2.8 3 4.8 6.2 5.1" />
            <path d="M6.4 13.2v2.4" />
            <path d="M8.6 12.6V16" />
            <path d="M10.8 12.8v2.8" />
            @break
        @case('zakat')
            <path d="M12 19.2s-6.2-3.8-6.2-8a3.5 3.5 0 0 1 6.2-1.8 3.5 3.5 0 0 1 6.2 1.8c0 4.2-6.2 8-6.2 8z" />
            @break
        @case('infak')
            <path d="M7.6 13.2V9.4" />
            <path d="M10.4 12.4V8" />
            <path d="M13.2 12.6V8.6" />
            <path d="M16 13.4V10.4" />
            <path d="M6.2 14.4c-.8 1.6-.2 3.4 1.4 4.4 1.8 1.1 4.2 1.6 6.2 1.6h1.4c2 0 3.4-1.3 3.4-3.1v-1.6" />
            <path d="M6.2 14.4c-1.1.2-1.9 1-2 2" />
            @break
        @case('akun')
            <circle cx="12" cy="8" r="3.2" />
            <path d="M5.5 19.2c1.4-2.8 3.7-4.2 6.5-4.2s5.1 1.4 6.5 4.2" />
            @break
    @endswitch
</svg>
