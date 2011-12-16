<?php $layout=new afExtjsDesktopLayout(); ?>

<style>
body {
    <?php if ($layout->getBackgroundImage() != 'none') : ?>
        background:<?php echo $layout->getBackgroundColor() ?> url(<?php echo $layout->getBackgroundImage() ?>) repeat bottom right;
    <?php else: ?>
        background-color:<?php echo $layout->getBackgroundColor() ?>;
    <?php endif; ?>
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