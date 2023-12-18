@extends('layouts.app')

@section('page_title', 'Gebruikers')

@section('content')
    <table id="dataTable" class="table">
        <thead>
            <tr>
                <th>Naam</th>
                <th>Email</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Naam</th>
                <th>Email</th>
                <th>Acties</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{  }}</td>
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
