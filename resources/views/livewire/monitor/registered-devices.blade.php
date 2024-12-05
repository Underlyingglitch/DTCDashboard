<table border="1">
    <tr>
        <th width="300px">Device ID</th>
        <th width="150px">Code</th>
    </tr>
    @foreach ($devices as $code => $device_id)
        <tr>
            <td>{{ $device_id }}</td>
            <td>{{ $code }} <span wire:click="deleteRegistration({{ $code }})" style="color: red"><i
                        class="fas fa-trash"></i></span></td>
        </tr>
    @endforeach
</table>
