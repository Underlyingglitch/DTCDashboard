@extends('layouts.app')

@section('page_title', 'Wedstrijdplanning')

@section('content')
    <div>
        <a href="{{ route('settings.calendar_updates') }}">Email meldingen beheren</a>
        <div style="float:right">
            <span style="color: rgb(0, 166, 255)"><i class="fas fa-info-circle"></i></span> = Beschrijving
            <span style="color: rgb(149, 0, 255)"><i class="fas fa-table-cells-large"></i></span> = Programma/indeling
            <span style="color: rgb(255, 179, 0)"><i class="fas fa-trophy"></i></span> = Uitslagen
        </div>
    </div>
    <br>
    @livewire('calendar.index')
@endsection
