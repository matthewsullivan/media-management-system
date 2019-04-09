<?php

if (isset($_POST["bulletinID"]) && isset($_POST["comment"])) {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/admin/inc/config.php");
    require_once(ROOT_DIR ."/model/bulletin.class.php");

    $details = \Fr\LS::getUser();
    $userID = $details["id"];
    
    $bulletinItem = $_POST["bulletinID"];
    $comment = $_POST["comment"];
    $bulletins = new Bulletin($bulletinItem);

    $comment = json_encode($bulletins->insertComment($bulletinItem, $userID, $comment)); 
} else {
    $bulletinItem = "null";
}

?>