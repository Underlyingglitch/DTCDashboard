<tr @if ($registration->signed_off) style="text-decoration: line-through" @endif>
    <td>{{ $registration->startnumber }}</td>
    <td>{{ $registration->gymnast->name }}</td>
    <td>{{ $registration->club->name }}</td>
    <td>{{ $registration->niveau->full_name }}</td>
    <td>
        @can('signoff', $registration)
            @if ($registration->signed_off)
                <a wire:click="toggle_signoff()" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></a>
            @else
                <a wire:click="toggle_signoff()" class="btn btn-sm btn-success"><i class="fas fa-check"></i></a>
            @endif
        @endcan
    </td>
    @can('manage', \App\Models\Registration::class)
        <td>
            <select wire:model.change="baan">
                @for ($b = 1; $b <= 4; $b++)
                    <option value="{{ $b }}" @if ($registration->baan == $b) selected @endif>{{ $b }}
                    </option>
                @endfor
            </select>
            <select wire:model.change="group">
                @for ($b = 1; $b <= 10; $b++)
                    <option value="{{ $b }}" @if ($registration->baan == $b) selected @endif>{{ $b }}
                    </option>
                @endfor
            </select>
        </td>
    @endcan
</tr>
