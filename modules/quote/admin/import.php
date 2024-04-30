<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 hoặc phiên bản mới hơn
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use NukeViet\Files\Download;

$page_title = $nv_Lang->getModule('import');
// Tạo file mẫu
if ($nv_Request->get_title('template', 'get', '') === NV_CHECK_SESSION) {
    $file = NV_ROOTDIR . '/modules/' . $module_file . '/excel/excel-template.xlsx';
    $file_name = 'excel-template.xlsx';
    $download = new Download($file, dirname($file), $file_name);
    $download->download_file();
    exit();
}

$array = [
    'catids' => 0,
    'author_id' => 0,
    'tagids' => '',
    'truncate_data' => 0,
];

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

// Đọc dữ liệu từ Excel
if ($nv_Request->get_title('save', 'post', '') === NV_CHECK_SESSION) {
    // Kiểm tra thư viện tồn tại
    if (!class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
        trigger_error('No phpspreadsheet lib. Run command "composer require phpoffice/phpspreadsheet" to install phpspreadsheet', 256);
    }

    if ($sys_info['allowed_set_time_limit']) {
        set_time_limit(0);
    }
    if ($sys_info['ini_set_support']) {
        $memoryLimitMB = (integer) ini_get('memory_limit');
        if ($memoryLimitMB < 4096) {
            ini_set('memory_limit', '4096M');
        }
    }

    $error = [];
    $array_read = [];
    $array['catids'] = $nv_Request->get_int('catids', 'post', 0);
    $array['author_id'] = $nv_Request->get_int('author_id', 'post', 0);
    $array['tagids'] = $nv_Request->get_typed_array('tagids', 'post', 'int', []);
    $array['tagids'] = !empty($array['tagids']) ? implode(',', $array['tagids']) : '';
    $array['truncate_data'] = (int) $nv_Request->get_bool('truncate_data', 'post', false);

    if (isset($_FILES['import_file']) and is_uploaded_file($_FILES['import_file']['tmp_name'])) {
        $upload = new NukeViet\Files\Upload(['documents'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], $global_config['nv_max_size'], NV_MAX_WIDTH, NV_MAX_HEIGHT);
        $upload->setLanguage(\NukeViet\Core\Language::$lang_global);
        $upload_info = $upload->save_file($_FILES['import_file'], NV_ROOTDIR . '/' . NV_TEMP_DIR, false);

        @unlink($_FILES['import_file']['tmp_name']);
        if (!empty($upload_info['error'])) {
            $error[] = $upload_info['error'];
        }
    } else {
        $error[] = $nv_Lang->getModule('excel_error_nofile');
    }

    if (empty($error)) {
        try {
            $spreadsheet = IOFactory::load($upload_info['name']);
            $sheet = $spreadsheet->getActiveSheet();
        } catch (Exception $e) {
            $error[] = $nv_Lang->getModule('import_error_readexcel');
        }
    }

    if (empty($error)) {
        $highest_row = $sheet->getHighestDataRow();
        $start_row = 2;

        for ($read_row = $start_row; $read_row <= $highest_row; ++$read_row) {
            $item = [];

            $item['stt'] = trim($sheet->getCell('A' . $read_row)->getCalculatedValue());
            $item['content'] = trim($sheet->getCell('B' . $read_row)->getCalculatedValue());
            $item['keywords'] = trim($sheet->getCell('C' . $read_row)->getCalculatedValue() ?? '');
            $item['catids'] = $array['catids'];
            $item['author_id'] = $array['author_id'];
            $item['tagids'] = $array['tagids'];

            $array_read[] = $item;
        }
        if (empty($error)) {
            if ($array['truncate_data']) {
                $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data);
            }
        }

        foreach ($array_read as $row) {
            $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . " (catids, author_id, tagids, content, keywords, addtime) VALUES (:catids, :author_id, :tagids, :content, :keywords, :addtime)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':catids', $row['catids'], PDO::PARAM_INT);
            $stmt->bindParam(':author_id', $row['author_id'], PDO::PARAM_INT);
            $stmt->bindParam(':tagids', $row['tagids'], PDO::PARAM_STR);
            $stmt->bindParam(':content', $row['content'], PDO::PARAM_STR);
            $stmt->bindParam(':keywords', $row['keywords'], PDO::PARAM_STR);
            $stmt->bindValue(':addtime', NV_CURRENTTIME, PDO::PARAM_INT);
            $stmt->execute();
        }
        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_import_excel', 'import excel', $admin_info['userid']);
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }
}

$xtpl = new XTemplate('import.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('MODULE_FILE', $module_file);
$xtpl->assign('OP', $op);

$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('LINK_TEMPLATE', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&template=' . NV_CHECK_SESSION);
$xtpl->assign('LINK_EXPORT', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&export=' . NV_CHECK_SESSION);

if (!empty($array_catids)) {
    foreach ($array_catids as $catid => $title) {
        $xtpl->assign('CAT', [
            'key' => $catid,
            'title' => $title,
            'selected' => $array['catids'] == $catid ? ' selected="selected"' : '',
        ]);
        $xtpl->parse('main.cat');
    }
}

if (!empty($array_authors)) {
    foreach ($array_authors as $author_id => $name_author) {
        $xtpl->assign('AUTHOR', [
            'key' => $author_id,
            'name' => $name_author,
            'selected' => $array['author_id'] == $author_id ? ' selected="selected"' : '',
        ]);
        $xtpl->parse('main.author');
    }
}

if (!empty($array_tags)) {
    foreach ($array_tags as $tag_id => $title) {
        $xtpl->assign('TAG', [
            'key' => $tag_id,
            'title' => $title,
            'selected' => in_array($tag_id, explode(',', $array['tagids'])) ? ' selected="selected"' : '',
        ]);
        $xtpl->parse('main.tag');
    }
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
