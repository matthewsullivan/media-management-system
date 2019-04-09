<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . "/admin/inc/config.php");
    require_once(ROOT_DIR ."/model/bulletin.class.php");

    $bulletins = new Bulletin();
    $bulletinItems = json_encode($bulletins->fetchAllBulletinItems());
 
?>