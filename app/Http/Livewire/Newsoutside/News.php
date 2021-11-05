<?php

namespace App\Http\Livewire\Newsoutside;

use Livewire\Component;
use Modules\NewsOutside\Entities\NewsOutside;
use Modules\NewsOutside\Entities\NewsOutsideCategory;
use Livewire\WithPagination;

class News extends Component
{
    // use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $category_id;
    public $type;
    public $parent_id;
    public $get_id_cate_parent_related;
    public $limitPerPage = 5;

    protected $listeners = [
        'load-more' => 'loadMore'
    ];

    public function mount($type, $category_id, $parent_id, $get_id_cate_parent_related) {
        $this->category_id = $category_id;
        $this->type = $type;
        $this->parent_id = $parent_id;
        $this->get_id_cate_parent_related = $get_id_cate_parent_related;
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
            'a.date_setup_icon',
            'a.description',
            'a.image',
        ]);
        $query->from('el_news_outside AS a');
        if($this->type == 1) {
            $query->where('a.category_id', '=', $this->category_id);
            $query->where('hot',0);
        } else {
            $query->where('a.category_parent_id', '=', $this->parent_id);
            $query->whereNotIn('a.id', $this->get_id_cate_parent_related);
        }
        $query->where('a.status', '=', 1);
        $query->orderByDesc('a.created_at');
        $news = $query->latest()->paginate($this->limitPerPage);

        return view('livewire.newsoutside.news', [
            'news' => $news,
        ]);
    }
}
