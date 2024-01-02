@extends('layouts.app')

@section('page_title', 'Competities')

@section('content')
    <a href="{{ route('competitions.create') }}" wire:navigate class="btn btn-sm btn-success">Nieuwe competitie</a>
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
                        <a href="{{ route('competitions.show', $competition) }}" class="btn btn-sm btn-info"><i
                                class="fas fa-info-circle"></i></a>
                        <a href="{{ route('competitions.edit', $competition) }}" class="btn btn-sm btn-warning"><i
                                class="fas fa-pencil"></i></a>
                        <form class="button-form" method="post" action="{{ route('competitions.destroy', $competition) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                        @if ($competition->id != $activeCompetition)
                            <a href="{{ route('competitions.setactive', $competition) }}" class="btn btn-sm btn-success"><i
                                    class="fas fa-check"></i></a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
