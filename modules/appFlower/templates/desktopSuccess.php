<?php $layout=new afExtjsDesktopLayout(); ?>

<style>
body {
	background:<?php echo $layout->getBackgroundColor() ?> url(<?php echo $layout->getBackgroundImage() ?>) no-repeat bottom right;
}
</style>


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