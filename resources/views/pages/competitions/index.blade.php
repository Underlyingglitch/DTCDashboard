@extends('layouts.app')

@section('page_title', 'Competities')

@section('content')
    <a href="{{ route('competitions.create') }}" wire:navigate class="btn btn-sm btn-success">Nieuwe competitie</a>
    @livewire('competitions.index-table')
@endsection
