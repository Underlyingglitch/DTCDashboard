<button class="btn btn-sm btn-{{ $subscribed ? 'success' : 'danger' }}"
    wire:click="toggleSubscription()">{{ $subscribed ? 'Geabonneerd' : 'Niet geabonneerd' }}</button>
