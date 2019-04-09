<?php

if (isset($_POST["trackID"])) {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/admin/inc/config.php");
    require_once(ROOT_DIR ."/model/tracks.class.php");

    $trackID = $_POST["trackID"];
    
    $track = new Tracks();

    echo json_encode($track->fetchTrack($trackID));
} else {
    echo "null";
}

?>