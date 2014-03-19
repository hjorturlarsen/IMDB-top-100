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
		<title>Register</title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" type="text/css" href="../css/userStyle.css">
	</head>

	<body>
		<h1>Register</h1>
		<table style="width: 100%; margin-top: 1em;"><tr><td style="width: 24em; padding-top:1em;">
			<?php		if(!$USER->authenticated) { ?>

			<!-- Allow a new user to register -->
			<form class="controlbox" name="New user registration" id="registration" action="login.php
			" method="POST">
				<input type="hidden" name="op" value="register"/>
				<input type="hidden" name="sha1" value=""/>
				<table>
					<tr><td>User name </td><td><input type="text" name="username" value="" /></td></tr>
					<tr><td>Email address </td><td><input type="text" name="email" value="" /></td></tr>
					<tr><td>Password </td><td><input type="password" name="password1" value="" /></td></tr>
					<tr><td>Repeat password </td><td><input type="password" name="password2" value="" /></td></tr>
				</table>
				<input type="button" value="register" onclick="User.processRegistration()"/>
			</form>
			<?php }	?>
		</table>
	</body>
	<script type="text/javascript" src="../js/sha1.js"></script>
	<script type="text/javascript" src="../js/user.js"></script>
	<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../js/script.js"></script>
</html>