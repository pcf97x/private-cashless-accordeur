@extends('layouts.admin')

@section('content')
<h1 class="text-xl font-bold mb-6">
    {{ isset($time_slot) ? 'Modifier' : 'Créer' }} un créneau
</h1>

<form method="POST"
    action="{{ isset($time_slot) ? route('admin.time-slots.update', $time_slot) : route('admin.time-slots.store') }}">
    @csrf
    @if(isset($time_slot)) @method('PUT') @endif

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label>Code</label>
            <input type="text" name="code" class="input"
                value="{{ old('code', $time_slot->code ?? '') }}" required>
        </div>

        <div>
            <label>Libellé</label>
            <input type="text" name="label" class="input"
                value="{{ old('label', $time_slot->label ?? '') }}" required>
        </div>

        <div>
            <label>Heure début</label>
            <input type="time" name="start_time" class="input"
                value="{{ old('start_time', $time_slot->start_time ?? '') }}" required>
        </div>

        <div>
            <label>Heure fin</label>
            <input type="time" name="end_time" class="input"
                value="{{ old('end_time', $time_slot->end_time ?? '') }}" required>
        </div>

        <div>
            <label>Ordre</label>
            <input type="number" name="order_index" class="input"
                value="{{ old('order_index', $time_slot->order_index ?? 0) }}">
        </div>

        <div class="flex items-center mt-6">
            <input type="checkbox" name="active" value="1"
                {{ old('active', $time_slot->active ?? true) ? 'checked' : '' }}>
            <span class="ml-2">Actif</span>
        </div>
    </div>

    <div class="mt-6">
        <button class="btn btn-primary">Enregistrer</button>
        <a href="{{ route('admin.time-slots.index') }}" class="btn btn-secondary">Annuler</a>
    </div>
</form>
@endsection
