<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/admin/inc/config.php");
require_once(ROOT_DIR . "/model/user.class.php");

$details = \Fr\LS::getUser();
$userID = $details["id"];
$user = new User();

$removalMessage = $user->removeProfilePicture($userID);

echo $removalMessage;

?>