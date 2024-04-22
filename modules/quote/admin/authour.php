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
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

$error = [];

$data = [
    'name_author' => '',
    'description' => '',
    'bodyhtml' => '',
    'image' => '',
];

$data['id'] = $nv_Request->get_absint('id', 'post,get', 0);

if ($nv_Request->isset_request('save','post, get')) {
    $data['name_author'] = $nv_Request->get_title('name_author', 'post', '');
    $data['alias'] = change_alias($data['name_author']);
    $data['description'] = $nv_Request->get_textarea('description', '', NV_ALLOWED_HTML_TAGS);
    $data['bodyhtml'] = $nv_Request->get_textarea('bodyhtml', '', NV_ALLOWED_HTML_TAGS);
    $data['image'] = $nv_Request->get_title('image', 'post', '');

    // Kiểm tra lỗi nếu có
    if (empty($data['name_author'])) {
        $error[] = $nv_Lang->getModule('error_required_name_author');
    }

    $is_duplicate = false;
    $sth = $db->prepare("SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_authors WHERE name_author = :name_author");
    $sth->bindParam(':name_author', $data['name_author'], PDO::PARAM_STR);
    $sth->execute();
    if ($sth->fetchColumn()) {
        $is_duplicate = true;
    }


    // Nếu không lỗi thì hiện thêm hoặc sửa
    if (empty($error)) {
        try {
            if ($data['id'] > 0) {
                $stmt = $db->prepare("UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_authors SET
                 name_author = :name_author,
                 alias = :alias,
                 description = :description,
                 bodyhtml = :bodyhtml,
                 image = :image,
                 admin_id = :admin_id,
                 updatetime = :updatetime
                 WHERE id = " . $data['id']);
                $stmt->bindValue(':updatetime', NV_CURRENTTIME);
            } else {

                // Kiểm tra trùng rồi mới thêm mới
                if ($is_duplicate) {
                    $error[] = $nv_Lang->getModule('error_duplicate_name_author');
                }

                $stmt = $db->prepare("INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_authors
                (name_author, alias, description, bodyhtml, image, admin_id, addtime) VALUES
                (:name_author, :alias, :description, :bodyhtml, :image, :admin_id, :addtime)");
                $stmt->bindValue(':addtime', NV_CURRENTTIME);
            }
            $stmt->bindParam(':name_author', $data['name_author'], PDO::PARAM_STR);
            $stmt->bindParam(':alias', $data['alias'], PDO::PARAM_STR);
            $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
            $stmt->bindParam(':bodyhtml', $data['bodyhtml'], PDO::PARAM_STR);
            $stmt->bindParam(':image', $data['image'], PDO::PARAM_STR);
            $stmt->bindParam(':admin_id', $admin_info['admin_id'], PDO::PARAM_INT);
            $stmt->execute();

            // sau khi sửa lưu log
            if ($data['id'] > 0 ) {
                nv_insert_logs(NV_LANG_DATA, $module_name, 'log_edit_authour', 'id ' . $data['id'], $admin_info['userid']);
            } else {
                nv_insert_logs(NV_LANG_DATA, $module_name, 'log_add_authour', ' ', $admin_info['userid']);
            }
            $nv_Cache->delMod($module_name);
            nv_redirect_location($base_url);
        } catch (PDOException $e) {
            trigger_error($e->getMessage());
            $error[] = $nv_Lang->getModule('errorsave');
        }
    }
}

if ($nv_Request->isset_request('action', 'post,get') && $nv_Request->isset_request('id', 'post,get')) {
    if ($data['id'] > 0) {
        if ($nv_Request->get_title('checksess', 'post,get', '') === md5($data['id'] . NV_CHECK_SESSION)) {
            $sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_authors WHERE id = :id';
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
            if ($stmt->execute()) {
                nv_insert_logs(NV_LANG_DATA, $module_name, 'log_delete_authour', 'id ' . $data['id'], $admin_info['userid']);
                $nv_Cache->delMod($module_name);
                nv_redirect_location($base_url);
            } else {
                $error[] = $nv_Lang->getModule('error_delete');
            }
        } else {
            $error[] = $nv_Lang->getModule('error_accuracy');
        }
    }
}

if ($data['id'] > 0) {
    $data = $db->query("SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_authors WHERE id = " . $data['id'])->fetch();
    if (empty($data)) {
        nv_redirect_location($base_url);
    }
}

// Hiển thị giao diện
$perpage = 20;
$page = $nv_Request->get_int('page', 'get', 1);

// Đếm tổng số dữ liệu
$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_authors');
$total = $db->query($db->sql())->fetchColumn();

$db->select('*')
    ->order('id DESC')
    ->limit($perpage)
    ->offset(($page - 1) * $perpage);
$sth = $db->prepare($db->sql());
$sth->execute();
$array_list = [];
while ($row = $sth->fetch()) {
    $array_list[] = $row;
}

if ($page > 1 and empty($array_list)) {
    nv_redirect_location($base_url);
}

$xtpl = new XTemplate('authour.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('TOTAL', $total);
$xtpl->assign('NV_UPLOADS_DIR', NV_UPLOADS_DIR);
$xtpl->assign('MODULE_UPLOAD', $module_upload);

if (!empty($array_list)) {
    $i = ($page - 1) * $perpage;
    foreach ($array_list as $row) {
        $row['stt'] = ++$i;
        $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . basename($row['image']);
        $row['url_edit'] = $base_url . '&id=' . $row['id'];
        $row['url_delete'] = $base_url . '&id=' . $row['id'] . '&action=delete&checksess=' . md5($row['id'] . NV_CHECK_SESSION);
        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.loop');
    }

    // Tạo phân trang
    $generate_page = nv_generate_page($base_url, $total, $perpage, $page);
    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    }
}

if (!empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}

$xtpl->assign('DATA', $data);
$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';