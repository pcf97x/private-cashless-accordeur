@if($price !== null)
    <div class="mt-4 text-xl font-bold">
        Prix : {{ number_format($price, 2, ',', ' ') }} €
    </div>
@else
    <div class="mt-4 text-red-600">
        Aucun tarif défini pour ce choix
    </div>
@endif
<script>
document.addEventListener('DOMContentLoaded', () => {

    const slotSelect = document.getElementById('time_slot_id');
    const profileSelect = document.getElementById('pricing_profile_id');
    const priceBox = document.getElementById('priceBox');

    function calculatePrice() {
        if (!slotSelect.value || !profileSelect.value) {
            priceBox.innerHTML = '';
            return;
        }

        fetch("{{ route('reservation.calculatePrice') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                room_id: {{ $room->id }},
                time_slot_id: slotSelect.value,
                pricing_profile_id: profileSelect.value
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.price !== undefined) {
                priceBox.innerHTML = `<strong>Prix : ${data.price} €</strong>`;
            } else {
                priceBox.innerHTML = '';
            }
        });
    }

    // 🔥 déclenchement automatique
    calculatePrice();

    // 🔁 changements
    slotSelect.addEventListener('change', calculatePrice);
    profileSelect.addEventListener('change', calculatePrice);
});
</script>
