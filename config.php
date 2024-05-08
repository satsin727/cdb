<?php

ob_start();
session_start();
ini_set( "display_errors", false );
date_default_timezone_set("America/Chicago");
//define( "ADMIN_USERNAME", "admin@metahorizon.com" );
//define( "ADMIN_PASSWORD", "deathburner" );

define( "DB_DSN", "mysql:host=localhost;dbname=cdb_gmail" );
define( "DB_USERNAME", "metahorizon" );
define( "DB_PASSWORD", "metahorizon" );

if(!isset($_SESSION['cdbgusername'])){
$_SESSION['cdbgusername']=0;
}


?>