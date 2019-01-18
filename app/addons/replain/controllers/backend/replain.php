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

use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($mode == 'create' && (!empty($_REQUEST['replain_settings']))) {
        if(isset($_REQUEST['replain_settings']['general']['langId'])) {
            $secret_key = fn_replain_create_chat([]);
            $langid = $_REQUEST['replain_settings']['general']['langId'];
            list($protocol, $storefront_domain, $storefront_path) = fn_replain_get_url_setings();
            $query = REPLAIN_CONST . "_" . $storefront_domain . "_" . $protocol . "_" . $storefront_path . "_" . $langid . "_" . REPLAIN_PROVIDER . "_" . $secret_key;

            if (urlencode($storefront_path) === $storefront_path && strlen($query) <= 14) {
                header('Location: tg://resolve?domain=ReplainBot&start=' . $query);
            } else {
                header('Location: ' . Registry::get("config.current_location") . "/" . Registry::get("config.admin_index") . '?dispatch=replain.manage&bot_url=Y');
            }
            exit;
        } elseif (isset($_REQUEST['replain_settings']['general']['raw_key'])) {
            fn_replain_create_chat($_REQUEST['replain_settings']);
        }
            
    }  
    if ($mode == 'delete' && (!empty($_REQUEST['replain_settings']['id']))) {
        fn_delete_replain_chat();
    }
    if ($mode == 'disable') {
        fn_update_replain_settings(['active' => false, 'general' => []]);
    }
    if ($mode == 'enable') {
        fn_update_replain_settings(['general' => []]);
    }
    
    return array(CONTROLLER_STATUS_OK, 'replain.manage');
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($mode == 'manage') {
    
        if(isset($_REQUEST['bot_url'])) {
            Tygh::$app['view']->assign('bot_url', 'tg://resolve?domain=ReplainBot&start=g_cid_null');
        }
        $replain_settings = fn_get_replain_settings();
        Tygh::$app['view']->assign('available_languages', $replain_settings['available_languages']);

        if($replain_settings['general']['active']['value']) {
            Tygh::$app['view']->assign('active', $replain_settings['general']['active']);
        }
    }
}