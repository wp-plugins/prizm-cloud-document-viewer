(function()
{
	tinymce.PluginManager.requireLangPack('prizmcloud');
	tinymce.create('tinymce.plugins.PrizmCloudPlugin',
	{
		init : function(ed,url)
		{
			ed.addCommand('mcePrizmCloud', function()
			{
				ed.windowManager.open(
				{
					//file : url.substr(0,url.indexOf("plugins/prizm-cloud-document-viewer") + 18) + '/prizmcloud-dialog.php',
					file : ajaxurl + '?action=prizmcloud_dialog_window',
					width : 600 + parseInt(ed.getLang('prizmcloud.delta_width',0)),
					height : 460 + parseInt(ed.getLang('prizmcloud.delta_height',0)),
					inline : 1
				},
				{
					plugin_url : url,
					some_custom_arg : 'custom arg'
				})
			});
			ed.addButton('prizmcloud',
			{
				title : 'Prizm Cloud Embedded Document Viewer',
				cmd : 'mcePrizmCloud',
				image : url.substr(0,url.indexOf("plugins/prizm-cloud-document-viewer") + 35) + '/images/prizmcloud.png'
			});
			ed.onNodeChange.add
				(function(ed,cm,n)
				{
					cm.setActive('prizmcloud',n.nodeName=='IMG')
				})
		},
		createControl : function(n,cm)
		{
			return null
		},
		getInfo : function() 
		{
			return {
					longname : 'Prizm Cloud Embedded Document Viewer',
					author : 'Accusoft Corporation',
					authorurl : 'http://www.accusoft.com',
					infourl : 'http://www.prizmcloud.com',
					version : "1.2"
			};
		}
	});
	tinymce.PluginManager.add('prizmcloud',tinymce.plugins.PrizmCloudPlugin)
})();
