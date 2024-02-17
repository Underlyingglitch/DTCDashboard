@extends('layouts.app')

@section('page_title', 'Wedstrijd bewerken')

@section('content')

    <form method="post" action="{{ route('wedstrijden.update', $wedstrijd) }}">
        @csrf
        @method('PUT')
        <x-form.number name="index" label="Wedstrijdindex" placeholder="Index" :value="$wedstrijd->index" />
        <div class="form-group">
            @error('niveaus')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <label>Niveaus in wedstrijd</label><br>
            @foreach ($niveaus as $key => $niveau)
                <input type="checkbox" name="niveaus[]" id="{{ $key }}"
                    @if ($wedstrijd->niveaus->contains($niveau->id)) checked @endif value="{{ $niveau->id }}">
                <label for="{{ $key }}">{{ $niveau->name }}
                    {{ $niveau->supplement }}</label><br>
            @endforeach
        </div>
        <x-form.select name="round_settings" label="Ronde instellingen" :options="[
            '1-2-3-4-5-6' => 'Vloer - Voltige - Ringen - Sprong - Brug - Rek',
            '1-2-3-4-5-6-7' => 'Vloer - Voltige - Ringen - Sprong - Brug - Rek - Rust',
            '1-2-3-4-5-6-7-7' => 'Vloer - Voltige - Ringen - Sprong - Brug - Rek - Rust - Rust',
            '1-2-3-7-4-5-6' => 'Vloer - Voltige - Ringen - Rust - Sprong - Brug - Rek',
            '1-2-3-7-4-5-6-7' => 'Vloer - Voltige - Ringen - Rust - Sprong - Brug - Rek - Rust',
        ]" :value="$wedstrijd->round_settings" />
        <input class="btn btn-sm btn-primary" type="submit" value="Opslaan" />
    </form>
    <hr>
    <form method="post" action="{{ route('wedstrijden.groupsettings', $wedstrijd) }}">
        @csrf
        @for ($baan = 1; $baan <= $wedstrijd->baans(); $baan++)
            <x-form.text name="baan{{ $baan }}" label="Baan {{ $baan }}" placeholder="Groepen volgorde"
                :value="$wedstrijd->group_settings[0][$baan - 1] ?? null" />
        @endfor
        <input class="btn btn-sm btn-primary" type="submit" value="Groepen instellen" />
    </form>
@endsection
