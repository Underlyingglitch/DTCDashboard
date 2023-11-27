@extends('layouts.app')

@section('page_title', 'Locatie aanmaken')

@section('content')
    <form method="post" action="{{ route('locations.store') }}">
        @csrf
        <div class="form-group">
            <label for="name">Naam locatie</label>
            <input class="form-control" type="text" name="name" placeholder="Naam" />
        </div>
        <div class="form-group">
            <label for="address">Adres locatie</label>
            <input class="form-control" type="text" name="address" placeholder="Adres" />
        </div>
        <input class="btn btn-primary" type="submit" value="Opslaan" />
    </form>
@endsection
