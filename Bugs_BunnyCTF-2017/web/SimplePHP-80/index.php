<?php

include "flag.php";

$_403 = "Access Denied";
$_200 = "Welcome Admin";

if ($_SERVER["REQUEST_METHOD"] != "POST")
	die("BugsBunnyCTF is here :p...");

if ( !isset($_POST["flag"]) )
	die($_403);


foreach ($_GET as $key => $value)
	$$key = $$value;

foreach ($_POST as $key => $value)
	$$key = $value;


if ( $_POST["flag"] !== $flag )
	die($_403);


echo "This is your flag : ". $flag . "\n";
die($_200);

?>
