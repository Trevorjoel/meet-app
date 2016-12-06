<?php
//START Code for Pm SYSTEM
include_once("../startsession.php");
include_once("../connectvars.php");

 


?><?php
$dbc         = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
// New PM
if (isset($_POST['action']) && $_POST['action'] == "new_pm"){
	// Make sure post data is not empty
	if(strlen($_POST['data']) < 1){
		mysqli_close($dbc);
	    echo "data_empty";
	    exit();
	}
	// Make sure post data is not empty
	if(strlen($_POST['data2']) < 1){
		mysqli_close($dbc);
	    echo "data_empty";
	    exit();
	}	
	
	// Clean all of the $_POST vars that will interact with the database
	$fuser = preg_replace('#[^a-z0-9]#i', '', $_POST['fuser']);
	$tuser = preg_replace('#[^a-z0-9]#i', '', $_POST['tuser']);
	$data = htmlentities($_POST['data']);
	$data = mysqli_real_escape_string($dbc, $data);
	$data2 = htmlentities($_POST['data2']);
	$data2 = mysqli_real_escape_string($dbc, $data2);
	$viewer_id = $_SESSION['user_id'];
	
	// Make sure account name exists (the profile being posted on)

	$sql = "SELECT COUNT(user_id) FROM mismatch_user WHERE user_id='$tuser' AND activated='1' LIMIT 1";
	$query = mysqli_query($dbc, $sql);
	$row = mysqli_fetch_row($query);
	if($row[0] < 1){
		mysqli_close($dbc);
		echo "$account_no_exist";
		exit();
	}
	//No message to yourself
	if ($_SESSION['user_id'] == $tuser){
		echo "cannot_message_self";
		exit();
	}
	// Insert the status post into the database now
	$defaultP = "x";
	$sql = "INSERT INTO pm(receiver, sender, senttime, subject, message, parent) 
			VALUES('$tuser','$fuser',now(),'$data2','$data','$defaultP')";
	$query = mysqli_query($dbc, $sql);
	mysqli_close($dbc);
	echo "pm_sent";
	exit();
}
?><?php
// Reply To PM
if (isset($_POST['action']) && $_POST['action'] == "pm_reply"){
	// Make sure data is not empty
	if(strlen($_POST['data']) < 1){
		mysqli_close($dbc);
	    echo "data_empty";
	    exit();
	}
	// Clean the posted variables
	$osid = preg_replace('#[^0-9]#', '', $_POST['pmid']);
	$account_name = preg_replace('#[^a-z0-9]#i', '', $_POST['user']);
	$osender = preg_replace('#[^a-z0-9]#i', '', $_POST['osender']);
	$data = htmlentities($_POST['data']);
	$data = mysqli_real_escape_string($dbc, $data);
	// Make sure account name exists (the profile being posted on)
	$sql = "SELECT COUNT(username) FROM mismatch_user WHERE user_id='$account_name' AND activated='1' LIMIT 1";
	$query = mysqli_query($dbc, $sql);
	$row = mysqli_fetch_row($query);
	if($row[0] < 1){
		mysqli_close($dbc);
		echo "account_no_exist";
		exit();
	}
	// Insert the pm reply post into the database now
	$viewer_id = $_SESSION['user_id'];
	$x = "x";
	$sql = "INSERT INTO pm(receiver, sender, senttime, subject, message, parent)
	        VALUES('$x','$account_name',now(),'$x','$data','$osid')";
	$query = mysqli_query($dbc, $sql);	
	$id = mysqli_insert_id($dbc);
	
	if ($viewer_id != $osender){
		$query2 = mysqli_query($dbc, "UPDATE pm SET hasreplies='1', rread='1', sread='0' WHERE id='$osid' LIMIT 1");
	} else {
		$query2 = mysqli_query($dbc, "UPDATE pm SET hasreplies='1', rread='0', sread='1' WHERE id='$osid' LIMIT 1");
	}
	mysqli_close($dbc);
	echo "reply_ok|$id";
	exit();
}
?><?php
// Delete PM
if (isset($_POST['action']) && $_POST['action'] == "delete_pm"){
	if(!isset($_POST['pmid']) || $_POST['pmid'] == ""){
		mysqli_close($dbc);
		echo "id_missing";
		exit();
	}
	
		
	$pmid = preg_replace('#[^0-9]#', '', $_POST['pmid']);
	if(!isset($_POST['originator']) || $_POST['originator'] == ""){
		mysqli_close($dbc);
		echo "originator_missing";
		exit();
	}
	$originator = preg_replace('#[^a-z0-9]#i', '', $_POST['originator']);
	// see who is deleting
$viewer_id = $_SESSION['user_id'];
	if ($originator == $viewer_id) {
		$updatedelete = mysqli_query($dbc, "UPDATE pm SET sdelete='1' WHERE id='$pmid' LIMIT 1");
		//Delete from db where sender and receiver have both deleted msg 
	
			$delete_perm = mysqli_query($dbc, "DELETE FROM pm  WHERE rdelete='1' AND sdelete='1' ");
		}
	if ($originator != $viewer_id) {
		//Delete from db where sender and receiver have both deleted msg 
	
			
		$updatedelete = mysqli_query($dbc, "UPDATE pm SET rdelete='1' WHERE id='$pmid' LIMIT 1");
		$delete_perm = mysqli_query($dbc, "DELETE FROM pm  WHERE rdelete='1' AND sdelete='1' ");
		}
		
	mysqli_close($dbc);
	echo "delete_ok";
	exit();
}
?><?php
// Mark As Read
if (isset($_POST['action']) && $_POST['action'] == "mark_as_read"){
	if(!isset($_POST['pmid']) || $_POST['pmid'] == ""){
		mysqli_close($dbc);
		echo "id_missing";
		exit();
	}
	$pmid = preg_replace('#[^0-9]#', '', $_POST['pmid']);
	if(!isset($_POST['originator']) || $_POST['originator'] == ""){
		mysqli_close($dbc);
		echo "originator_missing";
		exit();
	}
	$originator = preg_replace('#[^a-z0-9]#i', '', $_POST['originator']);
	// see who is marking as read
	$viewer_id = $_SESSION['user_id'];
	if ($originator == $viewer_id) {
		$updatedelete = mysqli_query($dbc, "UPDATE pm SET sread='1' WHERE id='$pmid' LIMIT 1");
		}
	if ($originator != $viewer_id) {
		$updatedelete = mysqli_query($dbc, "UPDATE pm SET rread='1' WHERE id='$pmid' LIMIT 1");
		}
	mysqli_close($dbc);
	echo "read_ok";
	exit();
}

?>