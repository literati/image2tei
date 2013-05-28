<?php
/**
 * process dirty OCR into line-numbered text; insert into a TEI template
 */
require_once('lib.php');

main($argv[1], $argv[2], $argv[3]);

/**
 * @param string $template tei template file path
 * @param string $src ocr files source directory
 * @param string $dst tei destination directory
 */
function main($template, $src, $dst){
    if(checkdir($src, false, false) and checkDir($dst, true, true)){
        foreach(array_diff(scandir($src), array('..', '.')) as $ocr){

            $outName = preg_replace('/ocr/', 'tei1', preg_replace('/.txt/', '.xml', $ocr));
            $content = markLines($src.$ocr);
            if(writeXML($dst.$outName, html_entity_decode(getTei($template, $content)))){
                echo sprintf("wrote %s\n",$dst.$outName);
            }
        }
    }
}

/**
 * 
 * @param string $in file path
 * @return string line-numbered at newlines
 */
function markLines($in){
    $out = "";
    $i =0;
    if(($handle = fopen($in,'r'))!=false){
        while (($buffer = fgets($handle, 4096)) !== false) {
            $out.="<lb n=\"{$i}\"/>".$buffer;
            $i++;
        }
        if (!feof($handle)) {
            echo "Error: unexpected fgets() fail\n";
        }
        fclose($handle);
    }
    
    return $out;
    
}

/**
 * 
 * @param string $template template file path
 * @param string $ocr tei body text content
 * @return string XML result o finserting $ocr into $template
 */
function getTei($template,$ocr){
    
    $xdoc   = new DOMDocument();
    $xdoc->load($template);

    $xpath  = new DOMXPath($xdoc);
    $b      = $xpath->query('//text/body');
    $insert = $b->item(0);
    
    $p = $xdoc->createElement('p', $ocr);
    
    $insert->appendChild($p);
    return $xdoc->saveXML();
}


/**
 * 
 * @param string $fname filename
 * @param string $contents file content
 * @return boolean false if file write fails
 */
function writeXML($fname, $contents){
//    die($contents);
    if(($handle = fopen($fname, 'w'))!=false){
        fwrite($handle, $contents);
    }else{
        return false;
    }
    fclose($handle);
    return true;
}





?>
