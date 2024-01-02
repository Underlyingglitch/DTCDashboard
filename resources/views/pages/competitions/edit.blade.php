@extends('layouts.app')

@section('page_title', 'Competitie bewerken')

@section('content')
    <form method="post" action="{{ route('competitions.update', $competition) }}">
        @csrf
        @method('PUT')
        <x-form.text name="name" label="Naam competitie" placeholder="Naam" :value="$competition->name" />
        <input class="btn btn-sm btn-primary" type="submit" value="Opslaan" />
    </form>
@endsection
