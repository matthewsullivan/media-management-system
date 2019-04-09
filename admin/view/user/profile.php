<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/admin/inc/config.php");
require_once(ROOT_DIR . "/model/user.class.php");

$details = \Fr\LS::getUser();

$name = $details["name"];
$username = $details["username"];
$email = $details["email"];
$userID = $details["id"];
$profileImg = $details["profile_img"];

$user = new User();

$trackCount = $user->numberUserUploadTracks($userID);

$titleWord = "You have uploaded $trackCount tracks. <i class=\"material-icons iconUp\">thumb_up</i> ";

if($trackCount == 1){
    $titleWord = "You have uploaded $trackCount track. <i class=\"material-icons iconUp\">thumb_up</i> ";
}else if($trackCount == 0){
    $titleWord = "You have uploaded $trackCount tracks. <i class=\"material-icons iconUp\">thumb_down</i> ";
}

if(!$profileImg){
    $imagesDir = '../../assets/img/temp/profile/';
    $images = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

    $profileImg = $images[array_rand($images)];
    
    $file = basename($profileImg);
            
    $profileImg = "../assets/img/temp/profile/$file";
    
    $deleteBtn = "";
} else {
    $deleteBtn = "<div class=\"deletePictureBtn\"><a href=\"#!\" id=\"deleteProfilePic\">Delete Picture</a></div>";
}

$registrationAge = \Fr\LS::joinedSince();

print("

<div class=\"col s12 m7 desktopProfile\">
    <div class=\"card horizontal\">
        <div class=\"card-image\" alt=\"Profile Image\">
            <img src=\"$profileImg\" alt=\"User Profile Image\">
            $deleteBtn
        </div>
        <div class=\"card-stacked\">
            <div class=\"card-content\">
                <h2>$name</h2>
                <p>Name  : $username</p>
                <p>Email : $email</p><br/>
                <p>You registered on this website <strong>$registrationAge</strong> ago.</p><br/>
                <p>$titleWord</p>
            </div>
            <div class=\"card-action\">
                <form action=\"#\" method=\"POST\" id=\"newProfilePicture\" name=\"fileInfo\" enctype=\"multipart/form-data\">
                    <div class=\"input-field col s6\">
                        <div class=\"form-group\">
                            <div class=\"file-field input-field\">
                                <div class=\"btn\">
                                    <span>Change Profile Picture</span>
                                    <input type=\"file\" name=\"profilePic\" id=\"profilePic\">
                                </div>
                                <div class=\"file-path-wrapper\">
                                    <input class=\"file-path validate\" type=\"text\" placeholder=\"Change Profile Picture\">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class=\"col s12 m7 mobileProfile\">
    <div class=\"card\">
        <div class=\"card-image\">
            <img src=\"$profileImg\" alt=\"User Profile Image\">
            $deleteBtn
        </div>
        <div class=\"card-content\">
            <h2>$name</h2>
            <p>Name  : $username</p>
            <p>Email : $email</p><br/>
            <p>You registered on this website <strong>$registrationAge</strong> ago.</p><br/>
            <p>$titleWord</p>
        </div>
        <div class=\"card-action\">
            <form action=\"#\" method=\"POST\" id=\"newProfilePicture\" name=\"fileInfo\" enctype=\"multipart/form-data\">
                <div class=\"input-field col s6\">
                    <div class=\"form-group\">
                        <div class=\"file-field input-field\">
                            <div class=\"btn\">
                                <span>Change Profile Picture</span>
                                <input type=\"file\" name=\"profilePic\" id=\"profilePic\">
                            </div>
                            <div class=\"file-path-wrapper\">
                                <input class=\"file-path validate\" type=\"text\" placeholder=\"Change Profile Picture\">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
");

?>