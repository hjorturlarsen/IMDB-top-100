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
		<title>Update Account</title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" type="text/css" href="../css/userStyle.css">
	</head>

	<body>
		<h1>Update Account</h1>
		<?php if($USER->authenticated) { ?>
			<!-- If a user is logged in, her or she can modify their email and password -->
			<form class="controlbox" name="Update" id="update" action="login.php
			" method="POST">
				<input type="hidden" name="op" value="update"/>
				<input type="hidden" name="sha1" value=""/>
				<p>Update your email address and/or password here</p>
				<table>
					<tr><td>email address </td><td><input type="text" name="email" value="<?php $USER->email; ?>" /></td></tr>
					<tr><td>new password </td><td><input type="password" name="password1" value="" /></td></tr>
					<tr><td>new password (again) </td><td><input type="password" name="password2" value="" /></td></tr>
				</table>
				<input type="button" value="update" onclick="User.processUpdate()"/>
			</form>
		<?php 		} ?>
	</body>
	<script type="text/javascript" src="../js/sha1.js"></script>
	<script type="text/javascript" src="../js/user.js"></script>
	<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../js/script.js"></script>
</html>