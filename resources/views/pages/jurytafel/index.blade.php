@extends('layouts.jury')

@section('page_title', 'Jurytafel')

@section('content')
    <div class="row">
        @foreach ($toestellen as $i => $toestel)
            <div class="col-md-4">
                <a href="{{ route('jurytafel.toestel', ['toestel' => $i + 1]) }}">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title text-center">{{ ucfirst($toestel) }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <img src="{{ asset('svg/' . $toestel . '.svg') }}" alt="{{ $toestel }}"
                                    style="width: 100%; max-width: 200px;">
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endsection
