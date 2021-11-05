<?php

namespace Modules\Libraries\Entities;

use App\BaseModel;
use App\Warehouse;
use Illuminate\Database\Eloquent\Model;
use Modules\Libraries\Entities\LibrariesCategory;
use Response;

/**
 * Modules\Libraries\Entities\Libraries
 *
 * @property int $id
 * @property string $name
 * @property string|null $image
 * @property int $views
 * @property int $current_number
 * @property int $download
 * @property int $status
 * @property string|null $description
 * @property int $type
 * @property int $category_id
 * @property int $created_by
 * @property int $updated_by
 * @property string|null $attachment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\Libraries newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\Libraries newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\Libraries query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\Libraries whereAttachment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\Libraries whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\Libraries whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\Libraries whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\Libraries whereCurrentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\Libraries whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\Libraries whereDownload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\Libraries wherePhoneContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\Libraries whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\Libraries whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\Libraries whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\Libraries whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\Libraries whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\Libraries whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\Libraries whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\Libraries whereViews($value)
 * @mixin \Eloquent
 * @property-read \Modules\Libraries\Entities\LibrariesCategory $category
 */
class Libraries extends BaseModel
{
    protected $table = 'el_libraries';
    protected $fillable = [
        'name',
        'image',
        'views',
        'download',
        'status',
        'current_number',
        'description',
        'type',
        'category_id',
        'created_by',
        'updated_by',
        'attachment',
        'phone_contact',
        'name_author',
    ];
    protected $primaryKey = 'id';

    //lấy tất cả các sách
    public static function getListBook($search_cate, $search, $per_page = 8){
        $query = self::query();
        $query->where('status', '=', 1);
        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('name','like','%' . $search . '%');
            });
        }
        if ($search_cate){
            $query->where('category_id', '=', $search_cate);
        }
        $query->where('type', '=', 1);
        return $query->paginate($per_page);
    }

    //lấy tất cả các ebook
    public static function getListEbook($id, $search_cate, $search, $search_author, $per_page = 8){
        $query = self::query();
        $query->where('status', '=', 1);
        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('name','like','%' . $search . '%');
            });
        }

        if($search_author){
            $query->where(function($sub_query) use ($search_author){
                $sub_query->orWhere('name_author','like','%' . $search_author . '%');
            });
        }

        if($id > 0){
            $cate_with_id = LibrariesCategory::find($id);
            $query->where('category_parent','like', '%' . $cate_with_id->name . '%');
        }

        $query->where('type', '=', 2);
        $query->orderByDesc('id');

        if(($search_author || $search) && !url_mobile()) {
            return $query->get();
        } else {
            return $query->paginate($per_page);
        }
    }

    //lấy tất cả các tài liệu
    public static function getListDocument($id, $search_cate, $search, $search_author, $per_page = 8){
        $query = self::query();
        $query->where('status', '=', 1);
        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('name','like','%' . $search . '%');
            });
        }

        if($search_author){
            $query->where(function($sub_query) use ($search_author){
                $sub_query->orWhere('name_author','like','%' . $search_author . '%');
            });
        }

        if($id > 0){
            $cate_with_id = LibrariesCategory::find($id);
            $query->where('category_parent','like', '%' . $cate_with_id->name . '%');
        }

        $query->where('type', '=', 3);
        $query->orderByDesc('id');

        if(($search_author || $search ) && !url_mobile()) {
            return $query->get();
        } else {
            return $query->paginate($per_page);
        }
    }

    //lấy tất cả các video
    public static function getListVideo($id, $search_cate, $search, $search_author, $per_page = 8){
        $query = self::query();
        $query->where('status', '=', 1);
        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('name','like','%' . $search . '%');
            });
        }

        if($search_author){
            $query->where(function($sub_query) use ($search_author){
                $sub_query->orWhere('name_author','like','%' . $search_author . '%');
            });
        }

        if($id > 0){
            $cate_with_id = LibrariesCategory::find($id);
            $query->where('category_parent','like', '%' . $cate_with_id->name . '%');
        }

        $query->where('type', '=', 4);
        $query->orderByDesc('id');

        if(($search_author || $search) && !url_mobile()) {
            return $query->get();
        } else {
            return $query->paginate($per_page);
        }
    }
    //lấy tất cả các sách nói
    public static function getListAudiobook($id, $search_cate, $search, $search_author, $per_page = 8){
        $query = self::query();
        $query->where('status', '=', 1);
        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('name','like','%' . $search . '%');
            });
        }

        if($search_author){
            $query->where(function($sub_query) use ($search_author){
                $sub_query->orWhere('name_author','like','%' . $search_author . '%');
            });
        }

        if($id > 0){
            $cate_with_id = LibrariesCategory::find($id);
            $query->where('category_parent','like', '%' . $cate_with_id->name . '%');
        }

        $query->where('type', '=', 5);
        $query->orderByDesc('id');

        if(($search_author || $search) && !url_mobile()) {
            return $query->get();
        } else {
            return $query->paginate($per_page);
        }
    }

    //lấy sách mới nhất
    public static function getNewBook($length = 8){
        $query = self::query();
        $query->where('status', '=', 1);
        $query->where('type', '=', 1);
        $query->orderBy('id', 'DESC');
        $query->limit($length);
        return $query->get();
    }

    //lấy ebook mới nhất
    public static function getNewEbook($length = 8){
        $query = self::query();
        $query->where('status', '=', 1);
        $query->where('type', '=', 2);
        $query->orderBy('id', 'DESC');
        $query->limit($length);
        return $query->get();
    }

    //lấy tài liệu mới nhất
    public static function getNewDocument($length = 8){
        $query = self::query();
        $query->where('status', '=', 1);
        $query->where('type', '=', 3);
        $query->orderBy('id', 'DESC');
        $query->limit($length);
        return $query->get();
    }

    //lấy video mới nhất
    public static function getNewVideo($length = 8){
        $query = self::query();
        $query->where('status', '=', 1);
        $query->where('type', '=', 4);
        $query->orderBy('id', 'DESC');
        $query->limit($length);
        return $query->get();
    }

    //lấy book, ebook, document liên quan
    public static function getLibrariesCategory($category_id, $current_id = 0){
        $query = self::query();
        $query->where('status', '=', 1);
        $query->where('category_id', '=', $category_id);
        $query->where('id', '!=', $current_id);
        return $query->get();
    }

    public static function getAttributeName() {
        return [
            'name'=>'Tên sách',
            'image'=>'Ảnh',
            'views'=>'Lượt xem',
            'download'=>'download',
            'status'=>'bật',
            'current_number'=>'Số lượng hiện có',
            'description'=>'Mô tả',
            'type'=>'Loại sách',
            'category_id'=>'Id sách',
            'created_by'=>'Ngày tạo',
            'updated_by'=>'Ngày sửa',
            'phone_contact'=>'Số điện thoại liên hệ',
            'name_author' => 'Tên tác giả',
        ];
    }

    public function getLinkDownload() {
        return link_download('uploads/'.$this->attachment);
    }

    public function isFilePdf() {
        if (empty($this->attachment)) {
            return false;
        }

        $extention = pathinfo($this->attachment, PATHINFO_EXTENSION);
        if ($extention == 'pdf' || $extention == 'PDF') {
            return true;
        }

        return false;
    }

    public function getLinkView() {
        if (!$this->isFilePdf()) {
            return false;
        }
        return upload_file($this->attachment);
    }

    //Đếm sl sách
    public static function countAllBook(){
        return self::where('type', '=', 1)->count();
    }
    //Đếm sl sách được bật
    public static function countBookByStatus(){
        return self::where('type', '=', 1)->where('status', '=', 1)->count();
    }

    //Đếm sl ebook
    public static function countAllEBook(){
        return self::where('type', '=', 2)->count();
    }
    //Đếm sl ebook được bật
    public static function countEBookByStatus(){
        return self::where('type', '=', 2)->where('status', '=', 1)->count();
    }

    //Đếm sl tài liệu
    public static function countAllDoc(){
        return self::where('type', '=', 3)->count();
    }
    //Đếm sl tài liệu được bật
    public static function countDocByStatus(){
        return self::where('type', '=', 3)->where('status', '=', 1)->count();
    }

    public static function getLibrariesByCategory($type, $category_id, $length = 8)
    {
        $query = self::query();
        $query->where('type', '=', $type);
        $query->where('category_id', '=', $category_id);
        $query->orderBy('id', 'DESC');
        $query->limit($length);
        return $query->get();
    }

    public function category()
    {
        return $this->belongsTo('Modules\Libraries\Entities\LibrariesCategory');
    }

    public function getLinkPlay() {
        $storage = \Storage::disk('local');
        $file = encrypt_array([
            'path' => $storage->path('uploads/' . $this->attachment),
        ]);

        return route('stream.video', [$file]);
    }
}
