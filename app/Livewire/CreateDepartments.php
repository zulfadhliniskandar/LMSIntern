<?php

namespace App\Livewire;

use App\Models\Departments;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateDepartments extends Component
{
    #[Validate('required|min:3|max:100|string')]
    public $name = '';

    #[Validate('required|min:3|max:5|string')]
    public $code = '';

    public function render()
    {
        return view('livewire.create-departments');
    }

    public function createDepartments()
    {
        $validated = $this->validate();
        Departments::create($validated);
        session()->flash('success', 'Department created successfully');
        return redirect()->route('listDepartments');
    }
}
