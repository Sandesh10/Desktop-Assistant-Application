<?php
if(isset($_GET)&&isset($_GET["arg"]))
{
	switch ($_GET["arg"]) {
		case 'invalid':
			# code...
			break;
		
		default:
			# code...
			break;
	}
}
?>

<html>
<head>
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/3/w3.css">
</head>
<body>
<div class="w3-card-4" style="width:50%;margin:auto">

<header class="w3-container w3-green">
  <h1>Onlineword Login</h1>
</header>

<form class="w3-container" method="post" action="auth.php">

<label class="w3-label">user ID</label>
<input class="w3-input" type="text" name="uid" placeholder="user id">

<label class="w3-label">Password</label>
<input class="w3-input" type="password" name="password" placeholder="password">
<br>
<button class="w3-button w3-green" type="submit" name="submit" value="submit">login</button>
</form>
<footer class="w3-container w3-green">
  <h5>Footer</h5>
</footer>

</div>
</body>
</html>