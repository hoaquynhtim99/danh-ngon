<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! nv_function_exists( 'nv_dn_tag' ) )
{
    function nv_dn_tag ()
    {
        global $module_info, $module_name, $module_data, $db;
		
		$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_tags` WHERE `status`=1 ORDER BY `title` ASC";
		$list = nv_db_cache( $sql, '', $module_name );

		if( ! empty( $list ) )
		{
			$a = 1;
			$xtpl = new XTemplate( "block_tags.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/danh-ngon" );
			
			foreach( $list as $row )
			{
				$row['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;tag=" . urlencode( $row['title'] );
				
				$xtpl->assign( 'ROW', $row );

				$xtpl->parse( 'main.loop' );
			}

			$xtpl->parse( 'main' );
			return $xtpl->text( 'main' );
		}
    }
}

if ( defined( 'NV_IS_MOD_DANH_NGON' ) )
{
	$content = nv_dn_tag();
}

?>