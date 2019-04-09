
<?php
$page = basename($_SERVER['PHP_SELF']);

$folder = ".";

if($page != "index.php"){

  $folder = "..";

}
print("

    <!-- jQuery JS
    ================================================== -->
    <script src=\"$folder/assets/js/lib/jquery-2.2.4.min.js\"></script>
    
    <!-- MDL JS
    ================================================== -->
    <script src=\"$folder/assets/js/lib/mdl.min.js\"></script>
    
    <!-- Material JS
    ================================================== -->
    <script type=\"text/javascript\" src=\"$folder/assets/js/lib/materialize.min.js\"></script>
    
    <!-- TinyMCE JS
    ================================================== -->
    <script type=\"text/javascript\" src=\"$folder/assets/js/lib/tinymce/tinymce.min.js\"></script>

    <!-- Main JS
    ================================================== -->
    <script src=\"$folder/assets/js/common/main.js\"></script>
  </body>
</html>

");
?>
