/*
 * @brief Main document ready function
 */
$(document).ready(function () {

    //Initialize collapsible
    $(".collapsible").collapsible();
    $(".dropdown-button").dropdown();
    
    //Side bar menu and button collapsing
    if ($(window).width() < 995) {
        //Collapse side bar on desktop nav click
        $('.button-collapse').sideNav({
            menuWidth: 300,
            closeOnClick: true
        });
    } else {
        //Do not collapse side bar on desktop nav click
        $('.button-collapse').sideNav({
            menuWidth: 300,
            closeOnClick: false
        });
    }

    /*
     * Main AJAX Handlers
     */
    InitializeFirstScreen();

    $(document).on('click', '.appAction', function (e) {
        e.preventDefault();

        HandleViewSelection($(this), '1');
    });

    $(document).on('click', '#uploadTrack', function (e) {
        e.preventDefault();

        LoadNewTrack();
    });

    $(document).on('click', '.trackSelection', function (e) {
        e.preventDefault();

        let trackID = $(this).data('id');
        
        FetchTrack(trackID);
    });

    $(document).on('click', '#updateTrack', function (e) {
        e.preventDefault();

        UpdateTrack();
    });
    
    $(document).on('click', '#deleteTrack', function (e) {
        e.preventDefault();

        DeleteTrack();
    });
    
    $(document).on('change', '#profilePic', function (e) {
        UpdateUserPhoto();
    });
    
    $(document).on('click', '#deleteProfilePic', function (e) {
        e.preventDefault();

        DeleteProfilePicture();
    });
    
    $(document).on("paste keyup", '#filterString', function() {
        StringFilter($(this).val());
    });
    
    $(document).on('click', '.modal-trigger', function (){
        ScrollAppWindow();
    });
    
    $(document).on('click', '#uploadBulletinItem', function (e) {
        e.preventDefault();
    
        AddBulletinItem();
    });
    
    $(document).on('click', '.bulletinItem', function (e) {
        e.preventDefault();
        
        let bulletinID = $(this).attr("data-id");
    
        RetrieveBulletinItem(bulletinID);
    });
    
    $(document).on('click', '#addBulletinComment', function (e) {
        e.preventDefault();
        
        let bulletinID = $(this).attr("data-id");
        let comment = $("#bulletinComment").val();
        
        AddBulletinComment(bulletinID, comment);
    });
    
    /*
     * Initial App View Loader
     */
    function InitializeFirstScreen() {
        InitTinyMCE();

        $.ajax({
            url: '../view/bulletin/home.php',
            type: 'POST',
            dataType: 'HTML',
            success: function (data) {
                $('#mainWindow').html(data);
            },
            complete: function () {
                $('#insertBulletinItem').modal();
                $('.tooltipped').tooltip({delay: 50});
            },
            error: function (e, exception) {
                 GetErrorMessage(e, exception);

                $('#mainWindow').html("<p>Oops.. Something went wrong.</p>");
            }
        });
    }
    
    /*
     * View Selection Handlers
     */
    function HandleViewSelection(view, falsePage) {
        ScrollAppWindow();
                
        let page = view.attr("data-view");

        switch(falsePage) {
            case "0":
                page = "tracks/music";
                
                break;
            case "2":    
                page = "user/profile";
                
                break;
            case "3":    
                page = "bulletin/home";
            
                break;
            default:
                page = "bulletin/home";
            
                break;
        }
                
        $.ajax({
            url: '../view/' + page + '.php',
            type: 'POST',
            dataType: 'HTML',
            success: function (data) {
                $('#mainWindow').html(data);
            },
            complete: function () {
                switch(view.text().trim()) {
                        
                    case "Music":
                        $("li.active").removeClass("active");
                    
                        view.addClass('active');
                        
                        break;
                    case "Home":
                        $("li.active").removeClass("active");

                        view.addClass('active');
                    
                        $('.tooltipped').tooltip({delay: 50});
                        
                        break;
                    case "Settings":
                        $("li.active").removeClass("active");

                        view.addClass('active');
                        
                        break;
                    case "Calendar":
                        $("li.active").removeClass("active");

                        view.addClass('active');
                        
                        //Initialize Datapicker
                        $('.datepicker').pickadate({
                            selectMonths: true, 
                            selectYears: 15 
                        });
                        
                        break;
                    case "Media Hub":
                        $("li.active").removeClass("active");

                        view.addClass('active');
                        
                        break;
                    default:
                        $("li.active").removeClass("active");

                        view.addClass('active');
                    
                        $('.tooltipped').tooltip({delay: 50});
                        
                        break;
                }
                
                $('#insertTrackModal').modal();
            },
            error: function (e, exception) {
                 GetErrorMessage(e, exception);

                $('#mainWindow').html("<p>Oops.. Something went wrong.</p>");
            }
        });
    }

    /*
     * Upload A New Track
     */
    function LoadNewTrack() {
        SaveTinyMCE();

        let title = $("#titleField").val();
        let lyrics = $("#lyricsField").val();

        var formContents = new FormData(document.getElementById("newTrackForm"));

        formContents.append("uploadContent", "WEBUPLOAD");
        formContents.append("title", title);
        formContents.append("lyrics", lyrics);

        $("#uploadTrack").prop("disabled", true);

        $(".load-indicator").show();

        $.ajax({
            url: '../controller/tracks/insertTrack.php',
            type: 'POST',
            data: formContents,
            processData: false,
            contentType: false,
            dataType: 'HTML',
            success: function (data) {        
                HandleViewSelection($(this), '0');
            },
            complete: function () {
                $("#uploadTrack").prop("disabled", false);
                $(".load-indicator").hide();
                $('#insertTrackModal').modal('close');
                
                ResetFormFields();
            },
            error: function (e, exception) {
                GetErrorMessage(e, exception);
                
                $("#uploadTrack").prop("disabled", false);
                $(".load-indicator").hide();

                $('#mainWindow').append("<p>Oops.. Something went wrong.</p>");
            }
        });
    }
    
    /*
     * Fetch Singular Track
     */
    function FetchTrack(trackID) {
        ScrollAppWindow();
        
        $.ajax({
            url:  '../controller/tracks/retrieveTrack.php',
            type: 'POST',
            data: {'trackID': trackID},
            dataType: 'JSON',
            success: function (data) {
                var formatData = '';

                  for (var key in data) {
                     var audioPath = data[key].audio;
                      
                     //Since our audio path can or may contain spaces we need to encodeURI to not break our code.     
                     audioPath = encodeURI(audioPath);
                      
                     formatData += [
                            '<div class="row collection">',
                                '<h2>' + data[key].title + '</h2>',
                                '<div>' + data[key].lyrics + '</div>',
                            '</div>',
                            '<div class="appAction" data-view="tracks/music">',
                                '<button class="btn-floating btn-large waves-effect waves-light red left" id="goBackBtn">',
                                    '<i class="material-icons backBtn">trending_flat</i>',
                                '</button>',
                            '</div><div class="appAction" data-view="tracks/music">',
                                '<button class="btn-floating btn-large waves-effect waves-light red left" id="goBackBtn">',
                                    '<i class="material-icons backBtn">trending_flat</i>',
                                '</button>',
                            '</div>',
                            '<div class="col s12">',
                                '<div class="audioBox card horizontal s10">',
                                    '<audio controls class="audioPlayer" preload="none">',
                                        '<source src='+ audioPath +' type="audio/mpeg"/>',
                                    '</audio>',
                                '</div>',
                             '</div>'
                      ].join('\n');
                    
                      $('#titleUpdateField').val(data[key].title);
                      
                      tinymce.get('updateLyricsField').setContent(data[key].lyrics);

                      $('#trackUpdateID').val(data[key].id);
                  }
                
                var editBtn = '<a class="btn-floating btn-large waves-effect waves-light red right modal-trigger" href="#editTrackModal" id="editTrackModalTrigger"><i class="material-icons">edit</i></a>';

                $('#mainWindow').html(formatData + editBtn);
            },
            complete: function () {
                $('#editTrackModal').modal();
            },
            error: function (e, exception) {
                 GetErrorMessage(e, exception);

                $('#mainWindow').html("Error in jquery: " + msg);
            }
        });

    }
    
    /*
     * Update a track
     */
    function UpdateTrack() {
        SaveTinyMCE();
        
        let title = $("#titleUpdateField").val();
        let lyrics = $("#updateLyricsField").val();
        let trackID = $("#trackUpdateID").val();

        var formContents = new FormData(document.getElementById("editTrackForm"));

        formContents.append("label", "WEBUPLOAD");
        formContents.append("title", title);
        formContents.append("lyrics", lyrics);
        formContents.append("trackID", trackID);

        $("#updateTrack").prop("disabled", true);

        $(".load-indicator").show();

        $.ajax({
            url: '../controller/tracks/updateTrack.php',
            type: 'POST',
            data: formContents,
            processData: false,
            contentType: false,
            dataType: 'html',
            success: function (data) {
                $('#mainWindow').append(data);

                FetchTrack(trackID);
            },
            complete: function () {
                $("#updateTrack").prop("disabled", false);
                $(".load-indicator").hide();

                $('#editTrackModal').modal('close');
            },
            error: function (e, exception) {
                GetErrorMessage(e, exception);

                $("#updateTrack").prop("disabled", false);
                $(".load-indicator").hide();

                $('#mainWindow').append("<p>Oops.. Something went wrong.</p>");
            }
        });
    }
    
    /*
     * Delete A Track
     */
    function DeleteTrack() {
        let trackID = $("#trackUpdateID").val();

        $(".load-indicator").show();

        $.ajax({
            url: '../controller/tracks/deleteTrack.php',
            type: 'POST',
            data: {"trackID":trackID},
            success: function (data) {
                $('#mainWindow').append(data);
                
                HandleViewSelection($(this), '0');
            },
            complete: function () {
                $(".load-indicator").hide();

                $('#editTrackModal').modal('close');
            },
            error: function (e, exception) {
                 GetErrorMessage(e, exception);

                $('#mainWindow').append("<p>Oops.. Something went wrong.</p>");
            }
        });

    }
    
    /*
     * Filter JSON response
     */
    var trackData = "";
    
    function StringFilter(stringText){
        if (trackData == ""){
        
            $.ajax({
                url: '../controller/tracks/retrieveTracks.php',
                type: 'POST',
                data: {"filter" : "true"},
                success: function (data) {
                    trackData = data;                    
                },
                complete: function () {},
                error: function (e, exception) {
                 GetErrorMessage(e, exception);
                    $('#mainWindow').append("<p>Oops.. Something went wrong.</p>");
                }
            });
            
        } else {
            var search_query_regex = new RegExp(".*"+stringText+".*", "g");
            var searchTracks = '';
            
            $.each(JSON.parse(trackData), function(key, value){
                var trackInfo = value['info'];
                var authorInfo = value['author'];
                
                var title = trackInfo['title'];
                var trackId = trackInfo['id'];
                var uploadDate = trackInfo['upload_date'];
                
                var authorName = authorInfo['name'];
                var authorPic = authorInfo['profile_img'];
                
                if (title.match(search_query_regex)){
                    
                    searchTracks += [
                        '<li class="collection-item avatar">',
                            '<img src='+ authorPic +' alt="Profile Image of"' + authorName + ' class="circle">',
                            '<span class="title">',
                                '<a href="#!" class="collection-item trackSelection" data-id='+ trackId +'><div class="shortText truncate">'+ title +'</div></a>', 
                            '</span>',
                            '<div class=\"shortText\">',
                                '<p class="truncate subTxt">Uploaded By: '+ authorName +' <br> Date Uploaded : '+ uploadDate +'</p>',
                            '</div>',
                            '<i class="material-icons secondary-content">trending_flat</i>',
                         '</li>'
                      ].join('\n');              
                }
            });
            
            $("#allTracksCollection").html(searchTracks);
        } 
    }
    
    /*
     * Update User Profile Photo
     */
    function UpdateUserPhoto() {
        var formContents = new FormData(document.getElementById("newProfilePicture"));
        formContents.append("uploadContent", "WEBUPLOAD");
        
        $.ajax({
            url: '../controller/user/updateUserPhoto.php',
            type: 'POST',
            data: formContents,
            processData: false,
            contentType: false,
            dataType: 'HTML',
            success: function (data) {        
                HandleViewSelection($(this), '2');
            },
            complete: function () {},
            error: function (e, exception) {
                 GetErrorMessage(e, exception);

                $('#mainWindow').append("<p>Oops.. Something went wrong.</p>");
            }
        });
    }
    
    /*
     * Delete Profile Picture
     */
    function DeleteProfilePicture() {
        
        $.ajax({
            url: '../controller/user/deleteProfilePicture.php',
            type: 'POST',
            success: function (data) {
                HandleViewSelection($(this), '2');
            },
            complete: function () {
                
            },
            error: function (e, exception) {
                 GetErrorMessage(e, exception);

                $('#mainWindow').append("<p>Oops.. Something went wrong.</p>");
            }
        });
    }
    
    /*
     * Add New Bulletin Item
     */
    function AddBulletinItem() {
        SaveTinyMCE();
        
        let name = $("#bulletinTitleField").val();
        let content = $("#bulletinContent").val();
        
        var formContents = new FormData(document.getElementById("newBulletinForm"));
        
        formContents.append("uploadContent", "WEBUPLOAD");
        formContents.append("name", name);
        formContents.append("content", content);
        
        $.ajax({
            url: '../controller/bulletin/insertBulletinItem.php',
            type: 'POST',
            data: formContents,
            processData: false,
            contentType: false,
            dataType: 'HTML',
            success: function () {
                HandleViewSelection($(this), '3');
            },
            complete: function () {
                ResetFormFields();
                
                $('#insertBulletinItem').modal('close');
            },
            error: function (e, exception) {
                GetErrorMessage(e, exception);

                $('#mainWindow').append("<p>Oops.. Something went wrong.</p>");
            }
        });
    }
    
    /*
     * Retrieve Bulletin Items
     */
    function RetrieveBulletinItem(bulletinID){
        
        $.ajax({
            url: '../view/bulletin/bulletinItem.php',
            type: 'POST',
            data: {"bulletinID" : bulletinID},
            dataType: 'HTML',
            success: function (data) {
                $('#mainWindow').html(data);
            },
            complete: function () {},
            error: function (e, exception) {
                GetErrorMessage(e, exception);

                $('#mainWindow').append("<p>Oops.. Something went wrong.</p>");
            }
        });
    }
    
    /*
     * Add Bulletin Comment
     */
    function AddBulletinComment(bulletinID, comment){
        
        $.ajax({
            url: '../controller/bulletin/insertBulletinComment.php',
            type: 'POST',
            data: {"bulletinID" : bulletinID, "comment" : comment},
            dataType: 'HTML',
            success: function (data) {    
               RetrieveBulletinItem(bulletinID);
                
                $('#mainWindow').html(data);
            },
            complete: function () {
                $("html, body").animate({ scrollTop: $(document).height() }, "fast");
            },
            error: function (e, exception) {
                GetErrorMessage(e, exception);

                $('#mainWindow').append("<p>Oops.. Something went wrong.</p>");
            }
        });
    }
    
    function ResetFormFields(){    
        $('#newTrackForm').trigger("reset");
        $('#newBulletinForm').trigger("reset");
    }
    
    function InitTinyMCE() {
        tinyMCE.remove();
        tinyMCE.init({
            height: 300,
            menubar: false,
            mode: "textarea",
            elements: "content",
            selector: 'textarea',
            plugins: "textcolor",
            toolbar: "forecolor backcolor | undo redo  | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent"
        });
    }
    
    function ScrollAppWindow(){
        $("html, body").animate({ scrollTop: 0 }, "fast");
    }
    
    function GetErrorMessage(e, exception){
        var msg = '';
          if (e.status === 0) {
              msg = 'Not connect.\n Verify Network.';
          } else if (e.status == 404) {
              msg = 'Requested page not found. [404]';
          } else if (e.status == 500) {
              msg = 'Internal Server Error [500].';
          } else if (exception === 'parsererror') {
              msg = 'Requested JSON parse failed.';
          } else if (exception === 'timeout') {
              msg = 'Time out error.';
          } else if (exception === 'abort') {
              msg = 'Ajax request aborted.';
          } else {
              msg = 'Uncaught Error.\n' + e.responseText;
          }

         alert("Error in jquery: " + msg);
    }

    function SaveTinyMCE() {
        tinyMCE.triggerSave();
    }
    
    /*
     * Super Scroll Helpers
     */
    var keys = {37: 1, 38: 1, 39: 1, 40: 1};

    function preventDefault(e) {
      e = e || window.event;
      if (e.preventDefault)
          e.preventDefault();
      e.returnValue = false;  
    }

    function preventDefaultForScrollKeys(e) {
        if (keys[e.keyCode]) {
            preventDefault(e);
            return false;
        }
    }

    function disableScroll() {
      if (window.addEventListener) // older FF
          window.addEventListener('DOMMouseScroll', preventDefault, false);
      window.onwheel = preventDefault; // modern standard
      window.onmousewheel = document.onmousewheel = preventDefault; // older browsers, IE
      window.ontouchmove  = preventDefault; // mobile
      document.onkeydown  = preventDefaultForScrollKeys;
    }

    function enableScroll() {
        if (window.removeEventListener)
            window.removeEventListener('DOMMouseScroll', preventDefault, false);
        window.onmousewheel = document.onmousewheel = null; 
        window.onwheel = null; 
        window.ontouchmove = null;  
        document.onkeydown = null;  
    }
});