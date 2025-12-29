


<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Pointage visiteurs
        </h2>
    </x-slot>
<form method="POST" action="{{ route('checkins.scan.weez') }}">
    @csrf
    <input
        name="barcode"
        placeholder="Scanner le QR billet"
        autofocus
        class="border p-2"
    >
    <button class="ml-2 bg-black text-white px-4 py-2">
        Scanner
    </button>
</form>

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
