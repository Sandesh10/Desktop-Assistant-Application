<?php
session_start();
unset($_SESSION["is_open"]);
session_destroy();

if(!isset($_SESSION["is_open"]))
	header("Location: index.php");
?>