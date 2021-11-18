# 🚀 Cài đặt

### Cài đặt package

Sử dụng Composer để cài:

```bash
composer require rennokki/laravel-eloquent-query-cache
```

### Chuẩn bị lớp models

 Trong các model muốn sử dụng tính năng cache của thư viện, khai báo sử dụng trait `Rennokki\QueryCache\Traits\QueryCacheable`.

```php
use Rennokki\QueryCache\Traits\QueryCacheable;

class Podcast extends Model
{
    use QueryCacheable;
}
```
