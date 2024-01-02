@extends('layouts.app')

@section('page_title', 'Competitie aanmaken')

@section('content')
    <form method="post" action="{{ route('competitions.store') }}">
        @csrf
        <x-form.text name="name" label="Naam competitie" placeholder="Naam" />
        <input class="btn btn-sm btn-primary" type="submit" value="Opslaan" />
    </form>
@endsection
