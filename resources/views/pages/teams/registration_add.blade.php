@extends('layouts.app')

@section('page_title', 'Inschrijving aan team toevoegen')

@section('content')
    <h4>{{ $registration->startnumber }} - {{ $registration->gymnast->name }}</h4>
    <form method="post" action="{{ route('teams.registration.add.store', [$wedstrijd, $registration]) }}">
        @csrf
        <x-form.select name="team_id" label="Team" :options="$teams" :value="$registration->team_id" />
        <input class="btn btn-sm btn-primary" type="submit" value="Opslaan" />
    </form>
@endsection
