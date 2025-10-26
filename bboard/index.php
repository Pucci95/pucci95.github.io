<style>
    * {
        font-size:48px;
    }
</style>

<?php

include_once('securitycheck.php');
include_once('model/Message.php');
include_once('model/User.php');
include_once('model/Functions.php'); // All index.php's functions, idk why i made it a separate file lol, function explanations explained in those comments

$error=__DIR__ . '/db/error.json';
$rH=__DIR__ . '/db/requestHandler.json';
$GLOBALS['requestNum'] = handleRequest($rH);
$msg = getMessage();

if(commandReciever($error,$msg)){
    include_once('view/board.php');
}else{
    file_put_contents($error, "Unknown error, failed to recieve command");
}

?>