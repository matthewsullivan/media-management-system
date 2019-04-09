<?php

if (isset($_POST["trackID"])) {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/admin/inc/config.php");
    require_once(ROOT_DIR . "/model/tracks.class.php");

    $trackID = $_POST["trackID"];
    $track = new Tracks();

    $track->deleteTrack($trackID);
}

?>