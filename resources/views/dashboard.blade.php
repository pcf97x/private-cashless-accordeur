<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard – L’Accordeur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-lg font-bold">Bienvenue sur la plateforme Private Cashless</p>

                <ul class="mt-4 list-disc list-inside">
                    <li>Pointage des visiteurs</li>
                    <li>Réservation des salles</li>
                    <li>Snack cashless</li>
                    <li>Contrôle d’accès (à venir)</li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
