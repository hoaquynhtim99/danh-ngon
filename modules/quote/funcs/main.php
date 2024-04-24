<?php

if (!defined('NV_IS_MOD_DANH_NGON')) {
    exit('Stop!!!');
}

$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name ;
$page_url = $base_url;

$contents = nv_quote_main();

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
