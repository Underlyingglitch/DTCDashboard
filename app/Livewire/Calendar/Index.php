<?php

namespace App\Livewire\Calendar;

use Livewire\Component;
use App\Models\CalendarItem;
use App\Models\CalendarUpdate;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $months = ['Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'];

    public $selectedMonth;
    public $selectedDistrict;
    public $selectedDiscipline;

    public $results = [];
    public $subscribed = [];
    public $created;
    public $updated;

    public $selected = [];

    public function mount()
    {
        $this->selectedMonth = date('n');
        $this->selectedDistrict = '*';
        $this->selectedDiscipline = '*';
        $this->created = CalendarUpdate::where('type', 'created')->pluck('calendar_item_id')->toArray();
        $this->updated = CalendarUpdate::where('type', 'updated')->pluck('calendar_item_id')->toArray();
        $this->getResults();
    }

    public function getResults()
    {
        $monthStart = now()->month((int)$this->selectedMonth)->startOfMonth();
        $monthEnd = now()->month((int)$this->selectedMonth)->endOfMonth();
        $this->results = CalendarItem::where(function ($query) use ($monthStart, $monthEnd) {
            $query->whereBetween('date_from', [$monthStart, $monthEnd])
                ->orWhereBetween('date_to', [$monthStart, $monthEnd])
                ->orWhere(function ($query) use ($monthStart, $monthEnd) {
                    $query->where('date_from', '<=', $monthStart)
                        ->where('date_to', '>=', $monthEnd);
                });
        })
            ->when($this->selectedDistrict !== '*', function ($query) {
                return $query->where('district', $this->selectedDistrict);
            })
            ->when($this->selectedDiscipline !== '*', function ($query) {
                return $query->where('discipline', $this->selectedDiscipline);
            })
            ->get();
        $this->subscribed = Auth::user()->calendar_subscriptions->pluck('id')->toArray();
    }

    public function toggleSubscription($id)
    {
        $item = CalendarItem::find($id);
        if ($item->subscribers->contains(Auth::id())) {
            $item->subscribers()->detach(Auth::id());
            $this->subscribed = array_diff($this->subscribed, [$id]);
        } else {
            $item->subscribers()->attach(Auth::id());
            $this->subscribed[] = $id;
        }
    }

    public function toggle($id)
    {
        if ($this->selected === $id) {
            $this->selected = null;
            return;
        }
        $this->selected = $id;
    }

    public function render()
    {
        return view('livewire.calendar.index');
    }
}
