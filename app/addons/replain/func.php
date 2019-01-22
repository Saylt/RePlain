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

use Tygh\Settings;
use Tygh\Registry;

function fn_get_replain_settings() 
{
    $replain_settings = fn_get_replain_addon_settings();

    return $replain_settings;
}

function fn_replain_create_chat($replain_settings)
{
    if (isset($replain_settings['general']['script'])) {
        fn_update_replain_settings($replain_settings);
    }
}

function fn_delete_replain_chat() {
    $addon_settings = fn_get_replain_addon_settings();

    foreach ($addon_settings['general'] as $k => $v) {
        $addon_settings['general'][$k]['value'] = '';
    }
    $addon_settings['general']['active']['value'] = true;
    
    foreach($addon_settings['general'] as $option) {
        Settings::instance()->updateValueById($option['object_id'], $option['value'], '', false, $addon_settings['company_id']);
    }
    
}

function fn_update_replain_settings($replain_settings = [])
{
    $addon_settings = fn_get_replain_addon_settings();

    if (isset($replain_settings['active'])) {
        $addon_settings['general']['active']['value'] = $replain_settings['active'];
        if($replain_settings['active']){
            fn_set_notification('N', __('notice'), __('replain.disabled'), 'I');
        } else {
            fn_set_notification('N', __('notice'), __('replain.enabled'), 'I');
        }
    } else {
        $addon_settings['general']['active']['value'] = true;
    }

    if (isset($replain_settings['general'])) {
        foreach ($addon_settings['general'] as $k => $v) {
            foreach ($replain_settings['general'] as $j => $i) {
                if($k == $j) {
                    $addon_settings['general'][$k]['value'] = $i;
                }
            }
        }
    }

    foreach($addon_settings['general'] as $option) {
        Settings::instance()->updateValueById($option['object_id'], $option['value'], '', false, $addon_settings['company_id']);
    }
}

function fn_get_replain_addon_settings () 
{
    $company_id = fn_allowed_for('ULTIMATE') ? Registry::get('runtime.company_id') : 0;
    $replain_settings_section = Settings::instance()->getSectionByName('replain', 'ADDON');
    $replain_settings = Settings::instance()->getList($replain_settings_section['section_id'], 0, false, $company_id);
    $replain_settings['company_id'] = $company_id;
    
    foreach ($replain_settings['general'] as $k => $v) {
        unset($replain_settings['general'][$k]);
        $replain_settings['general'][$v['name']] = $v;
    }

    return $replain_settings;
}