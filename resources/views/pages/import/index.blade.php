@extends('layouts.app')

@section('page_title', 'Importeren')

@section('content')
    <form action="{{ route('import.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <x-form.select name="type" label="Type" :options="$import_options" :value="$type" />
        <x-form.select name="competition" label="Competitie" :options="$competitions" :value="$competition" />
        <x-form.file name="file" label="Bestand" />
        <input class="btn btn-primary" type="submit" value="Importeren" />
    </form>
@endsection
