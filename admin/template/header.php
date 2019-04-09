
<?php

$page = basename($_SERVER['PHP_SELF']);

$folder = ".";

if($page != "index.php") $folder = "..";

print("
    <!DOCTYPE html>
    <html lang=\"en\">
    <head>
    	<meta charset=\"UTF-8\">
    	<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0 maximum-scale=1.0, user-scalable=no\">
    	<meta name=\"description\" content=\"\">
    	<meta name=\"author\" content=\"\">
    	<title>Giza Peaks || Admin</title>
    	<!-- Favicons-->
    	<link rel=\"apple-touch-icon\" sizes=\"180x180\" href=\"$folder/assets/img/favicons/apple-touch-icon.png\">
    	<link rel=\"icon\" type=\"image/png\" href=\"$folder/assets/img/favicons/favicon-32x32.png\" sizes=\"32x32\">
    	<link rel=\"icon\" type=\"image/png\" href=\"$folder/assets/img/favicons/favicon-16x16.png\" sizes=\"16x16\">
    	<link rel=\"manifest\" href=\"$folder/assets/img/favicons/manifest.json\">
    	<link rel=\"mask-icon\" href=\"$folder/assets/img/favicons/safari-pinned-tab.svg\" color=\"#5bbad5\">
    	<link rel=\"shortcut icon\" href=\"$folder/assets/img/favicons/favicon.ico\">
    	<meta name=\"msapplication-config\" content=\"$folder/assets/img/favicons/browserconfig.xml\">
    	<meta name=\"theme-color\" content=\"#ffffff\">
    	<!-- Web Fonts-->
    	<link href=\"https://fonts.googleapis.com/css?family=Roboto:400,700\" rel=\"stylesheet\" type=\"text/css\">
    	<link href=\"https://fonts.googleapis.com/css?family=Suranna\" rel=\"stylesheet\" type=\"text/css\">
      <link href=\"https://fonts.googleapis.com/icon?family=Material+Icons\" rel=\"stylesheet\">

      <!-- MDL CSS
      ================================================== -->
      <link href=\"$folder/assets/css/lib/mdl.min.css\" rel=\"stylesheet\">

      <!-- Material CSS
      ================================================== -->
      <link rel=\"stylesheet\" type=\"text/css\"  href=\"$folder/assets/css/lib/materialize.min.css\">


      <!-- Core CSS-->
      <link href=\"$folder/assets/css/common/main.css\" rel=\"stylesheet\">

    </head>
    <body>

");
?>
