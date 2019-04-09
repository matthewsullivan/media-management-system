<?php
    
    require_once($_SERVER['DOCUMENT_ROOT'] . "/admin/inc/config.php");
        
	if( isset($_POST['newName']) ){

		$_POST['newName'] = $_POST['newName'] == "" ? "Dude" : $_POST['newName'];

		\Fr\LS::updateUser(array(

			"name" => $_POST['newName']

		));

	}

	$details = \Fr\LS::getUser();
	$username = $details["username"];

	require_once(ROOT_DIR ."/template/header.php");

	print("
			<nav class=\"mainNav\">
				<header class=\"nav-wrapper actionHeader\">
                    <a href=\"#\" data-activates=\"slide-out\" class=\"button-collapse\">
                            <img src=\"$folder/assets/img/menu-icon@2x.png\" alt=\"Menu Icon\" class=\"menuIcon\"/>
                    </a>
                    <h1>Welcome, $username</h1>
                    <ul id=\"mainNavDropdown\" class=\"dropdown-content\">
                      <li class=\"appAction\" data-view=\"user/profile\" ><a href=\"#!\">Settings</a></li>
                      <li><a href=\"../view/user/logout.php\">Logout</a></li>
                    </ul>
                    
                    <ul class=\"right hide-on-med-and-down\">
                        <!-- Dropdown Trigger -->
                        <li>
                            <a class=\"dropdown-button profileTrigger\" href=\"#!\" data-activates=\"mainNavDropdown\">Profile<i class=\"material-icons right\">arrow_drop_down</i></a>
                        </li>
                    </ul>
                  
				</header>
			</nav>
	");
?>
<div id="wrapper">
    <?php require_once("../template/sidebar.php") ?>
    <!-- Main Content Holder. All data will go within mainWindow -->
    <section class="mainWindow" id="mainWindow"></section>
</div>

<!-- Modal Bulleting Item Trigger -->
<div id="insertBulletinItem" class="modal modal-fixed-footer fullScreenOverlay">
	<div class="modal-content">
		<div class="panel panel-default">
            <form action="#" method="POST" id="newBulletinForm" name="fileInfo" enctype="multipart/form-data">
				<div class="panel-heading panel-heading-add">
                    <div class="load-indicator">
                        <p>Creating Item: <i>this box will close when the upload is complete.</i></p>
                        <div class="progress">
                              <div class="indeterminate"></div>
                        </div>
                    </div>
				    <h3 class="panel-title modalPadding">Add New Bulletin Item</h3>
				</div>
				<div class="panel-body">
                    <div class="row col s12 modalPadding">
                        <div class="form-group col s6">
                            <label>Bulletin Title</label>
                            <input class="form-control" id="bulletinTitleField" type="text" name="title" placeholder="Bulletin Name">
                        </div>
                        <div class="input-field col s6">
                            <div class="form-group">
                                <div class="file-field input-field">
                                    <div class="btn">
                                        <span>Bulletin Image</span>
                                        <input type="file" name="bulletinMedia">
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path validate" type="text" placeholder="Bulletin Image">
                                    </div>
                                  </div>
                            </div>
                        </div>
                    </div>
				    <div class="form-group">
                        <label>Bulletin Information</label>
                        <textarea class="form-control" id="bulletinContent" rows="10" placeholder="Bulletin Information" name="content"></textarea>
				    </div>
				</div>
            </form>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat" id="insertBulletinItemClose">Close</a>
		<button type="submit" id="uploadBulletinItem" class="btn btn-default modal-action waves-effect waves-green btn-flat " name="add">Add</button>
	</div>
</div>

<!-- Modal Upload Track Trigger -->
<div id="insertTrackModal" class="modal modal-fixed-footer fullScreenOverlay">
	<div class="modal-content">
		<div class="panel panel-default">
            <form action="#" method="POST" id="newTrackForm" name="fileInfo" enctype="multipart/form-data">
				<div class="panel-heading panel-heading-add">
                    <div class="load-indicator">
                        <p>Uploading Track: <i>this box will close when the upload is complete.</i></p>
                        <div class="progress">
                              <div class="indeterminate"></div>
                        </div>
                    </div>
				    <h3 class="panel-title modalPadding">Add New Track</h3>
				</div>
				<div class="panel-body">
                        <div class="row col s12 modalPadding">
                            <div class="form-group col s6">
                                <label>Track Title</label>
                                <input class="form-control" id="titleField" type="text" name="title" placeholder="Track Name">
                            </div>
                            <div class="input-field col s6">
                                <div class="form-group">
                                    <div class="file-field input-field">
                                        <div class="btn">
                                            <span>File</span>
                                            <input type="file" name="fileup">
                                        </div>
                                        <div class="file-path-wrapper">
                                            <input class="file-path validate" type="text" placeholder="Track Music">
                                        </div>
                                      </div>
                                </div>
                            </div>
                        </div>
				    <div class="form-group">
                        <label>Lyrics &amp; Chords</label>
                        <textarea class="form-control" id="lyricsField" rows="10" placeholder="Track Name" name="content"></textarea>
				    </div>
				</div>
            </form>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat" id="insertTrackModalClose">Close</a>
		<button type="submit" id="uploadTrack" class="btn btn-default modal-action waves-effect waves-green btn-flat " name="add">Add</button>
	</div>
</div>
<!-- Modal Edit Track -->              
<div id="editTrackModal" class="modal modal-fixed-footer fullScreenOverlay">
	<div class="modal-content">
		<div class="panel panel-default">
            <form action="#" method="POST" id="editTrackForm" name="fileInfo" enctype="multipart/form-data">
				<div class="panel-heading panel-heading-add">
                    <div class="load-indicator">
                        <p>Uploading Track: <i>this box will close when the upload has complete.</i></p>
                        <div class="progress">
                              <div class="indeterminate"></div>
                        </div>
                    </div>
				    <h3 class="panel-title modalPadding">Edit Track</h3>
				</div>
				<div class="panel-body">
                        <div class="row col s12 modalPadding">
                            <div class="form-group col s12">
                                <label>Track Title</label>
                                <input class="form-control" id="titleUpdateField" type="text" name="title" value="" placeholder="Track Name">
                            </div>
                        </div>
				    <div class="form-group">
                        <label>Lyrics &amp; Chords</label>
                        <textarea class="form-control" id="updateLyricsField" rows="10" placeholder="Track Lyrics" name="content"></textarea>
				    </div>
                    <input type="hidden" id="trackUpdateID" value="">
				</div>
            </form>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat mobileSmall" id="insertTrackModalClose">Close</a>
		<button type="submit" id="updateTrack" class="btn btn-default modal-action waves-effect waves-green btn-flat mobileSmall" name="update">Update</button>
        <button type="submit" id="deleteTrack" class="btn btn-default modal-action waves-effect waves-green btn-flat mobileSmall" name="delete">Delete</button>
	</div>
</div>
<!-- /#wrapper -->
<?php require_once("../template/footer.php"); ?>
