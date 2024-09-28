<button class="btn btn-sm btn-{{ $subscribed ? 'success' : 'danger' }}"
    wire:click="toggleSubscription({{ $id }})">{{ $subscribed ? 'Geabonneerd' : 'Niet geabonneerd' }}</button>
