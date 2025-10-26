<?php

include_once('securitycheck.php');

class Message {
    private $id;
    private $xPos;
    private $yPos;
    private $content;

    public function __construct($x,$y,$msg,$userId) {
        $error=__DIR__ . '/../db/error.json';
        $filePath = __DIR__ . '/../db/messages.json';
        $jsonData = json_decode(file_get_contents($filePath), true);

        if ($jsonData !== null) {
            $GLOBALS['user'] = new User($userId);
            $currentTime = strtotime(date('Y-m-d H:i:s'));
            $userTime = strtotime($GLOBALS['user']->getLastPlacement());
            $timeDiff = $currentTime - $userTime;
            if ($timeDiff < 10) {
                file_put_contents($error, "You must wait ".(10-$timeDiff)." more seconds before placing another message");
                return;
            }else{
                $yum=$GLOBALS['user']->setLastPlacement(date('Y-m-d H:i:s'));
                $new = $this->addMessage($x,$y,$msg,$filePath);
                if($new){
                    $this->xPos= $x;
                    $this->yPos = $y;
                    $this->content = strval($msg);
                }else{
                    file_put_contents($error, "FATAL ERROR!!!!!!!!!!! ERROR 420 MESSAGE UPLOAD FAIL @GDNACHO");
                    die();
                };
            }
            $yum=$GLOBALS['user']->updateUser();
        }
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return;
    }

    public function getXPos() {
        return $this->xPos;
    }

    public function setXPos($xPos) {
        $this->xPos = $xPos;
        return;
    }

    public function getYPos() {
        return $this->yPos;
    }

    public function setYPos($yPos) {
        $this->yPos = $yPos;
        return;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
        return;
    }

    // Methods
    public function addMessage($x,$y,$msg) {
        $error=__DIR__ . '/../db/error.json';
        $filePath = __DIR__ . '/../db/messages.json';
        $jsonData = json_decode(file_get_contents($filePath), true);
    
        if ($jsonData !== null) {
            $biggestId=0;
            foreach ($jsonData as $message) {
                if ($message['id']>$biggestId) {
                    $biggestId = $message['id'];
                }
            }
            $this->id = $biggestId+1;
            $newMsgData = [
                'id' => $this->id,
                'xPos' => $x,
                'yPos' => $y,
                'content' => strval($msg)
            ];
            $jsonData[] = $newMsgData;
            $jsonString = json_encode($jsonData, JSON_PRETTY_PRINT);
            file_put_contents($filePath, $jsonString);
            file_put_contents($error, "Message added");
            return true;
        }
        return false;
    }

}

?>