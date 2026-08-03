<?php

namespace App\Livewire;

use App\Models\Departments;
use Livewire\Component;

class ListDeparments extends Component
{
    public $search = '';

    public function delete($id)
    {
        $department = Departments::findOrFail($id);
        $department->delete();
        session()->flash('success', 'Department deleted successfully.');
    }

    public function render()
    {
        $departments = Departments::withCount('programmes')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->get();

        return view('livewire.list-deparments', [
            'departments' => $departments,
        ]);
    }
}
