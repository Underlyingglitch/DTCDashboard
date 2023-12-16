@extends('layouts.app')

@section('page_title', 'Trainers')

@section('content')
    <table id="dataTable" class="table">
        <thead>
            <tr>
                <th>Naam</th>
                <th>Vereniging</th>
                <th>Email</th>
                <th>Telefoonnummer</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Naam</th>
                <th>Vereniging</th>
                <th>Email</th>
                <th>Telefoonnummer</th>
                <th>Acties</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach ($trainers as $trainer)
                <tr>
                    <td>{{ $trainer->name }}</td>
                    <td>{{ $trainer->club->name }}</td>
                    <td>{{ $trainer->email }}</td>
                    <td>{{ $trainer->phone }}</td>
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
