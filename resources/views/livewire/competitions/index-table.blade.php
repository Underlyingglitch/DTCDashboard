<table class="table">
    <thead>
        <tr>
            <th>Naam</th>
            <th>Datum</th>
            <th>Acties</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Naam</th>
            <th>Datum</th>
            <th>Acties</th>
        </tr>
    </tfoot>
    <tbody>
        @foreach ($competitions as $competition)
            <tr>
                <td>{{ $competition->name }}</td>
                <td>{{ implode(', ', $competition->dates->toArray()) }}</td>
                <td>
                    <a wire:navigate href="{{ route('competitions.show', $competition) }}" class="btn btn-sm btn-info"><i
                            class="fas fa-info-circle"></i></a>
                    <a wire:navigate href="{{ route('competitions.edit', $competition) }}"
                        class="btn btn-sm btn-warning"><i class="fas fa-pencil"></i></a>
                    <a wire:click="delete({{ $competition->id }})"
                        wire:confirm="Weet u zeker dat u deze competitie wilt verwijderen?"
                        class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
