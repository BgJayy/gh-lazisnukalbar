<nav class="fixed inset-x-0 bottom-0 z-50 border-t border-line bg-white lg:hidden" aria-label="Pintasan">
    <ul class="grid grid-cols-5">
        @foreach ([
            ['home', 'Home', route('home'), request()->routeIs('home')],
            ['campaign', 'Campaign', route('campaign.index'), request()->routeIs('campaign.*')],
            ['zakat', 'Zakat', route('ziswaf.zakat'), request()->routeIs('ziswaf.zakat')],
            ['infak', 'Infak', route('ziswaf.infak'), request()->routeIs('ziswaf.infak')],
            ['akun', 'Akun', route('akun'), request()->routeIs('akun')],
        ] as [$icon, $label, $href, $active])
            <li>
                <a href="{{ $href }}" class="flex flex-col items-center gap-1 px-1 py-2 text-[11px] font-semibold {{ $active ? 'text-nu' : 'text-muted' }}" @if ($active) aria-current="page" @endif>
                    <x-nav-icon :name="$icon" />
                    {{ $label }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>
