@extends('layouts.app')

@section('page_title', 'Monitor')

@section('content')
    <h4>
        Jurytafels
        @livewire('jury.reload-button', ['page' => null])
    </h4>
    <div class="row">
        @foreach ($jury_laptops as $i => $laptop)
            <div class="col-md-2">
                <div class="card">
                    <div class="card-header text-center">
                        {{ $laptop->name }}
                    </div>
                    <div class="card-body">
                        @livewire('monitor.page_select', ['device' => $laptop, 'type' => 'jury'])
                        @livewire('monitor.user_select', ['device' => $laptop])
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
