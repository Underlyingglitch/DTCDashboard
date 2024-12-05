<div class="row">
    @foreach ($jury_laptops as $i => $laptop)
        <div class="col-md-2">
            @livewire('monitor.jury-laptop', ['laptop' => $laptop], key($i))
        </div>
    @endforeach
</div>
