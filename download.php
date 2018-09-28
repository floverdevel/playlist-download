<?php

namespace Floverdevel\Download;

//////////////////////////////////////////////////
echo 'playlist download' . PHP_EOL;

$options = getopt('v::', ['from:', 'to:']);

if (!isset($options['from'])) {
    exit('vous devez spécifiez "--from"' . PHP_EOL);
}

if (!isset($options['to'])) {
    exit('vous devez spécifiez "--to"' . PHP_EOL);
}

$from = $options['from'];
$to = $options['to'];
$documentRoot = dirname($from);

//////////////////////////////////////////////////
echo 'downloading from "' . $from . '" to "' . $to . '"' . PHP_EOL;

//if (!is_file($from) || !is_readable($from)) {
//    exit('"' . $from . '" n\'est pas un fichier' . PHP_EOL);
//}

$playlist = fopen($from, 'r');
$files = [];

$playlistEntry = fgets($playlist);
while ($playlistEntry) {
    $playlistEntryTrimmed = trim($playlistEntry);
    if ($playlistEntryTrimmed === '') {
        echo 'S';
    } elseif ($playlistEntryTrimmed[0] === '#') {
        echo 'S';
    } else {
        echo '.';
        $files[] = $playlistEntryTrimmed;
    }

    $playlistEntry = fgets($playlist);
}


// var_export($files);

foreach ($files as $file) {
    download($documentRoot . DIRECTORY_SEPARATOR . $file, $to . DIRECTORY_SEPARATOR . $file);
}

//$urls = array_map(function($file) use ($documentRoot) {
//    return $documentRoot . DIRECTORY_SEPARATOR . $file;
//}, $files);

function download($source, $destination) {
    echo 'downloading "' . $source . '" to "' . $destination . '" ... ';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $source);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec ($ch);
    curl_close ($ch);
    $file = fopen($destination, "w+");
    fputs($file, $data);
    fclose($file);
    
    echo 'DONE' . PHP_EOL;
    
}
