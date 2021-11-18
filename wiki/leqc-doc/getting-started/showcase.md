# 🙌 Ví dụ

Thư viện sẽ tự động sử dụng các câu query trong lớp làm key để thực hiện cache, làm cho việc cache trở nên thuận tiện và độc lập với các truy vấn ORM được sử dụng trong model.

Mặc định, caching sẽ không được bật cho đến khi thiết lập giá trị cho thuộc tính `$cacheFor` của lớp.

```php
use Rennokki\QueryCache\Traits\QueryCacheable;

class Article extends Model
{
    use QueryCacheable;

    /**
     * Specify the amount of time to cache queries.
     * Do not specify or set it to null to disable caching.
     *
     * @var int|\DateTime
     */
    public $cacheFor = 3600; // cache time, in seconds
}
```

Hai truy vấn sau đây được lưu riêng rẽ bằng các key khác nhau trong cache storage:

```php
// For the below query, a hash will be made using the following SQL:
// SELECT * FROM articles ORDER BY created_at DESC LIMIT 1;
$latestArticle = Article::latest()->first();

// SELECT * FROM articles WHERE published = 1;
$publishedArticles = Article::wherePublished(true)->get();
```

