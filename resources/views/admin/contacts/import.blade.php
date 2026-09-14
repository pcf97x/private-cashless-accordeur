@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">

    <a href="{{ route('admin.contacts.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-accordeur-600 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour aux contacts
    </a>

    <div class="page-header">
        <h1>Importer des contacts</h1>
        <p>Importez un fichier CSV ou Excel pour creer les contacts et generer les acces</p>
    </div>

    <div class="card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.contacts.import') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label for="file" class="form-label">Fichier CSV ou Excel</label>
                <input type="file" name="file" id="file" required accept=".csv,.txt,.xlsx,.xls" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-accordeur-50 file:text-accordeur-600 hover:file:bg-accordeur-100 file:cursor-pointer file:transition-colors">
            </div>

            <div class="space-y-3">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="create_weezevent" value="1" checked class="rounded border-gray-300 text-accordeur-500 focus:ring-accordeur-500">
                    <div>
                        <span class="text-sm font-medium text-gray-700">Creer les acces Weezevent</span>
                        <p class="text-xs text-gray-400">Genere un billet WeezAccess avec QR code pour chaque contact (necessite un email)</p>
                    </div>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="send_email" value="1" class="rounded border-gray-300 text-accordeur-500 focus:ring-accordeur-500">
                    <div>
                        <span class="text-sm font-medium text-gray-700">Envoyer l'email de confirmation</span>
                        <p class="text-xs text-gray-400">Chaque contact recevra un email avec son billet d'acces</p>
                    </div>
                </label>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    Importer
                </button>
                <a href="{{ route('admin.contacts.index') }}" class="btn-ghost">Annuler</a>
            </div>
        </form>
    </div>

    {{-- Format --}}
    <div class="card p-6 mt-6">
        <h3 class="font-display font-bold text-gray-900 mb-3">Format du fichier</h3>
        <p class="text-sm text-gray-500 mb-4">CSV (separateur <strong>;</strong>) ou Excel. Colonnes acceptees :</p>

        <div class="overflow-x-auto">
            <table class="text-xs w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-3 py-2 text-left font-semibold text-gray-600">structure</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600">prenom</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600">nom</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600">poste</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600">portable</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600">mail</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t">
                        <td class="px-3 py-2">GUYACOOP</td>
                        <td class="px-3 py-2">Jules</td>
                        <td class="px-3 py-2">KAKPO</td>
                        <td class="px-3 py-2">Directeur</td>
                        <td class="px-3 py-2">0694438115</td>
                        <td class="px-3 py-2">jm.kakpo@guyacoop.fr</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="text-xs text-gray-400 mt-3">Colonnes alternatives acceptees : <strong>prénom/firstname</strong>, <strong>nom/lastname</strong>, <strong>email</strong>, <strong>telephone/tel</strong>, <strong>societe/company</strong>, <strong>fonction</strong></p>
    </div>

</div>
@endsection
