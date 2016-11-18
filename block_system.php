<?php
include_once("../php_includes/check_login_status.php");
if (isset($_POST['type']) && isset($_POST['blockee'])){
	$blockee = preg_replace('#[^a-z0-9]#i', '', $_POST['blockee']);
	$sql = "SELECT COUNT(id) FROM mismatch_user WHERE username='$blockee' AND activated='1' LIMIT 1";
	$query = mysqli_query($dbc, $sql);
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
			mysqli_close($db_conx);
	        echo "blocked_ok";
	        exit();
		}
	} else if($_POST['type'] == "unblock"){
	    if ($numrows == 0) {
		    mysqli_close($dbc);
	        echo "You do not have this user blocked, therefore we cannot unblock them.";
	        exit();
	    } else {
			$sql = "DELETE FROM blockedusers WHERE blocker='$log_username' AND blockee='$blockee' LIMIT 1";
			$query = mysqli_query($dbc, $sql);
			mysqli_close($dbc);
	        echo "unblocked_ok";
	        exit();
		}
	}
}
?>