<?php

/**
 * @Project NUKEVIET 3.1
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES.,JSC. All rights reserved
 * @Createdate 07-03-2011 20:15
 */

if ( ! defined( 'NV_IS_MOD_DANH_NGON' ) ) die( 'Stop!!!' );

$page_title = $mod_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$page = 1;
if( isset( $array_op[0] ) and preg_match( "/^page\-([0-9]+)$/i", $array_op[0], $m ) ) $page = ( int ) $m[1];
$per_page = 10;
$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;
$custom_query = "";

$sql_tag = "";
$tag = filter_text_input( 'tag', 'get', '', 1, 100 );
if( ! empty( $tag ) )
{
	$tag_key = mysql_real_escape_string( $tag );
	$sql_tag = " AND (`tags`='" . $tag_key . "' OR `tags` REGEXP '^" . $tag_key . "\\\|' OR `tags` REGEXP '\\\|" . $tag_key . "\\\|' OR `tags` REGEXP '\\\|" . $tag_key . "$')";
	$custom_query .= "tag=" . urlencode( $tag );
	$page_title .= " " . $tag;
	
	$tag_title = ucfirst( $tag );

	$array_mod_title[] = array('catid' => 0, 'title' => $tag_title, 'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;tag=" . urlencode( $tag ));

}

$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `status`=1" . $sql_tag . " ORDER BY `id` DESC LIMIT " . ( ( $page - 1 ) * $per_page ) . "," . $per_page;

$result = $db->sql_query( $sql );
$query = $db->sql_query( "SELECT FOUND_ROWS()" );
list( $all_page ) = $db->sql_fetchrow( $query );

if( ( ( ( $page - 1 ) * $per_page ) >= $all_page and $page > 1 ) or ( ! $all_page and ! empty( $tag ) ) )
{
	Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
	exit();
}
	
if( $page > 1 )
{
	$page_title .= " " . NV_TITLEBAR_DEFIS . "  " . sprintf( $lang_module['page'], $page );
}

$generate_page = nv_page_theme( $lang_module['goto'], $base_url, $all_page, $per_page, $page, true, $custom_query );

$array = array();
while( $row = $db->sql_fetchrow( $result ) )
{
	$array[] = $row;
}

$contents = nv_main_theme( $array, $generate_page );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>