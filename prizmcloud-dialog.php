<?php
$path  = ''; // It should be end with a trailing slash    
if (!defined('WP_LOAD_PATH'))
{
	/** classic root path if wp-content and plugins is below wp-config.php */
	$classic_root = dirname(dirname(dirname(dirname(__FILE__)))) . '/' ;
	
	if (file_exists( $classic_root . 'wp-load.php') )
		define( 'WP_LOAD_PATH', $classic_root);
	else
		if (file_exists( $path . 'wp-load.php') )
			define( 'WP_LOAD_PATH', $path);
		else
			exit("Could not find wp-load.php");
}

// Load WordPress
require_once( WP_LOAD_PATH . 'wp-load.php');

// Get Prizm Cloud License Key
$licenseKey = get_option('licenseKey');

ini_set('display_errors', '0');
error_reporting(E_ALL | E_STRICT);
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>Prizm Cloud Embedded Document Viewer</title>
		<link href="css/mcColorPicker.css" type="text/css" rel="stylesheet" />
		<script type="text/javascript" src="../../../wp-includes/js/jquery/jquery.js"></script>
		<script type="text/javascript" src="js/tiny_mce_popup.js"></script>
		<script type="text/javascript" src="js/prizmcloud-dialog.js"></script>
		<script type="text/javascript" src="js/mcColorPicker.js"></script>
	</head>
	
	<body>
		<form id="formPrizmCloud" method="post">
		<?php
		if (strlen(trim($licenseKey)) > 0)
		{
			echo "<input type=\"hidden\" id=\"licenseKey\" name=\"licenseKey\" value=\"".$licenseKey."\" />\r\n";
		}
		?>
		<table> 
			<?php
			if (strlen(trim($licenseKey)) == 0)
			{
			?>
				<tr>
					<td align="right" class="gray dwl_gray"><strong>Key:</strong><br /></td>
					<td valign="top"><input name="licenseKey" type="text" class="opt dwl" id="licenseKey" style="width:200px;" value="<?php echo $licenseKey; ?>" /></td>
				</tr>
			<?php
			}
			?>
			<tr>
				<td align="right" class="gray dwl_gray"><strong>Your Document URL:</strong></td>
				<td valign="top"><input name="viewerDocument" type="text" id="viewerDocument" size="40" /></td>
			</tr>
			<tr>
				<td align="right" class="gray dwl_gray"><strong>Viewer Type:</strong></td>
				<td valign="top">
					<input type="radio" value="flash" name="viewerType" checked="checked" /> <span>Flash</span>
					<input type="radio" value="html5" name="viewerType" /> <span>HTML5</span>
				</td>
			</tr>
			<tr>
				<td align="right" class="gray dwl_gray"><strong>Viewer Width:</strong></td>
				<td valign="top"><input name="viewerWidth" type="text" id="viewerWidth" size="6" value="600" />px</td>
			</tr>
			<tr>
				<td align="right" class="gray dwl_gray"><strong>Viewer Height:</strong></td>
				<td valign="top"><input name="viewerHeight" type="text" id="viewerHeight" size="6" value="800" />px</td>
			</tr>
			<tr>
				<td align="right" class="gray dwl_gray"><strong>Print Button:</strong></td>
				<td valign="top">
					<input type="radio" name="viewerPrintButton" value="Yes" checked="checked" /> <span>Yes</span>
					<input type="radio" name="viewerPrintButton" value="No" /> <span>No</span>
				</td>
			</tr>
			<tr>
				<td align="right" class="gray dwl_gray"><strong>Toolbar Color:</strong></td>
				<td valign="top">
					<input type="text" id="viewerToolbarColor" name="viewerToolbarColor" value="#CCCCCC" class="color" />
				</td>
			</tr>
		</table>		
		
		<fieldset>
			<table width="100%" border="0" cellspacing="0" cellpadding="5">
				<tr>
					<td colspan="2">
						<br />Shortcode Preview
						<textarea name="shortcode" cols="72" rows="2" id="shortcode"></textarea>
					</td>
				</tr>
			</table>
		</fieldset>
		
		<div class="mceActionPanel">
			<div class="fl"><input type="button" id="insert" name="insert" value="Insert" onclick="PrizmCloudInsertDialog.insert();" /></div>
			<div class="fr"><input type="button" id="cancel" name="cancel" value="Cancel" onclick="tinyMCEPopup.close();"/></div>
		</div>
		</form>

	</body>
</html>