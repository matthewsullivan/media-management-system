<?php

if (isset($_POST["trackID"]) && isset($_POST["title"]) && isset($_POST["lyrics"])) {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/admin/inc/config.php");
    require_once(ROOT_DIR ."/model/tracks.class.php");

    $tracks = new Tracks();
    
    $trackID = $_POST["trackID"];
    $title = $_POST["title"];
    $lyrics = $_POST["lyrics"];
    
    $tracks->updateTrack($trackID, $title, $lyrics);
    
    echo $tracks->updateTrack($trackID, $title, $lyrics);
} else {  
    echo null;
}



?>