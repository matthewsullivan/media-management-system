<?php

if (isset($_POST["uploadContent"])) {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/admin/inc/config.php");
    require_once(ROOT_DIR ."/model/user.class.php");

    $details = \Fr\LS::getUser();
    
    $userID = $details["id"];
    $image = $_FILES['profilePic']; 
    
    $user = new User();
    
    $userPhotoResponse = $user->profilePicture($userID, $image);

    echo $userPhotoResponse;
} else {
    echo null;
}

?>