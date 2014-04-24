<?php

/**
 * @Project NUKEVIET 3.1
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES.,JSC. All rights reserved
 * @Createdate 07-03-2011 20:15
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$submenu['content'] = $lang_module['content'];
$submenu['tags'] = $lang_module['tags'];

$allow_func = array( 'main', 'content', 'tags' );

define( 'NV_IS_DANH_NGON_ADMIN', true );

?>