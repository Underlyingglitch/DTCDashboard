@extends('layouts.app')

@section('page_title', 'Databases vergelijken')

@section('content')
    <h1>Databases vergelijken</h1>

    @foreach ($tables as $table => $value)
        @livewire('compare-table', ['table' => $table, 'value' => $value])
    @endforeach
@endsection
