@extends('layouts.app')

@section('page_title', 'Locatie bewerken')

@section('content')
    <form method="post" action="{{ route('locations.update', $location) }}">
        @csrf
        @method('PUT')
        <x-form.text name="name" label="Naam locatie" placeholder="Naam" :value="$location->name" />
        <x-form.text name="address" label="Adres locatie" placeholder="Adres" :value="$location->address" />
        <input class="btn btn-sm btn-primary" type="submit" value="Opslaan" />
    </form>
@endsection
