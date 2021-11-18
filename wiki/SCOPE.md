# Toplearning Performance Optimization

## I. Client Side

-   **Mục tiêu cần đạt**: Tăng điểm Performance đo bằng Lighthouse lên trên 80/100 cho tất cả các trang (hiện tại là khoảng ~22/100)

### 1. Flexiable pack the asset files: js, css, font

-   Review lại toàn bộ việc sử dụng các thư viện javascript, css frontend. Loại bỏ tất cả những thứ không cần thiết
-   Đối với các thư viện đang sử dụng: Bootstrap, Axios, Jquery, Lodas, Vue review việc sử dụng các thư viện này, nếu cần thì build bản optimize chỉ những component có dùng ở Toplearning.
-   Loại bỏ các tập tin font không cần thiết

### 2. CDN Binding

-   Viết hook để cho phép thiết lập chế độ chạy sử dụng CDN (CloudFront) cho các tập tin asset

### 3. Caching

-   Review việc tối ưu thiết lập của web-server để chỉ định caching các tập tin asset ở browser

### 4. Optimization static files: jpg, png

-   Chuyển sang sử dụng SVG, WEBP để giảm dung lượng
-   Viết các lớp thư viện cần thiết cho Laravel để thực hiện optimize (kích thước, định dạng, chất lượng) hình ảnh khi hình ảnh được upload lên server.

## II. Server Side

-   **Mục tiêu cần đạt**: Không có trang nào mà Server Side render chậm hơn 1s

### 1. Application processing

-   Viết các Hook/Middleware cần thiết để kiểm soát thời gian/bộ nhớ cần để thực thi mỗi tiến trình của ứng dụng. Từ đó sử dụng để kiểm soát và phát hiện ra các vấn đề cụ thể làm chậm một trang bất kì.
-   Hook/Middleware này được khởi chạy ở chế độ debug để các dev có thể sử dụng để kiểm tra khi viết một Module mới cho dự án.
-   Chạy Automation Stress Test cho tất cả các trang của ứng dụng. Record lại tất cả các trang chạy chậm, tìm hiểu nguyên nhân và fix tất cả các trang đó.

### 2. Database Queries

-   Viết các Hook/Middleware cần thiết để kiểm soát thời gian/bộ nhớ cần để thực thi mỗi câu truy vấn MySQL. Từ đó sử dụng để kiểm soát và phát hiện ra các Slow Query và thực hiện optimization trên lược đồ dữ liệu nhằm tăng tốc.
-   Viết hướng dẫn thiết lập Slow Queries Log cho MySQL nhằm ghi log các câu truy vấn chậm nhằm có hướng xử lý.

### 3. Caching

-   Thực hiện Memory Caching dùng Memcached cho tất cả các thứ có thể Cache nhằm tăng tốc và loại bỏ các xử lý thừa thãi.
-   Viết file hướng dẫn cách áp dụng Cache cho dev áp dụng khi viết Module mới.

## III. Server Configuration

-   Đề xuất cấu hình chuẩn của Apache; MySQL; Php trên cả server Linux và Windows sao cho tối ưu nhất cho ứng dụng Toplearning

## IV. Application Monitoring Platform

-   Hướng dẫn cách tích hợp Application Monitoring cho ứng dụng nhằm kiểm soát tất cả các bug/exception xảy ra khi deploy ứng dụng. Dựa vào đó sẽ biết được tất cả các lỗi xảy ra, xảy ra với user nào, sử dụng trình duyệt gì, đặc điểm của máy tính người gặp lỗi ra sao.
