@extends('layouts.app')

@section('page_title', 'Gebruikers')

@section('content')
    <div style="overflow-x: auto;">
        <table id="dataTable" class="table">
            <thead>
                <tr>
                    <th>Naam</th>
                    <th>Email</th>
                    <th>Rol(len)</th>
                    <th>Actief</th>
                    <th>Laatst online</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Naam</th>
                    <th>Email</th>
                    <th>Rol(len)</th>
                    <th>Actief</th>
                    <th>Laatst online</th>
                    <th>Acties</th>
                </tr>
            </tfoot>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td class="{{ $user->email_verified_at ? 'text-green' : 'text-red' }}">{{ $user->email }}</td>
                        <td>{{ implode(', ', $user->getRoleNames()->toArray()) }}</td>
                        <td>
                            @if ($user->active)
                                <a href="{{ route('users.activate', [$user]) }}" class="btn btn-sm btn-success"><i
                                        class="fas fa-check"></i></a>
                                <span style="display:none">Ja</span>
                            @else
                                <a href="{{ route('users.activate', [$user]) }}" class="btn btn-sm btn-danger"><i
                                        class="fas fa-times"></i></a>
                                <span style="display:none">Nee</span>
                            @endif

                        </td>
                        {{-- <td class="text-bold {{ $user->active ? 'text-green' : 'text-red' }}">{{ $user->active ? 'Ja' : 'Nee' }} --}}
                        {{-- </td> --}}
                        <td data-order="{{ $user->last_seen_at->timestamp ?? 'Nooit' }}">
                            {{ $user->last_seen_at ? $user->last_seen_at->diffForHumans() : 'Nooit' }}</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-info"><i class="fas fa-info-circle"></i></a>
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning"><i
                                    class="fas fa-pencil"></i></a>
                            <form class="button-form" method="post" action="{{ route('users.destroy', $user) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
