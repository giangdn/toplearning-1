# ⚡ Caching các truy vấn

Các thiết lập cache có thể được thực hiện ở cấp độ đối tượng, hoặc ở cấp độ mỗi truy vấn.

Ví dụ, chúng ta có thể thiết lập cache cho model tên `Book` như sau:

```php
class Book extends Model
{
    /**
     * Specify the amount of time to cache queries.
     * Do not specify or set it to null to disable caching.
     *
     * @var int|\DateTime
     */
    public $cacheFor = 3600;

    /**
     * The tags for the query cache. Can be useful
     * if flushing cache for specific tags only.
     *
     * @var null|array
     */
    public $cacheTags = ['books'];

    /**
     * A cache prefix string that will be prefixed
     * on each cache key generation.
     *
     * @var string
     */
    public $cachePrefix = 'books_';

    /**
     * The cache driver to be used.
     *
     * @var string
     */
    public $cacheDriver = 'dynamodb';
}
```

### Bật/Tắt caching on-demand

Với tùy chọn thiết lập cache ở mức đối tượng, giá trị `$cacheFor` được thiết lập thì toàn bộ các query đều được cache lại. Để thực hiện một ngoại lệ nào đó khi truy vấn, có thể sử phương thức `dontCache` như sau:

```php
$uncachedBooks = Book::dontCache()->get();
```

Thêm nữa, nếu muốn thiết lập lại giá trị cache-for cho truy vấn cụ thể nào đó, hãy cân nhắc sử dụng `cacheFor()` khi truy vấn:

```php
$booksCount = Book::cacheFor(60 * 60)->count();
```

Sử dụng DateTime theo cách của Carbon:

```php
$booksCount = Book::cacheFor(now()->addDays(1))->count();
```

### Prefixing

Tương tự, `$cachePrefix` cũng có thể được thiết lập ở cả cấp độ đối tượng và cấp độ truy vấn:

```php
$scifiBooks = Book::cachePrefix('scifi_')->count();
```

### Khai báo các giá trị linh động

Có nhiều tình huống ta muốn các giá trị cache không cố định mà linh hoạt. Giả sử ta chỉ cache cho đối tượng người sử dụng bình thường mà không cache khi admin truy cập, điều này hoàn toàn có thể thực hiện được thông qua các phương thức tương ứng. Như ở ví dụ sau:

```php
class Book extends Model
{
    /**
     * Specify the amount of time to cache queries.
     * Do not specify or set it to null to disable caching.
     *
     * @var int|\DateTime
     */
    public $cacheFor = 3600;

    /**
     * Specify the amount of time to cache queries.
     * Set it to null to disable caching.
     *
     * @return int|\DateTime
     */
    protected function cacheForValue()
    {
        if (optional(request()->user())->hasRole('admin')) {
            return null;
        }
        
        return $this->cacheFor;
    }
}
```

Phía sau là các phương thức giúp thiết lập giá trị cho cache một cách "programatically":

```php
class Book extends Model
{
    /**
     * Specify the amount of time to cache queries.
     * Do not specify or set it to null to disable caching.
     *
     * @return int|\DateTime
     */
    protected function cacheForValue()
    {
        return 3600;
    }

    /**
     * The tags for the query cache. Can be useful
     * if flushing cache for specific tags only.
     *
     * @return null|array
     */
    protected function cacheTagsValue()
    {
        return ['books'];
    }

    /**
     * A cache prefix string that will be prefixed
     * on each cache key generation.
     *
     * @return string
     */
    protected function cachePrefixValue()
    {
        return 'books_';
    }

    /**
     * The cache driver to be used.
     *
     * @return string
     */
    protected function cacheDriverValue()
    {
        return 'dynamodb';
    }
}
```
