@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Réserver une salle</h1>

    <div class="row">
        @foreach($rooms as $room)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5>{{ $room->name }}</h5>
                        <p>Capacité : {{ $room->capacity }}</p>
                        <a href="{{ route('reservation.show', $room) }}"
                           class="btn btn-primary">
                            Réserver
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
