<?php
	/**
		php fyrir login kerfi
	**/
    require_once("user.php");
    $USER = new User("registration_callback");
?>
<?php
	/**
		Nær í data fyrir þann notanda sem er innskráður, svo hægt sé að haka við þær myndir sem hann hefur séð.
	**/
	$db = new PDO('sqlite:../db/users.db');
	$user = $_SESSION["username"];
	$idArray = array();
	foreach ($db->query("SELECT * FROM seen WHERE uName='$user' AND seen='true'") as $data) {
		array_push($idArray, $data['movieId']);
	}
	echo json_encode($idArray); 
?>