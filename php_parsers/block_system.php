<?php
include_once("../startsession.php");
include_once("../connectvars.php");

if (isset($_POST['type']) && isset($_POST['blockee'])){
	$viewer = $_SESSION['user_id'];
if (isset ($_GET['user_id'])) {
  $viewed = $_GET['user_id'];
}
	$blockee = preg_replace('#[^a-z0-9]#i', '', $_POST['blockee']);
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$sql = "SELECT (user_id) FROM mismatch_user WHERE user_id='$blockee' AND activated='1' LIMIT 1 ";
	$query = mysqli_query($dbc, $sql);
	//print_r($query);
	$exist_count = mysqli_fetch_row($query);
	if($exist_count[0] < 1){
		mysqli_close($dbc);
		echo "$blockee does not exist.";
		exit();
	}
	$sql = "SELECT id FROM blockedusers WHERE blocker='$viewer' AND blockee='$blockee' LIMIT 1";
	$query = mysqli_query($dbc, $sql);
	$numrows = mysqli_num_rows($query);
	if($_POST['type'] == "block"){
	    if ($numrows > 0) {
			mysqli_close($dbc);
	        echo "You already have this member blocked.";
	        exit();
	    } else {
			$sql = "INSERT INTO blockedusers(blocker, blockee, blockdate) VALUES('$viewer','$blockee',now())";
			$query = mysqli_query($dbc, $sql);
			mysqli_close($dbc);
	        echo "blocked_ok";
	        exit();
		}
	} else if($_POST['type'] == "unblock"){
	    if ($numrows == 0) {
		    mysqli_close($dbc);
	        echo "You do not have this user blocked, therefore we cannot unblock them.";
	        exit();
	    } else {
			$sql = "DELETE FROM blockedusers WHERE blocker='$viewer' AND blockee='$blockee' LIMIT 1";
			$query = mysqli_query($dbc, $sql);
			mysqli_close($dbc);
	        echo "unblocked_ok";
	        exit();
		}
	}
}
?>