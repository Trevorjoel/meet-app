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
    exit();
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
?>
</div>

<?php

$data = mysqli_query($dbc, $query);
if (mysqli_num_rows($data) == 1) {
    // The user row was found so display the user data
    $row = mysqli_fetch_array($data);
    echo '<table>';
    if (!empty($row['username'])) {
        echo '<tr><h4>' . $row['username'] . '</td></h4></tr>';
    }
    if (!empty($row['picture'])) {
        echo '<tr><td><img src="' . MM_UPLOADPATH . $row['picture'] . '" class="img-circle" alt="Profile Picture" /></td></tr>';
    }
    if (!empty($row['birthdate'])) { // Show the user their own birthdate
            $dob = $row['birthdate'];
        $condate = date("Y-m-d");  //Certain fix Date of Age
echo '<tr><td class="label">Age:</td><td>';
        echo getAge($dob, $condate);
        echo '</td></tr>';
    }

    if (!empty($row['gender'])) {
        echo '<tr><td class="label">Gender:</td><td>';
        if ($row['gender'] == 'M') {
            echo 'Male';
        } elseif ($row['gender'] == 'F') {
            echo 'Female';
        } else {
            echo '?';
        }
        echo '</td></tr>';
    }
    
    if (!empty($row['city']) || !empty($row['state'])) {
        echo '<tr><td class="label">Location:</td><td>' . $row['city'] . ', ' . $row['state'] . '</td></tr>';
    }
    
    echo '</table>';
    if (!isset($_GET['user_id']) || ($_SESSION['user_id'] == $_GET['user_id'])) {
        $isOwner = true;
        $user_id = $_SESSION['user_id'];
    }
    if (isset($isOwner)) {
        require_once('friendlist.php');
        require_once('pm_inbox.php');
        require_once('notifications.php');
        require_once('mymismatch.php');
        require_once('footer.php');
        exit();
    } else {
        require_once('block_add.php');
        require_once('friendlist.php');
        $viewed_id = $_GET['user_id'];
        $viewer_id = $_SESSION['user_id'];

//check for conversations with viewed user. Display pm template

$sql1 = "SELECT * FROM pm WHERE
      receiver = '$viewer_id' AND sender = '$viewed_id' OR receiver = '$viewed_id' AND sender = '$viewer_id' ";
        $data1 = mysqli_query($dbc, $sql1);


        $row1 = mysqli_fetch_array($data1);
        $rdelete = $row1['rdelete'];
        $sdelete = $row1['sdelete'];
        $hasreplies = $row1 ['hasreplies'];
        $subject = $row1 ['subject'];
        $receiver = $row1['receiver'];
        $sender = $row1['sender'];
//$parent = $row1['parent'];



// Determine if the Logged user and the viewed user
// have previous undeleted  conversations (on viewed user profile)
$mail = "";// && ($viewer_id == $receiver && $rdelete = 0)
if (isset($sender) && (isset($receiver))
  && ($viewer_id == $receiver || $sender && $sdelete == 0)) {
    // Display viewed user and veiwing user's msgs on viewed users profile
 echo '<h4>Your conversation with this user:</h4>';
    $sql = "SELECT * FROM pm WHERE (receiver='$viewed_id' AND sender='$viewer_id' AND parent='x' ) OR (sender='$viewed_id' AND receiver='$viewer_id' AND sdelete='0' AND parent='x' ) ORDER BY senttime DESC ";

    $query = mysqli_query($dbc, $sql);
    $statusnumrows = mysqli_num_rows($query);




// Gather data about parent pm's
if ($statusnumrows > 0) {
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        $pmid = $row["id"];
    //div naming
    $pmid2 = 'pm_'.$pmid;
        $wrap = 'pm_wrap_'.$pmid;
    //button naming
    $btid2 = 'bt_'.$pmid;
    //textarea naming
    $rt = 'replytext_'.$pmid;
    //button naming
    $rb = 'replyBtn_'.$pmid;
        $receiver = $row["receiver"];
        $sender = $row["sender"];
        $subject = $row["subject"];
        $message = $row["message"];
        $time = $row["senttime"];
        $rread = $row["rread"];
        $sread = $row["sread"];

//Message DEBUG unit

  $debug = 1;

        if ($debug == 1) {
            echo "This is viewprofile.php<br>";
            echo "Has replies: $hasreplies<br>";
            echo "Logged in username: $username<br>";
            echo "Logged in viewer_id: $viewer_id<br>";
            echo "receiver: $receiver <br>";
            echo "Sender: $sender <br>";
            echo "viewed_id: $viewed_id<br>";
echo "sdelete: $sdelete<br>";
echo "rdelete: $rdelete<br>";
echo "pm id: $pmid<br>";
//echo "pm_: $pm_<br>";
        }

        $query1 = "SELECT user_id, username, first_name, last_name, gender, birthdate, city, state, picture FROM mismatch_user WHERE user_id = '" . $sender . "'";
        $data1 = mysqli_query($dbc, $query1);
        $row1 = mysqli_fetch_array($data1);
        $frm = $row1['username'];
        $frm_id = $row1['user_id'];
        $profile_pic = $row1['picture'];
  
        $mail = '';
    // Start to build our list of parent pm's
    $mail .= '<div id="'.$wrap.'" class="pm_wrap form-group">';
        $mail .= '<div class="pm_header form-control"><a href="viewprofile.php?user_id='.$frm_id.'"><img class="friendpics img-circle" src="' . MM_UPLOADPATH . $profile_pic . '" class="img-circle" alt="Profile Picture" alt="'.$frm.'" title="'.$frm.'"></a><br /><b>Subject: </b>'.$subject.'<br /><br />';
        $mail .= '<button id="hide" class="btn  btn-sm " style="float:right">Hide</button>
           <button id="show" class="btn btn-primary btn-sm " style="float:right">Show</button>';
          ?> <script type="text/javascript">
$(document).ready(function(){
    $("#hide").click(function(){
        $(".<?php echo $frm_id; ?>").hide('slow');
    });
    $("#show").click(function(){
        $(".<?php echo $frm_id; ?>").show('slow');
    });
});
</script>

<?php

    // Add button for mark as read
    $mail .= '<button class="btn btn-primary btn-sm" onclick="markRead('.$pmid.','.$viewer_id.')">Mark As Read</button>';
    // Add Delete button
    $mail .= '<button class="btn btn-warning btn-sm" id="'.$btid2.'" onclick="deletePm('.$pmid.','.$wrap.','.$viewer_id.')">Delete</button></div>';
        $mail .= '<div style="display:none;" class="'.$frm_id.'" id="$pmid2" >';
        //start expanding area
    $mail .= '<div class="pm_post">From: '.$frm_id.' - '.$time.'<br />'.$message.'</div>';

    
    // Gather up any replies to the parent pm's
    $pm_replies = "";
        $query_replies = mysqli_query($dbc, "SELECT sender, message, senttime FROM pm WHERE parent='$pmid' ORDER BY senttime ASC");
        $replynumrows = mysqli_num_rows($query_replies);
        if ($replynumrows > 0) {
            while ($row2 = mysqli_fetch_array($query_replies, MYSQLI_ASSOC)) {
                $rsender = $row2["sender"];
                $reply = $row2["message"];
                $time2 = $row2["senttime"];
                $mail .= '<div class ="pm_post ">Your reply: on '.$time2.'<br />'.$reply.'<br /></div>';
            }
        }


    // Each parent and child is now listed
    $mail .= '</div>';
    // Add reply textbox
    $mail .= '<textarea class="form-control" id="'.$rt.'" width="100" placeholder="Reply..."></textarea><br />';
    // Add reply button '.$pmid.','.$sender.',\''.$rt.'\',\''.$rb.'\','.$viewer_id.'
   $mail .= '<button class="form-control btn btn-primary btn-md" id="'.$rb.'" onclick="replyToPm('.$pmid.','.$viewed_id.',\''.$rt.'\',\''.$rb.'\','.$sender.')">Reply</button>';
        $mail .= '</div>';
    }
}
    echo $mail; ?>

<?php

} else {
    require_once('template_pm.php');
}
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
                } else {
                    $category_totals[count($category_totals) - 1][1]++;
                }
            }
        // Generate and display the mismatched category bar graph image
        echo '<h4>You match with this user in the following ways:</h4>';
            draw_bar_graph(480, 240, $category_totals, 5, 'MM_UPLOADPATH' . $_SESSION['user_id'] . '-mymismatchgraph.png');
            echo '<img src="' . 'MM_UPLOADPATH' . $_SESSION['user_id'] . '-mymismatchgraph.png" alt="Mismatch category graph" />';
            echo '</td></tr></table>';
        //end bar graph
        // Display the matched topics in a table with four columns
        echo '<h4>You can talk about these ' . count($mismatch_topics) . ' topics:</h4>';
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
    require_once('footer.php');
    exit();
}
// End of check for a single row of user results
else {
    echo '<p class="error">There was a problem accessing your profile.</p>';
}
if (!isset($_GET['user_id']) || ($_SESSION['user_id'] == $_GET['user_id'])) {
    echo '<p>Would you like to <a href="editprofile.php">edit your profile</a>?</p>';
} else {
    echo '<p>You must first <a href="questionnaire.php">answer the questionnaire</a> before you can be mismatched.</p>';
}

  mysqli_close($dbc);


require_once('footer.php');

?>
<meta charset="UTF-8">

</div>
</body> 
<!--
if (isset($sender) && (isset($receiver)) && ($viewer_id == $viewed_id) || (isset($sender) && (isset($receiver)) && $sender == $viewer_id)) {
<!--
$sql = "SELECT * FROM pm WHERE 
(receiver='$viewer_id' AND parent='x' ) 
OR 
(sender='$viewer_id' AND sdelete='0' AND parent='x' ) 
ORDER BY senttime DESC";
-->