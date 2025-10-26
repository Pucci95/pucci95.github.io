<?php

// This file just makes sure NotSoBot is the only one that can enter the website

function checkMatchingStrings($input) {
    $firstSubstring = substr($input, 0, 13);
    $lastSubstring = substr($input, -13);
    return ($firstSubstring === "2602:fd50:102" && $lastSubstring === "feed:dad:beef");
}

if(!checkMatchingStrings($_SERVER['REMOTE_ADDR'])){
    echo "<a href='https://www.youtube.com/watch?v=2VXacYLcjGA'>click here!!!</a>";
    die();
}

?>