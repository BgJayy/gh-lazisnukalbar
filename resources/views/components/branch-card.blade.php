@props(['branch'])

<article class="rounded-3xl border border-line bg-white p-5">
    <p class="text-xs font-semibold uppercase tracking-wide text-nu">{{ $branch->type->getLabel() }}</p>
    <h2 class="mt-1 text-lg font-bold">{{ $branch->name }}</h2>
    @if ($branch->city_district)
        <p class="mt-1 text-sm text-muted">{{ $branch->city_district }}</p>
    @endif
    @if ($branch->address)
        <p class="mt-3 text-sm leading-6">{{ $branch->address }}</p>
    @endif
    @if ($branch->whatsapp_contact)
        <p class="mt-3 text-sm">WhatsApp {{ $branch->whatsapp_contact }}</p>
    @endif
</article>
