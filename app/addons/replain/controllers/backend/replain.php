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
        fn_replain_create_chat($_REQUEST['replain_settings']);
    }  
    if ($mode == 'delete' && (!empty($_REQUEST['replain_settings']['id']))) {
        fn_update_replain_settings(array());
    }
    if ($mode == 'disable') {
        fn_update_replain_settings(array('disabled' => true, 'general' => array()));
    }
    if ($mode == 'enable') {
        fn_update_replain_settings(array('disabled' => false, 'general' => array()));
    }
    
    return array(CONTROLLER_STATUS_OK, 'replain.manage');
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($mode == 'manage') {
        $replain_settings = fn_get_replain_settings();
        Tygh::$app['view']->assign('available_languages', $replain_settings['available_languages']);

        if ($replain_settings['general']['new_activation']['value'] == 'Y') {
            fn_set_notification('N', __('notice'), __('replain.activated'), 'I');
            fn_update_replain_settings(array('general' => array('new_activation' => '')));
        }

        if ($replain_settings['general']['activated']['value'] == 'N') {
            $langid = $replain_settings['general']['langId']['value'];
            $secret_key = $replain_settings['general']['secret_key']['value'];
            list($protocol, $storefront_domain, $storefront_path) = fn_replain_get_url_setings();

            $query = "5_" . $storefront_domain . "_" . $protocol . "_" . $storefront_path . "_" . $langid . "_" . REPLAIN_PROVIDER . "_" . $secret_key;
            if (urlencode($storefront_path) === $storefront_path && strlen($query) <= 64) {
                Tygh::$app['view']->assign('invite_link', "tg://resolve?domain=ReplainBot&start=" . $query);
            } else {
                Tygh::$app['view']->assign('bot_url', array('[bot_url]' => 'tg://resolve?domain=ReplainBot&start=g_cid_null'));
            }
        }
        
        if($replain_settings['general']['disabled']['value']) {
            Tygh::$app['view']->assign('disabled', $replain_settings['general']['disabled']);
        }
    }
}