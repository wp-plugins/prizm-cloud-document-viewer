<?php
/* Added by Robin */

if (!defined('PRIZMCLOUD_WP_PLUGIN_NAME'))
    define('PRIZMCLOUD_WP_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));
	
if (!defined('PRIZMCLOUD_WP_PLUGIN_URL'))
{
	define('PRIZMCLOUD_WP_PLUGIN_URL', WP_PLUGIN_URL . '/' . PRIZMCLOUD_WP_PLUGIN_NAME);
}

function prizmcloud_mce_addbuttons()
{
	// Permissions Check
	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
		return;

	// Add button to TinyMCE Editor
	if ( get_user_option('rich_editing') == 'true')
	{
		add_filter("mce_external_plugins", "prizmcloud_add_tinymce_plugin");
		add_filter('mce_buttons', 'prizmcloud_register_mce_button');
	}
}

function prizmcloud_register_mce_button($buttons)
{
	array_push($buttons, "separator", "prizmcloud");
	return $buttons;
}

function prizmcloud_add_tinymce_plugin($plugin_array)
{
	$plugin_array['prizmcloud'] = PRIZMCLOUD_WP_PLUGIN_URL.'/js/prizmcloud_plugin.js';
	return $plugin_array;
}

function prizmcloud_admin_print_scripts($arg)
{
	global $pagenow;
	if (is_admin() && ($pagenow == 'post-new.php' || $pagenow == 'post.php'))
	{
		$js = PRIZMCLOUD_WP_PLUGIN_URL.'/js/prizmcloud-quicktags.js';
		wp_enqueue_script("prizmcloud_qts", $js, array('quicktags') );
	}
}

function prizmcloud_dialog_window()
{
	define('JS_PLUGIN_URL', includes_url() . '/js');

	// Get Prizm Cloud License Key
	$licenseKey = get_option('licenseKey');
	
	// Display Form
    echo "<!DOCTYPE html>
	<!--[if lt IE 7]>      <html class=\"no-js lt-ie9 lt-ie8 lt-ie7\"> <![endif]-->
	<!--[if IE 7]>         <html class=\"no-js lt-ie9 lt-ie8\"> <![endif]-->
	<!--[if IE 8]>         <html class=\"no-js lt-ie9\"> <![endif]-->
	<!--[if gt IE 8]><!--> <html class=\"no-js\"> <!--<![endif]-->
		<head>
			<meta charset=\"utf-8\">
			<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\">
			<title>Prizm Cloud Embedded Document Viewer</title>
			<link href=\"".PRIZMCLOUD_WP_PLUGIN_URL."/css/mcColorPicker.css\" type=\"text/css\" rel=\"stylesheet\" />
			<script type=\"text/javascript\" src=\"".JS_PLUGIN_URL."/jquery/jquery.js\"></script>
			<script type=\"text/javascript\" src=\"".PRIZMCLOUD_WP_PLUGIN_URL."/js/tiny_mce_popup.js\"></script>
			<script type=\"text/javascript\" src=\"".PRIZMCLOUD_WP_PLUGIN_URL."/js/prizmcloud-dialog.js\"></script>
			<script type=\"text/javascript\" src=\"".PRIZMCLOUD_WP_PLUGIN_URL."/js/mcColorPicker.js\"></script>
		</head>
		
		<body>
			<form id=\"formPrizmCloud\" method=\"post\">\r\n";
			if (strlen(trim($licenseKey)) > 0)
			{
				echo "<input type=\"hidden\" id=\"licenseKey\" name=\"licenseKey\" value=\"".$licenseKey."\" />\r\n";
			}
			echo "<table>\r\n";
				if (strlen(trim($licenseKey)) == 0)
				{
					echo "<tr>
						<td align=\"right\" class=\"gray dwl_gray\"><strong>Key:</strong><br /></td>
						<td valign=\"top\"><input name=\"licenseKey\" type=\"text\" class=\"opt dwl\" id=\"licenseKey\" style=\"width:200px;\" value=\"".$licenseKey."\" /></td>
					</tr>\r\n";
				}
				echo "<tr>
					<td align=\"right\" class=\"gray dwl_gray\"><strong>Your Document URL:</strong></td>
					<td valign=\"top\"><input name=\"viewerDocument\" type=\"text\" id=\"viewerDocument\" size=\"40\" /></td>
				</tr>
				<tr>
					<td align=\"right\" class=\"gray dwl_gray\"><strong>Viewer Type:</strong></td>
					<td valign=\"top\">
						<input type=\"radio\" value=\"html5\" name=\"viewerType\" onclick=\"javascript:pcSettings(this.value)\" checked=\"checked\" /> <span>HTML5</span>
						<input type=\"radio\" value=\"flash\" name=\"viewerType\" onclick=\"javascript:pcSettings(this.value)\" /> <span>Flash</span>
						<input type=\"radio\" value=\"slideshow\" name=\"viewerType\" onclick=\"javascript:pcSettings(this.value)\" /> <span>Slideshow</span>
					</td>
				</tr>
				<tr>
					<td align=\"right\" class=\"gray dwl_gray\"><strong>Viewer Width:</strong></td>
					<td valign=\"top\"><input name=\"viewerWidth\" type=\"text\" id=\"viewerWidth\" size=\"6\" value=\"600\" />px</td>
				</tr>
				<tr>
					<td align=\"right\" class=\"gray dwl_gray\"><strong>Viewer Height:</strong></td>
					<td valign=\"top\"><input name=\"viewerHeight\" type=\"text\" id=\"viewerHeight\" size=\"6\" value=\"800\" />px</td>
				</tr>
			</table>
			<div id=\"documentViewer\" class=\"show\">
				<table>
					<tr>
						<td align=\"right\" class=\"gray dwl_gray\"><strong>Print Button:</strong></td>
						<td valign=\"top\">
							<input type=\"radio\" name=\"viewerPrintButton\" value=\"Yes\" checked=\"checked\" /> <span>Yes</span>
							<input type=\"radio\" name=\"viewerPrintButton\" value=\"No\" /> <span>No</span>
						</td>
					</tr>
					<tr>
						<td align=\"right\" class=\"gray dwl_gray\"><strong>Toolbar Color:</strong></td>
						<td valign=\"top\">
							<input type=\"text\" id=\"viewerToolbarColor\" name=\"viewerToolbarColor\" value=\"#CCCCCC\" class=\"color\" />
						</td>
					</tr>
				</table>
			</div>
			<div id=\"slideshowViewer\" class=\"hide\">
				<table>
					<tr>
						<td align=\"right\" class=\"gray dwl_gray\"><strong>Animation Type:</strong></td>
						<td valign=\"top\">
							<select id=\"viewerAnimType\" name=\"viewerAnimType\">
							<option value=\"slide\">Slide</option>
							<option value=\"fade\">Fade</option>
							</select>
						</td>
					</tr>
					<tr>
						<td align=\"right\" valign=\"top\" class=\"gray dwl_gray\"><strong>Animation Duration:</strong></td>
						<td valign=\"top\">
							<input type=\"text\" id=\"viewerAnimDuration\" name=\"viewerAnimDuration\" value=\"450\" /><br /><em>(Note: # in milliseconds)</em>
						</td>
					</tr>
					<tr>
						<td align=\"right\" valign=\"top\" class=\"gray dwl_gray\"><strong>Animation Speed:</strong></td>
						<td valign=\"top\">
							<input type=\"text\" id=\"viewerAnimSpeed\" name=\"viewerAnimSpeed\" value=\"4000\" /><br /><em>(Note: # in milliseconds)</em>
						</td>
					</tr>
					<tr>
						<td align=\"right\" class=\"gray dwl_gray\"><strong>Start Automatically:</strong></td>
						<td valign=\"top\">
							<select id=\"viewerAutomatic\" name=\"viewerAutomatic\">
							<option value=\"yes\">Yes</option>
							<option value=\"no\">No</option>
							</select>
						</td>
					</tr>
					<tr>
						<td align=\"right\" class=\"gray dwl_gray\"><strong>Show Controls:</strong></td>
						<td valign=\"top\">
							<select id=\"viewerShowControls\" name=\"viewerShowControls\">
							<option value=\"yes\">Yes</option>
							<option value=\"no\">No</option>
							</select>
						</td>
					</tr>
					<tr>
						<td align=\"right\" class=\"gray dwl_gray\"><strong>Center Controls: (if shown)</strong></td>
						<td valign=\"top\">
							<select id=\"viewerCenterControls\" name=\"viewerCenterControls\">
							<option value=\"yes\">Yes</option>
							<option value=\"no\">No</option>
							</select>
						</td>
					</tr>
					<tr>
						<td align=\"right\" class=\"gray dwl_gray\"><strong>Allow Keyboard Navigation:</strong></td>
						<td valign=\"top\">
							<select id=\"viewerKeyboardNav\" name=\"viewerKeyboardNav\">
							<option value=\"yes\">Yes</option>
							<option value=\"no\">No</option>
							</select>
						</td>
					</tr>
					<tr>
						<td align=\"right\" class=\"gray dwl_gray\"><strong>Pause on Hover:</strong></td>
						<td valign=\"top\">
							<select id=\"viewerHoverPause\" name=\"viewerHoverPause\">
							<option value=\"yes\">Yes</option>
							<option value=\"no\">No</option>
							</select>
						</td>
					</tr>
				</table>
			</div>
			
			<fieldset>
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">
					<tr>
						<td colspan=\"2\">
							<br />Shortcode Preview
							<textarea name=\"shortcode\" cols=\"72\" rows=\"2\" id=\"shortcode\"></textarea>
						</td>
					</tr>
				</table>
			</fieldset>
			
			<div class=\"mceActionPanel\">
				<div class=\"fl\"><input type=\"button\" id=\"insert\" name=\"insert\" value=\"Insert\" onclick=\"PrizmCloudInsertDialog.insert();\" /></div>
				<div class=\"fr\"><input type=\"button\" id=\"cancel\" name=\"cancel\" value=\"Cancel\" onclick=\"tinyMCEPopup.close();\"/></div>
			</div>
			</form>
		</body>
	</html>\r\n";
	
	exit();
}