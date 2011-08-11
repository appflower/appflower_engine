<?php $layout=new afExtjsDesktopLayout(); ?>


<div id="x-desktop">    
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