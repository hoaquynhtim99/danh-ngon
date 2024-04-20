<?php

/**
 * @Project NUKEVIET 3.1
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES.,JSC. All rights reserved
 * @Createdate 24-06-2011 10:35
 */

if ( ! defined( 'NV_IS_DANH_NGON_ADMIN' ) ) die( 'Stop!!!' );

// Xoa tags
if ( $nv_Request->isset_request( 'del', 'post' ) )
{
    if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
    
    $id = filter_text_input( 'id', 'post', '' );
    
    if ( empty( $id ) )
    {
        die( "NO" );
    }
    
    $sql = "SELECT `title` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_tags` WHERE `title`=" . $db->dbescape( $id );
    $result = $db->sql_query( $sql );
    list( $title ) = $db->sql_fetchrow( $result );
    
    if ( empty( $title ) )
    {
        die( "NO" );
    }
	
	// Lay tat ca cac danh ngon nay
	$key = mysql_real_escape_string( $id );
	$sql = "SELECT `id`, `tags` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `tags`='" . $key . "' OR `tags` REGEXP '^" . $key . "\\\|' OR `tags` REGEXP '\\\|" . $key . "\\\|' OR `tags` REGEXP '\\\|" . $key . "$'";
	$result = $db->sql_query( $sql );
	
	// Cap nhat lai tags
	while( $row = $db->sql_fetchrow( $result ) )
	{
		$row['tags'] = explode( "|", $row['tags'] );
		foreach( $row['tags'] as $key => $val )
		{
			if( $val == $id )
			{
				unset( $row['tags'][$key] );
			}
		}
		$row['tags'] = empty( $row['tags'] ) ? "" : implode( "|", $row['tags'] );
		
		$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `tags`=" . $db->dbescape( $row['tags'] ) . " WHERE `id`=" . $row['id'] );
	}
	
	// Xoa tags
    $sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_tags` WHERE `title`=" . $db->dbescape( $id );
    $db->sql_query( $sql );

    nv_del_moduleCache( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['tags_delete'] , $id, $admin_info['userid'] );
    
    die( "OK" );
}

// Page title collum
$page_title = $lang_module['tags'];

// List levels
$array = array();
$sql = "SELECT `title`, `nums`, `status` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_tags` ORDER BY `title` ASC";
$result = $db->sql_query( $sql );
$num = $db->sql_numrows( $result );

$i = 1;
while ( list ( $title, $nums, $status ) = $db->sql_fetchrow( $result ) )
{
	$array[] = array(
		"id" => urlencode( $title ),
		"title" => $title,
		"nums" => $nums,
		"status" => ( $status ) ? " checked=\"checked\"" : "",  //
		"url_edit" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;levelid=" . urlencode( $title ) . "#addeditarea",  //
		"class" => ( $i % 2 == 0 ) ? " class=\"second\"" : ""  //
	);
	$i ++;
}

// Add - Edit standard
$levelid = filter_text_input( 'levelid', 'get', '' );
$error = "";

if ( $levelid )
{
	$sql = "SELECT `title` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_tags` WHERE `title`=" . $db->dbescape( $levelid );
	$result = $db->sql_query( $sql );
	$check_ok = $db->sql_numrows( $result );
	
	if ( $check_ok != 1 )
	{
		nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
	}
	
	list ( $title ) = $db->sql_fetchrow( $result );
	$level_data_old = $level_data = array(
		"title" => $title,  //
	);
	
	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;levelid=" . urlencode( $levelid );
	$table_caption = $lang_module['tags_edit'];
}
else
{
	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
	$table_caption = $lang_module['tags_add'];
	
	$level_data = array(
		"title" => "",  //
	);
}

if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
    $level_data['title'] = nv_strtolower( filter_text_input( 'title', 'post', '', 1, 255 ) );
     
	if ( empty ( $level_data['title'] ) )
	{
		$error = $lang_module['error_title'];
	}
	else
	{
		if ( empty ( $levelid ) )
		{
			$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_tags` WHERE `title`=" .  $db->dbescape( $level_data['title'] );
			$result = $db->sql_query( $sql );
			list ( $check_exist ) = $db->sql_fetchrow( $result );
			
			if ( $check_exist )
			{
				$error = $lang_module['tags_error_exist'];
			}
			else
			{				
				$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_tags` VALUES ( " . $db->dbescape( $level_data['title'] ) . ", 0, 1 )";
				
				if ( $db->sql_query( $sql ) )
				{
					$db->sql_freeresult();
					nv_del_moduleCache( $module_name );
					nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['tags_add'], $level_data['title'], $admin_info['userid'] );
					Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
					exit();
				}
				else
				{
					$error = $lang_module['error_save'];
				}
			}
		}
		else
		{
			$check_exist = false;
			if( $level_data['title'] != $levelid )
			{
				$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_tags` WHERE `title`=" .  $db->dbescape( $level_data['title'] );
				$result = $db->sql_query( $sql );
				list ( $check_exist ) = $db->sql_fetchrow( $result );
			}
			
			if ( $check_exist )
			{
				$error = $lang_module['tags_error_exist'];
			}
			else
			{
				$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_tags` SET `title`= " . $db->dbescape( $level_data['title'] ) . " WHERE `title`=" . $db->dbescape( $levelid );	
				
				if ( $db->sql_query( $sql ) )
				{
					$db->sql_freeresult();
					nv_del_moduleCache( $module_name );
					nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['tags_edit'], $level_data_old['title'] . "&nbsp;=&gt;&nbsp;" . $level_data['title'], $admin_info['userid'] );
					Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
					exit();
				}
				else
				{
					$error = $lang_module['error_update'];
				}
			}
		}
	}
}

$xtpl = new XTemplate( "tags.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'TABLE_CAPTION', $table_caption );
$xtpl->assign( 'FORM_ACTION', $form_action );
$xtpl->assign( 'DATA', $level_data );

if ( ! empty ( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

foreach ( $array as $row )
{
	$xtpl->assign( 'ROW', $row );

	$xtpl->parse( 'main.row' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>