@extends('layouts.app')

@section('page_title', 'KNGU Bronnen')

@section('content')
    @if (request()->has('show_deleted'))
        Verwijderde bronnen weergeven <a class="btn btn-sm btn-success" href="{{ route('dg_resources.index') }}">Aan</a>
    @else
        Verwijderde bronnen weergeven <a class="btn btn-sm btn-danger"
            href="{{ route('dg_resources.index', ['show_deleted']) }}">Uit</a>
    @endif
    Email meldingen @livewire('dg-resources.email-toggle')
    @foreach ($dg_resources as $category => $resources)
        <h2>{{ $category }}</h2>
        <ul>
            @foreach ($resources as $resource)
                <li>
                    <a @if ($resource->url) href="{{ $resource->url }}" @endif
                        target="_blank">{!! $resource->name !!}</a>
                    @if ($resource->status == 'deleted')
                        <span class="badge badge-danger">Verwijderd</span>
                    @elseif ($resource->created_at->diffInDays() < 5)
                        <span class="badge badge-success">Nieuw</span>
                    @elseif ($resource->updated_at->diffInDays() < 5)
                        <span class="badge badge-warning">Bijgewerkt</span>
                    @endif
                </li>
            @endforeach
        </ul>
    @endforeach
@endsection
