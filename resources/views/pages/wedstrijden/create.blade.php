@extends('layouts.app')

@section('page_title', 'Wedstrijd aanmaken')

@section('content')

    <form method="post" action="{{ route('wedstrijden.store', $matchday) }}">
        @csrf
        <x-form.number name="index" label="Wedstrijdindex" placeholder="Index" />
        <div class="form-group">
            @error('niveaus')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <label>Niveaus in wedstrijd</label><br>
            @foreach ($niveaus as $key => $niveau)
                <input type="checkbox" name="niveaus[]" id="{{ $key }}" value="{{ $niveau->id }}">
                <label for="{{ $key }}">{{ $niveau->name }}
                    {{ $niveau->supplement }}</label><br>
            @endforeach
        </div>
        <input class="btn btn-sm btn-primary" type="submit" value="Opslaan" />
    </form>
@endsection
