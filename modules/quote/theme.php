<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_DANH_NGON')) {
    die('Stop!!!');
}

function nv_page_theme($title, $base_url, $num_items, $per_page, $on_page, $add_prevnext_text = true, $custom_query = "")
{
    global $lang_global;

    $custom_query = $custom_query ? "&amp;" . $custom_query : "";

    $total_pages = ceil($num_items / $per_page);

    if ($total_pages < 2)
        return '';

    $title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'];
    $page_string = ($on_page == 1) ? "<strong>1</strong> " : "<a title=\"" . $title . " 1\" href=\"" . $base_url . $custom_query . "\">1</a> ";

    if ($total_pages > 10) {
        $init_page_max = ($total_pages > 3) ? 3 : $total_pages;

        for ($i = 2; $i <= $init_page_max; ++$i) {
            $page_string .= ($i == $on_page) ? "<strong>" . $i . "</strong>" : "<a title=\"" . $title . " " . $i . "\" href=\"" . $base_url . "&amp;" . NV_OP_VARIABLE . "=page-" . $i . $custom_query . "\">" . $i . "</a>";

            if ($i < $init_page_max)
                $page_string .= " ";
        }

        if ($total_pages > 3) {
            if ($on_page > 1 && $on_page < $total_pages) {
                $page_string .= ($on_page > 5) ? " ... " : " ";
                $init_page_min = ($on_page > 4) ? $on_page : 5;
                $init_page_max = ($on_page < $total_pages - 4) ? $on_page : $total_pages - 4;

                for ($i = $init_page_min - 1; $i < $init_page_max + 2; ++$i) {
                    $page_string .= ($i == $on_page) ? "<strong>" . $i . "</strong>" : "<a title=\"" . $title . " " . $i . "\" href=\"" . $base_url . "&amp;" . NV_OP_VARIABLE . "=page-" . $i . $custom_query . "\">" . $i . "</a>";

                    if ($i < $init_page_max + 1) {
                        $page_string .= " ";
                    }
                }

                $page_string .= ($on_page < $total_pages - 4) ? " ... " : " ";
            } else {
                $page_string .= " ... ";
            }

            for ($i = $total_pages - 2; $i < $total_pages + 1; ++$i) {
                $page_string .= ($i == $on_page) ? "<strong>" . $i . "</strong>" : "<a title=\"" . $title . " " . $i . "\" href=\"" . $base_url . "&amp;" . NV_OP_VARIABLE . "=page-" . $i . $custom_query . "\">" . $i . "</a>";

                if ($i < $total_pages) {
                    $page_string .= " ";
                }
            }
        }
    } else {
        for ($i = 2; $i < $total_pages + 1; ++$i) {
            $page_string .= ($i == $on_page) ? "<strong>" . $i . "</strong>" : "<a title=\"" . $title . " " . $i . "\" href=\"" . $base_url . "&amp;" . NV_OP_VARIABLE . "=page-" . $i . $custom_query . "\">" . $i . "</a>";

            if ($i < $total_pages) {
                $page_string .= " ";
            }
        }
    }

    if ($add_prevnext_text) {
        if ($on_page > 1) {
            $page_string = "&nbsp;&nbsp;<span><a title=\"" . $title . " " . ($on_page - 1) . "\" href=\"" . $base_url . "&amp;" . NV_OP_VARIABLE . "=page-" . ($on_page - 1) . $custom_query . "\">" . $lang_global['pageprev'] . "</a></span>&nbsp;&nbsp;" . $page_string;
        }

        if ($on_page < $total_pages) {
            $page_string .= "&nbsp;&nbsp;<span><a title=\"" . $title . " " . ($on_page + 1) . "\"  href=\"" . $base_url . "&amp;" . NV_OP_VARIABLE . "=page-" . ($on_page + 1) . $custom_query . "\">" . $lang_global['pagenext'] . "</a></span>";
        }
    }

    return $page_string;
}

function nv_main_theme($array, $generate_page)
{
    global $global_config, $lang_global, $lang_module, $module_name, $module_file, $module_info, $my_head;

    $xtpl = new XTemplate("main.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    foreach ($array as $row) {
        $xtpl->assign('ROW', $row);

        if (!empty($row['tags'])) {
            $row['tags'] = explode("|", $row['tags']);

            foreach ($row['tags'] as $tags) {
                $xtpl->assign('TAGS', $tags);
                $xtpl->assign('LINK', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;tag=" . urlencode($tags));
                $xtpl->parse('main.row.tags.loop');
            }

            $xtpl->parse('main.row.tags');
        }

        $xtpl->parse('main.row');
    }

    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

function nv_quote_main()
{
    global $global_config, $lang_global, $lang_module, $module_name, $module_file, $module_info, $my_head;

    $xtpl = new XTemplate("main.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);



    $xtpl->parse('main');
    return $xtpl->text('main');
}
