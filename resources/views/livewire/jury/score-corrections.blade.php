<div>
    <table border="1" style="width:100%">
        <tr>
            <th>Toestel</th>
            <th>Startnummer</th>
            <th>D</th>
            <th>E</th>
            <th>N</th>
            <th>Totaal</th>
            <th></th>
        </tr>
        @foreach ($corrections as $correction)
            <tr>
                <td>{{ $toestellen[$correction['score']['toestel'] - 1] }}</td>
                <td>{{ $correction['score']['startnumber'] }}</td>
                <td>
                    @if ($correction['d'] != $correction['score']['d'])
                        <span style="color:red">{{ $correction['score']['d'] }}</span> {{ $correction['d'] }}
                    @else
                        {{ $correction['d'] }}
                    @endif
                </td>
                <td>
                    @if ($correction['e'] != $correction['score']['e'])
                        <del>{{ $correction['score']['e'] }}</del>
                        <ins>{{ $correction['e'] }}</ins>
                    @else
                        {{ $correction['e'] }}
                    @endif
                </td>
                <td>
                    @if ($correction['n'] != $correction['score']['n'])
                        <span style="color:red">{{ $correction['score']['n'] }}</span> {{ $correction['n'] }}
                    @else
                        {{ $correction['n'] }}
                    @endif
                </td>
                <td>
                    <del>{{ $correction['score']['total'] }}</del>
                    <ins>{{ $correction['total'] }}</ins>
                </td>
                <td>
                    <button class="btn btn-sm btn-danger" wire:click="delete({{ $correction['id'] }})"><i
                            class="fas fa-trash"></i></button>
                    <button class="btn btn-sm btn-success" wire:click=approve({{ $correction['id'] }})><i
                            class="fas fa-check"></i></button>
                </td>
            </tr>
        @endforeach
    </table>
</div>
