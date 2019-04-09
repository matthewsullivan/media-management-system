<?php

if (isset($_POST["bulletinID"])) {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/admin/inc/config.php");
    require_once(ROOT_DIR ."/model/bulletin.class.php");

    $bulletinItem = $_POST["bulletinID"];
    $bulletins = new Bulletin($bulletinItem);

    $bulletinItem = json_encode($bulletins->fetchBulletin($bulletinItem));
} else {
    $bulletinItem = "null";
}
        
?>