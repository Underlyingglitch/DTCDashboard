@extends('layouts.app')

@section('page_title', 'Monitor')

@section('content')
    <h4>Jurytafels</h4>
    <div class="row">
        @foreach ($toestellen as $i => $toestel)
            <div class="col-md-2">
                <div class="card">
                    <div class="card-header">
                        {{ $toestel }}
                    </div>
                    <div class="card-body">
                        @livewire('monitor.toestel', ['toestel' => $i+1])
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
