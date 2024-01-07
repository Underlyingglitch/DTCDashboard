@extends('layouts.app')

@section('page_title', 'Livescores')

@section('content')
    @livewire('livescores.teams')

    @livewire('livescores.individual')
@endsection
