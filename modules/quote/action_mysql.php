<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_MODULES')) {
    die('Stop!!!');
}

$sql_drop_module = [];

$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data;

$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_cats";

$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_authors";

$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_tags";

$sql_create_module = $sql_drop_module;

// # 1. Bảng quản lý danh ngôn
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . " (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  catids mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'ID Danh mục',
  author_id mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'ID tác giả',
  tagids varchar(255) NOT NULL DEFAULT '0' COMMENT 'ID tag có thể 1 danh ngôn nhiêu tag tag1,tag2,...',
  content mediumtext NOT NULL COMMENT 'Nội dung',
  addtime int(11) unsigned NOT NULL DEFAULT '0',
  updatetime int(11) unsigned NOT NULL DEFAULT '0',
  keywords varchar(250) NOT NULL DEFAULT '' COMMENT 'Từ khóa',
  status tinyint(4) NOT NULL DEFAULT '1' COMMENT '0: Dừng, 1 Hoạt động',
  PRIMARY KEY (id),
  KEY catids (catids),
  KEY author_id (author_id),
  KEY tagids (tagids),
  KEY addtime (addtime),
  KEY updatetime (updatetime),
  KEY status (status)
) ENGINE=InnoDB COMMENT 'Danh ngôn'";

// # 2. Bảng quản lý tác giả
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_authors (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  name_author varchar(190) NOT NULL DEFAULT '' COMMENT 'Tên tác giả',
  alias varchar(190) NOT NULL COMMENT 'Liên kết tĩnh không trùng',
  description text  COMMENT 'Mô tả ngắn gọn',
  bodyhtml longtext  COMMENT 'Chi tiết',
  image varchar(255) NOT NULL DEFAULT '' COMMENT 'Ảnh mô tả',
  is_thumb tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 là không có ảnh, 1 là có ảnh',
  admin_id int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'ID người đăng',
  addtime int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian thêm',
  updatetime int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian sửa',
  PRIMARY KEY (id),
  KEY addtime (addtime),
  KEY updatetime (updatetime),
  UNIQUE KEY alias (alias)
) ENGINE=InnoDB COMMENT 'Tác giả danh ngôn'";

// # 3. Bảng quản lý danh mục
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_cats (
  id smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  nums int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượng',
  title varchar(190) NOT NULL DEFAULT '' COMMENT 'Tiêu đề',
  description text COMMENT 'Note',
  addtime int(11) unsigned NOT NULL DEFAULT '0',
  updatetime int(11) unsigned NOT NULL DEFAULT '0',
  weight smallint(4) unsigned NOT NULL DEFAULT '0',
  status tinyint(4) NOT NULL DEFAULT '1' COMMENT '0: Dừng, 1: Hoạt động',
  PRIMARY KEY (id),
  KEY addtime (addtime),
  KEY updatetime (updatetime),
  KEY weight (weight),
  KEY status (status),
  UNIQUE KEY title (title)
) ENGINE=InnoDB COMMENT 'Danh mục'";

// Quản lý tags
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_tags (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  title varchar(250) NOT NULL DEFAULT '' COMMENT 'Tên tag',
  alias varchar(250) NOT NULL DEFAULT '' COMMENT 'Liên kết tĩnh',
  description text  COMMENT 'Mô tả',
  keywords text  COMMENT 'Từ khóa',
  image varchar(255) DEFAULT '' COMMENT 'Ảnh mô tả',
  addtime int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian thêm',
  updatetime int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian sửa',
  PRIMARY KEY (id),
  KEY addtime (addtime),
  KEY updatetime (updatetime),
  UNIQUE KEY alias (alias)
) ENGINE=InnoDB COMMENT 'Tags'";
