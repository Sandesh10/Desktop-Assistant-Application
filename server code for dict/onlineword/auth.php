<?php
if(isset($_POST)&&isset($_POST["submit"]))
{
	$uid=$_POST["uid"];
	$pass=$_POST["password"];
	if($uid=="admin"&&$pass=="admin")
	{
		echo "success";
		session_start();

		$_SESSION['is_open'] = TRUE;
		echo isset($_SESSION["is_open"]);
		header("Location: "."insertword.php");
	}
	else header("Location: "."index.php?arg=invalid");
}else
{
header("Location: index.php?arg=login");
}
?>