<?php
require_once('startsession.php');
$page_title = 'View profile';
require_once('header.php');
require_once('appvars.php');
require_once('connectvars.php');
require_once('php_functions.php');
require_once('navmenu.php');
?>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
</head>
<?php
// Make sure the user is logged in before going any further.
if (!isset($_SESSION['user_id'])) {
    echo '<p class="login">Please <a href="login.php">log in</ a> to access this page.</p>';
   
}
// Connect to the database
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Grab the profile data from the database for the logged in user
if (!isset($_GET['user_id'])) {
    $query = "SELECT username, first_name, last_name, gender, birthdate, city, state, picture FROM mismatch_user WHERE user_id = '" . $_SESSION['user_id'] . "'";
    $data = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($data);
    $viewer = $row['username'];
    

} else {
  // Grab the profile data from the database for the viewed user
    $query = "SELECT username, first_name, last_name, gender, birthdate, city, state, picture FROM mismatch_user WHERE user_id = '" . $_GET['user_id'] . "'";
    $data = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($data);
  $viewed = $row['username'];
    
    $query1 = "SELECT username FROM mismatch_user WHERE user_id = '" . $_SESSION['user_id'] . "'";
    $data1 = mysqli_query($dbc, $query1);
    $row = mysqli_fetch_array($data1);
    $viewer = $row['username'];
    
}
$data = mysqli_query($dbc, $query);
if (mysqli_num_rows($data) == 1) {
    // The user row was found so display the user data
    $row = mysqli_fetch_array($data);
    echo '<table>';
    if (!empty($row['username'])) {
        echo '<tr><td class="label">Username:</td><td>' . $row['username'] . '</td></tr>';
    }
    if (!empty($row['first_name'])) {
        echo '<tr><td class="label">First name:</td><td>' . $row['first_name'] . '</td></tr>';
    }
    if (!empty($row['last_name'])) {
        echo '<tr><td class="label">Last name:</td><td>' . $row['last_name'] . '</td></tr>';
    }
    if (!empty($row['gender'])) {
        echo '<tr><td class="label">Gender:</td><td>';
        if ($row['gender'] == 'M') {
            echo 'Male';
        } else if ($row['gender'] == 'F') {
            echo 'Female';
        } else {
            echo '?';
        }
        echo '</td></tr>';
    }
    if (!empty($row['birthdate'])) {
        if (!isset($_GET['user_id']) || ($_SESSION['user_id'] == $_GET['user_id'])) {
            // Show the user their own birthdate
            echo '<tr><td class="label">Birthdate:</td><td>' . $row['birthdate'] . '</td></tr>';
        } else {
            // Show only the birth year for everyone else
            list($year, $month, $day) = explode('-', $row['birthdate']);
            echo '<tr><td class="label">Year born:</td><td>' . $year . '</td></tr>';
        }
    }
    if (!empty($row['city']) || !empty($row['state'])) {
        echo '<tr><td class="label">Location:</td><td>' . $row['city'] . ', ' . $row['state'] . '</td></tr>';
    }
    if (!empty($row['picture'])) {
        echo '<tr><td class="label ">Picture:</td><td><img src="' . MM_UPLOADPATH . $row['picture'] . '" class="img-circle" alt="Profile Picture" /></td></tr>';
    }
    echo '</table>';
require_once('block_add.php');

    require_once('friendlist.php');
} // End of check for a single row of user results
else {
    echo '<p class="error">There was a problem accessing your profile.</p>';
}
if (!isset($_GET['user_id']) || ($_SESSION['user_id'] == $_GET['user_id'])) {
    echo '<p>Would you like to <a href="editprofile.php">edit your profile</a>?</p>';

    exit();
}
   
   

//BEGIN code for QUESTIONNAIRE MATCHING 
// Only look for a mismatch if the user has questionnaire responses stored
  $query = "SELECT * FROM mismatch_response WHERE user_id = '" . $_SESSION['user_id'] . "'";
  $data = mysqli_query($dbc, $query);
  if (mysqli_num_rows($data) != 0) {
    // First grab the user's responses from the response table (JOIN to get the topic and category names)
    $query = "SELECT mr.response_id, mr.topic_id, mr.response, mt.name AS topic_name, mc.name AS category_name " .
      "FROM mismatch_response AS mr " .
      "INNER JOIN mismatch_topic AS mt USING (topic_id) " .
      "INNER JOIN mismatch_category AS mc USING (category_id) " .
      "WHERE mr.user_id = '" . $_SESSION['user_id'] . "'";
    $data = mysqli_query($dbc, $query);
    $user_responses = array();
    while ($row = mysqli_fetch_array($data)) {
      array_push($user_responses, $row);
    }
    // Initialize the mismatch search results
    $mismatch_score = 0;
    $mismatch_user_id = -1;
    $mismatch_topics = array();
    $mismatch_categories = array();
    // Loop through the user table comparing other people's responses to the user's responses
    $query = "SELECT user_id FROM mismatch_user WHERE user_id != '" . $_GET['user_id'] . "'";
    $data = mysqli_query($dbc, $query);
    while ($row = mysqli_fetch_array($data)) {
      // Grab the response data for the user (a potential mismatch)
      $query2 = "SELECT response_id, topic_id, response FROM mismatch_response WHERE user_id = '" . $_GET['user_id'] . "'";
      $data2 = mysqli_query($dbc, $query2);
      $mismatch_responses = array();
      while ($row2 = mysqli_fetch_array($data2)) {
        array_push($mismatch_responses, $row2);
      } // End of inner while loop
      // Compare each response and calculate a mismatch total
      $score = 0;
      $topics = array();
      $categories = array();
      for ($i = 0; $i < count($user_responses) && !empty($mismatch_responses); $i++) {
        if ($user_responses[$i]['response'] + $mismatch_responses[$i]['response'] == 3) {
          $score += 1;
          array_push($topics, $user_responses[$i]['topic_name']);
          array_push($categories, $user_responses[$i]['category_name']);
        }
      }
      // Check to see if this person is better than the best mismatch so far
      if ($score > $mismatch_score) {
        // We found a better mismatch, so update the mismatch search results
        $mismatch_score = $score;
        $mismatch_user_id = $row['user_id'];
        $mismatch_topics = array_slice($topics, 0);
        $mismatch_categories = array_slice($categories, 0);
      }
    } // End of outer while loop
    // Make sure a match was found
    if ($mismatch_user_id != -1) {
      $query = "SELECT username, first_name, last_name, city, state, picture FROM mismatch_user WHERE user_id = '$mismatch_user_id'";
      $data = mysqli_query($dbc, $query);
      if (mysqli_num_rows($data) == 1) {
       
        //END code for QUESTIONNAIRE MATCHING

        //BEGIN BARGRAPH code
        // Calculate the mismatched category totals
       
        $category_totals = array(array($mismatch_categories[0], 0));
        foreach ($mismatch_categories as $category) {
          if ($category_totals[count($category_totals) - 1][0] != $category) {
            array_push($category_totals, array($category, 1));
          }
          else {
            $category_totals[count($category_totals) - 1][1]++;
          }
        }
        // Generate and display the mismatched category bar graph image
        echo '<h4>Match category breakdown:</h4>';
        draw_bar_graph(480, 240, $category_totals, 5, 'MM_UPLOADPATH' . $_SESSION['user_id'] . '-mymismatchgraph.png');
        echo '<img src="' . 'MM_UPLOADPATH' . $_SESSION['user_id'] . '-mymismatchgraph.png" alt="Mismatch category graph" />';
echo '</td></tr></table>';
        //end bar graph
        // Display the matched topics in a table with four columns
        echo '<h4>You feel the same about the following ' . count($mismatch_topics) . ' topics:</h4>';
        echo '<table ><tr>';
        $i = 0;
        foreach ($mismatch_topics as $topic) {
          echo '<td>' . $topic . '</td>';
          if (++$i > 3) {
            echo '</tr><tr>';
            $i = 0;
          }
        }
        echo '</tr></table><br>';
        
      } // End of check for a single row of mismatch user results
    } // //END BARGRAPH code
  } // End of check for any questionnaire response results
  else {
    echo '<p>You must first <a href="questionnaire.php">answer the questionnaire</a> before you can be mismatched.</p>';
    }

  mysqli_close($dbc);

require_once('footer.php');
?>
</div>
</body> 
</html>