# Toplearning Performance Optimization

## 1. Inital

- Php version 7.4
- Php7.4-memcached
- Cấu hình db trong config/database.php
- Sau khi chạy `composer install` thì thực hiện migrate dữ liệu bằng `php artisan migrate`
- Chạy seeding dữ liệu bằng `php artisan db:seed`

## 2. Cài đặt laravel-eloquent-query-cache
- Chạy `composer require rennokki/laravel-eloquent-query-cache` để cài đặt dùng composer.
- Nếu không thể cài đặt bằng composer thì cài đặt thủ công bằng cách:
	- Lên trang release chính thức tại [Github chính thức](https://github.com/renoki-co/laravel-eloquent-query-cache/releases) và tải về
	- Chép thư viện đã tải vào thư mục `vendor/rennokki/laravel-eloquent-query-cache` của project
	- Thêm `"Rennokki\\QueryCache\\": "vendor/rennokki/laravel-eloquent-query-cache/src"` vào mục `psr-4` của `autoload` trong file composer.json

## 3. Các thiết lập liên quan đến Memcached
- Trong tập tin .env của project Laravel, thêm vào các thiết lập sau
	- `CACHE_DRIVER=memcached` để chỉ định Laravel sử dụng Memcached
	- `MEMCACHED_HOST=127.0.0.1` địa chỉ truy cập đến Memcached
	- `MEMCACHED_PORT=11211` port truy cập đến Memcached
	- `MEMCACHED_PERSISTENT_ID=<Chuỗi mã số>`, ví dụ `MEMCACHED_PERSISTENT_ID=D63VNDpjACFA6vNB` 
- Host và Port của Memcached có thể thay đổi trong tập tin `/etc/memcached.conf` (trên Linux)
- Các tham số bên trên là các tham số khai báo cơ bản để Laravel có thể sử dụng Memcached thông qua lớp `Illuminate\Support\Facades\Cache`.
- Để bật chế độ **Eloquent Query Cache** thì trong tập tin .env thêm vào thiết lập `CACHE_SUBSTANCE_ENABLE=true`. Đồng thời binding nó vào ứng dụng bằng thiết lập `'enable_subtance_caching' => env('CACHE_SUBSTANCE_ENABLE', true)` trong tập tin `config/app.php`
- Các thiết lập về Memcached được khai báo trong tập tin .env sẽ được binding vào `config/cache.php` để Laravel dễ dàng truy xuất thông qua hàm `config()`.

## 4. Lớp cơ sở `CacheModel`
- Đây là lớp cơ sở được sử dụng làm lớp cha cho các lớp Eloquent Model muốn triển khai Cache.
- 