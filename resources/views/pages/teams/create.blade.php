@extends('layouts.app')

@section('page_title', 'Team aanmaken')

@section('content')

    <form method="post" action="{{ route('teams.store', $wedstrijd) }}">
        @csrf
        <x-form.text name="name" label="Teamnaam" placeholder="Naam" />
        <x-form.select name="niveau_id" label="Niveau" :options="$niveaus" />
        <input class="btn btn-sm btn-primary" type="submit" value="Opslaan" />
    </form>
@endsection
