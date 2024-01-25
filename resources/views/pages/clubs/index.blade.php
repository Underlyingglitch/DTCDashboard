@extends('layouts.app')

@section('page_title', 'Verenigingen')

@section('content')
    <table id="dataTable" class="table">
        <thead>
            <tr>
                <th>Naam</th>
                <th>Plaats</th>
                <th>District</th>
                <th>Email</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Naam</th>
                <th>Plaats</th>
                <th>District</th>
                <th>Email</th>
                <th>Acties</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach ($clubs as $club)
                <tr>
                    <td>{{ $club->name }}</td>
                    <td>{{ $club->place }}</td>
                    <td>{{ $club->district }}</td>
                    <td>{{ $club->email }}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-info"><i class="fas fa-info-circle"></i></a>
                        <a href="#" class="btn btn-sm btn-warning"><i class="fas fa-pencil"></i></a>
                        <form class="button-form" method="post" action={{ route('clubs.destroy', $club) }}">
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
