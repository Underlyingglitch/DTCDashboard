@extends('layouts.app')

@section('page_title', 'Turners')

@section('content')
    <table id="dataTable" class="table">
        <thead>
            <tr>
                <th>Naam</th>
                <th>Club</th>
                <th>Geboortedatum</th>
                <th>Foto</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Naam</th>
                <th>Club</th>
                <th>Geboortedatum</th>
                <th>Foto</th>
                <th>Acties</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach ($gymnasts as $gymnast)
                <tr>
                    <td>{{ $gymnast->name }}</td>
                    <td>{{ $gymnast->club->name }}</td>
                    <td>{{ $gymnast->birthdate }}</td>
                    <td>{{ $gymnast->photo ? 'Ja' : 'Nee' }}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-info"><i class="fas fa-info-circle"></i></a>
                        <a href="#" class="btn btn-sm btn-warning"><i class="fas fa-pencil"></i></a>
                        <form class="button-form" method="post" action="#">
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
