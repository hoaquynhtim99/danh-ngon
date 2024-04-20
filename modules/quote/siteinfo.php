<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SITEINFO')) {
    die('Stop!!!');
}

$sql = "SELECT COUNT(id) FROM " . NV_PREFIXLANG . "_" . $mod_data;
$number = $db->query($sql)->fetchColumn();

if ($number > 0) {
    $siteinfo[] = [
        'key' => $nv_Lang->getModule('siteinfo_num'),
        'value' => number_format($number, 0, ',', '.')
    ];
}
