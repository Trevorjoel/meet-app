<?php
 /* Latest Info:




  */

  // Insert the page header
  $page_title = 'Sign Up';
  require_once('header.php');
  require_once('appvars.php');
  require_once('connectvars.php');
 
  // Have a D&M with the database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  if (isset($_POST['submit'])) {
     // Grab the profile data from the POST 'by the pussy' sanitize and validate that shizzle
    if(empty($_POST['nationality'])) {
      echo '<p class="error">You must enter nationality.</p>';
    }else{


   
    $nationality = mysqli_real_escape_string($dbc, trim($_POST['nationality']));
    $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
    
    $email = mysqli_real_escape_string($dbc, trim($_POST['email']));
    $password1 = mysqli_real_escape_string($dbc, trim($_POST['password1']));
    $password2 = mysqli_real_escape_string($dbc, trim($_POST['password2']));

      filter_input ( INPUT_POST, $_POST['email'], FILTER_SANITIZE_EMAIL );
    $email = mysqli_real_escape_string ($dbc, trim($_POST['email']));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo  "<br><p class='error'>$email is not a valid email address. <br>";
        $email = '';
}
    if (!empty($username) && !empty($password1) && !empty($password2) && !empty($email) && !empty($nationality) && ($password1 == $password2)) {
      // Make sure username is unique
      $query = "SELECT * FROM mismatch_user WHERE username = '$username'";
      $data = mysqli_query($dbc, $query);
      if (mysqli_num_rows($data) == 0) {
        
        // The username is unique, so insert the data into the database
        $query = "INSERT INTO mismatch_user (nationality, username, email, password, join_date) VALUES ('$nationality', '$username', '$email', SHA('$password1'), NOW())";
        mysqli_query($dbc, $query);

        // Confirm success with the user
        echo '<p>Your new account has been successfully created. You\'re now ready to <a href="login.php">log in</a>.</p>';

        mysqli_close($dbc);
        exit();
      }
      else {
        // An account already exists for this username, so display an error message
        echo '<p class="error">An account already exists for this username. Please use a different address.</p>';
        $username = "";
      }
    }
    else {

      echo '<p class="error">You must enter all of the sign-up data, including the desired password twice.</p>';
    }
  }
}
  mysqli_close($dbc);
?>

  <p>Please enter your username and desired password to sign up and Meet a Russian.</p>
  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <fieldset>
      <legend>Registration Info</legend><br />     
<hr>
<fieldset>
<p><b>Nationality :</b><br>
<input type="radio" name="nationality" id="" value="1"<?php if (isset($_POST['nationality']) and $_POST['nationality'] == '1') 
  echo 'checked';?> />

<label for="russian"> Russian</label><br/>
<input type="radio" name="nationality" id="" value="2" <?php if (isset($_POST['nationality']) and $_POST['nationality'] == '2') 
  echo 'checked';?>/>
<label for="non-russian"> Non-Russian</label><br/>
</p>
</fieldset>

      <br /><label for="username">Username:</label>
      <input type="text" id="username" name="username" value="<?php if (!empty($username)) echo $username; ?>" /><br />
     <label for="email">Email Address:</label>
    <input id="email" type="text" name="email" maxlength="88" value="<?php if (!empty($email)) echo $email; ?>"><br />
      <label for="password1">Password:</label>
      <input type="password" id="password1" name="password1" /><br />
      <label for="password2">Password (retype):</label>
      <input type="password" id="password2" name="password2" /><br />
    </fieldset>
    <input type="submit" value="Sign Up" name="submit" />
  </form>

<?php
  // Insert the page footer
  require_once('footer.php');
?>
