<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . "/admin/inc/config.php");
    require_once(ROOT_DIR ."/controller/tracks/retrieveTracks.php");
   
    $allTracks = json_decode($trackListing, true);

    if($allTracks){        
        foreach($allTracks as $key) { 
            $track = $key['info']; 
            $user = $key['author'];
            
            $name = $track['title'];
            $uploadDate = $track['upload_date'];
            $trackID = $track['id'];
            $userName = $user['name'];
            $userImage = $user['profile_img'];
            
            if(!$userImage){
                $imagesDir = '../../assets/img/temp/profile/';
                $images = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                
                $userImage =  $images[array_rand($images)];
                
                $file = basename($userImage);
            
                $userImage = "../assets/img/temp/profile/$file";
            }

            $listTracks .= "
                <li class=\"collection-item avatar\">
                      <img src=\"$userImage\" alt=\"Profile Image of $userName\" class=\"circle\">
                      <span class=\"title\">
                        <a href=\"#!\" class=\"collection-item trackSelection\"data-id=\"$trackID\"><div class=\"shortText truncate\">$name</div></a> 
                     </span>
                     <div class=\"shortText\">
                          <p class=\"truncate subTxt\">Uploaded By: $userName <br> Date Uploaded : $uploadDate</p>
                     </div>
                     <i class=\"material-icons secondary-content\">trending_flat</i>
                </li>
            ";
        }
        
        print ("<ul class=\"collection\">
                <li class=\"collection-item\">
                      <a>
                        <form class=\"col s12\">
                            <div class=\"row\">
                                <div class=\"input-field col s12\">
                                    <i class=\"material-icons prefix\">spellcheck</i>
                                    <input id=\"filterString\" type=\"text\" class=\"validate\">
                                    <label for=\"filterString\">Search By Track Name</label>
                                </div>
                            </div>
                        </form>
                     </a>
                 </li>
                 <div id=\"allTracksCollection\">
                     $listTracks
                 </div>
            </ul>
           ");
    } else {
        echo "<ul class=\"collection\"><a href=\"#!\" class=\"collection-item\"data-id=\"\"> No Tracks Uploaded Yet. <i class=\"material-icons secondary-content\">info_outline</i></a></ul>";
    }

    echo '<a class="btn-floating btn-large waves-effect waves-light red right modal-trigger" href="#insertTrackModal" id="insertTrackModalTrigger"><i class="material-icons">add</i></a>';

?>
