<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Pointage visiteurs
        </h2>
    </x-slot>

    <div class="p-6">
        @foreach($checkins as $c)
            <div class="mb-2">
                {{ $c->firstname }} {{ $c->lastname }} —
                {{ $c->checked_in_at ? '✅ Présent' : '⏳ En attente' }}
                <a class="underline ml-2"
                   href="/admin/checkins/scan/{{ $c->qr_token }}">
                    Scanner
                </a>
            </div>
        @endforeach
    </div>
</x-app-layout>
