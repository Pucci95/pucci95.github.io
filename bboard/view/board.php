<style>
    * {
        font-family: Arial, sans-serif;
        text-align: center;
        font-size: 48px;
        position: absolute;
    }
</style>

<?php
include_once('securitycheck.php');
$messages = new User($_GET['userid']);
$messages = $messages->getAllMessages();
foreach ($messages as $message) {
    $id = $message['id'];
    $content = htmlspecialchars($message['content']);
    echo "<style>.m$id {
        margin-left: {$message['xPos']}px;
        margin-top: {$message['yPos']}px;
        z-index: $id;
    }</style>";
    echo "<div class=\"m$id\">$content</div>";
}
?>