@extends('layouts.app')

@section('page_title', 'Wedstrijddag aanmaken')

@section('content')

    <form method="post" action="{{ route('matchdays.store', $competition) }}">
        @csrf
        <x-form.input name="name" label="Naam wedstrijddag" placeholder="Naam" />
        <x-form.date name="date" label="Datum wedstrijddag" placeholder="Datum" />
        <x-form.select name="location_id" label="Locatie wedstrijddag" :options="$locations" />
        <input class="btn btn-sm btn-primary" type="submit" value="Opslaan" />
    </form>
@endsection
