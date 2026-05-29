# Đặc tả hiện tại: Memory Editor

## 1. Mục tiêu sản phẩm

`chuaminh.vn` là trang lưu kỷ niệm theo dạng album hình ảnh. Trình biên tập ưu tiên thao tác trực quan: chọn template, thêm block, chọn hoặc upload media, kéo sắp xếp và xuất bản. Nội dung bài viết không còn được lưu dưới dạng JSON tự do.

Hướng thiết kế hiện tại:

- Ảnh là nội dung chính; chữ được giữ ngắn để bổ trợ cảm xúc.
- Một bài có thể dùng nhiều bố cục ảnh khác nhau, kể cả nhiều block gallery.
- Video được upload vào thư viện media rồi gắn vào block, không nhập link video mới.
- Dữ liệu editor phải có cột rõ ràng để dễ kiểm tra, tìm kiếm và mở rộng UI.

## 2. Luồng sử dụng editor

Trang editor là `/admin/memories/{post}/editor`.

1. Panel trái chọn template và thêm block đang hoạt động.
2. Khung giữa xem trước thứ tự block, ảnh hoặc video đang gắn.
3. Panel phải sửa thông tin bài và thuộc tính của block đang chọn.
4. Upload media tạo một bản ghi `media`; block chỉ lưu `media_id`.
5. Khi lưu, controller ghi các trường typed vào `post_sections` và các phần tử lặp vào `post_section_items`.

## 3. Các bảng dữ liệu chính

### `posts`

Giữ thông tin nhận diện và xuất bản:

| Nhóm | Cột chính |
| --- | --- |
| Bài viết | `title`, `slug`, `excerpt`, `cover_media_id` |
| Phân loại | `template_id`, `category_id`, `mood` |
| Xuất bản | `status`, `visibility`, `published_at`, `is_featured` |
| Kỷ niệm | `memory_date`, `location_name` |
| SEO | `seo_title`, `seo_description`, `og_media_id` |

Các cột đã loại bỏ vì editor không dùng: `content`, `settings`, `memory_date_precision`, `location_lat`, `location_lng`, `sort_order`.

### `post_details`

Thông tin phụ chỉ có một bản ghi cho mỗi bài:

| Cột | Ý nghĩa |
| --- | --- |
| `date_range` | Chuỗi ngày hiển thị trong hero/card |
| `music_enabled` | Có hiển thị nhạc nền hay không |
| `music_url`, `music_title`, `music_artist` | Thông tin nhạc nền |

### `media` và `post_media`

`media` lưu ảnh, video, audio hoặc file đã upload; các block trỏ tới media bằng khóa ngoại. `post_media` chỉ giữ quan hệ bài-media gồm `role` và `sort_order`; metadata dư thừa ở pivot đã được bỏ.

### `post_sections`

Mỗi block trong bài là một hàng có kiểu cụ thể:

| Nhóm trường | Cột |
| --- | --- |
| Nhận diện | `type`, `title`, `subtitle`, `variant`, `sort_order`, `is_visible` |
| Media đơn | `media_id` |
| Nội dung ngắn | `headline`, `body`, `quote_text`, `quote_author`, `caption`, `url` |
| Trình bày có ích | `height`, `layout`, `autoplay` |

Các JSON cũ `data`, `style`, `settings` và các cột styling không được render (`accent_color`, `text_align`, `lightbox_enabled`, `overlay_style`) đã được bỏ sau khi backfill.

### `post_section_items`

Dùng cho block có danh sách con như stats, grid hoặc slider:

| Block | Trường item sử dụng |
| --- | --- |
| `stats` | `value`, `label` |
| `gallery_grid` | `media_id`, `caption` |
| `gallery_slider` | `media_id`, `caption` |
| `timeline` | `time_label`, `title`, `body`, `media_id` |

## 4. Block đang cho phép thêm mới

| Block | Mục đích | Điều khiển chính |
| --- | --- | --- |
| `hero_image` | Ảnh bìa mở bài | ảnh, headline, caption, chiều cao |
| `stats` | Dãy con số ngắn | các item giá trị/nhãn |
| `single_image` | Một ảnh nổi bật | ảnh, caption |
| `gallery_grid` | Nhóm ảnh dạng lưới | nhiều ảnh, layout |
| `gallery_slider` | Nhóm ảnh vuốt ngang | nhiều ảnh, autoplay |
| `video_embed` | Video upload | media loại video, caption, tỷ lệ |
| `quote` | Một câu trích dẫn | câu chữ, nguồn |
| `music` | Nhạc nền | URL audio, tiêu đề, nghệ sĩ |
| `timeline` | Chuỗi mốc kỷ niệm | thời gian, tiêu đề, mô tả, ảnh từng mốc |

Layout gallery hiện hỗ trợ: `mosaic`, `grid_2`, `grid_3`, `featured_left`, `masonry`, `film_strip`, `polaroid`.

Các block chữ dài cũ (`rich_text`, `image_text`, `ending`) được ngừng thêm mới để editor gọn và tập trung vào ảnh. `timeline` đã được bật lại vì mỗi mốc kết hợp ảnh và nội dung ngắn, phù hợp với luồng album kỷ niệm.

## 5. Video upload

Block `video_embed` giữ tên kỹ thuật cũ để tương thích component, nhưng UI hiện gọi là **Video Upload**.

- Người biên tập chọn video đã có hoặc bấm `Upload video mới`.
- File upload được lưu vào `media` với `type = video`.
- Khi lưu, controller từ chối `media_id` không phải video.
- Trang công khai render video bằng thẻ HTML `<video controls>`.
- URL iframe chỉ còn là fallback đọc bài cũ đã tồn tại trước migration; editor không tạo URL video mới.

## 6. Migration và dữ liệu mẫu

Migration `2026_05_25_000320_cleanup_legacy_post_section_columns.php` thực hiện theo thứ tự:

1. Chuyển `posts.settings` cũ sang `post_details` nếu chưa có dữ liệu mới.
2. Chuyển JSON của section cũ sang các cột typed và `post_section_items`.
3. Đánh dấu các loại block chữ legacy là không hoạt động cho việc thêm mới.
4. Xóa các cột legacy/không sử dụng.

`DemoContentSeeder` hiện tạo một album thiên về ảnh: hero, stats, hai kiểu gallery grid, slider, ảnh đơn, timeline và quote. Seeder không tạo JSON section và không tạo video nhúng bằng link.

Migration `2026_05_25_000330_retire_legacy_editor_section_types.php` là bước đồng bộ nhỏ cho database phát triển đã chạy migration dọn cột trước khi danh sách block mới được chốt; nó ẩn các block chữ legacy và đổi nhãn block video thành `Video Upload`.

Migration `2026_05_26_000340_enable_timeline_editor_section_type.php` bật lại `timeline` như một block chính thức và thêm nó vào danh sách block được hỗ trợ của các template hiện có.

Migration `2026_05_26_000350_drop_unused_preset_tables.php` xóa `template_presets` và `section_presets` vì editor hiện không có thao tác lưu/chọn preset; cấu hình đang dùng nằm trực tiếp ở `templates`, `section_types` và các section typed của bài.

## 7. Ranh giới hiện tại

- JSON trong `templates` và `section_types` vẫn được giữ vì đó là cấu hình hệ thống/schema UI, không phải nội dung của từng bài.
- Bảng `settings` vẫn được giữ riêng cho cấu hình toàn hệ thống; nó không phải bảng preset.
- Nhạc nền hiện vẫn nhận URL audio; việc chuyển audio sang upload có thể thực hiện tương tự video trong vòng tiếp theo.
- Các bố cục ảnh có thể tiếp tục mở rộng mà không thay đổi schema, vì `layout` là lựa chọn hiển thị của gallery.
