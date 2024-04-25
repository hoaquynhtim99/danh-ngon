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

