@extends('layouts.app')

@section('page_title', 'Wedstrijddag bewerken')

@section('content')

    <form method="post" action="{{ route('matchdays.update', $matchday) }}">
        @csrf
        @method('PUT')
        <x-form.date name="date" label="Datum wedstrijddag" placeholder="Datum" :value="$matchday->date" />
        <x-form.select name="location_id" label="Locatie wedstrijddag" :options="$locations" :value="$matchday->location_id" />
        <input class="btn btn-sm btn-primary" type="submit" value="Opslaan" />
    </form>
@endsection
