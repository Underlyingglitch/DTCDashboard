@extends('emails.inc.template')

@section('content')
    <p>Beste {{ $user->name }},</p>

    <p>Bedankt voor het registreren. Voordat u kunt
        inloggen moet u uw emailadres verifieren. Klik hiervoor op onderstaande
        knop.</p>
    <a href="{{ $button[1] }}" class="button button-primary" target="_blank" rel="noopener">{{ $button[0] }}</a>
    <br><br>
    <p>Als u niet kunt klikken op de knop, kopieer dan de onderstaande link en plak
        deze in uw browser.</p>
    <p>{{ $button[1] }}</p>
@endsection
