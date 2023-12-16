@extends('layouts.app')

@section('page_title', $matchday->competition->name)

@section('content')
    <h4>Wedstrijden</h4>
    <a href="{{ route('competitions.show', $matchday->competition) }}" class="btn btn-sm btn-primary">Terug naar
        competitie</a>
    <a href="{{ route('wedstrijden.create', $matchday) }}" class="btn btn-sm btn-success">Nieuwe wedstrijd</a>
    <table class="table">
        <thead>
            <tr>
                <th>Wedstrijdnummer</th>
                <th>Niveaus</th>
                <th>Banen</th>
                <th>Groepen</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Wedstrijdnummer</th>
                <th>Niveaus</th>
                <th>Banen</th>
                <th>Groepen</th>
                <th>Acties</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach ($wedstrijden as $wedstrijd)
                <tr>
                    <td>{{ $wedstrijd->index }}</td>
                    <td>{{ $wedstrijd->niveaus_list }}</td>
                    <td>{{ $wedstrijd->baans() }}</td>
                    <td>{{ $wedstrijd->group_amount }}</td>
                    <td>
                        <a href="{{ route('wedstrijden.show', $wedstrijd) }}" class="btn btn-sm btn-info"><i
                                class="fas fa-info-circle"></i></a>
                        <a href="{{ route('wedstrijden.edit', $wedstrijd) }}" class="btn btn-sm btn-warning"><i
                                class="fas fa-pencil"></i></a>
                        <form class="button-form" method="post" action="{{ route('wedstrijden.destroy', $wedstrijd) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
