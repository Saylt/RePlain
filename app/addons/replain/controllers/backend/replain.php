<?php
/***************************************************************************
*                                                                          *
*   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
*                                                                          *
****************************************************************************
* PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
* "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
****************************************************************************/

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($mode == 'create' && (!empty($_REQUEST['replain_settings']))) {
        fn_replain_create_chat($_REQUEST['replain_settings']);
    }
    if ($mode == 'delete') {
        fn_replain_delete_chat();
    }
    if ($mode == 'disable') {
        fn_replain_update_settings(['active' => false, 'general' => []]);
    }
    if ($mode == 'enable') {
        fn_replain_update_settings(['general' => []]);
    }

    return [CONTROLLER_STATUS_OK, 'replain.manage'];
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($mode == 'manage') {

        $replain_settings = fn_replain_get_addon_settings();

        if(!empty($replain_settings['general']['script']['value'])) {
            Tygh::$app['view']->assign('active', $replain_settings['general']['active']['value']);
        } else {
            Tygh::$app['view']->assign('bot_url', 'tg://resolve?domain=ReplainBot&start=g_cid_null');
        }
    }
}