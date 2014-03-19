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
<?php
    /**
        uppfærir database innskráðs notanda, þegar notandi hakar við kvikmynd.
    **/
	$db = new PDO('sqlite:../db/users.db');
	$user = $_SESSION["username"];
	$bool = $_POST['bool'];
	$cbxId =  $_POST['cbx'];
    $db->exec("UPDATE seen SET seen='$bool' WHERE movieId='$cbxId' AND uName='$user'");
?>