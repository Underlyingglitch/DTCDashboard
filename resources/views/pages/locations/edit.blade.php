@extends('layouts.app')

@section('page_title', 'Locatie bewerken')

@section('content')
    <form method="post" action="{{ route('locations.update', $location) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Naam locatie</label>
            <input class="form-control" type="text" name="name" placeholder="Naam" value="{{ $location->name }}" />
        </div>
        <div class="form-group">
            <label for="address">Adres locatie</label>
            <input class="form-control" type="text" name="address" placeholder="Adres"
                value="{{ $location->address }}" />
        </div>
        <input class="btn btn-primary" type="submit" value="Opslaan" />
    </form>
@endsection
