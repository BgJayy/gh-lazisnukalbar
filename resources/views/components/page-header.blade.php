@props(['kicker' => null, 'title', 'lead' => null])

<section class="border-b border-line bg-white">
    <div class="mx-auto max-w-6xl px-4 py-10 md:py-14">
        @if ($kicker)
            <p class="text-sm font-semibold text-nu">{{ $kicker }}</p>
        @endif
        <h1 class="mt-2 max-w-3xl text-3xl font-bold tracking-tight md:text-4xl">{{ $title }}</h1>
        @if ($lead)
            <p class="mt-3 max-w-2xl text-base leading-7 text-muted">{{ $lead }}</p>
        @endif
    </div>
</section>
