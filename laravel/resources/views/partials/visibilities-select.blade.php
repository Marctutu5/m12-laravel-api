<x-input-label for="visibility" :value="__('Visibility')" />
<select id="visibility" name="visibility" class="block mt-1 w-full">
@foreach(App\Models\Visibility::all() as $visibility)
    <option value="{{ $visibility->id }}" {{ (isset($visibility_id) && $visibility->id == $visibility_id) ? "selected" : "" }}>
        {{ __($visibility->name) }}
    </option>
@endforeach
</select>