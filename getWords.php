<?php
$line = "7 as evidence of the justice of this remark. Her l\i°¢°ry'
";

preg_match_all('/\b([a-zA-Z0-9]+)\b/',$line,$words);


$wds = array_shift($words);
$tag = 'en_US';
$r = enchant_broker_init();

$m = "";
 if (enchant_broker_dict_exists($r,$tag)) {
    $d = enchant_broker_request_dict($r, $tag);
    $xdoc = new DOMDocument();
    foreach($wds as $w){
        
        
        if(!enchant_dict_check($d, $w)){
//            $m .= "<choose><sic>{$w}</sic><corrected><corrected/></choose>";
            $q  = $xdoc->createDocumentFragment();
            $r  = $xdoc->createCDATASection($w);
            $q->appendChild($r);
            $xdoc->appendChild($q);    
            
        }else{
//            $q  = $xdoc->createDocumentFragment();
//            $r  = $xdoc->createTextNode($w);
//            $q->appendChild($r);
//            $xdoc->appendChild($q);
            $m .= $w." ";
            
        }
        
    }
}
echo $xdoc->saveXML();

?>
