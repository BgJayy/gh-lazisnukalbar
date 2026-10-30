@props(['name'])

<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="h-5 w-5">
    @switch($name)
        @case('zakat')
            <path d="M12 3.5v2.2" />
            <path d="M4.5 8h15" />
            <path d="M12 5.7V19" />
            <path d="M8 21h8" />
            <path d="M4.5 8 2.8 13.2a2.7 2.7 0 0 0 5.2 0L6.3 8" />
            <path d="M19.5 8 17.8 13.2a2.7 2.7 0 0 0 5.2 0L21.3 8" />
            @break
        @case('infak')
            <path d="M12 20.2s-6.6-4.1-6.6-8.4a3.7 3.7 0 0 1 6.6-1.8 3.7 3.7 0 0 1 6.6 1.8c0 4.3-6.6 8.4-6.6 8.4z" />
            @break
        @case('galang')
            <path d="M4 10.2v3.6h2.4L12 17V7L6.4 10.2H4z" />
            <path d="M15.2 9.2a3.4 3.4 0 0 1 0 5.6" />
            <path d="M17.4 7a6.2 6.2 0 0 1 0 10" />
            @break
        @case('fidyah')
            <path d="M5 10.5h14" />
            <path d="M6.5 10.5c.4 4.6 2.4 7.2 5.5 7.2s5.1-2.6 5.5-7.2" />
            <path d="M8.5 10.2c.3-1.8 1.6-2.8 3.5-2.2 1.6.5 2.4.2 3.2-1.4" />
            <path d="M9.2 7.6c.4-.9 1.2-1.2 1.6-.4" />
            @break
        @case('kalkulator')
            <rect x="5" y="3" width="14" height="18" rx="2" />
            <path d="M8 7h8" />
            <circle cx="8.5" cy="12" r="0.9" fill="currentColor" stroke="none" />
            <circle cx="12" cy="12" r="0.9" fill="currentColor" stroke="none" />
            <circle cx="15.5" cy="12" r="0.9" fill="currentColor" stroke="none" />
            <circle cx="8.5" cy="16" r="0.9" fill="currentColor" stroke="none" />
            <circle cx="12" cy="16" r="0.9" fill="currentColor" stroke="none" />
            <circle cx="15.5" cy="16" r="0.9" fill="currentColor" stroke="none" />
            @break
        @case('qurban')
            <path d="M8.2 9.2c-.7-2.2.4-3.6 1.2-2" />
            <path d="M10.4 8.4c.2-2 1.6-2.6 1.9-.8" />
            <circle cx="9" cy="11" r="2" />
            <path d="M10.6 12.2c1.4-.8 5.6-1 7.6.4 1.5 1 1.6 2.8.2 3.8-1.8 1.3-6.2 1.2-8.2-.2" />
            <path d="M12.4 16.4 12 20" />
            <path d="M14.8 16.6 14.8 20.2" />
            <path d="M17.1 16.2 17.4 20" />
            <path d="M11.2 10.2 13 11.4" />
            @break
    @endswitch
</svg>
