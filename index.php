<?php

require "./inc/config.php";

if (isset($_POST['action_login'])){
	$identification = $_POST['login'];
	$password = $_POST['password'];
    
	if ($identification == "" || $password == ""){
		$msg = array("Error", "Your Username OR Password is incorrect.");
	} else {
		$login = \Fr\LS::login($identification, $password, isset($_POST['remember_me']));
        
		if ($login === false){
			$msg = array("Error", "Your Username OR Password is incorrect.");
		} else if (is_array($login) && $login['status'] == "blocked"){
			$msg = array("Error", "Too many login attempts. You can attempt to login after ". $login['minutes'] ." minutes (". $login['seconds'] ." seconds)");
            
            $enable = "disabled";
		}
	}
}

require_once("template/header.php");

$imagesDir = './assets/img/temp/profile/';
$images = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

$profileImg = $images[array_rand($images)];
    
?>
	
<div class="row">
    <div class="col s12">
        <?php
            $enable = "";

            if (isset($msg)){
                $problem =  "<h2>{$msg[0]}</h2><p>{$msg[1]}</p>";
            }
        ?>
        <div class="col s12 m2 l4"></div>
        <div class="col s12 m8 l4 z-depth-1 account-wall">
            <?php echo "<img class=\"profile-img\" src=\"$profileImg\" alt=\"Login Image\">"?>
            <form class="form-signin" action="index.php" method="POST">
                <input type="text" class="form-control" name="login" placeholder="Username OR Email" required autofocus>
                <input type="password" class="form-control" name="password" placeholder="Password" required>
                <?php echo $problem; ?>
                <button class="waves-effect waves-light btn-large" type="submit" name="action_login" <?php echo $enable; ?>> Sign in</button>
            </form>
        </div>
        <div class="col s12 m2 l4"></div>
    </div>
</div>
    
