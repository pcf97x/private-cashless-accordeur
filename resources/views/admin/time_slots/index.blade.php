@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-xl font-bold">Créneaux horaires</h1>
    <a href="{{ route('admin.time-slots.create') }}" class="btn btn-primary">+ Nouveau créneau</a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('warning'))
<div class="alert alert-warning">{{ session('warning') }}</div>
@endif

<table class="table-auto w-full">
    <thead>
        <tr>
            <th>Ordre</th>
            <th>Code</th>
            <th>Libellé</th>
            <th>Heures</th>
            <th>Actif</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($timeSlots as $slot)
        <tr>
            <td>{{ $slot->order_index }}</td>
            <td>{{ $slot->code }}</td>
            <td>{{ $slot->label }}</td>
            <td>{{ $slot->start_time }} → {{ $slot->end_time }}</td>
            <td>{{ $slot->active ? 'Oui' : 'Non' }}</td>
            <td class="text-right">
                <a href="{{ route('admin.time-slots.edit', $slot) }}" class="text-blue-600">Modifier</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
