<style>
.table-notification{
	width:100%;	
	border:1px solid #ccc;
	background-color:#fff;
}
.table-notification .label{
	color:#000000;
	background-color:#e5e5e5;
	padding:5px;
	width:80px;	
}
.table-notification .content{
	color:#000000;
	background-color:#eee;
	padding:5px;	
}
</style>
<div style="font-weight:bold;padding:5px; background-color: #ddd;border:1px solid #ccc;"><?php echo $obj->getTitle();?></div>
<table class="table-notification" cellspacing=1>
	<tbody>		
		<tr><td class="label" valign="top">Type</td><td class="content"><?php echo $obj->getType();?></td></tr>
		<tr><td class="label" valign="top">Category</td><td class="content"><?php echo Notification::getCategoryName($obj->getCategory());?></td></tr>
		<tr><td class="label" valign="top">Message</td><td class="content"><?php echo html_entity_decode(afNotificationPeer::getDecoratedMessage($obj));?></td></tr>
		<tr><td class="label" valign="top">Details</td><td class="content"><?php echo html_entity_decode($obj->getLog());?></td></tr>
	</tbody>
</table>
