<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/admin/inc/config.php");
require_once(ROOT_DIR . "/controller/bulletin/retrieveBulletins.php");
require_once(ROOT_DIR . "/model/user.class.php");

$details = \Fr\LS::getUser();
$userID = $details["id"];

$allBulletins = json_decode($bulletinItems, true);

$users = new User();

foreach($allBulletins as $key) { 
    $id = $key['id'];
    $author = $key['author'];
    $media = $key['media'];
    $creationDate = $key['creation_date'];
    $name = $key['name'];
    $content = $key['content'];

    $user = $users->selectUser($author);

    foreach($user as $key){
        $authorImg = $key['profile_img'];
        $authorName = $key['username'];
    }

    if(!$authorImg){
        $imagesDir = '../../assets/img/temp/profile/';
        $images = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

        $authorImg = $images[array_rand($images)]; 
        
        $file = basename($authorImg);
            
        $authorImg = "../assets/img/temp/profile/$file";
    }

    if($userID == $author){
       $editor = "<a class=\"btn-floating halfway-fab waves-effect waves-light red right editBulletinPost\" data-activates=\"slide-out-bulletin\"><i class=\"material-icons\">edit</i></a>";
    } else {
        $editor = "<a class=\"avatar btn-floating halfway-fab waves-effect waves-light red right editBulletinPost tooltipped\" data-position=\"left\" data-delay=\"50\" data-tooltip=\"Posted by $authorName\">
                        <img src=\"$authorImg\" alt=\"Profile Image of $authorName\" class=\"circle\">
                   </a>";
    }

    $bulletinPosts .="
        <div class=\"col s12 m4 l4\">
            <div class=\"card bulletinCard\">
                <div class=\"card-image\">
                    <img src=\"$media\" alt=\"Bulletin Board Media For $name\"class=\"bulletinImg\">
                    <span class=\"card-title\">$name</span>
                    $editor
                </div>
                <div class=\"card-content\">
                    <p>$content</p>
                </div>
                <div class=\"card-action\">
                    <a href=\"#!\" class=\"bulletinItem\" data-id=\"$id\">View Full</a>
                </div>
            </div>
        </div>
    ";
}

echo "<div class=\"row\">$bulletinPosts</div>";
echo '<a class="btn-floating btn-large waves-effect waves-light red right modal-trigger" href="#insertBulletinItem" id="insertBulletinItemTrigger"><i class="material-icons">add</i></a>';

?>


