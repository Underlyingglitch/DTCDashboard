@extends('layouts.app')

@section('page_title', 'Database instelling')

@section('content')
    @if (Auth::user()->hasRole('admin'))
        Database schrijven:
        @if (\App\Models\Setting::getValue('db_write') == 'on')
            <a class="btn btn-danger" href="{{ route('settings.set', ['db_write', 'off']) }}">Uitschakelen</a>
        @else
            <a class="btn btn-success" href="{{ route('settings.set', ['db_write', 'on']) }}">Inschakelen</a>
        @endif
        <br><br>
        @if (config('app.env') == 'local' || config('app.env') == 'development')
            @livewire('sync-toggle-button')
        @endif
    @endif
@endsection
