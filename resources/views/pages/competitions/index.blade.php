@extends('layouts.app')

@section('page_title', 'Competities')

@section('content')
    @can('create', \App\Models\Competition::class)
        <a href="{{ route('competitions.create') }}" wire:navigate class="btn btn-sm btn-success">Nieuwe competitie</a>
    @endcan
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
                        @can('view', $competition)
                            <a href="{{ route('competitions.show', $competition) }}" class="btn btn-sm btn-info"><i
                                    class="fas fa-info-circle"></i></a>
                        @endcan
                        @can('update', $competition)
                            <a href="{{ route('competitions.edit', $competition) }}" class="btn btn-sm btn-warning"><i
                                    class="fas fa-pencil"></i></a>
                        @endcan
                        @can('delete', $competition)
                            <form class="button-form" method="post" action="{{ route('competitions.destroy', $competition) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        @endcan
                        @if ($competition->id != $activeCompetition && Auth::user()->can('update', $competition))
                            <a href="{{ route('competitions.setactive', $competition) }}" class="btn btn-sm btn-success"><i
                                    class="fas fa-check"></i></a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
