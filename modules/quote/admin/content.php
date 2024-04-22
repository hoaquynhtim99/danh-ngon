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
    die('Stop!!!');
}

// Lấy id bài viết cần sửa nếu có
$id = $nv_Request->get_int('id', 'get', 0);

$error = [];

if (!empty($id)) {
    // Kiểm tra bài viết sửa
    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id = " . $id;
    $result = $db->query($sql);
    $array = $result->fetch();

    if (empty($array)) {
        nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content'));
    }

    // Chuyển các dạng text trong DB thành dạng quản lý
    $array['catids'] = empty($array['catids']) ? [] : array_unique(array_filter(array_map('intval', explode(',', $array['catids']))));

    $page_title = $nv_Lang->getModule('main_edit');
    $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $id;
} else {
    $array = [
        'catid' => 0,
        'catids' => [],
        'title' => '',
        'alias' => '',
        'description' => '',
        'bodyhtml' => '',
        'image' => '',
        'keywords' => '',
        'status' => 1
    ];

    $page_title = $nv_Lang->getModule('main_add');
    $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
}

if ($nv_Request->get_title('save', 'post', '') === NV_CHECK_SESSION) {
    // Lấy dữ liệu
    $array['title'] = nv_substr($nv_Request->get_title('title', 'post', ''), 0, 190);
    $array['alias'] = nv_substr($nv_Request->get_title('alias', 'post', ''), 0, 190);
    $array['catid'] = $nv_Request->get_absint('catid', 'post', 0);
    $array['catids'] = $nv_Request->get_typed_array('catids', 'post', 'int', []);
    $array['description'] = $nv_Request->get_string('description', 'post', '');
    $array['keywords'] = $nv_Request->get_title('keywords', 'post', '');
    $array['image'] = nv_substr($nv_Request->get_title('image', 'post', ''), 0, 255);
    $array['status'] = (int) $nv_Request->get_bool('status', 'post', false);
    $array['bodyhtml'] = $nv_Request->get_editor('bodyhtml', '', NV_ALLOWED_HTML_TAGS);
    $array['bodyhtml'] = nv_editor_nl2br($array['bodyhtml']);

    // Xử lý dữ liệu
    $array['catids'] = array_intersect($array['catids'], array_keys($global_array_cats));
    if (!in_array($array['catid'], $array['catids'])) {
        $array['catid'] = 0;
    }
    if (sizeof($array['catids']) == 1 and empty($array['catid'])) {
        $array['catid'] = array_values($array['catids'])[0];
    }
    if (empty($array['catids'])) {
        $error[] = $nv_Lang->getModule('main_error_catids');
    } elseif (empty($array['catid'])) {
        $error[] = $nv_Lang->getModule('main_error_catid');
    }

    $array['alias'] = empty($array['alias']) ? change_alias($array['title']) : change_alias($array['alias']);
    $array['description'] = preg_replace('/\s[\s]+/u', ' ', nv_nl2br(nv_htmlspecialchars(strip_tags($array['description'])), ' '));

    if (nv_is_file($array['image'], NV_UPLOADS_DIR . '/' . $module_upload)) {
        $array['image'] = substr($array['image'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/'));
        $array['is_thumb'] = 2;
        if (file_exists(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_upload . '/' . $array['image'])) {
            $array['is_thumb'] = 1;
        }
    } elseif (!nv_is_url($array['image'])) {
        $array['image'] = '';
        $array['is_thumb'] = 0;
    } else {
        $array['is_thumb'] = 3;
    }

    // Kiểm tra trùng
    $is_exists = false;
    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE alias=:alias" . ($id ? ' AND id != ' . $id : '');
    $sth = $db->prepare($sql);
    $sth->bindParam(':alias', $array['alias'], PDO::PARAM_STR);
    $sth->execute();
    if ($sth->fetchColumn()) {
        $is_exists = true;
    }

    if (empty($array['title'])) {
        $error[] = $nv_Lang->getModule('main_error_title');
    } elseif ($is_exists) {
        $error[] = $nv_Lang->getModule('main_error_exists');
    }

    if (empty($array['bodyhtml'])) {
        $error[] = $nv_Lang->getModule('main_error_bodyhtml');
    }

    if (empty($error)) {

        if (!$id) {
            $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_rows (
                catid, catids, title, alias, description, bodyhtml, image, is_thumb, keywords,
                admin_id, add_time, status
            ) VALUES (
                " . $array['catid'] . ", " . $db->quote(implode(',', $array['catids'])) . ",
                :title, :alias, :description, :bodyhtml, :image, " . $array['is_thumb'] . ", :keywords,
                " . $admin_info['admin_id'] . ", " . NV_CURRENTTIME . ", " . $array['status'] . "
            )";
        } else {
            $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET
                catid=" . $array['catid'] . ",
                catids=" . $db->quote(implode(',', $array['catids'])) . ",
                title=:title, alias=:alias, description=:description, bodyhtml=:bodyhtml,
                image=:image, is_thumb=" . $array['is_thumb'] . ", keywords=:keywords,
                edit_time=" . NV_CURRENTTIME . ", status=" . $array['status'] . "
            WHERE id = " . $id;
        }

        try {
            $sth = $db->prepare($sql);
            $sth->bindParam(':title', $array['title'], PDO::PARAM_STR);
            $sth->bindParam(':alias', $array['alias'], PDO::PARAM_STR);
            $sth->bindParam(':description', $array['description'], PDO::PARAM_STR, strlen($array['description']));
            $sth->bindParam(':bodyhtml', $array['bodyhtml'], PDO::PARAM_STR, strlen($array['bodyhtml']));
            $sth->bindParam(':image', $array['image'], PDO::PARAM_STR);
            $sth->bindParam(':keywords', $array['keywords'], PDO::PARAM_STR, strlen($array['keywords']));
            $sth->execute();

            if ($id) {
                nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_EDIT_CONTENT', $id . ': ' . $array['title'], $admin_info['userid']);
            } else {
                nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_ADD_CONTENT', $array['title'], $admin_info['userid']);
            }

            $nv_Cache->delMod($module_name);
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
        } catch (PDOException $e) {
            trigger_error(print_r($e, true));
            $error[] = $nv_Lang->getModule('errorsave');
        }
    }
}

// Dùng đoạn này nếu dùng trình soạn thảo
if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}
$array['bodyhtml'] = nv_htmlspecialchars(nv_editor_br2nl($array['bodyhtml']));
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $array['bodyhtml'] = nv_aleditor('bodyhtml', '100%', '300px', $array['bodyhtml']);
} else {
    $array['bodyhtml'] = '<textarea class="form-control" rows="10" name="bodyhtml">' . $array['bodyhtml'] . '</textarea>';
}

// Xử lý đường dẫn ảnh
if (!empty($array['image']) and nv_is_file(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array['image'], NV_UPLOADS_DIR . '/' . $module_upload)) {
    $array['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array['image'];
    $currentpath = substr(dirname($array['image']), strlen(NV_BASE_SITEURL));
}

// Xử lý status cho checkbox
$array['status'] = empty($array['status']) ? '' : ' checked="checked"';

$xtpl = new XTemplate('content.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('FORM_ACTION', $form_action);
$xtpl->assign('DATA', $array);

// Xuất cái này nếu cấu hình chọn ảnh, file
$xtpl->assign('UPLOAD_CURRENT', $currentpath);
$xtpl->assign('UPLOAD_PATH', NV_UPLOADS_DIR . '/' . $module_upload);

// Tự động lấy alias mỗi khi thêm tiêu đề
if (empty($array['alias'])) {
    $xtpl->parse('main.getalias');
}

// Hiển thị lỗi
if (!empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}

// Xuất danh mục đa cấp
foreach ($global_array_cats as $cat) {
    $cat['space'] = '';
    for ($i = 0; $i < $cat['lev']; $i++) {
        $cat['space'] .= '&nbsp; &nbsp; ';
    }
    $cat['checked'] = in_array($cat['id'], $array['catids']) ? ' checked="checked"' : '';
    if (in_array($cat['id'], $array['catids']) and $cat['id'] == $array['catid']) {
        $cat['checked1'] = ' checked="checked"';
        $cat['class'] = '';
    } else {
        $cat['class'] = ' hidden';
    }
    $xtpl->assign('CAT', $cat);
    $xtpl->parse('main.cat');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
