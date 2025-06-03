@extends('layouts.app')

@section('page_title', 'Competitie bewerken')

@section('content')
    <form method="post" action="{{ route('competitions.update', $competition) }}">
        @csrf
        @method('PUT')
        <x-form.text name="name" label="Naam competitie" placeholder="Naam" :value="$competition->name" />
        <x-form.checkbox-single name="kngu_competition" label="KNGU competitie" :checked="$competition->kngu_competition" />
        <x-form.checkbox-single name="has_doorstroming" label="Heeft doorstroming" :checked="$competition->has_doorstroming" />
        <input class="btn btn-sm btn-primary" type="submit" value="Opslaan" />
    </form>
@endsection
