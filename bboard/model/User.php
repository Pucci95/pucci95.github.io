<?php

include_once('securitycheck.php');

class User {
    private $userId;
    private $lastPlacement;
    private $vote;

    public function __construct($targetUserId) {
        $error=__DIR__ . '/../db/error.json';
        $filePath = __DIR__ . '/../db/users.json';
        $jsonData = json_decode(file_get_contents($filePath), true);

        if ($jsonData !== null) {
            foreach ($jsonData as $user) {
                if (isset($user['userId']) && $user['userId'] === $targetUserId) {
                    $this->userId = $user['userId'];
                    $this->lastPlacement = $user['lastPlacement'];
                    $this->vote = $user['vote'];
                    break;
                }
            }
            if($this->userId == null){
                $new = $this->addUser($targetUserId,$filePath);
                if($new){
                    $this->userId = strval($targetUserId);
                    $this->lastPlacement = date("jS \of F Y, H:i:s", 0);
                    $this->vote = false;
                }else{
                    file_put_contents($error, "FATAL ERROR!!!!!!!!!!! ERROR 69 USER REGISTRATION DIDN'T WORK FUK");
                    die();
                };
            }
        }
    }

    public function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
        return;
    }

    public function getLastPlacement() {
        return $this->lastPlacement;
    }

    public function setLastPlacement($lastPlacement) {
        $this->lastPlacement = $lastPlacement;
        return;
    }

    public function getVote() {
        return $this->vote;
    }

    public function setVote($vote) {
        $this->vote = $vote;
        return;
    }

    // Methods
    public function addUser($id,$jsonFile) {
        $filePath = __DIR__ . '/../db/users.json';
        $jsonData = json_decode(file_get_contents($filePath), true);
    
        if ($jsonData !== null) {
            $newUserData = [
                'userId' => strval($id),
                'lastPlacement' => date("jS \of F Y, H:i:s", 0),
                'vote' => false
            ];
            $jsonData[] = $newUserData;
            $jsonString = json_encode($jsonData, JSON_PRETTY_PRINT);
            file_put_contents($jsonFile, $jsonString);

            return true;
        }
    
        return false;
    }

    public function updateUser(){
        $filePath = __DIR__ . '/../db/users.json';
        $jsonData = json_decode(file_get_contents($filePath), true);
        $id = $this->userId;

        if ($jsonData !== null) {
            $newUserData = [
                'userId' => $this->userId,
                'lastPlacement' => $this->lastPlacement,
                'vote' => $this->vote
            ];

            foreach ($jsonData as &$user) {
                if ($user['userId'] === $id) {
                    $user = array_merge($user, $newUserData);
                    break;
                }
            }

            $jsonString = json_encode($jsonData, JSON_PRETTY_PRINT);
            file_put_contents($filePath, $jsonString);

            return true;
        }

        return false; 
    }

    function resetVotes(){
        $this->vote = false;
        $jsonFile = __DIR__ . '/../db/users.json';
        $jsonData = json_decode(file_get_contents($jsonFile), true);
        if ($jsonData !== null) {
            foreach ($jsonData as &$user) {
                $user['vote'] = false;
            }
            $jsonString = json_encode($jsonData, JSON_PRETTY_PRINT);
            file_put_contents($jsonFile, $jsonString);
            return true;
        }
        return false;
    }

    public function checkReset($limit){
        $limit--;
        $error=__DIR__ . '/../db/error.json';
        $userFilePath = __DIR__ . '/../db/users.json';
        $jsonData = json_decode(file_get_contents($userFilePath), true);
        $votes=0;

        foreach ($jsonData as $user) {
            if ($user['vote'] == true) {
                $votes++;
            }
        }

        if($votes>$limit){
            $msgFilePath = __DIR__ . '/../db/messages.json';
            $file = fopen($msgFilePath, 'w');
            fwrite($file, "[\n\n]");
            fclose($file);
            $yum=$this->resetVotes();
            return "reset";
        }
        return $votes;
    }

    public function getAllMessages(){
        $msgFilePath = __DIR__ . '/../db/messages.json';
        $jsonData = json_decode(file_get_contents($msgFilePath), true);
        return $jsonData;
    }

}

?>