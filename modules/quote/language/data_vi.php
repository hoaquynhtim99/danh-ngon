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

// Danh mục
$db->query("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_cats (nums, title, description, addtime, updatetime, weight, status) VALUES
(0, 'Danh ngôn Việt Nam', 'Danh ngôn Việt Nam', " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 1, 1),
(0, 'Danh ngôn nước ngoài', 'Danh ngôn nước ngoài', " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 2, 1)");

// Tác giả
$db->query("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_authors (name_author, alias, description, bodyhtml, image, admin_id, addtime, updatetime, weight) VALUES
('Võ Nguyên Giáp', 'vo-nguyen-giap', 'Võ Nguyên Giáp', 'Võ Nguyên Giáp', '', 0, " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 1),
('Hồ Chí Minh', 'ho-chi-minh', 'Hồ Chí Minh', 'Hồ Chí Minh', '', 0, " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 2),
('Albert Einstein', 'albert-einstein', 'Albert Einstein', 'Albert Einstein', '', 0, " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 3),
('Isaac Newton', 'isaac-newton', 'Isaac Newton', 'Isaac Newton', '', 0, " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 4),
('Bill Gates', 'bill-gates', 'Bill Gates', 'Bill Gates', '', 0, " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 5),
('Steve Jobs', 'steve-jobs', 'Steve Jobs', 'Steve Jobs', '', 0, " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 6),
('Mark Zuckerberg', 'mark-zuckerberg', 'Mark Zuckerberg', 'Mark Zuckerberg', '', 0, " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 7),
('Warren Buffett', 'warren-buffett', 'Warren Buffett', 'Warren Buffett', '', 0, " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 8)");

// Danh ngôn
$db->query("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . " (catids, author_id, content, addtime, updatetime, keywords, status) VALUES
('1', 1, 'Không có gì là không thể', " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 'Không có gì là không thể', 1),
('1', 2, 'Không có gì là không thể', " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 'Không có gì là không thể', 1),
('1', 3, 'Không có gì là không thể', " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 'Không có gì là không thể', 1),
('1', 4, 'Không có gì là không thể', " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 'Không có gì là không thể', 1),
('1', 5, 'Không có gì là không thể', " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 'Không có gì là không thể', 1),
('1', 6, 'Không có gì là không thể', " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 'Không có gì là không thể', 1),
('1', 7, 'Không có gì là không thể', " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 'Không có gì là không thể', 1),
('1', 8, 'Không có gì là không thể', " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 'Không có gì là không thể', 1)");
