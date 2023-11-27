@extends('layouts.app')

@section('page_title', 'Locaties')

@section('content')
    <a href="{{ route('locations.create') }}" class="btn btn-success">Nieuwe locatie</a>
    <table class="table">
        <thead>
            <tr>
                <th>Naam</th>
                <th>Adres</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Naam</th>
                <th>Adres</th>
                <th>Acties</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach ($locations as $location)
                <tr>
                    <td>{{ $location->name }}</td>
                    <td>{{ $location->address }}</td>
                    <td>
                        <a href="{{ route('locations.edit', $location) }}" class="btn btn-warning"><i
                                class="fas fa-pencil"></i></a>
                        <form class="button-form" method="post" action="{{ route('locations.destroy', $location) }}">
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
