<?php

/*
Plugin Name: Prizm Viewer
Plugin URI: http://prizmviewer.com/
Description: Prizm Viewer enables you to offer high-speed document viewing without worrying about additional hardware or installing software.  The documents stay on your servers, so you can delete, update, edit and change them anytime. We don't keep copies of your documents, so they are always secure!
Author: Accusoft <prizmcloud@accusoft.com>
Author URI: http://www.accusoft.com/
Version: 1.7
License: GPL2
*/
define (ACCUSOFT_SERVER, '//api.accusoft.com');
define (ACCUSOFT_PATH, '/v1/viewer/');
include_once('prizmcloud-functions.php');

register_activation_hook( __FILE__, 'prizmcloud_plugin_activate' );   
function prizmcloud_plugin_activate() {
  //$notices= get_option('prizmcloud_deferred_admin_notices', array());
  //$notices[]= "You've successfully installed the Prizm Cloud Viewer. You're almost done! All you need to do now is get your key here: ";
  //update_option('prizmcloud_deferred_admin_notices', $notices);
}

add_action('admin_notices', 'prizmcloud_admin_notices');
function prizmcloud_admin_notices() {
  //if ($notices= get_option('prizmcloud_deferred_admin_notices')) {
  //    foreach ($notices as $notice) {
  //    echo "<div class='updated' style='border-style:solid;border-color:0xff6600'><p><img src=" . plugins_url( 'images/accusoft_logo_wordpress_admin.png' , __FILE__ ) . ">$notice <a href=''>Get my key</a></p></div>";
  //    }
  //  delete_option('prizmcloud_deferred_admin_notices');
  //}
}

register_deactivation_hook(__FILE__, 'prizmcloud_plugin_deactivate');
function prizmcloud_plugin_deactivate() {
//  delete_option('prizmcloud_deferred_admin_notices'); 
}

function prizmcloud_getdocument($atts)
{
	extract(shortcode_atts(array(
		'key' => '',
		'document' => '',
		'type' => '',
		'width' => '',
		'height' => '',
		'print' => '',
		'color' => '',
		'animtype' => '',
		'animduration' => '',
		'animspeed' => '',
		'automatic' => '',
		'showcontrols' => '',
		'centercontrols' => '',
		'keyboardnav' => '',
		'hoverpause' => ''
	), $atts));
	$integration = "wordpress";



	if (strcmp($type,"slideshow") != 0)
	{
		$viewerCode = "//connect.ajaxdocumentviewer.com/?key=".$key."&viewertype=".$type."&document=".$document."&viewerheight=".$height."&viewerwidth=".$width."&printButton=".$print."&toolbarColor=".$color."&integration=".$integration;
		$iframeWidth = $width + 20;
		$iframeHeight = $height + 40;
	}
	else
	{
		$viewerCode = "//connect.ajaxdocumentviewer.com/?key=".$key."&viewertype=".$type."&document=".$document."&viewerheight=".$height."&viewerwidth=".$width."&animtype=".$animtype."&animduration=".$animduration."&animspeed=".$animspeed."&automatic=".$automatic."&showcontrols=".$showcontrols."&centercontrols=".$centercontrols."&keyboardnav=".$keyboardnav."&hoverpause=".$hoverpause."&integration=".$integration;
		$iframeWidth = $width + 20;
		$iframeHeight = $height + 20;
	}
        if ($type == "flash" && $width < 650) {
	    $code = "<div id=\"widtherror\" width=\"600\" height=\"100\">Prizm Viewer Error: Please choose a width of 650px or greater for your Prizm Flash Viewer, or select the HTML5 viewer if you need a smaller size</div>";
        }
        else {
  	    $code = "<iframe src=\"".$viewerCode."\" width=\"".$iframeWidth."\" height=\"".$iframeHeight."\"></iframe>";
        }
	return $code;
}

// Activate Shortcode to Retrive Document with Prizm Cloud
add_shortcode('prizmcloud', 'prizmcloud_getdocument');

// Add Quicktag for Prizm Cloud Admin
//add_action('admin_print_scripts','prizmcloud_admin_print_scripts');

// Activate Shortcode to Retrive Document with ACS Viewer
add_shortcode('acsviewer', 'acsviewer_getdocument');

// Add ACS Viewer Dialog button to Tiny MCEEditor
add_action('admin_init','acsviewer_mce_addbuttons');

// Add ACS Viewer Dialog window to Tiny MCEEditor
add_action('wp_ajax_acsviewer_dialog_window', 'acsviewer_dialog_window');

// Add an Option to Settings Menu for ACS Viewer
add_action('admin_menu', 'acsviewer_settings_page');

add_action('admin_enqueue_scripts', 'enqueue_scripts_styles_admin');
function enqueue_scripts_styles_admin(){
    wp_enqueue_media();
}



function acsviewer_settings_page()
{
	global $prizmcloud_settings_page;

	$prizmcloud_settings_page = add_options_page('Prizm Viewer', 'Prizm Viewer', 'manage_options', basename(__FILE__), 'prizmcloud_settings');

}
if (!defined('PRIZMCLOUD_WP_PLUGIN_NAME'))
    define('PRIZMCLOUD_WP_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));

function prizmcloud_settings()
{
	if ( function_exists('current_user_can') && !current_user_can('manage_options') ) die(t('An error occurred.'));
	if (! user_can_access_admin_page()) wp_die('You do not have sufficient permissions to access this page');

	require(ABSPATH. 'wp-content/plugins/'. PRIZMCLOUD_WP_PLUGIN_NAME .'/acsviewer-settings.php');
}

function acsviewer_getdocument($atts, $content)
{
    $licenseKey = get_option('licenseKey');
    parse_str(html_entity_decode($content), $params);

    if($params['server']) {
        $server = $params['server'];
        unset($params['server']);
    } else {
        $server = ACCUSOFT_SERVER.ACCUSOFT_PATH;
    }

    if ($atts) {
        foreach ($atts as $key => $value) {
            if (!$params[$key]) {
                $params[$key] = $value;
            }
        }
    }
    $params = supportLegacy($params);

    if(!$params['key']) {
        $params['key'] = $licenseKey;
    }
    $viewerCode = $server."?";
    if($params) {
        foreach ($params as $key => $value) {
            $viewerCode .= $key . "=" . $value . "&";
        }
    }
    $viewerCode = rtrim($viewerCode, "&");

    if (strcmp($params['viewertype'],'slideshow') != 0) {
        if (preg_match('/.+%$/', $params['viewerheight'])) {
            $iframeHeight = intval($params['viewerheight'])/100 * 800;
        } else {
            $iframeHeight = $params['viewerheight'] + 40;
        }
        if (preg_match('/.+%$/', $params['viewerwidth'])) {
            $iframeWidth = $params['viewerwidth'];
        } else {
            $iframeWidth = $params['viewerwidth'] + 20;
        }
    } else {
        if (preg_match('/.+%$/', $params['viewerheight'])) {
            $iframeHeight = intval($params['viewerheight'])/100 * 600;
        } else {
            $iframeHeight = $params['viewerheight'] + 20;
        }
        if (preg_match('/.+%$/', $params['viewerwidth'])) {
            $iframeWidth = $params['viewerwidth'];
        } else {
            $iframeWidth = $params['viewerwidth'] + 20;
        }
    }

    $code = "<iframe src=\"".$viewerCode."\" width=\"".$iframeWidth."\" height=\"".$iframeHeight."\" frameborder =0 seemless></iframe>";
//    $code = "<iframe src=\"".$viewerCode."\" width=\"100%\" height=\"100%\" frameborder =0 seemless></iframe>";

    return $code;
}

function supportLegacy($atts)
{
    if($atts['type']) {
        $atts['viewertype'] = $atts['type'];
        unset($atts['type']);
    }
    if ($atts['width']) {
        $atts['viewerwidth'] = $atts['width'];
        unset($atts['width']);
    }
    if($atts['height']) {
        $atts['viewerheight'] = $atts['height'];
        unset($atts['height']);
    }
    if($atts['color']) {
        $atts['lowerToolbarColor'] = $atts['color'];
        unset($atts['color']);
    }
    if ($atts['print'] == "No") {
        if (strlen($atts['hidden']) > 0) {
            $atts['hidden'] .= ',print';
        } else {
            $atts['hidden'] = 'print';
        }
        unset($atts['print']);
    }
    return $atts;

}
