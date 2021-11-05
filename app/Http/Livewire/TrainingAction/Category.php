<?php

namespace App\Http\Livewire\TrainingAction;

use App\Models\TrainingActionCategory;
use Livewire\Component;

class Category extends Component
{
    public function render()
    {
        $categories = TrainingActionCategory::paginate(20);
        return view('livewire.training-action.category.index', [
            'categories' => $categories
        ]);
    }
    
    public function from() {
        return view('livewire.training-action.category.from');
    }
}
