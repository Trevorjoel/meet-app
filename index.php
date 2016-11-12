Test
<?php
require_once('startsession.php');
$page_title = 'Where opposites attract!';
require_once('header.php');
require_once('appvars.php');
  require_once('connectvars.php');
  require_once ('navmenu.php');
  
 
  // Connect to the database 
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME); 

  // Retrieve the user data from MySQL
  $query = "SELECT user_id, first_name, picture FROM mismatch_user WHERE first_name IS NOT NULL ORDER BY join_date DESC LIMIT 10";
  $data = mysqli_query($dbc, $query);

  // Loop through the array of user data, formatting it as HTML
  echo '<h4>Latest members:</h4>';
  echo '<table>';
  while ($row = mysqli_fetch_array($data)) {
    if (is_file(MM_UPLOADPATH . $row['picture']) && filesize(MM_UPLOADPATH . $row['picture']) > 0) {
      echo '<img class="img-circle" src="' . MM_UPLOADPATH . $row['picture'] . '" alt="' . $row['first_name'] . '" />';
    }
    else {
      echo '<img class="img-circle" src="' . MM_UPLOADPATH . 'nopic.jpg' . '" alt="' . $row['first_name'] . '" />';
    }
    if (isset($_SESSION['user_id'])) {
      echo '<a href="viewprofile.php?user_id=' . $row['user_id'] . '">' . $row['first_name'] . '</a>';
    }
    else {
      echo '' . $row['first_name'] . '';
    }
  }
  echo '</table>';

  mysqli_close($dbc);

?>
<?php
require_once('footer.php');
?>
</body> 
</html>