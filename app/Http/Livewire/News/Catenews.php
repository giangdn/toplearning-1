<?php

namespace App\Http\Livewire\News;

use Livewire\Component;
use Modules\News\Entities\News;
use Modules\News\Entities\NewsCategory;
use Livewire\WithPagination;

class Catenews extends Component
{
    // use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $category_id;
    public $type;
    public $parent_id;
    public $object_news_parent_cate_id;
    public $limitPerPage = 5;

    protected $listeners = [
        'load-more' => 'load'
    ];

    public function mount($type, $category_id, $parent_id, $object_news_parent_cate_id) {
        $this->category_id = $category_id;
        $this->type = $type;
        $this->parent_id = $parent_id;
        $this->object_news_parent_cate_id = $object_news_parent_cate_id;
    }

    public function load()
    {
        $this->limitPerPage = $this->limitPerPage + 4;
    }

    public function render()
    {
        $query = News::query();
        $query->select([
            'el_news.id',
            'el_news.title',
            'el_news.date_setup_icon',
            'el_news.description',
            'el_news.image',
        ]);
        if($this->type == 1) {
            $query->where('el_news.category_id', '=', $this->category_id);
            $query->where('hot',0);
        } else {
            $query->where('el_news.category_parent_id', '=', $this->parent_id);
        }
        $query->whereNotIn('el_news.id', $this->object_news_parent_cate_id);
        $query->where('el_news.status', '=', 1);
        $query->orderByDesc('el_news.created_at');
        $news = $query->latest()->paginate($this->limitPerPage);
        $this->emit('newsStore');

        return view('livewire.news.catenews', [
            'news' => $news,
        ]);
    }
}
