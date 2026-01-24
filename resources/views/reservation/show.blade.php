@extends('layouts.app')

@section('content')
<div style="padding:20px;">
    <h2>{{ $room->name }}</h2>

    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    @if($errors->any())
        <div style="color:red;">
            <ul>
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('reservation.store') }}">
        @csrf

        <input type="hidden" name="room_id" value="{{ $room->id }}">

        <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
            <div>
                <label>Date</label><br>
                <input type="date" name="date" value="{{ old('date') }}">
            </div>

            <div>
                <label>Créneau</label><br>
                <select name="time_slot_id" id="time_slot_id">
                    <option value="">—</option>
                    @foreach($timeSlots as $s)
                        <option value="{{ $s->id }}" @selected(old('time_slot_id')==$s->id)>
                            {{ $s->label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label>Profil</label><br>
              <select name="pricing_profile_id" id="pricing_profile_id">
    <option value="">—</option>
    @foreach($pricingProfiles as $p)
      <option value="{{ $p->id }}">{{ $p->label }}</option>
    @endforeach
</select>
            </div>
        </div>

        <p style="margin-top:10px;">
            <strong>Prix :</strong> <span id="price_display">—</span> €
        </p>

        <div style="display:flex; gap:12px; margin-top:10px; flex-wrap:wrap;">
            <input type="text" name="name" placeholder="Nom" value="{{ old('name', $user?->name) }}">
            <input type="email" name="email" placeholder="Email" value="{{ old('email', $user?->email) }}">
            <input type="text" name="phone" placeholder="Téléphone" value="{{ old('phone') }}">
            <button type="submit">Réserver</button>
        </div>
    </form>
</div>

<script>
async function fetchPrice() {
    const timeSlotId = document.getElementById('time_slot_id').value;
    const pricingProfileId = document.getElementById('pricing_profile_id').value;
    const date = document.querySelector('input[name="date"]').value;

    const priceDisplay = document.getElementById('price_display');

    if (!timeSlotId || !pricingProfileId || !date) {
        priceDisplay.textContent = '—';
        return;
    }

    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('room_id', '{{ $room->id }}');
    formData.append('time_slot_id', timeSlotId);
    formData.append('pricing_profile_id', pricingProfileId);
    formData.append('date', date);

    try {
        const res = await fetch('{{ route('reservation.price') }}', {
            method: 'POST',
            body: formData
        });

        const data = await res.json();
        priceDisplay.textContent = data.price ? Number(data.price).toFixed(2) : '—';
    } catch (e) {
        priceDisplay.textContent = '—';
    }
}

['time_slot_id','pricing_profile_id'].forEach(id =>
    document.getElementById(id).addEventListener('change', fetchPrice)
);
document.querySelector('input[name="date"]').addEventListener('change', fetchPrice);
</script>

@endsection
