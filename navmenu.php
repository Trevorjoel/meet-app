<?php
$envelope = '<img src="images/bg-mail.png" style="float:right;" width="35" height="40" alt="Notes" title="This envelope is for logged in members">';
// Generate the navigation menu
if (isset($_SESSION['username'])) {
	$username = ($_SESSION['username']);
 echo '<hr>';
 echo '&#10084<a href="index.php"> Home</a>  ';
  echo '&#10084; <a href="viewprofile.php"> View Profile</a> ';
  
  echo '&#10084; <a href="mymismatch.php"> My best match</a> ';
  echo ' &#10084; <a href="editprofile.php"> Edit Profile</a> ';
   echo '&#10084;<a href="questionnaire.php"> Questionnaire</a>  ';
  echo '&#10084; <a href="Logout.php"> log out (' . $_SESSION['username'] . ')</a>';
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  $sql = "SELECT notescheck FROM mismatch_user WHERE username='$username' LIMIT 1";
	$query = mysqli_query($dbc, $sql);
	$row = mysqli_fetch_row($query);
	$notescheck = $row[0];
	$sql = "SELECT id FROM notifications WHERE username='$username' AND date_time > '$notescheck' LIMIT 1";
	$query = mysqli_query($dbc, $sql);
	$numrows = mysqli_num_rows($query);
	if ($numrows == 0) {
		$envelope = '<a href="notifications.php" title="Your notifications and friend requests"><img src="images/bg-mail.png" width="35" height="40" alt="Notes"></a>';
		
    } else {
		
		$envelope = '<a href="notifications.php" title="You have new notifications"><img src="images/new_note.gif" width="35" height="40" alt="Notes"></a>';
	}
    

}else{
   echo '&#10084; <a href="login.php">Login </a> ';
  echo '&#10084; <a href="Signup.php">Sign up</a> ';


}
echo $envelope;
?>

<?php
echo "<hr>";
?>
