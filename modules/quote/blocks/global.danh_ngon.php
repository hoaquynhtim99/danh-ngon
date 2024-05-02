<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_danhngon')) {

    function nv_danhngon($block_config)
    {
        global $site_mods, $module_info, $module_data, $db, $nv_Cache, $global_config;

        $module = $block_config['module'];
        $module_data = $site_mods[$module]['module_data'];
        $list = [];
        $author = [];

        $db->sqlreset()
            ->select('author_id, content')
            ->from(NV_PREFIXLANG . '_' . $module_data)
            ->where('status = 1')
            ->order('RAND()')
            ->limit(1);
        $sth = $db->prepare($db->sql());
        $sth->execute();
        $list = $sth->fetch();

        if (!empty($list['author_id'])) {
            $db->sqlreset()
                ->select('name_author')
                ->from(NV_PREFIXLANG . '_' . $module_data . '_authors')
                ->where('id = ' . $list['author_id']);
            $sth = $db->prepare($db->sql());
            $sth->execute();
            $author = $sth->fetch();
        }

        if (!empty($list)) {
            $block_theme = get_tpl_dir($global_config['module_theme'], 'default', '/modules/quote/block_danhngon.tpl');
            $xtpl = new XTemplate('block_danhngon.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/quote');
            $xtpl->assign('TEMPLATE', $block_theme);
            $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
            $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);

            $xtpl->assign('CONTENT',[
                'content' => $list['content'],
                'author' => $author['name_author'] ?? '',
            ]);

            $xtpl->parse('main');
            return $xtpl->text('main');
        }

    }

}

if (defined('NV_SYSTEM')) {
    $content = nv_danhngon($block_config);
}
