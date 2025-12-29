<x-guest-layout>
    <div class="text-center mt-10">
        <h2 class="text-xl font-bold">Merci {{ $checkin->firstname }} 🙌</h2>
        <p class="mt-4">Présente ce QR à l’accueil :</p>

        <img
            class="mx-auto mt-4"
            src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $checkin->qr_token }}"
        >
    </div>
</x-guest-layout>
