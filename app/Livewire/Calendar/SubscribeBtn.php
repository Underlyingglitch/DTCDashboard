<?php

namespace App\Livewire\Calendar;

use Livewire\Component;
use App\Models\CalendarItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SubscribeBtn extends Component
{
    public $id;
    public $subscribed;

    public function mount($id, $subscribed = true)
    {
        $this->id = $id;
        $this->subscribed = $subscribed;
    }

    public function toggleSubscription($id)
    {
        $this->subscribed = !$this->subscribed;
        $item = CalendarItem::find($id);
        if ($item->subscribers->contains(Auth::id())) {
            $item->subscribers()->detach(Auth::id());
        } else {
            $item->subscribers()->attach(Auth::id());
        }
        Cache::forget('no_daily_updates');
    }

    public function render()
    {
        return view('livewire.calendar.subscribe-btn');
    }
}
