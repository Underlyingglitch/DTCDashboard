@extends('layouts.app')

@section('page_title', 'Locatie aanmaken')

@section('content')
    <form method="post" action="{{ route('locations.store') }}">
        @csrf
        <x-form.text name="name" label="Naam locatie" placeholder="Naam" />
        <x-form.text name="address" label="Adres locatie" placeholder="Adres" />
        <input class="btn btn-sm btn-primary" type="submit" value="Opslaan" />
    </form>
@endsection
