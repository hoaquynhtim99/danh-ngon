<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN')) {
    exit('Stop!!!');
}

$db->query("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_authors (id, name_author, alias, description, bodyhtml, image, is_thumb, admin_id, addtime, updatetime) VALUES
(1, 'Albert Camus', 'Albert-Camus', '', '', '', 0, 0, " . NV_CURRENTTIME . ", 0),
(2, 'Albert Einstein', 'Albert-Einstein', '', '', '', 0, 0, " . NV_CURRENTTIME . ", 0),
(3, 'Aristotle', 'Aristotle', '', '', '', 0, 0, " . NV_CURRENTTIME . ", 0),
(4, 'Benjamin Franklin', 'Benjamin-Franklin', '', '', '', 0, 0, " . NV_CURRENTTIME . ", 0),
(5, 'Brian Tracy', 'Brian-Tracy', '', '', '', 0, 0, " . NV_CURRENTTIME . ", 0),
(11, 'Jim Rohn', 'Jim-Rohn', '', '', '', 0, 0, " . NV_CURRENTTIME . ", 0),
(12, 'Johann Wolfgang von Goethe', 'Johann-Wolfgang-von-Goethe', '', '', '', 0, 0, " . NV_CURRENTTIME . ", 0),
(13, 'Khuyết Danh', 'Khuyet-Danh', '', '', '', 0, 0, " . NV_CURRENTTIME . ", 0),
(14, 'Luôn mỉm cười với cuộc sống', 'Luon-mim-cuoi-voi-cuoc-song', '', '', '', 0, 0, " . NV_CURRENTTIME . ", 0),
(15, 'Mahatma Gandhi', 'Mahatma-Gandhi', '', '', '', 0, 0, " . NV_CURRENTTIME . ", 0),
(16, 'Marcus Tullius Cicero', 'Marcus-Tullius-Cicero', '', '', '', 0, 0, " . NV_CURRENTTIME . ", 0),
(17, 'Mark Twain', 'Mark-Twain', '', '', '', 0, 0, " . NV_CURRENTTIME . ", 0),
(18, 'Martin Luther King Jr.', 'Martin-Luther-King-Jr', '', '', '', 0, 0, " . NV_CURRENTTIME . ", 0),
(19, 'Napoleon Hill', 'Napoleon-Hill', '', '', '', 0, 0, " . NV_CURRENTTIME . ", 0),
(20, 'Oscar Wilde', 'Oscar-Wilde', '', '', '', 0, 0, " . NV_CURRENTTIME . ", 0),
(21, 'Ralph Waldo Emerson', 'Ralph-Waldo-Emerson', '', '', '', 0, 0, " . NV_CURRENTTIME . ", 0),
(22, 'Samuel Johnson', 'Samuel-Johnson', '', '', '', 0, 0, " . NV_CURRENTTIME . ", 0),
(23, 'Thiền sư Ajahn Chah', 'Thien-su-Ajahn-Chah', '', '', '', 0, 0, " . NV_CURRENTTIME . ", 0),
(24, 'Thomas Carlyle', 'Thomas-Carlyle', '', '', '', 0, 0, " . NV_CURRENTTIME . ", 0),
(25, 'Thomas Fuller', 'Thomas-Fuller', '', '', '', 0, 0, " . NV_CURRENTTIME . ", 0),
(26, 'Tony Robbins', 'Tony-Robbins', '', '', '', 0, 0, " . NV_CURRENTTIME . ", 0),
(27, 'Tân Di Ổ', 'Tan-Di-O', '', '', '', 0, 0, " . NV_CURRENTTIME . ", 0),
(28, 'Victor Hugo', 'Victor-Hugo', '', '', '', 0, 0, " . NV_CURRENTTIME . ", 0),
(29, 'Voltaire', 'Voltaire', '', '', '', 0, 0, " . NV_CURRENTTIME . ", 0),
(30, 'Zig Ziglar', 'Zig-Ziglar', '', '', '', 0, 0, " . NV_CURRENTTIME . ", 0);");

// Bảng tag
$db->query("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_tags (id, title, alias, description, keywords, image, addtime, updatetime) VALUES
(1, 'Bản thân', 'Ban-than', '', '', '', " . NV_CURRENTTIME . ", 0),
(2, 'Bắt đầu', 'Bat-dau', '', '', '', " . NV_CURRENTTIME . ", 0),
(3, 'Can đảm', 'Can-dam', '', '', '', " . NV_CURRENTTIME . ", 0),
(4, 'Cuộc sống', 'Cuoc-song', '', '', '', " . NV_CURRENTTIME . ", 0),
(5, 'Giấc mơ', 'Giac-mo', '', '', '', " . NV_CURRENTTIME . ", 0),
(6, 'Hành động', 'Hanh-dong', '', '', '', " . NV_CURRENTTIME . ", 0),
(7, 'Hạnh phúc', 'Hanh-phuc', '', '', '', " . NV_CURRENTTIME . ", 0),
(8, 'Học hỏi', 'Hoc-hoi', '', '', '', " . NV_CURRENTTIME . ", 0),
(9, 'Lương tâm', 'Luong-tam', '', '', '', " . NV_CURRENTTIME . ", 0),
(10, 'Những câu nói hay về cuộc sống', 'Nhung-cau-noi-hay-ve-cuoc-song', '', '', '', " . NV_CURRENTTIME . ", 0),
(11, 'Những câu nói hay về tình yêu', 'Nhung-cau-noi-hay-ve-tinh-yeu', '', '', '', " . NV_CURRENTTIME . ", 0),
(12, 'Những trích dẫn hay', 'Nhung-trich-dan-hay', '', '', '', " . NV_CURRENTTIME . ", 0),
(13, 'Phụ nữ', 'Phu-nu', '', '', '', " . NV_CURRENTTIME . ", 0),
(14, 'Sai lầm', 'Sai-lam', '', '', '', " . NV_CURRENTTIME . ", 0),
(15, 'Suy nghĩ', 'Suy-nghi', '', '', '', " . NV_CURRENTTIME . ", 0),
(16, 'Sợ hãi', 'So-hai', '', '', '', " . NV_CURRENTTIME . ", 0),
(17, 'Sự thật', 'Su-that', '', '', '', " . NV_CURRENTTIME . ", 0),
(18, 'Thay đổi', 'Thay-doi', '', '', '', " . NV_CURRENTTIME . ", 0),
(19, 'Thành công', 'Thanh-cong', '', '', '', " . NV_CURRENTTIME . ", 0),
(20, 'Thất bại', 'That-bai', '', '', '', " . NV_CURRENTTIME . ", 0),
(21, 'Thế giới', 'The-gioi', '', '', '', " . NV_CURRENTTIME . ", 0),
(22, 'Tri thức', 'Tri-thuc', '', '', '', " . NV_CURRENTTIME . ", 0),
(23, 'Trái tim', 'Trai-tim', '', '', '', " . NV_CURRENTTIME . ", 0),
(24, 'Trí tuệ', 'Tri-tue', '', '', '', " . NV_CURRENTTIME . ", 0),
(25, 'Tài năng', 'Tai-nang', '', '', '', " . NV_CURRENTTIME . ", 0),
(26, 'Tình bạn', 'Tinh-ban', '', '', '', " . NV_CURRENTTIME . ", 0),
(27, 'Tình yêu', 'Tinh-yeu', '', '', '', " . NV_CURRENTTIME . ", 0),
(28, 'Tự Do', 'Tu-Do', '', '', '', " . NV_CURRENTTIME . ", 0),
(29, 'Yêu thương', 'Yeu-thuong', '', '', '', " . NV_CURRENTTIME . ", 0),
(30, 'Đau khổ', 'Dau-kho', '', '', '', " . NV_CURRENTTIME . ", 0);");

// Bảng danh mục
$db->query("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_cats (id, nums, title, description, addtime, updatetime, weight, status) VALUES
(1, 0, 'Danh ngôn Con người', '', " . NV_CURRENTTIME . ", 0, 1, 1),
(2, 0, 'Danh ngôn Cuộc sống', '', " . NV_CURRENTTIME . ", 0, 2, 1),
(3, 0, 'Danh ngôn Gia đình', '', " . NV_CURRENTTIME . ", 0, 3, 1),
(4, 0, 'Danh ngôn Giàu nghèo', '', " . NV_CURRENTTIME . ", 0, 4, 1),
(5, 0, 'Danh ngôn Giáo dục', '', " . NV_CURRENTTIME . ", 0, 5, 1),
(6, 0, 'Danh ngôn Hạnh phúc – Đau khổ', '', " . NV_CURRENTTIME . ", 0, 6, 1),
(7, 0, 'Danh ngôn Lời nói – Việc làm', '', " . NV_CURRENTTIME . ", 0, 7, 1),
(8, 0, 'Danh ngôn Nhân cách', '', " . NV_CURRENTTIME . ", 0, 8, 1),
(9, 0, 'Danh ngôn Niềm vui – Nỗi buồn', '', " . NV_CURRENTTIME . ", 0, 9, 1),
(10, 0, 'Danh ngôn Phẩm chất', '', " . NV_CURRENTTIME . ", 0, 10, 1),
(11, 0, 'Danh ngôn Sống chết', '', " . NV_CURRENTTIME . ", 0, 11, 1),
(12, 0, 'Danh ngôn Sự nghiệp', '', " . NV_CURRENTTIME . ", 0, 12, 1),
(13, 0, 'Danh ngôn Thành công – Thất bại', '', " . NV_CURRENTTIME . ", 0, 13, 1),
(14, 0, 'Danh ngôn Thời gian', '', " . NV_CURRENTTIME . ", 0, 14, 1),
(15, 0, 'Danh ngôn Tinh thần', '', " . NV_CURRENTTIME . ", 0, 15, 1),
(16, 0, 'Danh ngôn Tiếng anh', '', " . NV_CURRENTTIME . ", 0, 16, 1),
(17, 0, 'Danh ngôn Tiền bạc – Danh vọng', '', " . NV_CURRENTTIME . ", 0, 17, 1),
(18, 0, 'Danh ngôn Trái tim', '', " . NV_CURRENTTIME . ", 0, 18, 1),
(19, 0, 'Danh ngôn Trí tuệ', '', " . NV_CURRENTTIME . ", 0, 19, 1),
(20, 0, 'Danh ngôn Tâm hồn', '', " . NV_CURRENTTIME . ", 0, 20, 1),
(21, 0, 'Danh ngôn Tình bạn', '', " . NV_CURRENTTIME . ", 0, 21, 1),
(22, 0, 'Danh ngôn Tình yêu', '', " . NV_CURRENTTIME . ", 0, 22, 1),
(23, 0, 'Danh ngôn Tính cách', '', " . NV_CURRENTTIME . ", 0, 23, 1),
(24, 0, 'Danh ngôn Tự do', '', " . NV_CURRENTTIME . ", 0, 24, 1),
(25, 0, 'Danh ngôn Văn minh khoa học', '', " . NV_CURRENTTIME . ", 0, 25, 1),
(26, 0, 'Danh ngôn Yêu nước', '', " . NV_CURRENTTIME . ", 0, 26, 1),
(27, 0, 'Danh ngôn Ý chí', '', " . NV_CURRENTTIME . ", 0, 27, 1),
(28, 0, 'Những mảnh ngôn tình', '', " . NV_CURRENTTIME . ", 0, 28, 1),
(29, 0, 'Truyện ngụ ngôn', '', " . NV_CURRENTTIME . ", 0, 29, 1);");
