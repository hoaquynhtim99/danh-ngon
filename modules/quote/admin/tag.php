<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

if ($nv_Request->get_title('changealias', 'post', '') === NV_CHECK_SESSION) {
    $title = $nv_Request->get_title('title', 'post', '');
    $id = $nv_Request->get_absint('id', 'post', 0);

    $alias = strtolower(change_alias($title));

    $stmt = $db->prepare("SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_tags WHERE id !=" . $id . " AND alias = :alias");
    $stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->fetchColumn()) {
        $weight = $db->query("SELECT MAX(id) FROM " . NV_PREFIXLANG . "_" . $module_data . "_tags")->fetchColumn();
        $weight = intval($weight) + 1;
        $alias = $alias . '-' . $weight;
    }

    include NV_ROOTDIR . '/includes/header.php';
    echo $alias;
    include NV_ROOTDIR . '/includes/footer.php';
}

if ($nv_Request->get_title('delete', 'post', '') === NV_CHECK_SESSION) {
    $id = $nv_Request->get_absint('id', 'post', 0);

    // Kiểm tra tồn tại
    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_tags WHERE id=" . $id;
    $array = $db->query($sql)->fetch();
    if (empty($array)) {
        nv_htmlOutput('NO_' . $id);
    }
    // Lấy hết ID chủ đề con và ID chính nó

    $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_tags WHERE id = " . $id;
    $db->query($sql);

    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_DELETE_TAG', json_encode($array), $admin_info['admin_id']);
    $nv_Cache->delMod($module_name);

    nv_htmlOutput("OK");
}

if ($nv_Request->get_title('delete_all', 'post', '') === NV_CHECK_SESSION) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $listid = $nv_Request->get_title('listid', 'post', '');
    $listid = $listid . ',' . $id;
    $listid = array_filter(array_unique(array_map('intval', explode(',', $listid))));

    foreach ($listid as $id) {
        // Kiểm tra tồn tại
        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_tags WHERE id=" . $id;
        $array = $db->query($sql)->fetch();
        if (!empty($array)) {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_DELETE_CONTENT', json_encode($array), $admin_info['admin_id']);

            // Xóa
            $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_tags WHERE id =" . $id;
            $db->query($sql);
        }
    }
    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_DELETE_AUTHOUR_ALL', json_encode($array), $admin_info['admin_id']);
    $nv_Cache->delMod($module_name);
    nv_htmlOutput("OK");
}

if ($nv_Request->get_title('save_tag','post, get') === NV_CHECK_SESSION) {
    $data['id'] = $nv_Request->get_int('id', 'post', 0);
    $data['title'] = $nv_Request->get_title('title', 'post', '');
    $data['alias'] = $nv_Request->get_title('alias', 'post', '');
    $data['keywords'] = $nv_Request->get_title('keywords', 'post', '');
    $data['description'] = $nv_Request->get_editor('description', '', NV_ALLOWED_HTML_TAGS);
    $data['image'] = $nv_Request->get_title('image', 'post', '');

    if (empty($data['title'])) {
        $res = [
            'res' => 'error',
            'mess' => $nv_Lang->getModule('error_required_title_cats')
        ];
        nv_jsonOutput($res);
    }

    if ($data['id'] > 0) {
        $caption = $nv_Lang->getModule('edit_tag');
        $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags SET title = :title, alias = :alias, description = :description, keywords = :keywords, image = :image, updatetime = :updatetime WHERE id=' . $data['id']);
        $stmt->bindValue(':updatetime', NV_CURRENTTIME);
    } else {
        $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_tags (title, alias, description, keywords, image, addtime) VALUES (:title, :alias, :description, :keywords, :image, :addtime)');
        $stmt->bindValue(':addtime', NV_CURRENTTIME);
    }
    $stmt ->bindParam(':title', $data['title'], PDO::PARAM_STR);
    $stmt ->bindParam(':alias', $data['alias'], PDO::PARAM_STR);
    $stmt ->bindParam(':description', $data['description'], PDO::PARAM_STR);
    $stmt ->bindParam(':keywords', $data['keywords'], PDO::PARAM_STR);
    $stmt ->bindParam(':image', $data['image'], PDO::PARAM_STR);
    $stmt ->execute();
    if ($data['id'] > 0) {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit Tag', 'ID: ' . $data['id'], $admin_info['userid']);
    } else {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Add Tag', ' ', $admin_info['userid']);
    }
    $nv_Cache->delMod($module_name);
    $res = [
        'res' => 'success',
        'mess' => $nv_Lang->getModule('function_tag_success')
    ];
    nv_jsonOutput($res);
}

$array = [];
$per_page = 20;
$page = $nv_Request->get_absint('page', 'get', 1);
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

$page_title = $nv_Lang->getModule('tag_admin');

// Phần tìm kiếm
$array_search = [];
$where = [];
$array_search['q'] = $nv_Request->get_title('q', 'get', '');

$db->sqlreset()->select('COUNT(*)')->from(NV_PREFIXLANG . '_' . $module_data . '_tags');


if (!empty($array_search['q'])) {
    $base_url .= '&q=' . urlencode($array_search['q']);
    $where[] = "title LIKE '%" . $db->dblikeescape($array_search['q']) . "%'";
}

if (!empty($where)) {
    $db->where(implode(' AND ', $where));
}

$total = $db->query($db->sql())->fetchColumn();

$db->select('*')->order('id ASC')
    ->limit($per_page)
    ->offset(($page - 1) * $per_page);
$result = $db->query($db->sql());
$listall = [];
while ($row = $result->fetch()) {
    $listall[] = $row;
}

$xtpl = new XTemplate('tag.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('BASE_URL', $base_url);
$xtpl->assign('UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_upload);
$xtpl->assign('UPLOAD_PATH', NV_UPLOADS_DIR . '/' . $module_upload);
$xtpl->assign('DATA', $array);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_FILE', $module_file);
$xtpl->assign('OP', $op);
$xtpl->assign('SEARCH', $array_search);

if (!empty($listall)) {
    foreach ($listall as $row) {
        $xtpl->assign('ROW', $row);
        if (!empty($row['description'])) {
            $xtpl->parse('main.loop.complete');
        } else {
            $xtpl->parse('main.loop.incomplete');
        }
        $xtpl->parse('main.loop');
    }
}
$generate_page = nv_generate_page($base_url, $total, $per_page, $page);
if (!empty($generate_page)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}

if (empty($array['alias'])) {
    $xtpl->parse('main.getalias');
}


$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
