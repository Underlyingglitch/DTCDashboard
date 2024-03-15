@extends('layouts.jury')

@section('page_title', $toestellen[$toestel - 1] . ' | Wedstrijd ' . $wedstrijd->index . ' | ' .
    $wedstrijd->match_day->date->format('d-m-Y') . ' | ' . $wedstrijd->match_day->location->name)

@section('content')
    <div class="alert alert-warning" id="warning-message" style="display: none">
        Meerdere mensen aangemeld op dit toestel
    </div>
    <div class="row">
        <div class="col-md-12">
            @livewire('jury.round-table', ['toestel' => $toestel, 'wedstrijd' => $wedstrijd])
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">Score invoer</div>
                <div class="card-body">
                    @livewire('jury.score-input-form', ['toestel' => $toestel, 'matchday' => $wedstrijd->match_day])
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center">Score correctie</div>
                <div class="card-body">
                    @livewire('jury.score-correct-form', ['toestel' => $toestel, 'matchday' => $wedstrijd->match_day])
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
                count = users.length;
                warning.style.display = count > 1 ? 'block' : 'none';
                if (count > 1) {
                    window.toastr.warning('Dit toestel is al op een ander apparaat in gebruik', 'In gebruik');
                }
            })
            .joining((user) => {
                count++;
                warning.style.display = 'block';
            })
            .leaving((user) => {
                count--;
                warning.style.display = count > 1 ? 'block' : 'none';
            })
        window.Echo.channel('settings.all').listen('.SettingUpdated', (e) => {
            if (e.key == 'current_competition' || e.key == 'current_match_day' || e.key == 'current_wedstrijd') {
                window.location.reload();
            }
        })
        document.addEventListener('sn_clicked', function() {
            var dScoreField = document.getElementById('d-score-field');
            if (dScoreField) {
                dScoreField.focus();
            }
        });
    </script>
@endsection
