<?php

/*$files = array(
	'../source/NS.js',
	'../source/AbstractTreeStore.js',
	'../source/AdjacencyListStore.js ',
	'../source/NestedSetStore.js',
	'../source/GridView.js',
	'../source/GridPanel.js',
	'../source/PagingToolbar.js',
	'../source/XType.js'
);*/

$yui_path = "C:\Program Files\yuicompressor\build\yuicompressor-2.3.5.jar";

/*$output = '';

foreach ($files as $file) {
	$output .= file_get_contents($file) . PHP_EOL . PHP_EOL;
}

file_put_contents('../TreeGrid.js', $output);*/

if (isset($yui_path)) {
	exec(
		'java -jar "'.$yui_path.'" --type js --charset utf-8 --nomunge -o ..\TreeGrid.packed.js ..\TreeGrid.js'
	);
}

?>