@extends('layouts.app')

@section('page_title', 'Gebruiker bewerken')

@section('content')
    <form action="{{ route('users.update', $user) }}" method="post">
        @csrf
        @method('PUT')
        <x-form.text name="name" label="Naam" :value="$user->name" />
        <x-form.text name="email" label="Email" :value="$user->email" />
        <x-form.text name="password" label="Wachtwoord" placeholder="(laat leeg om niet te wijzigen)" />
    </form>
@endsection
