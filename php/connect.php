<?php 

/***** Connect to a MySQL Database *****/

// db config
$db_host		= "localhost";
$db_user		= "root";
$db_pass		= "";
$db_database	= "accounts";

$link = mysqli_connect($db_host, $db_user, $db_pass, $db_database) or die("Unable to connect to the database.");
?>
