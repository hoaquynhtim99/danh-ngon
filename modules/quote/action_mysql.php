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

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . " (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  catids varchar(250) NOT NULL DEFAULT '',
  content mediumtext NOT NULL,
  addtime int(11) unsigned NOT NULL DEFAULT '0',
  updatetime int(11) unsigned NOT NULL DEFAULT '0',
  status tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY catids (catids),
  KEY addtime (addtime),
  KEY updatetime (updatetime),
  KEY status (status)
) ENGINE=InnoDB COMMENT 'Danh ngôn'";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_authors (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  title varchar(190) NOT NULL COMMENT 'Tiêu đề',
  alias varchar(190) NOT NULL COMMENT 'Liên kết tĩnh không trùng',
  description text NOT NULL COMMENT 'Mô tả ngắn gọn',
  bodyhtml longtext NOT NULL COMMENT 'Chi tiết',
  image varchar(255) NOT NULL DEFAULT '' COMMENT 'Ảnh mô tả',
  is_thumb tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 là không có ảnh, 1 ảnh asset, 2 ảnh upload 3 ảnh remote',
  keywords text NOT NULL COMMENT 'Từ khóa, phân cách bởi dấu phảy',
  admin_id int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'ID người đăng',
  add_time int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian thêm',
  edit_time int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian sửa',
  status tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY catids (catids),
  KEY addtime (addtime),
  KEY updatetime (updatetime),
  KEY status (status)
) ENGINE=InnoDB COMMENT 'Tác giả danh ngôn'";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_cats (
  id smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  nums int(11) unsigned NOT NULL DEFAULT '0',
  title varchar(190) NOT NULL DEFAULT '' COMMENT 'Tiêu đề',
  description text NOT NULL COMMENT 'Note',
  add_time int(11) unsigned NOT NULL DEFAULT '0',
  edit_time int(11) unsigned NOT NULL DEFAULT '0',
  weight smallint(4) unsigned NOT NULL DEFAULT '0',
  status tinyint(4) NOT NULL DEFAULT '1' COMMENT '0: Dừng, 1: Hoạt động',
  PRIMARY KEY (id),
  KEY weight (weight),
  KEY status (status),
  UNIQUE KEY title (title)
) ENGINE=InnoDB COMMENT 'Danh mục'";
