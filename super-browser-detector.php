<?php
/**
 * Plugin Name: Super Browser Detector
 * Plugin URI: http://eastsidecode.com
 * Description: This plugin adds PHP user agent data to the body class.
 * Version: 1.1
 * Author: Louis Fico
 * Author URI: eastsidecode.com
 * License: GPL12
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// detect mobile devices
function uabc_isIpad() {
    $uabc_isIpad = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
    if ($uabc_isIpad)
        return true;
    else return false;
}
function uabc_isiPhone() {
    $cn_uabc_isiPhone = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPhone');
    if ($cn_uabc_isiPhone)
        return true;
    else return false;
}
function uabc_isiPod() {
    $cn_uabc_isiPod = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPod');
    if ($cn_uabc_isiPod)
        return true;
    else return false;
}

function uabc_isNewEdge() {
    $cn_uabc_isNewEdge= (bool) strpos($_SERVER['HTTP_USER_AGENT'],'Edg');
    if ($cn_uabc_isNewEdge)
        return true;
    else return false;
}

function uabc_isiOs() {
    if (uabc_isiPhone() || uabc_isIpad() || uabc_isiPod())
        return true;
    else return false;
}
function uabc_isAndroid() {
    $uabc_isAndroid = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'Android');
    if ($uabc_isAndroid)
        return true;
    else return false;
}
function uabc_isAndroid_mobile() {
    $uabc_isAndroid   = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'Android');
    $uabc_isAndroid_m = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'Mobile');
    if ($uabc_isAndroid && $uabc_isAndroid_m)
        return true;
    else return false;
}
function uabc_isAndroid_tablet() {
    if (uabc_isAndroid() && !uabc_isAndroid_mobile())
        return true;
    else return false;
}
function uabc_isMobileDevice() {
    if (uabc_isAndroid_mobile() || uabc_isiPhone() || uabc_isiPod())
        return true;
    else return false;
}
function uabc_isTablet() {
    if ((uabc_isAndroid() && !uabc_isAndroid_mobile()) || uabc_isIpad())
        return true;
    else return false;
}

// add browser name to body class

add_filter('body_class', 'addUAClasses');

// append new UA string as strings to body class array

function addUAClasses($classes) {
    global $is_gecko, $is_IE, $is_opera, $is_safari, $is_chrome, $uabc_isiPhone, $is_edge;
    // get user agent
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    if(!wp_is_mobile()) {
        // Desktop
        if($is_gecko) $classes[] = 'browser-gecko';
        elseif($is_opera) $classes[] = 'browser-opera';
        elseif($is_safari) $classes[] = 'browser-safari';
        elseif($is_edge || (uabc_isNewEdge())) $classes[] = 'browser-edge';
        elseif($is_chrome) $classes[] = 'browser-chrome';

        elseif($is_IE) {
            $classes[] = 'browser-ie';
            if(preg_match('/MSIE ([0-9]+)([a-zA-Z0-9.]+)/', $userAgent, $browser_version))
                $classes[] = 'ie-version-'.$browser_version[1];
        }
        else $classes[] = 'browser-unknown';
    } else {
        // Mobiles and Tablets
        if(uabc_isiPhone()) $classes[] = 'browser-iphone';
        elseif(uabc_isIpad()) $classes[] = 'browser-ipad';
        elseif(uabc_isiPod()) $classes[] = 'browser-ipod';
        elseif(uabc_isAndroid()) $classes[] = 'browser-android';
        elseif(strpos($userAgent, 'Kindle') !== false) $classes[] = 'browser-kindle';
        elseif(strpos($userAgent, 'BlackBerry') !== false) $classes[] = 'browser-blackberry';
        elseif(strpos($userAgent, 'Opera Mini') !== false) $classes[] = 'browser-opera-mini';
        elseif(strpos($userAgent, 'Opera Mobi') !== false) $classes[] = 'browser-opera-mobi';
        if(uabc_isTablet()) $classes[] = 'device-tablet';
        if(uabc_isMobileDevice()) $classes[] = 'device-mobile';
    }
    // Devise OS
    if(strpos($userAgent, 'Windows') !== false) $classes[] = 'os-windows';
    elseif(uabc_isAndroid()) $classes[] = 'os-android';
    elseif(uabc_isiOs()) $classes[] = 'os-ios';
    elseif(strpos($userAgent, 'Macintosh') !== false) $classes[] = 'os-mac';
    elseif(strpos($userAgent, 'Linux') !== false) $classes[] = 'os-linux';
    elseif(strpos($userAgent, 'Kindle') !== false) $classes[] = 'os-kindle';
    elseif(strpos($userAgent, 'BlackBerry') !== false) $classes[] = 'os-blackberry';
    return $classes;
}


?>