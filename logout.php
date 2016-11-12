<?php
session_start();
  // If the user is logged in, delete the cookie/session to log them out
  if (isset($_SESSION['user_id'])) {
  
    // Delete the user ID and username cookies/session by setting their expirations to an hour ago (3600)
    $_SESSION = array ();
    
    if (isset($_COOKIE['session_name()'])) {
   
   
    setcookie('session_name'(), '', time() - 3600);
}
session_destroy();
 }
 setcookie('user_id', $row ['user_id'], time() - 3600);
 setcookie('username', $row ['username'], time() - 3600); 

  setcookie('username', $row ['username'], time() + (60 * 60 * 24 * 30)); // Expires in 30 days
  // Redirect to the home page
  $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
  header('Location: ' . $home_url);
?>