@extends('layouts.app')

@section('page_title', 'Wedstrijdplanning')

@section('content')
    <a href="{{ route('settings.calendar_updates') }}">Email meldingen beheren</a>
    @livewire('calendar.index')
@endsection
