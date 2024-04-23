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

$array = $error = [];
$is_sumit_form = $is_edit = false;
$currentpath = NV_UPLOADS_DIR . '/' . $module_upload;
$id = $nv_Request->get_int('id', 'post,get', 0);

if (!empty($id)) {
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id = ' . $id;
    $result = $db->query($sql);
    $array = $result->fetch();

    if (empty($array)) {
        nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content'));
    }
    $is_edit = true;
    $page_title = $nv_Lang->getModule('content_edit');
    $caption = $nv_Lang->getModule('content_edit');
    $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $id;
} else {
    $array = [
        'id' => 0,
        'catids' => 0,
        'author_id' => 0,
        'content' => '',
        'keywords' => ''
    ];

    $caption = $nv_Lang->getModule('content');
    $page_title = $nv_Lang->getModule('content');
    $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
}

// Lấy dữ liệu bảng danh mục
$db->sqlreset()->select('id, title')->from(NV_PREFIXLANG . '_' . $module_data . '_cats')->order('weight ASC');
$result_cats = $db->query($db->sql());
while ($row = $result_cats->fetch()) {
    $array_catids[$row['id']] = $row['title'];
}

// Lấy dữ liệu bảng tác giả
$db->sqlreset()->select('id, name_author')->from(NV_PREFIXLANG . '_' . $module_data . '_authors')->order('weight ASC');
$result_authors = $db->query($db->sql());
while ($row = $result_authors->fetch()) {
    $array_authors[$row['id']] = $row['name_author'];
}

if ($nv_Request->get_title('save', 'post, get','') === NV_CHECK_SESSION) {
    $is_sumit_form = true;
    $array['catids'] = $nv_Request->get_int('catids', 'post', 0);
    $array['author_id'] = $nv_Request->get_int('author_id', 'post', 0);
    $array['content'] = $nv_Request->get_textarea('content', '', NV_ALLOWED_HTML_TAGS);
    $array['keywords'] = $nv_Request->get_title('keywords', 'post', '');

    if (empty($array['content'])) {
        $error[] = $nv_Lang->getModule('content_error_empty');
    }

    if ($array['catids'] <= 0) {
        $error[] = $nv_Lang->getModule('error_required_catid');
    }

    if ($array['author_id'] <= 0) {
        $error[] = $nv_Lang->getModule('error_required_name_author');
    }


    if (empty($error)) {
        if (!$id) {
            $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "
            (catids, author_id, content, addtime, keywords, status) VALUES
            (:catids, :author_id, :content, " . NV_CURRENTTIME . ", :keywords, 1)";
            $data_insert = [];
            $data_insert['catids'] = $array['catids'];
            $data_insert['author_id'] = $array['author_id'];
            $data_insert['content'] = $array['content'];
            $data_insert['keywords'] = $array['keywords'];

            $new_id = $db->insert_id($sql, 'id', $data_insert);

            if (!empty($new_id)) {
                $nv_Cache->delMod($module_name);
                nv_insert_logs(NV_LANG_DATA, $module_name, 'Add Content', 'ID: ' . $new_id, $admin_info['userid']);
                nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
            } else {
                $error[] = $nv_Lang->getModule('errorsave');
            }
        } else {
            $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET
            catids = :catids,
            author_id = :author_id,
            content = :content,
            keywords = :keywords,
            updatetime = :updatetime
            WHERE id = " . $id;

            $sth = $db->prepare($sql);
            $sth->bindParam(':catids', $array['catids'], PDO::PARAM_INT);
            $sth->bindParam(':author_id', $array['author_id'], PDO::PARAM_INT);
            $sth->bindParam(':content', $array['content'], PDO::PARAM_STR);
            $sth->bindParam(':keywords', $array['keywords'], PDO::PARAM_STR);
            $sth->bindValue(':updatetime', NV_CURRENTTIME);
            $exe = $sth->execute();

            if ($exe) {
                $nv_Cache->delMod($module_name);
                nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit Content', 'ID: ' . $id, $admin_info['userid']);
                nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
            } else {
                $error[] = $nv_Lang->getModule('errorsave');
            }
        }
    }
}


$xtpl = new XTemplate('content.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('CAPTION', $caption);
$xtpl->assign('FORM_ACTION', $form_action);
$xtpl->assign('DATA', $array);

if (!empty($array_catids)) {
    foreach ($array_catids as $catid => $title) {
        $xtpl->assign('CAT', [
            'key' => $catid,
            'title' => $title,
            'selected' => $catid == $array['catids'] ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('main.cat');
    }
}

if (!empty($array_authors)) {
    foreach ($array_authors as $author_id => $name_author) {
        $xtpl->assign('AUTHOR', [
            'key' => $author_id,
            'name' => $name_author,
            'selected' => $author_id == $array['author_id'] ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('main.author');
    }
}

// Hiển thị lỗi
if (!empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
