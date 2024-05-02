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

$allow_func = [
    'main',
    'content',
    'cats',
];

$submenu['cats'] = $nv_Lang->getModule('cats_manager');
$submenu['author'] = $nv_Lang->getModule('author_admin');
$submenu['tag'] = $nv_Lang->getModule('tag_admin');