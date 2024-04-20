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
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_tags";

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . " (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  tagids varchar(250) NOT NULL DEFAULT '',
  content mediumtext NOT NULL,
  addtime int(11) unsigned NOT NULL DEFAULT '0',
  updatetime int(11) unsigned NOT NULL DEFAULT '0',
  status tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY tagids (tagids),
  KEY addtime (addtime),
  KEY updatetime (updatetime),
  KEY status (status)
) ENGINE=InnoDB COMMENT 'Danh ngôn'";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_tags (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  title varchar(100) NOT NULL DEFAULT '',
  nums int(11) unsigned NOT NULL DEFAULT '0',
  addtime int(11) unsigned NOT NULL DEFAULT '0',
  updatetime int(11) unsigned NOT NULL DEFAULT '0',
  status tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY title (title),
  KEY addtime (addtime),
  KEY updatetime (updatetime),
  KEY status (status)
) ENGINE=InnoDB COMMENT 'Phân loại danh ngôn'";
