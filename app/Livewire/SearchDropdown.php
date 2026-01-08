<?php
namespace App\Livewire;

use Livewire\Component;

class SearchDropdown extends Component
{
    public $query = '';
    public $selectedItem = '';
    public $results;

    public $items;
    public $name = '';
    public $label = '';
    public $id = '';

    public function mount($items, $name, $label, $id) {
        $this->items = $items;
        $this->label = $label;
        $this->name = $name;
        $this->id = $id;
    }

    public function updatedQuery()
    {
        $this->querying();
    }

    public function querying() {
        // $this->results = Dapur::where('nama', 'like', '%' . $this->query . '%') // Adjust the field 'name' to match your needs
        //     ->take(10) // Limit the number of results
        //     ->get();

        $this->results = $this->items->filter(function ($item) {
            return stripos($item->nama, $this->query) !== false;
        });
    }

    public function selectedValue($selectValue) {
        $this->selectedItem = $selectValue;
        $this->query = $selectValue;
    }

    public function render()
    {
        return view('livewire.search-dropdown');
    }
}
