@extends('layouts.app')

@section('page_title', 'Competitie aanmaken')

@section('content')
    <form method="post" action="{{ route('competitions.store') }}">
        @csrf
        <div class="form-group">
            <label for="name">Naam competitie</label>
            <input class="form-control" type="text" name="name" placeholder="Naam" />
        </div>
        <div class="form-group">
            <label for="address">Locatie competitie</label>
            <select class="form-control" name="location_id">
                <option value="--">--</option>
                @foreach ($locations as $location)
                    <option value="{{ $location->id }}">{{ $location->name }} - {{ $location->address }}</option>
                @endforeach
            </select>
        </div>
        <input class="btn btn-primary" type="submit" value="Opslaan" />
    </form>
@endsection
