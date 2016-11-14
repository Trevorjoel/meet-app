<?php
// Generate the navigation menu
if (isset($_SESSION['username'])) {
 echo '<hr>';
 echo '&#10084<a href="index.php"> Home</a>  ';
  echo '&#10084; <a href="viewprofile.php"> View Profile</a> ';
  
  echo '&#10084; <a href="mymismatch.php"> My mismatch</a> ';
  echo ' &#10084; <a href="editprofile.php"> Edit Profile</a> ';
   echo '&#10084;<a href="questionnaire.php"> Questionnaire</a>  ';
  echo '&#10084; <a href="Logout.php"> log out (' . $_SESSION['username'] . ')</a>';
}else{
   echo '&#10084; <a href="login.php">Login </a> ';
  echo '&#10084; <a href="Signup.php">Sign up</a> ';


}
echo "<hr>";
?>