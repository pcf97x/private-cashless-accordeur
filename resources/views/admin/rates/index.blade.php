@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4">Grille tarifaire</h1>

    <form method="POST" action="{{ route('admin.rates.store') }}">
        @csrf

        @foreach ($rooms as $room)

            {{-- 🏷️ HEADER SALLE --}}
            <div class="card mb-4">
                <div class="card-header fw-bold">
                    {{ $room->name }}
                </div>

                <div class="card-body p-0">
                    <table class="table table-bordered mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 30%">Créneau</th>
                                @foreach ($profiles as $profile)
                                    <th class="text-center">
                                        {{ $profile->label }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($timeSlots as $slot)
                                <tr>
                                    <td>{{ $slot->label }}</td>

                                    @foreach ($profiles as $profile)
                                        @php
                                            $key = $room->id . '_' . $slot->id . '_' . $profile->id;
                                        @endphp
                                        <td>
                                            <input
                                                type="number"
                                                step="0.01"
                                                class="form-control text-end"
                                                name="rates[{{ $room->id }}][{{ $slot->id }}][{{ $profile->id }}]"
                                                value="{{ $rates[$key]->price ?? '' }}"
                                            >
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        @endforeach

        <div class="text-end">
            <button class="btn btn-primary">
                Enregistrer les tarifs
            </button>
        </div>

    </form>
</div>
@endsection
