@extends('layouts.app')

@section('page_title', 'Importeren')

@section('content')
    <form action="{{ route('import.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <x-form.select name="type" label="Type" :options="$import_options" :value="$type" />
        <x-form.select name="matchday" label="Wedstrijddag" :options="$matchdays" :value="$matchday" disabled="onvalue" />
        <x-form.select name="import_matchday" label="Importeren van wedstrijddag" :options="$matchdays" />
        <x-form.file name="file" label="Bestand" />
        <input class="btn btn-primary" type="submit" value="Importeren" />
    </form>
    @vite('resources/js/import.js')
@endsection
