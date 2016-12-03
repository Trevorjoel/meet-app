<?php
$envelope = '<img src="images/message_bg.png" style="float:right;" width="30" height="30" alt="Notes" title="Log in to check your messages">';
$friend_icon = '<a href="notifications.php" title="Log in to check your friend requests."><img src="images/users_add_bg.png" width="30" height="30" alt="Notes" style="float:right"></a>';
// Generate the navigation menu
if (isset($_SESSION['username'])) {
	$username = ($_SESSION['username']);
	$viewer_id = ($_SESSION['user_id']);
 echo '<hr>';
 echo ' &#9774<a href="index.php"> Home</a>  ';
  echo ' &#9733; <a href="viewprofile.php"> View Profile</a> ';
  
  echo ' &#9733; <a href="mymismatch.php"> My best match</a> ';
  echo ' &#9733; <a href="editprofile.php"> Edit Profile</a> ';
   echo ' &#9733;<a href="questionnaire.php"> Questionnaire</a>  ';
  echo ' &#9733; <a href="Logout.php"> log out (' . $_SESSION['username'] . ')</a>';
  //Generate the notifications
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  $sql = "SELECT id FROM pm WHERE (receiver='$viewer_id' AND parent='x' AND rdelete='0' AND rread='0')
  OR (sender= '$viewer_id' AND sdelete='0' AND parent='x' AND hasreplies='1' AND sread='0') LIMIT 1";
	$query = mysqli_query($dbc, $sql);
	$numrows = mysqli_num_rows($query);
	if ($numrows == 0) {
		$envelope = '<a href="notifications.php" title="No new messages"><img src="images/message_bg.png" width="30" height="30" alt="Notes" style="float:right"></a>';
		
    } else {
		
		$envelope = '<br /><a href="notifications.php" title="You have new messages"><img src="images/message_32.png" width="30" height="30" alt="Notes"></a>';
	}
    

}else{
   echo '&#10084; <a href="login.php">Login </a> ';
  echo '&#10084; <a href="Signup.php">Sign up</a> ';


}
echo $envelope;
if (isset($_SESSION['username'])) {
	$username = ($_SESSION['username']);
	$viewer_id = ($_SESSION['user_id']);
	

$sql = "SELECT (id) FROM friends WHERE user2='$viewer_id' AND accepted='0'";
    $query = mysqli_query($dbc, $sql);
    $numrows = mysqli_num_rows($query);
    if ($numrows == 0){
    	$friend_icon = '<a href="notifications.php" title="No pending friend requests"><img src="images/users_add_bg.png" width="30" height="30" alt="Notes" style="float:right"></a>';
    }else {
    	$friend_icon = '<a href="notifications.php" title="You have pending friend requests"><br /><img src="images/users_add.png" width="30" height="30" alt="Notes" >
    		
    	</style></a>';
		
		
	}
	
	}
	echo $friend_icon;
?>

<?php
echo "<hr>";
?>
