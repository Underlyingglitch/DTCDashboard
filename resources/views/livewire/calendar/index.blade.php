<div>
    <div class="row">
        <div class="col">
            Maand:
            <select wire:model="selectedMonth" wire:change="getResults">
                @foreach ($months as $i => $month)
                    <option value="{{ $i + 1 }}" wire:key="month_dropdown_{{ $i }}">
                        {{ $month }}</option>
                @endforeach
            </select>
        </div>
        <div class="col">
            District:
            <select wire:model="selectedDistrict" wire:change="getResults">
                <option value="*">Alle districten</option>
                @foreach ($districts as $district)
                    <option value="{{ $district }}" wire:key="district_dropdown_{{ $district }}">
                        {{ $district }}</option>
                @endforeach
            </select>
        </div>
        <div class="col">
            Discipline:
            <select wire:model="selectedDiscipline" wire:change="getResults">
                <option value="*">Alle disciplines</option>
                @foreach ($disciplines as $discipline)
                    <option value="{{ $discipline }}" wire:key="discipline_dropdown_{{ $discipline }}">
                        {{ $discipline }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <hr>

    <div class="accordion" id="accordionExample">
        @foreach ($results as $result)
            <div class="card">
                <div class="card-header @if ($result['id'] != $selected) collapsed @endif"
                    @if (in_array($result['id'], $created)) style="background-color: rgba(0, 255, 55, 0.2)" @endif
                    @if (in_array($result['id'], $updated)) style="background-color: rgba(255, 242, 0, 0.2)" @endif
                    id="heading{{ $result['event_id'] }}" data-toggle="collapse"
                    data-target="#collapse{{ $result['event_id'] }}"
                    aria-expanded=@if ($result['id'] == $selected) "true" @else "false" @endif
                    aria-controls="collapse{{ $result['event_id'] }}" wire:click="toggle({{ $result['id'] }})">
                    <div class="row" style="font-size: 20px">
                        <div class="col">
                            <h5>
                                {{ $result['title'] }}
                            </h5>
                            <small>{{ $result['discipline'] }} | {{ $result['district'] }}</small>
                        </div>
                        <div class="col-auto" style="width: 200px; margin-right: 50px">{{ $result['place'] }}</div>
                        <div class="col-auto" style="width: 250px">{{ $result['date'] }}</div>
                        <div class="col-auto" style="width: 100px">
                            @if (!is_null($result['description']) || !empty($result['description']) || count($result['description_files']) != 0)
                                <span style="color: rgb(0, 166, 255)"><i class="fas fa-info-circle"></i></span>
                            @endif
                            @if (!is_null($result['program']) || !empty($result['program']) || count($result['program_files']) != 0)
                                <span style="color: rgb(149, 0, 255)"><i class="fas fa-table-cells-large"></i></span>
                            @endif
                            @if (!is_null($result['results']) || !empty($result['results']) || count($result['results_files']) != 0)
                                <span style="color: rgb(255, 179, 0)"><i class="fas fa-trophy"></i></span>
                            @endif
                        </div>
                    </div>
                </div>
                <div id="collapse{{ $result['event_id'] }}"
                    class="collapse @if ($result['id'] == $selected) show @endif"
                    aria-labelledby="heading{{ $result['event_id'] }}" data-parent="#accordionExample">
                    <div class="card-body">
                        <b>Locatie:</b> {{ $result['location_name'] }} ({{ $result['location_address'] }})<br>
                        @if (!is_null($result['description']) || !empty($result['description']) || count($result['description_files']) != 0)
                            <hr>
                            <b>Beschrijving</b><br>
                            {!! clean(nl2br($result['description'])) !!}<br>
                            @foreach ($result['description_files'] as $file)
                                <a href="{{ $file }}" target="_blank">{{ array_reverse(explode('/', $file))[0] }}</a><br>
                            @endforeach
                        @endif
                        @if (!is_null($result['program']) || !empty($result['program']) || count($result['program_files']) != 0)
                            <hr>
                            <b>Programma</b><br>
                            {!! clean(nl2br($result['program'])) !!}<br>
                            @foreach ($result['program_files'] as $file)
                                <a href="{{ $file }}" target="_blank">{{ array_reverse(explode('/', $file))[0] }}</a><br>
                            @endforeach
                        @endif
                        @if (!is_null($result['results']) || !empty($result['results']) || count($result['results_files']) != 0)
                            <hr>
                            <b>Resultaten</b><br>
                            {!! clean(nl2br($result['results'])) !!}<br>
                            @foreach ($result['results_files'] as $file)
                                <a href="{{ $file }}"
                                    target="_blank">{{ array_reverse(explode('/', $file))[0] }}</a><br>
                            @endforeach
                        @endif
                        <hr>
                        <a target="_blank"
                            href="https://dutchgymnastics.nl/wedstrijden-en-uitslagen?event={{ $result['event_id'] }}">Bekijk
                            op DG</a> | @php($check_subscribed = in_array($result['id'], $subscribed))<button
                            class="btn btn-sm btn-{{ $check_subscribed ? 'success' : 'danger' }}"
                            wire:click="toggleSubscription({{ $result['id'] }})">{{ $check_subscribed ? 'Geabonneerd' : 'Niet geabonneerd' }}</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
