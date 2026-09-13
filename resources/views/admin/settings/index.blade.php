@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="page-header">
        <h1>Parametres</h1>
        <p>Configuration des notifications et emails</p>
    </div>

    <div class="card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
            @csrf

            <div>
                <label for="conciergerie_email" class="form-label">Email conciergerie</label>
                <input type="text" name="conciergerie_email" id="conciergerie_email" required class="form-input" value="{{ old('conciergerie_email', $settings['conciergerie_email']) }}">
                <p class="text-xs text-gray-400 mt-1">Recoit toutes les notifications de reservation. Plusieurs adresses separees par une virgule.</p>
                @error('conciergerie_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="admin_email" class="form-label">Email administration</label>
                <input type="text" name="admin_email" id="admin_email" required class="form-input" value="{{ old('admin_email', $settings['admin_email']) }}">
                <p class="text-xs text-gray-400 mt-1">Adresse de secours / copie. Plusieurs adresses separees par une virgule.</p>
                @error('admin_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 border-t border-gray-100">
                <button type="submit" class="btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>

</div>
@endsection
