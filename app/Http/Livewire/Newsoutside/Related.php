<?php

namespace App\Http\Livewire\Newsoutside;

use Livewire\Component;
use Modules\NewsOutside\Entities\NewsOutside;
use Modules\NewsOutside\Entities\NewsOutsideCategory;
use Livewire\WithPagination;

class Related extends Component
{
    // use WithPagination;

    public $category_id;
    public $get_new_outside_id;
    public $search;
    public $type;
    public $limitPerPage = 5;

    protected $listeners = [
        'load-more' => 'loadMore'
    ];
    
    public function mount($category_id, $get_new_outside_id, $type) {
        $this->category_id = $category_id;
        $this->get_new_outside_id = $get_new_outside_id;
        $this->type = $type;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function loadMore()
    {
        $this->limitPerPage = $this->limitPerPage + 3;
    }
    
    public function render()
    {
        $query = NewsOutside::query();
        $query->select([
            'a.id',
            'a.title',
            'a.description',
            'a.date_setup_icon',
            'a.image',
        ]);
        $query->from('el_news_outside AS a');
        $query->where('a.category_id', '=', $this->category_id);
        $query->where('a.status', '=', 1);
        $query->where('id','!=',$this->get_new_outside_id);
        $query->orderByDesc('a.created_at');

        if(!empty($this->search)) {
            $query->whereDate('a.created_at', '=', $this->search);
        } 
        $get_related_news_outside = $query->latest()->paginate($this->limitPerPage);
        return view('livewire.newsoutside.related', [
            'get_related_news_outside' => $get_related_news_outside,
            'type' => $this->type
        ]);
    }
}
