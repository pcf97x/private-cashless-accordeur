@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Compléter / modifier un visiteur</h1>

    <form method="POST" action="{{ route('checkins.update', $checkin->weez_ticket_code) }}">
        @csrf

        <div class="mb-3">
            <label>Prénom</label>
            <input type="text" name="firstname" class="form-control"
                   value="{{ old('firstname', $checkin->firstname) }}">
        </div>

        <div class="mb-3">
            <label>Nom</label>
            <input type="text" name="lastname" class="form-control"
                   value="{{ old('lastname', $checkin->lastname) }}">
        </div>

        <div class="mb-3">
            <label>Société</label>
            <input type="text" name="company" class="form-control"
                   value="{{ old('company', $checkin->company) }}">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control"
                   value="{{ old('email', $checkin->email) }}">
        </div>

        <div class="mb-3">
            <label>Motif</label>
            <input type="text" name="purpose" class="form-control"
                   value="{{ old('purpose', $checkin->purpose) }}">
        </div>

        <button class="btn btn-success">💾 Enregistrer</button>
        <a href="{{ route('checkins.index') }}" class="btn btn-secondary">Retour</a>
    </form>
</div>
@endsection
