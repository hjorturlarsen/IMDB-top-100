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
		<title>Unregister</title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" type="text/css" href="../css/userStyle.css">
	</head>

	<body>
		<h1>Unregister</h1>
		<table style="width: 100%; margin-top: 1em;"><tr><td style="width: 24em; padding-top:1em;">
			<?php if($USER->authenticated) { ?>

			<!-- If a user is logged in, they can select to unregister -->
			<form class="controlbox" name="Unregister" id="unregister" action="login.php
			" method="POST">
				<input type="hidden" name="op" value="unregister"/>
				<input type="hidden" name="username"value="<?php echo $_SESSION["username"]; ?>" />
				<p>To unregister, press the button...</p>
				<input type="submit" value="unregister"/>
			</form>
			<?php 	}?>
		</table>
	</body>
	<script type="text/javascript" src="../js/sha1.js"></script>
	<script type="text/javascript" src="../js/user.js"></script>
	<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../js/script.js"></script>
</html>