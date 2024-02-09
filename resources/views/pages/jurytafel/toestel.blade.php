@extends('layouts.jury')

@section('page_title', $toestellen[$toestel - 1] . ' | Wedstrijd x | {date} | {location}')

@section('content')
    <div class="alert alert-warning" id="warning-message" style="display: none">
        Meerdere mensen aangemeld op dit toestel
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center">Ronde x | Groep x</div>
                <div class="card-body">
                    <table class="table table-sm table-bordered">
                        <tr>
                            <th>Nr</th>
                            <th>Naam</th>
                            <th>Vereniging</th>
                            <th>Niveau</th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header text-center">Score invoer</div>
                <div class="card-body">
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header text-center">Score correctie</div>
                <div class="card-body">
                    @livewire('jury.score-correct-form', ['toestel' => $toestel, 'matchday' => $matchday])
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="module" defer>
        let toestel = @json($toestel);
        let warning = document.getElementById('warning-message');
        let count = 0;
        window.Echo.join(`jurytafel.${toestel}`)
            .here((users) => {
                console.log('here', users)
                count = users.length;
                warning.style.display = count > 1 ? 'block' : 'none';
                if (count > 1) {
                    window.toastr.warning('Dit toestel is al op een ander apparaat in gebruik', 'In gebruik');
                }
            })
            .joining((user) => {
                console.log('joining', user)
                count++;
                warning.style.display = 'block';
            })
            .leaving((user) => {
                console.log('leaving', user)
                count--;
                warning.style.display = count > 1 ? 'block' : 'none';
            })
    </script>
@endsection
