<div>
    <b wire:click="getData">{{ $table }}</b><br>
    @if ($loading)
        <i>Laden...</i>
    @else
        @if (count($value[2]) == 0)
            <i>Geen verschillen gevonden</i>
        @else
            <div class="table-responsive">
                Behoud alle: <b wire:click="keepAll('prod')">Productie</b> - <b wire:click="keepAll('local')">Lokaal</b>
                <table border="1">
                    <tr>
                        <th>ID</th>
                        <th>Productie</th>
                        <th>Lokaal</th>
                        <th>Type</th>
                    </tr>
                    @foreach ($value[2] as $index => $row)
                        <tr>
                            <td>{{ $row['id'] }}</td>
                            @if (is_null($row['local']))
                                <td wire:click="keepProd({{ $index }})">{{ json_encode($row['prod']) }}</td>
                                <td wire:click="keepLocal({{ $index }})">{{ json_encode($row['local']) }}</td>
                                <td>EXTERN</td>
                            @elseif(is_null($row['prod']))
                                <td wire:click="keepProd({{ $index }})">{{ json_encode($row['prod']) }}</td>
                                <td wire:click="keepLocal({{ $index }})">{{ json_encode($row['local']) }}</td>
                                <td>LOKAAL</td>
                            @else
                                <?php
                                $diff_local = array_diff_assoc($row['local'], $row['prod']);
                                $diff_prod = array_diff_assoc($row['prod'], $row['local']);
                                // $diff = array_merge($diff1, $diff2);
                                ?>
                                <td wire:click="keepProd({{ $index }})">{{ json_encode($diff_prod) }}</td>
                                <td wire:click="keepLocal({{ $index }})">{{ json_encode($diff_local) }}</td>
                                <td>CONFLICT</td>
                            @endif
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif
    @endif
</div>
