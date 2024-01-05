@extends('layouts.app')

@section('page_title', $competition->name)

@section('content')
    <h4>Wedstrijddagen</h4>
    <a href="{{ route('matchdays.create', $competition) }}" class="btn btn-sm btn-success">Nieuwe wedstrijddag</a>
    <table class="table">
        <thead>
            <tr>
                <th>Datum</th>
                <th>Locatie</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Datum</th>
                <th>Locatie</th>
                <th>Acties</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach ($matchdays as $matchday)
                <tr>
                    <td>{{ $matchday->date }}</td>
                    <td>{{ $matchday->location->name }}</td>
                    <td>
                        <a href="{{ route('matchdays.show', $matchday) }}" class="btn btn-sm btn-info"><i
                                class="fas fa-info-circle"></i></a>
                        <a href="{{ route('matchdays.edit', $matchday) }}" class="btn btn-sm btn-warning"><i
                                class="fas fa-pencil"></i></a>
                        <form class="button-form" method="post" action="{{ route('matchdays.destroy', $matchday) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                        @if ($matchday->id != $activeMatchDay)
                            <a href="{{ route('matchdays.setactive', $matchday) }}" class="btn btn-sm btn-success"><i
                                    class="fas fa-check"></i></a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
