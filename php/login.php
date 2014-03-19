<?php
	/**
	php fyrir login kerfi
	**/
  	date_default_timezone_set("Iceland");

	$data = false;

	ini_set("display_errors", 1);
	ini_set("error_reporting", E_ALL | E_STRICT);

	// this is a demonstrator function, which gets called when new users register
	function registration_callback($username, $email, $userdir)
	{
		// all it does is bind registration data in a global array,
		// which is echoed on the page after a registration
		global $data;
		$data = array($username, $email, $userdir);
	}

	require_once("user.php");
	$USER = new User("registration_callback");
?>
<!DOCTYPE html>

<html lang="is">
	<head>
		<title>Log in</title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" type="text/css" href="../css/userStyle.css">
	</head>

	<body>
		<h1>Log in</h1>

		<?php if($USER->error!="") { ?>
		<p class="error">Error: <?php echo $USER->error; ?></p>
		<?php } ?>


<?php 		

			if(!$USER->authenticated) { ?>

			<!-- Allow a user to log in -->
			<form class="controlbox" name="LOG IN" id="login" action="login.php
			" method="POST">
				<input type="hidden" name="op" value="login"/>
				<input type="hidden" name="sha1" value=""/>
				<table>
					<tr><td>User name </td><td><input type="text" name="username" value="" /></td></tr>
					<tr><td>Password </td><td><input type="password" name="password1" value="" /></td></tr>
				</table>
				<input type="button" value="log in" onclick="User.processLogin()"/>
			</form>
<?php 		} 


			if($USER->authenticated) { ?>

			<!-- Log out option -->
			<form class="controlbox" name="Log out" id="logout" action="login.php
			" method="POST">
				<input type="hidden" name="op" value="logout"/>
				<input type="hidden" name="username"value="<?php echo $_SESSION["username"]; ?>" />
				<p>You are logged in as <?php echo $_SESSION["username"]; ?></p>
			</form>
<?php 		}?>

		</td></tr></table>
	</body>
	<script type="text/javascript" src="../js/sha1.js"></script>
	<script type="text/javascript" src="../js/user.js"></script>
	<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../js/script.js"></script>
</html>