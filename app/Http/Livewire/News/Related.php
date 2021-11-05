<?php

namespace App\Http\Livewire\News;

use Livewire\Component;
use Modules\News\Entities\News;
use Modules\News\Entities\NewsCategory;
use Livewire\WithPagination;

class Related extends Component
{
    // use WithPagination;

    public $category_id;
    public $get_new_id;
    public $search;
    public $count = 0;
    public $object_news_parent_cate_id;
    public $limitPerPage = 5;

    protected $listeners = [
        'load-more' => 'loadMore'
    ];

    public function mount($category_id, $get_new_id, $object_news_parent_cate_id) {
        $this->category_id = $category_id;
        $this->get_new_id = $get_new_id;
        $this->object_news_parent_cate_id = $object_news_parent_cate_id;
    }

    public function loadMore()
    {
        $this->limitPerPage = $this->limitPerPage + 3;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = News::query();
        $query->select([
            'el_news.id',
            'el_news.title',
            'el_news.description',
            'el_news.date_setup_icon',
            'el_news.image',
        ]);
        $query->where('el_news.category_id', '=', $this->category_id);
        $query->where('el_news.status', '=', 1);
        $query->where('el_news.id','!=',$this->get_new_id);
        $query->whereNotIn('el_news.id', $this->object_news_parent_cate_id);
        $query->orderByDesc('el_news.created_at');

        if(!empty($this->search)) {
            $query->whereDate('el_news.created_at', '=', $this->search);
        }

        $get_related_news = $query->latest()->paginate($this->limitPerPage);;
        return view('livewire.news.related', [
            'get_related_news' => $get_related_news,
        ]);
    }
}
