#!/usr/bin/env php
<?php
error_reporting(E_ALL);
ini_set('log_errors', true);
ini_set('display_errors', true);

if(count($argv) < 2) {
    file_put_contents('php://stderr',
        'Usage: WEBDIR=/ ./markit.php FILENAME.txt ..
Generates FILENAME.html files.
');
    exit(1);
}

$base = dirname(__FILE__).'/../';
$webdir = getenv('WEBDIR');
if(!$webdir) {
    $webdir = "";
}


function renderPartial($filename) {
    ob_start();
    require($filename);
    return ob_get_clean();
}

function static_path($filename) {
    global $webdir;
    return $webdir.$filename;
}

$header = renderPartial($base.'template/header.inc.php');
$footer = renderPartial($base.'template/footer.inc.php');

require_once($base."util/markdown.php");

for($i = 1; $i < count($argv); $i++) {
    $filename = $argv[$i];
    $input = file_get_contents($filename);
    $filename=explode('.',$filename);
    unset($filename[count($filename)-1]);
    $filename=implode('.',$filename);    
    if($input !== false) {
        $html = Markdown($input);
        echo "generating $filename.html\n";
        file_put_contents($filename.'.html', $header.$html.$footer);
    }
}

