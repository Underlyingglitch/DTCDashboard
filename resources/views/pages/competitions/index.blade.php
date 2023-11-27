@extends('layouts.app')

@section('page_title', 'Competities')

@section('content')
    <a href="{{ route('competitions.create') }}" class="btn btn-success">Nieuwe competitie</a>
    <table class="table">
        <thead>
            <tr>
                <th>Naam</th>
                <th>Locatie</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Naam</th>
                <th>Locatie</th>
                <th>Acties</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach ($competitions as $competition)
                <tr>
                    <td>{{ $competition->name }}</td>
                    <td>{{ $competition->location->name }}</td>
                    <td>
                        <a href="{{ route('competitions.edit', $competition) }}" class="btn btn-warning"><i
                                class="fas fa-pencil"></i></a>
                        <form class="button-form" method="post" action="{{ route('competitions.destroy', $competition) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
