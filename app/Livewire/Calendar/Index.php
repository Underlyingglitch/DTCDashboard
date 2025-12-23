<?php

namespace App\Livewire\Calendar;

use Carbon\Carbon;
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

    public $monthOptions = [];
    public $results = [];
    public $subscribed = [];
    public $created;
    public $updated;

    public $selected = [];

    public function mount()
    {
        $this->generateMonthOptions();

        $current = now();
        $this->selectedMonth = $current->year . '-' . str_pad($current->month, 2, '0', STR_PAD_LEFT);
        $this->selectedDistrict = '*';
        $this->selectedDiscipline = '*';

        $this->created = CalendarUpdate::where('type', 'created')->pluck('calendar_item_id')->toArray();
        $this->updated = CalendarUpdate::where('type', 'updated')->pluck('calendar_item_id')->toArray();
        $this->getResults();
    }

    private function generateMonthOptions()
    {
        $current = now();
        $this->monthOptions = [];

        for ($i = -1; $i <= 4; $i++) {
            $date = $current->clone()->addMonths($i);
            $key = $date->year . '-' . str_pad($date->month, 2, '0', STR_PAD_LEFT);
            $monthName = $this->months[$date->month - 1];
            $this->monthOptions[$key] = "{$monthName} {$date->year}";
        }
    }

    public function getResults()
    {
        [$year, $month] = explode('-', $this->selectedMonth);

        $monthStart = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $monthEnd = Carbon::createFromDate($year, $month, 1)->endOfMonth();

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
