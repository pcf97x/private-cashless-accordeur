<div class="space-y-5">
    <div>
        <label for="title" class="form-label">Titre</label>
        <input type="text" name="title" id="title" required class="form-input" placeholder="Ex: Cours de yoga, Soiree networking..." value="{{ old('title', $event->title ?? '') }}">
        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="description" class="form-label">Description (optionnel)</label>
        <textarea name="description" id="description" rows="3" class="form-input" placeholder="Details visibles dans le planning (si public)...">{{ old('description', $event->description ?? '') }}</textarea>
        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" id="date" required class="form-input" value="{{ old('date', isset($event) && $event->date ? $event->date->format('Y-m-d') : now()->toDateString()) }}">
            @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="room_id" class="form-label">Salle (optionnel)</label>
            <select name="room_id" id="room_id" class="form-input">
                <option value="">Aucune salle</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}" {{ old('room_id', $event->room_id ?? '') == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="time_slot_id" class="form-label">Creneau (optionnel)</label>
            <select name="time_slot_id" id="time_slot_id" class="form-input">
                <option value="">Toute la journee</option>
                @foreach($timeSlots as $slot)
                    <option value="{{ $slot->id }}" {{ old('time_slot_id', $event->time_slot_id ?? '') == $slot->id ? 'selected' : '' }}>
                        {{ $slot->label }} ({{ \Carbon\Carbon::parse($slot->start_time)->format('H\hi') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('H\hi') }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
            <label for="visibility" class="form-label">Visibilite</label>
            <select name="visibility" id="visibility" class="form-input">
                <option value="public" {{ old('visibility', $event->visibility ?? 'public') === 'public' ? 'selected' : '' }}>Public (nom visible)</option>
                <option value="private" {{ old('visibility', $event->visibility ?? '') === 'private' ? 'selected' : '' }}>Prive (affiche "Reserve")</option>
            </select>
            <p class="text-xs text-gray-400 mt-1">Public : le titre s'affiche. Prive : affiche seulement "Reserve".</p>
        </div>
        <div>
            <label for="color" class="form-label">Couleur</label>
            <div class="flex items-center gap-3">
                <input type="color" name="color" id="color" class="w-10 h-10 rounded-lg border border-gray-200 cursor-pointer" value="{{ old('color', $event->color ?? '#8b5cf6') }}">
                <div class="flex gap-2">
                    @foreach(['#8b5cf6', '#f59e0b', '#ef4444', '#3b82f6', '#10b981', '#ec4899'] as $c)
                        <button type="button" onclick="document.getElementById('color').value='{{ $c }}'" class="w-6 h-6 rounded-full border-2 border-white shadow-sm" style="background-color: {{ $c }}"></button>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="flex items-end">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="active" value="1" class="rounded border-gray-300 text-accordeur-500 focus:ring-accordeur-500"
                    {{ old('active', $event->active ?? true) ? 'checked' : '' }}>
                <span class="text-sm font-medium text-gray-700">Actif (visible dans le planning)</span>
            </label>
        </div>
    </div>
</div>
