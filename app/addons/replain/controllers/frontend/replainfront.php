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

use Tygh\Settings;
use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($mode == 'activate' && isset($_REQUEST['key'])) {
        if (fn_check_key($_REQUEST['key'])) {
            exit();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($mode == 'set_id' && isset($_REQUEST['key']) && isset($_REQUEST['id'])) {
        if (fn_check_key($_REQUEST['key'], true)) {
            fn_update_replain_settings(array('general' => array('id' => $_REQUEST['id'], 'new_activation' => 'Y')));
            if (Settings::instance()->getValue('secure_admin', '') == 'Y') {
                $url = Registry::get('config.https_location');
            } else {
                $url = Registry::get('config.http_location');
            }
            $url .= "/" . Registry::get('config.admin_index') . "?dispatch=replain.manage";

            return array(CONTROLLER_STATUS_REDIRECT, $url);
        }
    }
}