<x-app-layout>
    <div class="p-6">
        @if($checkin->checked_in_at)
            <p>Déjà pointé à {{ $checkin->checked_in_at }}</p>
        @else
            <p>Pointage validé ✅</p>
        @endif
    </div>
</x-app-layout>
