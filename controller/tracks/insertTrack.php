<?php

if (isset($_FILES["fileup"]) && 
    isset($_POST["title"])   && 
    isset($_POST["lyrics"])  &&
    isset($_POST["uploadContent"])) {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/admin/inc/config.php");
    require_once(ROOT_DIR . "/model/tracks.class.php");
    
    $details = \Fr\LS::getUser();

    $userID = $details["id"];   
    
    $track = new Tracks();
    
    $title = $_POST['title'];
    $lyrics = $_POST['lyrics'];
    $trackUpload = $_FILES['fileup'];

    echo $track->insertTrack($trackUpload, $title, $lyrics, $userID);
} else {
    echo "null";
}

?>