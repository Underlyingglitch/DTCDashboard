@extends('layouts.app')

@section('page_title', 'Monitor')

@section('content')
    <h4>
        Jurytafels
        @livewire('jury.reload-button', ['page' => null])
    </h4>
    @livewire('monitor.jury-laptops')
    <hr>
    @livewire('monitor.registered-devices')
@endsection
