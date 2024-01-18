@extends('layouts.app')

@section('page_title', $matchday->competition->name)

@section('content')
    <h4>Wedstrijden</h4>

    <div class="d-flex flex-row justify-content-between">
        <div>
            <a href="{{ route('competitions.show', $matchday->competition) }}" class="btn btn-sm btn-primary">Terug naar
                competitie</a>
            <a href="{{ route('wedstrijden.create', $matchday) }}" class="btn btn-sm btn-success">Nieuwe wedstrijd</a>
            <a href="{{ route('import.index', ['matchday' => $matchday]) }}"
                class="btn btn-sm {{ $matchday->imported ? 'btn-warning' : 'btn-info' }}">Importeren</a>
        </div>
        <div class="col-md-4">
            <form action="{{ route('matchdays.export.select', $matchday) }}" method="post" target="_blank">
                @csrf
                <div class="input-group">
                    <select name="option" id="option"
                        class="form-control form-control-sm @error('option') is-invalid @enderror">
                        <option value="--">--</option>
                        @foreach ($matchday_export_options as $key => $option)
                            <option value="{{ $key }}">
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                    <input type="submit" value="Exporteren" class="btn btn-sm btn-info">
                </div>
            </form>
        </div>
    </div>
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
                        @if ($wedstrijd->id != $activeWedstrijd)
                            <a href="{{ route('wedstrijden.setactive', $wedstrijd) }}" class="btn btn-sm btn-success"><i
                                    class="fas fa-check"></i></a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
