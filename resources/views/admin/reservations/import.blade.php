@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">

    <a href="{{ route('admin.reservations.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-accordeur-600 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour aux reservations
    </a>

    <div class="page-header">
        <h1>Importer des reservations</h1>
        <p>Importez un fichier CSV pour creer plusieurs reservations d'un coup</p>
    </div>

    <div class="card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.reservations.import') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label for="file" class="form-label">Fichier CSV</label>
                <input type="file" name="file" id="file" required accept=".csv,.txt,.xlsx,.xls" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-accordeur-50 file:text-accordeur-600 hover:file:bg-accordeur-100 file:cursor-pointer file:transition-colors">
                @error('file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="default_status" class="form-label">Statut par defaut</label>
                    <select name="default_status" id="default_status" required class="form-input">
                        <option value="paid">Paye</option>
                        <option value="gratuit">Gratuit</option>
                        <option value="pending">En attente</option>
                    </select>
                </div>
                <div>
                    <label for="default_payment_method" class="form-label">Mode de reglement</label>
                    <select name="default_payment_method" id="default_payment_method" class="form-input">
                        <option value="especes">Especes</option>
                        <option value="carte">Carte bancaire</option>
                        <option value="virement">Virement</option>
                        <option value="cheque">Cheque</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    Importer
                </button>
                <a href="{{ route('admin.reservations.index') }}" class="btn-ghost">Annuler</a>
            </div>
        </form>
    </div>

    {{-- Format attendu --}}
    <div class="card p-6 mt-6">
        <h3 class="font-display font-bold text-gray-900 mb-3">Format du fichier CSV</h3>
        <p class="text-sm text-gray-500 mb-4">Separateur : <strong>point-virgule (;)</strong> — Encodage : <strong>UTF-8</strong></p>

        <div class="overflow-x-auto">
            <table class="text-xs w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-3 py-2 text-left font-semibold text-gray-600">salle</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600">date</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600">creneau</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600">client</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600">email</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600">telephone</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600">profil</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600">statut</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600">reglement</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600">heure_debut</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600">heure_fin</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600">evenement</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600">visibilite</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t">
                        <td class="px-3 py-2 text-gray-700">Salle 1</td>
                        <td class="px-3 py-2 text-gray-700">15/09/2026</td>
                        <td class="px-3 py-2 text-gray-700">AM</td>
                        <td class="px-3 py-2 text-gray-700">DPJJ</td>
                        <td class="px-3 py-2 text-gray-700">contact@dpjj.gf</td>
                        <td class="px-3 py-2 text-gray-700">0694000000</td>
                        <td class="px-3 py-2 text-gray-700">ADHERENT</td>
                        <td class="px-3 py-2 text-gray-700">paye</td>
                        <td class="px-3 py-2 text-gray-700">virement</td>
                        <td class="px-3 py-2 text-gray-700">7h</td>
                        <td class="px-3 py-2 text-gray-700">12h</td>
                        <td class="px-3 py-2 text-gray-700">Concours educateurs</td>
                        <td class="px-3 py-2 text-gray-700">public</td>
                    </tr>
                    <tr class="border-t">
                        <td class="px-3 py-2 text-gray-700">Coworking</td>
                        <td class="px-3 py-2 text-gray-700">04/09/2026</td>
                        <td class="px-3 py-2 text-gray-700">PM</td>
                        <td class="px-3 py-2 text-gray-700">EDEN</td>
                        <td class="px-3 py-2 text-gray-700">eden@asso.org</td>
                        <td class="px-3 py-2 text-gray-700">0694111111</td>
                        <td class="px-3 py-2 text-gray-700"></td>
                        <td class="px-3 py-2 text-gray-700">gratuit</td>
                        <td class="px-3 py-2 text-gray-700">18h30</td>
                        <td class="px-3 py-2 text-gray-700">22h</td>
                        <td class="px-3 py-2 text-gray-700">DEBAT</td>
                        <td class="px-3 py-2 text-gray-700">public</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p class="text-xs text-gray-400 mt-3">
            Colonnes obligatoires : <strong>salle, date, creneau, client</strong>. Toutes les autres sont optionnelles.<br>
            <strong>profil</strong> : code ou libelle. <strong>statut</strong> : paye, attente, gratuit.
            <strong>reglement</strong> : especes, carte, virement, cheque.<br>
            <strong>heure_debut / heure_fin</strong> : horaires reels (7h, 9h30, 18h30...). Si vide, utilise les heures du creneau.
        </p>
    </div>

</div>
@endsection
