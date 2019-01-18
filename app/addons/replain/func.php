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
use Tygh\Http;
use Tygh\Replain\BuildUrl;

function fn_get_replain_settings() 
{
   $available_languages = [ 
            'English'                   => 0,
            'Русский'                   => 1,
            'Español'                   => 2,
            'Farsi'                     => 3,
            'Português'                 => 4,
            'Arabic'                    => 5,
            'Deutsch'                   => 6,
            'Bahasa Indonesia'          => 7,
            'Oʻzbek tili'               => 8,
            'italiano'                  => 9,
            __('replain.autodetection') => -1
            ];

    $replain_settings = fn_get_replain_addon_settings();
    $replain_settings['available_languages'] = $available_languages;

    return $replain_settings;
}

function fn_replain_create_chat($replain_settings)
{
    if (isset($replain_settings['general']['raw_key'])) {
        preg_match('/([a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})/', $replain_settings['general']['raw_key'], $matches);
        unset($replain_settings['general']['raw_key']);
        if($matches) {
            $replain_settings['general']['id'] = $matches[1];
        }
    } else {
        $replain_settings = [];
        $replain_settings['general']['secret_key'] = substr(md5(serialize(rand())), 0, 13); //agreed with Re:plain team on 13 symbols for the secret key
    }
    fn_update_replain_settings($replain_settings);
    return isset($replain_settings['general']['secret_key']) ? $replain_settings['general']['secret_key'] : true;
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

function fn_check_key($key) 
{
    $addon_settings = fn_get_replain_addon_settings();

    if (!empty($addon_settings['general']['secret_key']['value'])) {
        if($addon_settings['general']['secret_key']['value'] == $key) {
            $replain_settings['general']['secret_key'] = '';

            fn_update_replain_settings($replain_settings);
            
            return true;
        } else {
            $replain_settings['general']['secret_key'] = '';
            fn_update_replain_settings($replain_settings);
            return false;
        }
    } else {
        $replain_settings['general']['secret_key'] = '';
        fn_update_replain_settings($replain_settings);
        return false;
    }
}

function fn_replain_get_url_setings() 
{
    $is_url_suitable = true;
    $protocol = (Registry::get('settings.Security.secure_storefront') == 'full') ? 1 : 2;
    if ($protocol === 1) {
        $storefront_array = explode('/', Registry::get('runtime.company_data.secure_storefront'));
    } else {
        $storefront_array = explode('/', Registry::get('runtime.company_data.storefront'));
    }

    $storefront_domain = array_shift($storefront_array);
    $domain_builder = new BuildUrl($storefront_domain);
    $domain_builder->punyEncode();
    $domain_builder->prepareDomain();
    $storefront_domain = $domain_builder->getPreparedUrl();

    $storefront_path = urlencode(implode("_", $storefront_array));

    return array($protocol, $storefront_domain, $storefront_path);
}