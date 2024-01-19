@extends('layouts.app')

@section('page_title', 'Juryleden')

@section('content')
    <table id="dataTable" class="table">
        <thead>
            <tr>
                <th>Naam</th>
                <th>Functie</th>
                <th>Vereniging</th>
                <th>Email</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Naam</th>
                <th>Functie</th>
                <th>Vereniging</th>
                <th>Email</th>
                <th>Acties</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach ($juries as $jury)
                <tr>
                    <td>{{ $jury->name }}</td>
                    <td>{{ $jury->function }}</td>
                    <td>{{ $jury->club->name ?? '' }}</td>
                    <td class="@if ($jury->user) text-green @else text-red @endif">{{ $jury->email }}</td>
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
