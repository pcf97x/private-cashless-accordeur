<div class="space-y-5">
    <div>
        <label for="title" class="form-label">Titre</label>
        <input type="text" name="title" id="title" required class="form-input" placeholder="Ex: Cours de yoga, Soiree networking..." value="{{ old('title', $event->title ?? '') }}">
        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="description" class="form-label">Description (optionnel)</label>
        <textarea name="description" id="description" rows="3" class="form-input" placeholder="Details visibles dans le planning...">{{ old('description', $event->description ?? '') }}</textarea>
        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" id="date" required class="form-input" value="{{ old('date', isset($event) ? $event->date->format('Y-m-d') : now()->toDateString()) }}">
            @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="start_time" class="form-label">Heure debut (optionnel)</label>
            <input type="time" name="start_time" id="start_time" class="form-input" value="{{ old('start_time', isset($event) && $event->start_time ? \Carbon\Carbon::parse($event->start_time)->format('H:i') : '') }}">
        </div>
        <div>
            <label for="end_time" class="form-label">Heure fin (optionnel)</label>
            <input type="time" name="end_time" id="end_time" class="form-input" value="{{ old('end_time', isset($event) && $event->end_time ? \Carbon\Carbon::parse($event->end_time)->format('H:i') : '') }}">
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
