<?php
require_once('lib.php');
/**
 * This script acts on a directory of images and applies tesseract to each.
 * @var string image source directory
 * @var string ocr destination directory
 * @var string file base name
 * @var string this will be appended to the text file base name
 */

if(count($argv)<7){
    printUsage($argv);
    die();
}

$src    = $argv[1];
$dest   = $argv[2];

$nameParts = array(
    'mag'    => $argv[3],
    'date'   => $argv[4],
    'inst'   => $argv[5],
    'genre'  => $argv[6],
    'append' => $argv[7]
);

//main
if(checkdir($src, false, false) and checkDir($dest, true, true)){
    foreach(array_diff(scandir($src), array('..', '.')) as $f){
        $out = hillToAPC($f, $nameParts);
        
        exec("tesseract ".$src.$f." ./".$dest.  $out);
        echo sprintf("finshed processing %s\n",$out);
        
    }
}

function hillToAPC($filename, $npts){
    $prefix     = $npts['mag'].$npts['date'].$npts['inst'];
    $suffix     = $npts['genre'].'-'.$npts['append'];
    $origFname  = preg_replace('/\.\w+/', '', $filename);
    $pageNum    = ltrim(substr($origFname, strlen($origFname)-3),0);
    return  strtolower($prefix.'p'.$pageNum.$suffix);
}







function printUsage($argv){
    echo sprintf("USAGE: %s src dest mag-code date inst genre appendation\n", $argv[0]);
}






?>
