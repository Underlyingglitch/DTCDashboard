@extends('layouts.app')

@section('page_title', 'Inschrijving naar andere groep verplaatsen')

@section('content')
    <h4>{{ $registration->startnumber }} - {{ $registration->gymnast->name }}</h4>
    <form method="post" action="{{ route('wedstrijden.registration.move_group.store', [$wedstrijd, $registration]) }}">
        @csrf
        <x-form.select name="baan" label="Baan" :options="[1, 2, 3, 4]" :value="$registration->group->baan - 1" />
        <x-form.select name="group" label="Groep" :options="[1, 2, 3, 4, 5, 6, 7, 8, 9, 10]" :value="$registration->group->nr - 1" />
        <input class="btn btn-sm btn-primary" type="submit" value="Opslaan" />
    </form>
@endsection
