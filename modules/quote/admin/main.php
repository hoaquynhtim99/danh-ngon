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

$page_title = $nv_Lang->getModule('main');
// Thay đổi hoạt động
if ($nv_Request->get_title('changestatus', 'post', '') === NV_CHECK_SESSION) {
    $id = $nv_Request->get_int('id', 'post', 0);

    // Kiểm tra tồn tại
    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id = " . $id;
    $array = $db->query($sql)->fetch();
    if (empty($array)) {
        nv_htmlOutput('NO_' . $id);
    }

    $status = empty($array['status']) ? 1 : 0;

    $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET status = " . $status . " WHERE id = " . $id;
    $db->query($sql);

    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_CHANGE_STATUS_CONTENT', json_encode($array), $admin_info['admin_id']);
    $nv_Cache->delMod($module_name);

    nv_htmlOutput("OK");
}

// Xóa bỏ 1 hoặc nhiều
if ($nv_Request->get_title('delete', 'post', '') === NV_CHECK_SESSION) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $listid = $nv_Request->get_title('listid', 'post', '');
    $listid = $listid . ',' . $id;
    $listid = array_filter(array_unique(array_map('intval', explode(',', $listid))));

    foreach ($listid as $id) {
        // Kiểm tra tồn tại
        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id = " . $id;
        $array = $db->query($sql)->fetch();
        if (!empty($array)) {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_DELETE_CONTENT', json_encode($array), $admin_info['admin_id']);

            // Xóa
            $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id = " . $id;
            $db->query($sql);
        }
    }

    $nv_Cache->delMod($module_name);
    nv_htmlOutput("OK");
}

$page_title = $nv_Lang->getModule('main');

$per_page = 20;
$error = [];
$page = $nv_Request->get_absint('page', 'get', 1);
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;

$db->sqlreset()
    ->select('*')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_cats');
$result = $db->query($db->sql());
$array_catids = [];
while ($row = $result->fetch()) {
    $array_catids[$row['id']] = $row['title'];
}

$db->sqlreset()
    ->select('*')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_authors');
$result = $db->query($db->sql());
$array_authors = [];
while ($row = $result->fetch()) {
    $array_authors[$row['id']] = $row['name_author'];
}

$db->sqlreset()
    ->select('*')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_tags');
$result = $db->query($db->sql());
$array_tags = [];
while ($row = $result->fetch()) {
    $array_tags[$row['id']] = $row['title'];
}

// Phần tìm kiếm
$array_search = [];
$array_search['q'] = $nv_Request->get_title('q', 'get', '');
$array_search['catids'] = $nv_Request->get_int('catids', 'get', 0);
$array_search['author_id'] = $nv_Request->get_int('author_id', 'get', 0);
$array_search['tagids'] = $nv_Request->get_int('tagids', 'get', 0);
$array_search['from'] = $nv_Request->get_title('f', 'get', '');
$array_search['to'] = $nv_Request->get_title('t', 'get', '');
$array_search['catids'] = $nv_Request->get_int('catids', 'get', 0);

// Xử lý dữ liệu tìm kiếm
if (preg_match('/^([0-9]{1,2})\-([0-9]{1,2})\-([0-9]{4})$/', $array_search['from'], $m)) {
    $array_search['from'] = mktime(0, 0, 0, intval($m[2]), intval($m[1]), intval($m[3]));
} else {
    $array_search['from'] = 0;
}
if (preg_match('/^([0-9]{1,2})\-([0-9]{1,2})\-([0-9]{4})$/', $array_search['to'], $m)) {
    $array_search['to'] = mktime(23, 59, 59, intval($m[2]), intval($m[1]), intval($m[3]));
} else {
    $array_search['to'] = 0;
}

$db->sqlreset()->select('COUNT(*)')->from(NV_PREFIXLANG . '_' . $module_data);

$where = [];
if (!empty($array_search['q'])) {
    $base_url .= '&amp;q=' . urlencode($array_search['q']);
    $dblikekey = $db->dblikeescape($array_search['q']);
    $where[] = "(
        content LIKE '%" . $dblikekey . "%' OR
        keywords LIKE '%" . $dblikekey . "%'
    )";
}

if (!empty($array_search['catids'])) {
    $base_url .= '&amp;catids=' . $array_search['catids'];
    $where[] = "catids=" . $array_search['catids'];
}

if (!empty($array_search['author_id'])) {
    $base_url .= '&amp;author_id=' . $array_search['author_id'];
    $where[] = "author_id=" . $array_search['author_id'];
}

if (!empty($array_search['tagids'])) {
    $base_url .= '&amp;tagids=' . $array_search['tagids'];
    $where[] = "tagids LIKE '%" . $array_search['tagids'] . "%'";
}

if ($array_search['from'] > $array_search['to']) {
    $error[] = $nv_Lang->getModule('error_date');
}

if (!empty($array_search['from'])) {
    $base_url .= '&amp;f=' . nv_date('d-m-Y', $array_search['from']);
    $where[] = "addtime>=" . $array_search['from'];
}
if (!empty($array_search['to'])) {
    $base_url .= '&amp;t=' . nv_date('d-m-Y', $array_search['to']);
    $where[] = "addtime<=" . $array_search['to'];
}

// Phần sắp xếp
$array_order = [];
$array_order['field'] = $nv_Request->get_title('of', 'get', '');
$array_order['value'] = $nv_Request->get_title('ov', 'get', '');
$base_url_order = $base_url;
if ($page > 1) {
    $base_url_order .= '&amp;page=' . $page;
}

// Định nghĩa các field và các value được phép sắp xếp
$order_fields = ['content', 'addtime', 'updatetime'];
$order_values = ['asc', 'desc'];

if (!in_array($array_order['field'], $order_fields)) {
    $array_order['field'] = '';
}
if (!in_array($array_order['value'], $order_values)) {
    $array_order['value'] = '';
}

if (!empty($where)) {
    $db->where(implode(' AND ', $where));
}

$num_items = $db->query($db->sql())->fetchColumn();

if (!empty($array_order['field']) and !empty($array_order['value'])) {
    $order = $array_order['field'] . ' ' . $array_order['value'];
} else {
    $order = 'id DESC';
}
$db->select('*')->order($order)->limit($per_page)->offset(($page - 1) * $per_page);
$result = $db->query($db->sql());
$list = [];
$catIDs = [];
$author_ids = [];
while ($row = $result->fetch()) {
    $list[] = $row;
    $catIDs[] = $row['catids'];
    $author_ids[] = $row['author_id'];
    $tag_ids[] = explode(',', $row['tagids']);
}

if (!empty($catIDs)) {
    $db->sqlreset()->select('id, title')->from(NV_PREFIXLANG . '_' . $module_data . '_cats')->where('id IN (' . implode(',', $catIDs) . ')');
    $result = $db->query($db->sql());
    while ($row = $result->fetch()) {
        $array_catids[$row['id']] = $row['title'];
    }
}
if (!empty($author_ids)) {
    $db->sqlreset()->select('id, name_author')->from(NV_PREFIXLANG . '_' . $module_data . '_authors')->where('id IN (' . implode(',', $author_ids) . ')');
    $result = $db->query($db->sql());
    while ($row = $result->fetch()) {
        $array_authors[$row['id']] = $row['name_author'];
    }
}

if (!empty($tag_ids)) {
    $tag_ids = array_map('intval', array_unique(array_merge(...$tag_ids)));
    $db->sqlreset()->select('id, title')->from(NV_PREFIXLANG . '_' . $module_data . '_tags')->where('id IN (' . implode(',', $tag_ids) . ')');
    $result = $db->query($db->sql());
    while ($row = $result->fetch()) {
        $array_tag[$row['id']] = $row['title'];
    }
}

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_FILE', $module_file);
$xtpl->assign('OP', $op);

$xtpl->assign('LINK_ADD_NEW', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content');
$xtpl->assign('LINK_IMPORT', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=import');

// Chuyển tìm kiếm sang ngày tháng
$array_search['from'] = empty($array_search['from']) ? '' : nv_date('d-m-Y', $array_search['from']);
$array_search['to'] = empty($array_search['to']) ? '' : nv_date('d-m-Y', $array_search['to']);

$xtpl->assign('SEARCH', $array_search);

if (!empty($array_catids)) {
    foreach ($array_catids as $id => $title) {
        $xtpl->assign('CAT', [
            'id' => $id,
            'title' => $title,
            'selected' => $id == $array_search['catids'] ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('main.cat');
    }
}

if (!empty($array_authors)) {
    foreach ($array_authors as $id => $name_author) {
        $xtpl->assign('AUTHOR', [
            'id' => $id,
            'name_author' => $name_author,
            'selected' => $id == $array_search['author_id'] ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('main.author');
    }
}

if (!empty($array_tags)) {
    foreach ($array_tags as $id => $title) {
        $xtpl->assign('TAG', [
            'id' => $id,
            'title' => $title,
            'selected' => $id == $array_search['tagids'] ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('main.tag');
    }
}

// Xuất danh sách
foreach ($list as $row) {
    $row['title'] = nv_clean60($row['content'], 50);
    $row['name_cat'] = isset($array_catids[$row['catids']]) ? $array_catids[$row['catids']] : '';
    $row['name_author'] = isset($array_authors[$row['author_id']]) ? $array_authors[$row['author_id']] : '';
    if (isset($row['tagids'])) {
        $tag_ids = explode(',', $row['tagids']);
        $tag_names = [];
        foreach ($tag_ids as $v) {
            if (isset($array_tag[$v])) {
                $tag_names[] = $array_tag[$v];
            }
        }
        $row['name_tags'] = implode(', ', $tag_names);
    }
    $row['url_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&amp;id=' . $row['id'];
    $row['status_render'] = $row['status'] ? ' checked="checked"' : '';
    $row['addtime'] = nv_date('d/m/Y H:i', $row['addtime']);
    $row['updatetime'] = $row['updatetime'] ? nv_date('d/m/Y H:i', $row['updatetime']) : '';
    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.loop');
}

// Xuất phân trang
$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
if (!empty($generate_page)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}
// Xuất các phần sắp xếp
foreach ($order_fields as $field) {
    $url = $base_url_order;
    if ($array_order['field'] == $field) {
        if (empty($array_order['value'])) {
            $url .= '&amp;of=' . $field . '&amp;ov=asc';
            $icon = '<i class="fa fa-sort" aria-hidden="true"></i>';
        } elseif ($array_order['value'] == 'asc') {
            $url .= '&amp;of=' . $field . '&amp;ov=desc';
            $icon = '<i class="fa fa-sort-asc" aria-hidden="true"></i>';
        } else {
            $icon = '<i class="fa fa-sort-desc" aria-hidden="true"></i>';
        }
    } else {
        $url .= '&amp;of=' . $field . '&amp;ov=asc';
        $icon = '<i class="fa fa-sort" aria-hidden="true"></i>';
    }

    $xtpl->assign(strtoupper('URL_ORDER_' . $field), $url);
    $xtpl->assign(strtoupper('ICON_ORDER_' . $field), $icon);
}

if (!empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
