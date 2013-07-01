tinyMCEPopup.requireLangPack();
	
var PrizmCloudInsertDialog =
{
	init : function()
	{
		jQuery('input[name=viewerType]').click(function()
		{
			buildShortcode();
		});
		jQuery('#viewerWidth').blur(function()
		{
			buildShortcode();
		});
		jQuery('#viewerHeight').blur(function()
		{
			buildShortcode();
		});
		jQuery('input[name=viewerPrintButton]').click(function()
		{
			buildShortcode();
		});
		jQuery('#viewerToolbarColor').blur(function()
		{
			buildShortcode();
		});	
		jQuery('#viewerDocument').blur(function()
		{
			buildShortcode();
		});
	},
	insert : function()
	{
		// insert the contents from the input into the document
		tinyMCEPopup.editor.execCommand("mceInsertContent", false, jQuery("#shortcode").val());
		tinyMCEPopup.close();
	}
};
		
function buildShortcode()
{
	var shortcode = 'prizmcloud';			
	var licenseKey = jQuery("#licenseKey").val();
	var viewerType = jQuery("input[name=viewerType]:checked").val();
	var document = jQuery("#viewerDocument").val();
	var viewerWidth = jQuery("#viewerWidth").val();
	var viewerHeight = jQuery("#viewerHeight").val();
	var toolbarColor = jQuery("#viewerToolbarColor").val();
	toolbarColor = toolbarColor.replace("#","");
	var printButton = jQuery("input[name=viewerPrintButton]:checked").val();
	
	if (licenseKey.length > 0)
	{
		shortcode += ' key="' + licenseKey + '"';
	}
	
	if (viewerType.length > 0)
	{
		shortcode += ' type="' + viewerType + '"';
	}
	
	if (document.length > 0)
	{
		shortcode += ' document="' + document + '"';
	}
	
	if (viewerWidth.length > 0)
	{
		shortcode += ' width="' + viewerWidth + '"';
	}
	
	if (viewerHeight.length > 0)
	{
		shortcode += ' height="' + viewerHeight + '"';
	}
	
	if (printButton.length > 0)
	{
		shortcode += ' print="' + printButton + '"';
	}
	
	if (toolbarColor.length > 0)
	{
		shortcode += ' color="' + toolbarColor + '"';
	}
	
	jQuery('#shortcode').val('['+shortcode+']');
}

tinyMCEPopup.onInit.add(PrizmCloudInsertDialog.init, PrizmCloudInsertDialog);
