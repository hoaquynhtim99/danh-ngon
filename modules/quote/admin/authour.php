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

$page_title = $nv_Lang->getModule('authour_admin');
$per_page = 5;
$page = $nv_Request->get_absint('page', 'get', 1);
$array = $error = [];
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
$is_submit_form = $is_edit = false;
$currentpath = NV_UPLOADS_DIR . '/' . $module_upload;
$id = $nv_Request->get_absint('id', 'get', 0);

if ($nv_Request->get_title('changealias', 'post', '') === NV_CHECK_SESSION) {
    $name_author = $nv_Request->get_title('name_author', 'post', '');
    $id = $nv_Request->get_absint('id', 'post', 0);

    $alias = strtolower(change_alias($name_author));

    $stmt = $db->prepare("SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_authors WHERE id !=" . $id . " AND alias = :alias");
    $stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->fetchColumn()) {
        $weight = $db->query("SELECT MAX(id) FROM " . NV_PREFIXLANG . "_" . $module_data . "_authors")->fetchColumn();
        $weight = intval($weight) + 1;
        $alias = $alias . '-' . $weight;
    }

    include NV_ROOTDIR . '/includes/header.php';
    echo $alias;
    include NV_ROOTDIR . '/includes/footer.php';
}

// Thay đổi weight
if ($nv_Request->get_title('changeweight', 'post', '') === NV_CHECK_SESSION) {
    $id = $nv_Request->get_absint('id', 'post', 0);
    $new_weight = $nv_Request->get_absint('new_weight', 'post', 0);

    // Kiểm tra tồn tại
    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_authors WHERE id=" . $id;
    $array = $db->query($sql)->fetch();
    if (empty($array)) {
        nv_htmlOutput('NO_' . $id);
    }
    if (empty($new_weight)) {
        nv_htmlOutput('NO_' . $id);
    }

    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_authors WHERE id!=" . $id . "  ORDER BY weight ASC";
    $result = $db->query($sql);

    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        if ($weight == $new_weight) {
            ++$weight;
        }
        $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_authors SET weight=" . $weight . " WHERE id=" . $row['id'];
        $db->query($sql);
    }

    $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_authors SET weight=" . $new_weight . " WHERE id=" . $id;
    $db->query($sql);


    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_CHANGE_WEIGHT_AUTHOUR', json_encode($array), $admin_info['admin_id']);
    $nv_Cache->delMod($module_name);
    nv_htmlOutput('OK_' . $id);
}

if ($nv_Request->get_title('delete', 'post', '') === NV_CHECK_SESSION) {
    $id = $nv_Request->get_absint('id', 'post', 0);

    // Kiểm tra tồn tại
    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_authors WHERE id=" . $id;
    $array = $db->query($sql)->fetch();
    if (empty($array)) {
        nv_htmlOutput('NO_' . $id);
    }

    // Lấy hết ID chủ đề con và ID chính nó

    $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_authors WHERE id = " . $id;
    $db->query($sql);



    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_DELETE_AUTHOUR', json_encode($array), $admin_info['admin_id']);
    $nv_Cache->delMod($module_name);

    nv_htmlOutput("OK");
}

if (!empty($id)) {
    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_authors WHERE id = " . $id;
    $result = $db->query($sql);
    $array = $result->fetch();

    if (empty($array)) {
        nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content'));
    }

    $is_edit = true;
    $caption = $nv_Lang->getModule('edit_authour');
    $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $id;
} else {
    $array = [
        'id' => 0,
        'name_author' => '',
        'alias' => '',
        'description' => '',
        'bodyhtml' => '',
        'image' => '',
    ];

    $caption = $nv_Lang->getModule('add_authour');
    $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
}

// Xóa nhiều
if ($nv_Request->get_title('delete_all', 'post', '') === NV_CHECK_SESSION) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $listid = $nv_Request->get_title('listid', 'post', '');
    $listid = $listid . ',' . $id;
    $listid = array_filter(array_unique(array_map('intval', explode(',', $listid))));

    foreach ($listid as $id) {
        // Kiểm tra tồn tại
        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_authors WHERE id=" . $id;
        $array = $db->query($sql)->fetch();
        if (!empty($array)) {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_DELETE_CONTENT', json_encode($array), $admin_info['admin_id']);

            // Xóa
            $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_authors WHERE id =" . $id;
            $db->query($sql);
        }
    }

    $nv_Cache->delMod($module_name);
    nv_htmlOutput("OK");
}


if ($nv_Request->get_title('save', 'post', '') === NV_CHECK_SESSION) {
    $is_submit_form = true;
    $array['name_author'] = $nv_Request->get_title('name_author', 'post', '');
    $array['alias'] = nv_substr($nv_Request->get_title('alias', 'post', ''), 0, 190);
    $array['description'] = $nv_Request->get_string('description', 'post', '');
    $array['bodyhtml'] = $nv_Request->get_editor('bodyhtml', '', NV_ALLOWED_HTML_TAGS);
    $array['image'] = nv_substr($nv_Request->get_title('image', 'post', ''), 0, 255);

    $array['alias'] = empty($array['alias']) ? change_alias($array['name_author']) : change_alias($array['alias']);
    $array['description'] = nv_nl2br(nv_htmlspecialchars(strip_tags($array['description'])), '<br />');

    if (nv_is_file($array['image'], NV_UPLOADS_DIR . '/' . $module_upload)) {
        $array['image'] = substr($array['image'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/'));
    } else {
        $array['image'] = '';
    }

    $is_exists = false;
    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_authors WHERE alias = :alias" . ($id ? ' AND id != ' . $id : '');
    $sth = $db->prepare($sql);
    $sth->bindParam(':alias', $array['alias'], PDO::PARAM_STR);
    $sth->execute();
    if ($sth->fetchColumn()) {
        $is_exists = true;
    }

    if (empty($array['name_author'])) {
        $error[] = $nv_Lang->getModule('error_required_name_author');
    } elseif ($is_exists) {
        $error[] = $nv_Lang->getModule('error_duplicate_name_author');
    }

    if (empty($error)) {
        if (!$id) {
            $sql = "SELECT MAX(weight) weight FROM " . NV_PREFIXLANG . "_" . $module_data . "_authors";
            $weight = intval($db->query($sql)->fetchColumn()) + 1;

            $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_authors 
            (name_author, alias, description, bodyhtml, image, admin_id, addtime, updatetime, weight) 
            VALUES 
            (:name_author, :alias, :description, :bodyhtml, :image, " . $admin_info['admin_id'] . ", " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", " . $weight . ")";

            $data_insert = [];
            $data_insert['name_author'] = $array['name_author'];
            $data_insert['alias'] = $array['alias'];
            $data_insert['description'] = $array['description'];
            $data_insert['bodyhtml'] = $array['bodyhtml'];
            $data_insert['image'] = $array['image'];

            $new_id = $db->insert_id($sql, 'id', $data_insert);

            if (!empty($new_id)) {
                $nv_Cache->delMod($module_name);
                nv_insert_logs(NV_LANG_DATA, $module_name, 'log_add_author', 'id ' . $new_id, $admin_info['userid']);
                nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=authour');
            } else {
                $error[] = $nv_Lang->getModule('errorsave');
            }
        } else {
            $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_authors SET
            name_author = :name_author,
            alias = :alias,
            description = :description,
            bodyhtml = :bodyhtml,
            image = :image,
            updatetime = " . NV_CURRENTTIME . "
            WHERE id = " . $id;
            $sth = $db->prepare($sql);
            $sth->bindParam(':name_author', $array['name_author'], PDO::PARAM_STR);
            $sth->bindParam(':alias', $array['alias'], PDO::PARAM_STR);
            $sth->bindParam(':description', $array['description'], PDO::PARAM_STR);
            $sth->bindParam(':bodyhtml', $array['bodyhtml'], PDO::PARAM_STR);
            $sth->bindParam(':image', $array['image'], PDO::PARAM_STR);
            $sth->execute();

            $nv_Cache->delMod($module_name);
            nv_insert_logs(NV_LANG_DATA, $module_name, 'log_edit_author', 'id ' . $id, $admin_info['userid']);
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=authour');
        }
    }
}

if (!empty($array['image']) and nv_is_file(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array['image'], NV_UPLOADS_DIR . '/' . $module_upload)) {
    $array['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array['image'];
    $currentpath = substr(dirname($array['image']), strlen(NV_BASE_SITEURL));
}

$db->sqlreset()->select('COUNT(*)')->from(NV_PREFIXLANG . '_' . $module_data . '_authors');
$total = $db->query($db->sql())->fetchColumn();

$db->select('*')->order('id ASC')->limit($per_page)->offset(($page - 1) * $per_page);
$result = $db->query($db->sql());
while ($row = $result->fetch()) {
    $array_cats[] = $row;
}

$xtpl = new XTemplate('authour.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('CAPTION', $caption);
$xtpl->assign('FORM_ACTION', $form_action);
$xtpl->assign('DATA', $array);

$xtpl->assign('UPLOAD_CURRENT', $currentpath);
$xtpl->assign('UPLOAD_PATH', NV_UPLOADS_DIR . '/' . $module_upload);

if (!empty($array_cats)) {
    foreach ($array_cats as $row) {
        for ($i = 1; $i <= $total; ++$i) {
            $xtpl->assign('WEIGHT', [
                'w' => $i,
                'selected' => ($i == $row['weight']) ? ' selected="selected"' : ''
            ]);

            $xtpl->parse('main.loop.weight');
        }
        if (empty($row['image'])) {
            $row['image'] = NV_STATIC_URL . 'themes/default/images/users/no_avatar.png';
        } else {
            $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . basename($row['image']);
        }
        $row['url_edit'] = $base_url . '&id=' . $row['id'];
        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.loop');
    }

    $gerate_page = nv_generate_page($base_url, $total, $per_page, $page);
    if (!empty($gerate_page)) {
        $xtpl->assign('GENERATE_PAGE', $gerate_page);
        $xtpl->parse('main.generate_page');
    }
}


// Hiển thị nút lấy alias
if (empty($array['alias'])) {
    $xtpl->parse('main.getalias');
}

// Xuất thông báo lỗi
if (!empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}

// Hiển thị nút thêm
if (!$is_edit) {
    $xtpl->parse('main.add_btn');
}

// Cuộn đến form
if ($is_submit_form or $is_edit) {
    $xtpl->parse('main.scroll');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
