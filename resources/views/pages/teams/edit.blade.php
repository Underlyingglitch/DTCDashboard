@extends('layouts.app')

@section('page_title', 'Team bewerken')

@section('content')
    <h4>{{ $team->name }}</h4>
    <form method="post" action="{{ route('teams.update', [$wedstrijd, $team]) }}">
        @csrf
        @method('PUT')
        <x-form.text name="name" label="Teamnaam" placeholder="Naam" :value="$team->name" />
        <x-form.select name="niveau_id" label="Niveau" :options="$niveaus" :value="$team->niveau_id" />
        <input class="btn btn-sm btn-primary" type="submit" value="Opslaan" />
    </form>
@endsection
