#!/usr/bin/php
<?php
/**
 * This script is a tool that can parse any given CSS file and inline images referenced inside
 * for example instead of such line:
 * .icon-star-grey{background:url("/images/famfamfam/star_grey.png") no-repeat center left !important;}
 * you will get:
 * .icon-star-grey{background:url("data:image/png;base64,iVBORw0KGgoAAAANSUhEU...............") no-repeat center left !important;}
 * 
 * Script will output to STDOUT CSS file content with inlined images
 * Errors are sended to STDERR
 * 
 * WARNING:
 * this script can't handle url(...) definitions if there is more than one such definition in one line
 * 
 * Example usage:
 * PATH_TO/image_inliner.php web/css/main.css > web/css/main_inlined.css
 * IF there are images with absolute paths you should provide it as second argument
 * PATH_TO/image_inliner.php web/css/main.css web > web/css/main_inlined.css
 */

$cssFile = trim(@$argv[1]);
$webRootPath = trim(@$argv[2]);

if ($cssFile == '') {
    die("Provide CSS file path as first argument\n");
}

$cssFileRealPath = realpath(dirname($cssFile));
$cssFileContent = file_get_contents($cssFile);

$lines = explode("\n", $cssFileContent);

foreach ($lines as $index => &$line) {
    $pos = strpos($line, 'url(');
    if (!is_numeric($pos)) {
        continue;
    }
    // I had troubles referencing ' and " together in regular expression pattern so here is a workaround :)
    $line = str_replace('\'', '"', $line);
    
    $pos = strpos($line, 'data:image');
    if (is_numeric($pos)) {
        continue;
    }
    
    $pattern = '/url\("?(.*)"?\)/';
    $matches = array();
    if ( !preg_match($pattern, $line, $matches)) {
        die("preg_match returned an error for: '$line'"."\n");
    }
    $imagePath = $matches[1];
    // another lack of knowledge about regexp's from my side :|
    $imagePath = str_replace('"', '', $imagePath);
    
    if (@$imagePath[0] != '/') {
        $imageAbsolutePath = realpath($cssFileRealPath.'/'.$imagePath);

        if (!file_exists($imageAbsolutePath)) {
            fwrite(STDERR, "File '$imagePath' does not exist :(\n");
            continue;
        }
    } else {
        if ($webRootPath == '') {
            fwrite(STDERR, "File '$imagePath' needs web base directory provided by YOU :) - please provide it as second argument - skipping\n");
            continue;
        } else {
            $imageAbsolutePath = realpath($webRootPath.'/'.$imagePath);

            if (!file_exists($imageAbsolutePath)) {
                fwrite(STDERR, "File '$imagePath' does not exist :(\n");
                continue;
            }
        }
    }

    $mimeType = mime_content_type($imageAbsolutePath);
    
    $inlineImage = base64_encode(file_get_contents($imageAbsolutePath));
    $line = preg_replace($pattern, "url(\"data:$mimeType;base64,$inlineImage\")", $line);
}

echo join("\n", $lines);