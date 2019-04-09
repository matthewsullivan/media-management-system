<?php

if (isset($_POST["uploadContent"])) {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/admin/inc/config.php");
    require_once(ROOT_DIR . "/model/bulletin.class.php");

    $details = \Fr\LS::getUser();
    
    $userID = $details["id"];
    $image = $_FILES['bulletinMedia']; 
    $name = $_POST['name'];
    $content = $_POST['content'];
    
    $bulletin = new Bulletin();
    
    $bulletinBoardResponse = $bulletin->newBulletinItem($userID, $image, $name, $content);
    
    echo $bulletinBoardResponse;
} else {
    echo null;
}

?>