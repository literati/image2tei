<?php
function checkDir($d, $needEmpty=true, $create=false){
    if(!is_dir($d)){
        if($create){
            echo sprintf("couldn't find directory %s - try to create it\n", $d);
            if($create and !mkdir($d, 0755)){
                die(sprintf("could not create directory %s\n", $d));
            }
        }else{
            echo sprintf("couldn't find directory %s\n", $d);
        }

    }
    if(!is_readable($d)){
        die(sprintf("directory %s is not readable\n", $d));
    }
    if(!is_writable($d)){
        die(sprintf("directory %s is not writable\n", $d));
    }
    if($needEmpty and !checkIsEmpty($d)){
        die(sprintf("directory %s is not empty\n", $d));
    }elseif(!$needEmpty and checkIsEmpty($d)){
        die(sprintf("directory %s is empty\n", $d));
    }
    return true;
}

function checkIsEmpty($d){
    if((count(scandir($d)) == 2)){
        return true;
    }else{
        return false;
    }
}
?>
