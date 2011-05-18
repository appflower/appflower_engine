<?php $layout=new afExtjsDesktopLayout(); ?>


<div id="x-desktop">
    <a href="http://www.appflower.com" target="_blank" style="margin:5px; float:right;"><img src="/appFlowerPlugin/images/logo-transparent.png" /></a>

    <dl id="x-shortcuts">
        <?php $layout->getShortcuts();?>
    </dl>
</div>

<div id="ux-taskbar">
	<div id="ux-taskbar-start"></div>
	<div id="ux-taskbuttons-panel"></div>
	<div class="x-clear"></div>
</div>

<?php $layout->end(); ?>