@extends('layouts.app')

@section('page_title', 'Monitor')

@section('content')
    <h4>
        Jurytafels
    </h4>
    @livewire('monitor.jury-laptops')
    <hr>
    @livewire('monitor.registered-devices')
@endsection
