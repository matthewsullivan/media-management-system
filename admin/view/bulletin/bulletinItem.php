<?php 
    require_once($_SERVER['DOCUMENT_ROOT'] . "/admin/inc/config.php");
    require_once(ROOT_DIR ."/controller/bulletin/retrieveBulletin.php");

    $items = json_decode($bulletinItem, true);
     foreach($items as $key) { 
        $bulletin = $key['info']; 
        $user = $key['author'];
        $comments = $key['comments'];
         
        $image = $bulletin['media'];
        $bulletinID = $bulletin['id']; 
        $userImg = generateFakeImage($user['profile_img']);
        $author = $user['username'];

         foreach ($comments as $com){
             $user = $com['user'];
             $comment = $com['comment'];
             $userName = $user['username'];
             $commentUserImg = $user['profile_img'];
             
             $commentMsg = $comment['comment'];
             $commentDate = $comment['comment_date'];
             $commentID = $comment['id'];
             
             $commentUserImg = generateFakeImage($commentUserImg);
             
             $commentBox .= "
                <div class=\"comment mdl-color-text--grey-700\">
                    <header class=\"comment__header\">
                        <img src=\"$commentUserImg\" alt=\"Profile Image Of $userName\" class=\"comment__avatar\">
                        <div class=\"comment__author\">
                            <strong>$userName</strong>
                            <span>$commentDate</span>
                        </div>
                    </header>
                    <div class=\"comment__text\" data-comment=\"$commentID\">
                        $commentMsg
                    </div>
                    <div class=\"comment__actions bulletinCommentActions\">
                        <button class=\"mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon\">
                            <i class=\"material-icons\" role=\"presentation\">thumb_up</i><span class=\"visuallyhidden\">like comment</span>
                        </button>
                        <button class=\"mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon\">
                            <i class=\"material-icons\" role=\"presentation\">thumb_down</i><span class=\"visuallyhidden\">dislike comment</span>
                        </button>
                        <button class=\"mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon\">
                            <i class=\"material-icons\" role=\"presentation\">reply</i><span class=\"visuallyhidden\">Reply</span>
                        </button>
                    </div>
                </div>
             ";
         }
     }

    function generateFakeImage($userImg){
        if(!$userImg){

            $imagesDir = '../../assets/img/temp/profile/';
            $images = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

            $userImg = $images[array_rand($images)];

            $file = basename($userImg);

            $userImg = "../assets/img/temp/profile/$file";
        }
        
        return $userImg;
    }
?>
<style>
    .bulletin--bulletinitem .bulletin__item > .mdl-card .mdl-card__media {
      background-image: url(<?php echo $image;?>);
      height: 280px;
    }
</style>
<div class="bulletin bulletin--bulletinitem">
    <div class="bulletin__item">
        <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col">
            <div class="mdl-card__media mdl-color-text--grey-50">
                <h3><?php echo $bulletin['name']; ?></h3>
            </div>
            <div class="mdl-color-text--grey-700 mdl-card__supporting-text meta">
                <div class="minilogo"><img src="<?php echo $userImg ?>" alt="Profile Image of <?php echo $author; ?>" class="circle"></div>
                <div>
                    <strong><?php echo $author; ?></strong>
                    <span><?php echo $bulletin['creation_date']; ?></span>
                </div>
                <div class="section-spacer"></div>
            </div>
            <div class="mdl-color-text--grey-700 mdl-card__supporting-text">
                <?php echo $bulletin['content'] ?>
            </div>
            <div class="mdl-color-text--primary-contrast mdl-card__supporting-text comments">
                <form>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <div class="input-field col s12">
                          <textarea id="bulletinComment" class="materialize-textarea"></textarea>
                          <label for="bulletinComment">Get in on the discussion</label>
                        </div>
                    </div>
                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" data-id="<?php echo $bulletinID; ?>" id="addBulletinComment">
                        <i class="material-icons" role="presentation">check</i><span class="visuallyhidden">add comment</span>
                    </button>
                </form>
                <?php echo $commentBox; ?>
            </div>
        </div>
    </div>
</div>
<div class="appAction" data-view="bulletin/home">
    <button class="btn-floating btn-large waves-effect waves-light red left" id="goBackBtn">
        <i class="material-icons backBtn">trending_flat</i>
    </button>
</div>