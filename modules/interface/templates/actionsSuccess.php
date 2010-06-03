<!-- vim: ts=2:sw=2:nu:fdc=2:spell

Ext.ux.grid.RowActions Example Page

@author    Ing.Jozef Sakáloš
@copyright (c) 2008, by Ing. Jozef Sakáloš
@date      30. March 2008
@version   $Id: rowactions.html 152 2008-04-08 21:56:11Z jozo $

@license rowactions.html is licensed under the terms of the Open Source
LGPL 3.0 license. Commercial use is permitted to the extent that the 
code/component(s) do NOT become part of another Open Source or Commercially
licensed development library or toolkit without explicit permission.
 
License details: http://www.gnu.org/licenses/lgpl.html
-->

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="/sfExtjs2Plugin/extjs/resources/css/ext-all.css">
	<link rel="stylesheet" type="text/css" href="/sfExtjs2Plugin/extjs/examples/rowactionsImm/css/icons.css">
	<link rel="stylesheet" type="text/css" href="/sfExtjs2Plugin/extjs/examples/rowactionsImm/css/Ext.ux.GridRowActions.css">
	<link rel="stylesheet" type="text/css" href="/sfExtjs2Plugin/extjs/examples/rowactionsImm/css/empty.css" id="theme">
	<link rel="stylesheet" type="text/css" href="/sfExtjs2Plugin/extjs/examples/rowactionsImm/css/webpage.css">
	<link rel="stylesheet" type="text/css" href="/sfExtjs2Plugin/extjs/examples/rowactionsImm/css/rowactions.css">
	<link rel="shortcut icon" href="../img/extjs.ico">
	<script type="text/javascript" src="/sfExtjs2Plugin/extjs/adapter/ext/ext-base.js"></script>
	<script type="text/javascript" src="/sfExtjs2Plugin/extjs/ext-all.js"></script>
	<script type="text/javascript" src="/sfExtjs2Plugin/extjs/examples/rowactionsImm/js/WebPage.js"></script>
	<script type="text/javascript" src="/sfExtjs2Plugin/extjs/examples/rowactionsImm/js/Ext.ux.GridRowActions.js"></script>
	<script type="text/javascript" src="/sfExtjs2Plugin/extjs/examples/rowactionsImm/js/Ext.ux.ThemeCombo.js"></script>
	<script type="text/javascript" src="/sfExtjs2Plugin/extjs/examples/rowactionsImm/js/Ext.ux.Toast.js"></script>
	
	<script type="text/javascript" src="/sfExtjs2Plugin/extjs/examples/rowactionsImm/rowactions.js"></script>
	<title id="page-title">Ext.ux.GridRowActions Plugin by Saki</title>
</head>
<body>

<div id="center-content" class="x-hidden">
	<h2>Uses <a href="http://extjs.com/forum/showthread.php?t=24970" target="_blank">Buffering Http State Provider</a>
		with <a href="http://sqlite.org" target="_blank">SQLite</a> backend
	</h2>
</div>

<div id="west-content" class="x-hidden">
	<div id="description">
		<h3>Description</h3>
		<p>
		This example shows how to use RowActions plugin. Row actions are small images, icons, optionally
		followed by a text, that fit into a grid column.
		</p>

		<div id="adsense-float" class="adsense x-hidden">
			<script type="text/javascript"><!--
			google_ad_client = "pub-2768521146228687"
			/* 125x125 - in text */
			google_ad_slot = "2156000855";
			google_ad_width = 125;
			google_ad_height = 125;
			//-->
			</script>
			<script type="text/javascript"
			src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
			</script>
		</div>

		<p>
		When an icon is clicked <i>beforeaction</i> event is fired first followed by <i>action</i> event.
		Action can be cancelled by returning false from <i>beforeaction</i> event handler.
		</p>
		<p>
		Both events are fired with <i>grid, record, action, rowIndex</i> and <i>colIndex</i> parameters
		that can be used to identify and execute the requested action.
		</p>
		<p>
		Icons, optional texts and icon tooltips can be defined either statically; they are same for all grid
		rows in this case, or they can be bound to the grid store record fields; they can be different in each grid row
		in this case.
		</p>
		<p>
		This example shows actions bound to the grid records.
		</p>
		<p>
		You can see the code used for this example by right clicking the page and selecting "View Page Source"
		item from the context menu.
		</p>
		<p>
		The example is powered by Apache web server and PHP 5.2.5. PHP script that returns grid rows
		is included in the download package.
		</p>
	</div>

	<a href="http://extjs.com/forum/showthread.php?t=29961">RowActions Forum Thread</a>

	<div id="paypal">
		<!-- PayPal Donate Button -->
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-butcc-donate.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
		<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
		">
		</form>
		<!-- PayPal Donate Button End-->
	</div>

	<p><b>Downloads:</b></p>
	<a href="http://extjs.eu/phpmv2/phpmyvisites.php?url=http%3A//rowactions.extjs.eu/rowactions-1.0.tar.bz2&id=1&pagename=FILE: download/rowactions/rowactions-1.0.tar.bz2">rowactions-1.0.tar.bz2</a><br>
	<a href="http://extjs.eu/phpmv2/phpmyvisites.php?url=http%3A//rowactions.extjs.eu/rowactions-1.0.tar.gz&id=1&pagename=FILE: download/rowactions/rowactions-1.0.tar.gz">rowactions-1.0.tar.gz</a><br>
	<a href="http://extjs.eu/phpmv2/phpmyvisites.php?url=http%3A//rowactions.extjs.eu/rowactions-1.0.zip&id=1&pagename=FILE: download/rowactions/rowactions-1.0.zip">rowactions-1.0.zip</a><br>
	<br>
	<p>
		<b><a href="http://extjs.eu" target="_blank">Other Extensions and Plugins</a></b>
	</p>

	<!-- digg -->
	<div id="digg">
		<script>
		digg_url = 'http://rowactions.extjs.eu';
		</script>
		<script src="http://digg.com/api/diggthis.js"></script>
	</div>
	<!-- digg -->
</div>

<div id="adrow-content" class="adsense x-hidden">
	<div id="adsense-top">
		<script type="text/javascript"><!--
		google_ad_client = "pub-2768521146228687";
		/* recordform top row wide */
		google_ad_slot = "3909046423";
		google_ad_width = 728;
		google_ad_height = 15;
		//-->
		</script>
		<script type="text/javascript"
		src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
		</script>
	</div>
</div>

<?//if("rowactions.extjs.eu"===$_SERVER["SERVER_NAME"])include("stats.php");?>
</body>
</html>
<!-- eof -->
