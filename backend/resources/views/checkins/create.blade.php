<x-guest-layout>
    <form method="POST" action="/pointage" class="max-w-md mx-auto mt-10">
        @csrf

        <input name="firstname" placeholder="Prénom" required class="block w-full mb-3">
        <input name="lastname" placeholder="Nom" required class="block w-full mb-3">
        <input name="email" placeholder="Email (optionnel)" class="block w-full mb-3">

        <select name="purpose" required class="block w-full mb-3">
            <option value="">Motif de visite</option>
            <option value="coworking">Coworking</option>
            <option value="réunion">Réunion</option>
            <option value="événement">Événement</option>
        </select>

        <button class="bg-black text-white px-4 py-2">
            Valider mon entrée
        </button>
    </form>
</x-guest-layout>
