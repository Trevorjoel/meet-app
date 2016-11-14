<?php
$page_title = 'Where peace comes through understanding!';
  require_once('header.php');
  require_once('navmenu.php');
require_once('connectvars.php');
session_start();
// Clear the error message
$error_msg = "";
// If the user isn't logged in, try to log them in
if (!isset($_SESSION['user_id'])) {
if (isset($_POST['submit'])) {
// Connect to the database
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
// Grab the user-entered log-in data
$user_username = mysqli_real_escape_string($dbc, trim($_POST['username']));
$user_password = mysqli_real_escape_string($dbc, trim($_POST['password']));
if (!empty($user_username) && !empty($user_password)) {
// Look up the username and password in the database
$query = "SELECT user_id, username FROM mismatch_user WHERE username = '$user_username' AND " .
"password = SHA('$user_password')";
$data = mysqli_query($dbc, $query);
if (mysqli_num_rows($data) == 1) {
// The log-in is OK so set the user ID and username cookies, and redirect to the home page
$row = mysqli_fetch_array($data);
          $_SESSION['user_id'] = $row['user_id'];
          $_SESSION['username'] = $row['username'];
  setcookie(['user_id'], time() + (60 * 60 * 24 * 30)); // Expires in 30 days
  setcookie('user_id', $row ['user_id'], time() + (60 * 60 * 24 * 30)); // Expires in 30 days

  setcookie('username', $row ['username'], time() + (60 * 60 * 24 * 30)); // Expires in 30 days
$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
header('Location: ' . $home_url);
}
else {
// The username/password are incorrect so set an error message
$error_msg = 'Sorry, you must enter a valid username and password to log in.';
}
}
else {
// The username/password weren't entered so set an error message
$error_msg = 'Sorry, you must enter your username and password to log in.';
}
}
}
?>
<html>
<head>
<title>Mismatch - Log In</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>

  
   <?php
   //if the cookie is empty show any error message and the login form. Otherwise confirm the login.
   if (empty($_SESSION['user_id'])) {
     echo '<p class="error">' . $error_msg . '</p>';
     ?>

     <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
     <fieldset>
       <legend>Log In</legend>
     <label for="username">Username:</label>
     <input type="text" id="username" name="username" value="<?php if (!empty($user_username)) echo $user_username; ?> "><br>
     <label for="password">Password:</label><input type="password" name="password" value="">
     </fieldset>
     <input type="submit" value="Log In" name="submit">
     </form>
   <?php
   }else{
  // Confirm the successful log-in
  echo ('<p class="login">You are logged in as ' . $_SESSION ['username'] . '.</p>');
 
}
?>
</body>
 </html> 
