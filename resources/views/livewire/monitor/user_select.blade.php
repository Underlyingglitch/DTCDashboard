<div>
    @if (is_null($authenticated_user_id))
        <select wire:model="authenticated_user_id" wire:change="setUser" id="">
            @foreach ($users as $key => $user)
                <option value="{{ $key }}">{{ $user }}</option>
            @endforeach
        </select>
    @else
        {{ \App\Models\User::find($authenticated_user_id)->name }}
    @endif
</div>
