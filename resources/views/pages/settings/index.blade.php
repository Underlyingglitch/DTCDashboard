@extends('layouts.app')

@section('page_title', 'Applicatie instellingen')

@section('content')
    Database schrijven:
    @if (\App\Models\Setting::getValue('db_write_enabled'))
        <a class="btn btn-danger" href="{{ route('settings.set', ['db_write_enabled', 0]) }}">Uitschakelen</a>
    @else
        <a class="btn btn-success" href="{{ route('settings.set', ['db_write_enabled', 1]) }}">Inschakelen</a>
    @endif
    <br><br>
    @if (config('app.env') == 'local' || config('app.env') == 'dev')
        @livewire('sync-toggle-button')
        Database importeren:
        @livewire('import-db-form')
    @endif
    <br>
    @livewire('export-db-button')
@endsection
