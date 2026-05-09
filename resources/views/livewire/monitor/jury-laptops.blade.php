<div class="row">
    @foreach ($devices as $i => $device)
        <div class="col-md-2">
            @livewire('monitor.jury-laptop', ['device' => $device], key($i))
        </div>
    @endforeach
</div>
