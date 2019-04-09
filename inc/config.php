<?php
/**
 * For Development Purposes
 */
ini_set("display_errors", "on");

define( 'ROOT_DIR', $_SERVER['DOCUMENT_ROOT'] . "/admin" );

require_once(ROOT_DIR . "/model/database.class.php");

require __DIR__ . "../../model/LS.php";

\Fr\LS::config(array(
  "db" => array(
    "host" => "",
    "port" => 0,
    "username" => "",
    "password" => "",
    "name" => "",
    "table" => ""
  ),
  "features" => array(
    "auto_init" => true
  ),
  "pages" => array(
    "no_login" => array(
      "/admin",
      "/admin/view/reset.php"
    ),
    "everyone" => array(
      "admin/view/status.php"
    ),
    "login_page" => "/admin/index.php",
    "home_page" => "/admin/template/app.php"
  )
));
