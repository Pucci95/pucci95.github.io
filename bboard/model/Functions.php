<?php

include_once('securitycheck.php');

// Increments a number in db/requestHandler.json because notsobot's iscript enters websites twice, for some reason, so you gotta make sure code doesn't repeat
function handleRequest($fileName) {
    $currentNumber = (int)file_get_contents($fileName);
    $newNumber = $currentNumber + 1;
    file_put_contents($fileName, $newNumber);
    return $newNumber;
}

// Gets user's message
function getMessage(){
    $msg='';
    if(isset($_GET['x'])){
        $argEx=true;
        for($i=0;$argEx;$i++){
            if(isset($_GET['msg'.$i])){
                $msg = $msg.$_GET['msg'.$i]." ";
            }else{
                $argEx=false;
            }
        }
        return rtrim($msg);
    }
    return false;
}

// Gets user's command
function commandReciever($error,$msg){
    if($GLOBALS['requestNum']%2!=0){ // Checks if notsobot is on its first visit to prevent running the code twice
        if($_GET['action']=="empty"){
            file_put_contents($error, "Type  *-t .bboard help*  for help");
            return true;
        }
        if(strtolower($_GET['action'])=="write"){
            $GLOBALS['message'] = new Message($_GET['x'],$_GET['y'],strval($msg),$_GET['userid']);
            return true;
        }
        if(strtolower($_GET['action'])=="votereset"){
            $GLOBALS['user'] = new User($_GET['userid']);
            $limit = 5;
            
            if($GLOBALS['user']->getVote()){
                $GLOBALS['user']->setVote(false);
                $yum=$GLOBALS['user']->updateUser();
                file_put_contents($error, $_GET['name']." revoked their vote to clear the board (".$GLOBALS['user']->checkReset($limit)."/".$limit.")");
            }else{
                $GLOBALS['user']->setVote(true);
                $yum=$GLOBALS['user']->updateUser();
                if($GLOBALS['user']->checkReset($limit)=="reset"){
                    file_put_contents($error, $_GET['name']." has voted to clear the board (".$limit."/".$limit.")\nVote passed, resetting...");
                }else{
                    file_put_contents($error, $_GET['name']." has voted to clear the board (".$GLOBALS['user']->checkReset($limit)."/".$limit.")");
                }
            }
            return true;
        }
        if($_GET['action']!=null){
            file_put_contents($error, "Unknown command, type  *-t .bboard help*  for help");
            return true;
        }
        return false;
    }
    return true;
}

?>