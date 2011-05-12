
<tbody>
	<?php foreach ($pager->getResults() as $line): ?>
	<tr class="color<?php echo $line['0'] ?>-even" style="border-bottom: 1px dotted;">	    
		<td>
			<?php echo $line['0'] ?>&nbsp;
		</td>
		<td style="white-space: nowrap;">
	    <?php echo $line['1']; ?>
		</td>
		<td style="padding: 0 5 0 0; width: 120px;"><?php echo $line['2'] ?></td>
		<td><?php echo $line['3'] ?></td>
	</tr>
	<?php endforeach ?>
	</tbody>