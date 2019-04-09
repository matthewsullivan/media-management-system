<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/admin/inc/config.php");
require_once(ROOT_DIR ."/model/tracks.class.php");

$tracks = new Tracks();
$trackListing = json_encode($tracks->fetchAllTracks());

if (isset($_POST["filter"])){
    echo $trackListing;  
}

?>