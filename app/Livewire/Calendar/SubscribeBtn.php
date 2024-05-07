<?php

namespace App\Livewire\Calendar;

use Livewire\Component;
use App\Models\CalendarItem;

class SubscribeBtn extends Component
{
    public $item;
    public $subscribed;

    public function mount($id)
    {
        $this->item = CalendarItem::find($id);
        $this->subscribed = $this->item->subscribers->contains(auth()->id());
    }

    public function toggleSubscription()
    {
        if ($this->item->subscribers->contains(auth()->id())) {
            $this->unsubscribe();
        } else {
            $this->subscribe();
        }
    }

    public function subscribe()
    {
        $this->item->subscribers()->attach(auth()->id());
        $this->subscribed = true;
    }

    public function unsubscribe()
    {
        $this->item->subscribers()->detach(auth()->id());
        $this->subscribed = false;
    }

    public function render()
    {
        return view('livewire.calendar.subscribe-btn');
    }
}
