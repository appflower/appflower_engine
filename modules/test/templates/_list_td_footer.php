<?php echo use_helper('Pagination') ?>

<tr>
  <th colspan="4">
  	<div style="float: left">
  		
  	</div>
  	
  <div style="float: right;">
    <?php echo pager_navigation($pager, $sf_context->getInstance()->getModuleName().'/'.$sf_context->getInstance()->getActionName())  ?>
   
  </div>
  </th>
</tr>