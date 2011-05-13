#!/usr/bin/php
<?php
/**
 * This script will take config/cactus.xml file and parse all CSS files
 * For each of them script will run image_inliner.php script
 * It expects image_inliner.php script to reside in the same directory as this script file
 * Until you provide argument like 'overwrite' script will write resulting files as ORIGINAL_FILE.inlined
 */

$overwrite = trim(@$argv[1] == 'overwrite') ? true : false;

$cactusConfigPath = dirname(__DIR__).'/config/cactus.xml';
if (!is_readable($cactusConfigPath)) {
    echo "Could not read Cactus config file: '$cactusConfigPath'\n";
    exit;
}

$cactusConfig = simplexml_load_file($cactusConfigPath);
$rootDir = dirname(__DIR__).'/web';

foreach ($cactusConfig->css->needles->needle->files->file as $file) {
    $cssFile = (string)$file;
    $cmd = __DIR__."/image_inliner.php {$rootDir}{$cssFile} $rootDir > {$rootDir}{$cssFile}.inlined";
    if ($overwrite) {
        $cmd .="; mv {$rootDir}{$cssFile}.inlined {$rootDir}{$cssFile}";
    }

    passthru($cmd, $returnCode);
}