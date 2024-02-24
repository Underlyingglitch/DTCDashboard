@extends('layouts.app')

@section('page_title', 'Applicatie instellingen')

@section('content')
    <a href="{{ route('settings.database.compare') }}">Database</a> schrijven:
    @if (\App\Models\Setting::getValue('db_write') == 'on')
        <a class="btn btn-danger" href="{{ route('settings.set', ['db_write', 'off']) }}">Uitschakelen</a>
    @else
        <a class="btn btn-success" href="{{ route('settings.set', ['db_write', 'on']) }}">Inschakelen</a>
    @endif
    <br><br>
    @if (config('app.env') == 'local' || config('app.env') == 'dev')
        @livewire('sync-toggle-button')
    @endif
@endsection
