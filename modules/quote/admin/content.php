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

// Tạo alias
if ($nv_Request->get_title('changealias','post, get') === NV_CHECK_SESSION) {
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

$array = $error = [];
$is_sumit_form = $is_edit = false;
$currentpath = NV_UPLOADS_DIR . '/' . $module_upload;
$id = $nv_Request->get_int('id', 'post,get', 0);
$formmodal = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=authour';
if (!empty($id)) {
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id = ' . $id;
    $result = $db->query($sql);
    $array = $result->fetch();
    $array['keywords'] = explode(',', $array['keywords']);
    $array['keywords'] = implode(',', array_map('trim', $array['keywords']));

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
        'tagids' => '',
        'content' => '',
        'keywords' => '',
        'name_author' => '',
        'alias' => '',
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
$db->sqlreset()->select('id, name_author')->from(NV_PREFIXLANG . '_' . $module_data . '_authors')->order('id ASC');
$result_authors = $db->query($db->sql());
while ($row = $result_authors->fetch()) {
    $array_authors[$row['id']] = $row['name_author'];
}

// //Phần lấy danh sách tag
$db->sqlreset()->select('id, title')->from(NV_PREFIXLANG . '_' . $module_data . '_tags')->order('id ASC');
$result_tags = $db->query($db->sql());
while ($row = $result_tags->fetch()) {
    $array_tags[$row['id']] = $row['title'];
}

if ($nv_Request->get_title('save', 'post, get','') === NV_CHECK_SESSION) {
    $is_sumit_form = true;
    $array['catids'] = $nv_Request->get_int('catids', 'post', 0);
    $array['author_id'] = $nv_Request->get_int('author_id', 'post', 0);
    $array['tagids'] = $nv_Request->get_typed_array('tagids', 'post', 'int', []);
    $array['tagids'] = implode(',', $array['tagids']);
    $array['content'] = $nv_Request->get_textarea('content', '', NV_ALLOWED_HTML_TAGS);
    $array['keywords'] = $nv_Request->get_typed_array('keywords', 'post', 'string', '');
    $array['keywords'] = implode(',', $array['keywords']);

    if (empty($array['content'])) {
        $error[] = $nv_Lang->getModule('content_error_empty');
    }
    if (empty($error)) {
        if (!$id) {
            $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "
            (catids, author_id, tagids, content, addtime, keywords, status) VALUES
            (:catids, :author_id, :tagids, :content, " . NV_CURRENTTIME . ", :keywords, 1)";
            $data_insert = [];
            $data_insert['catids'] = $array['catids'];
            $data_insert['author_id'] = $array['author_id'];
            $data_insert['tagids'] = $array['tagids'];
            $data_insert['content'] = $array['content'];
            $data_insert['keywords'] = $array['keywords'];

            $new_id = $db->insert_id($sql, 'id', $data_insert);

            if (!empty($new_id)) {
                $nv_Cache->delMod($module_name);
                nv_insert_logs(NV_LANG_DATA, $module_name, 'Add Content', 'ID: ' . $new_id, $admin_info['userid']);

                // Cho phép tùy chỉnh để thêm tiếp hay quay lại
                if ($nv_Request->isset_request('add_again', 'post')) {
                    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
                } elseif ($nv_Request->isset_request('add_return', 'post')) {
                    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
                }

            } else {
                $error[] = $nv_Lang->getModule('errorsave');
            }
        } else {
            $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET
            catids = :catids,
            author_id = :author_id,
            tagids = :tagids,
            content = :content,
            keywords = :keywords,
            updatetime = :updatetime
            WHERE id = " . $id;

            $sth = $db->prepare($sql);
            $sth->bindParam(':catids', $array['catids'], PDO::PARAM_INT);
            $sth->bindParam(':author_id', $array['author_id'], PDO::PARAM_INT);
            $sth->bindParam(':tagids', $array['tagids'], PDO::PARAM_STR);
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

if ($nv_Request->get_title('add_author','post,get') === NV_CHECK_SESSION) {
    $array['name_author'] = $nv_Request->get_title('name_author', 'post', '');
    $array['alias'] = $nv_Request->get_title('alias', 'post', '');

    if (empty($array['name_author'])) {
        $res = [
            'res' => 'error',
            'mess' => $nv_Lang->getModule('error_required_name_author')
        ];
        nv_jsonOutput($res);
    }

    $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_authors (name_author, alias ,addtime) VALUES (:name_author, :alias, :addtime)";
    $sth = $db->prepare($sql);
    $sth->bindParam(':name_author', $array['name_author'], PDO::PARAM_STR);
    $sth->bindParam(':alias', $array['alias'], PDO::PARAM_STR);
    $sth->bindValue(':addtime', NV_CURRENTTIME);
    $sth->execute();
    nv_insert_logs(NV_LANG_DATA, $module_name, 'Add Author_modal', ' ', $admin_info['admin_id']);

    $res = [
        'res' => 'success',
        'mess' => $nv_Lang->getModule('author_add_success')
    ];
    nv_jsonOutput($res);
}

$xtpl = new XTemplate('content.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('CAPTION', $caption);
$xtpl->assign('FORM_ACTION', $form_action);
$xtpl->assign('DATA', $array);
$xtpl->assign('URL_BACK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);

if (!$is_edit) {
    $xtpl->parse('main.btn_add');
} else {
    $xtpl->parse('main.btn_edit');
}

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

if (!empty($array_tags)) {
    // Chuyển chuỗi tagids thành một mảng các ID
    $selected_tag_ids = explode(',', $array['tagids']);

    foreach ($array_tags as $tag_id => $title) {
        $xtpl->assign('TAG', [
            'key' => $tag_id,
            'title' => $title,
            'selected' => in_array($tag_id, $selected_tag_ids) ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('main.tag');
    }
}

if (empty($array['alias'])) {
    $xtpl->parse('main.getalias');
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
