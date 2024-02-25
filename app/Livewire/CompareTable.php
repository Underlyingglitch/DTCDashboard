<?php

namespace App\Livewire;

use Livewire\Component;

class CompareTable extends Component
{
    public $table;
    public $value;
    public $loading = true;

    public function getListeners()
    {
        return [
            "echo:database,.ComparedTable" => 'updateValue',
        ];
    }

    public function mount($table, $value)
    {
        $this->table = $table;
        $this->value = $value;

        $this->getData();
    }

    public function updateValue($data)
    {
        if ($this->table == $data[0]) {
            $this->value = $data[1];
            $this->loading = false;
        }
    }

    public function getData()
    {
        $this->loading = true;
        \App\Jobs\Database\CompareTable::dispatch($this->table, $this->value);
    }

    public function keepAll($from)
    {
        if ($from == 'local') {
            foreach ($this->value[2] as $index => $row) {
                $this->keepLocal($index);
            }
        } else {
            foreach ($this->value[2] as $index => $row) {
                $this->keepProd($index);
            }
        }
    }

    public function keepLocal($index)
    {
        // If the local record is null, delete the prod record
        if ($this->value[2][$index]['local'] == null) {
            // Delete the production record
            \DB::connection('prod_server')->table($this->table)
                ->where('id', $this->value[2][$index]['id'])->delete();
        } else {
            // If the prod record is null, insert the local record into the prod table
            if ($this->value[2][$index]['prod'] == null) {
                // Insert the local record into the prod table
                \DB::connection('prod_server')->table($this->table)
                    ->insert((array) $this->value[2][$index]['local']);
            } else {
                // Update the prod record
                \DB::connection('prod_server')->table($this->table)
                    ->where('id', $this->value[2][$index]['id'])
                    ->update((array) $this->value[2][$index]['local']);
            }
        }
        unset($this->value[2][$index]);
        if (count($this->value[2]) == 0) {
            $this->getData();
        }
    }

    public function keepProd($index)
    {
        if ($this->value[2][$index]['prod'] == null) {
            // Delete the local record
            \DB::connection()->table($this->table)
                ->where('id', $this->value[2][$index]['id'])->delete();
        } else {
            if ($this->value[2][$index]['local'] == null) {
                // Insert the prod record into the local table
                \DB::connection()->table($this->table)
                    ->insert((array) $this->value[2][$index]['prod']);
            } else {
                // Update the local record
                \DB::connection()->table($this->table)
                    ->where('id', $this->value[2][$index]['id'])
                    ->update((array) $this->value[2][$index]['prod']);
            }
        }
        unset($this->value[2][$index]);
        if (count($this->value[2]) == 0) {
            $this->getData();
        }
    }

    public function render()
    {
        return view('livewire.compare-table');
    }
}
