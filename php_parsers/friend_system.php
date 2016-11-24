 <?php
include_once("../startsession.php");
include_once("../connectvars.php");
//This file parses data from the add friends block user features
//select data for the logged user and ajax posted data
if (isset($_POST['type']) && isset($_POST['user']))  {
 $dbc         = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
     $query = "SELECT user_id, username, first_name, last_name, gender, birthdate, city, state, picture FROM mismatch_user WHERE user_id = '" . $_POST['user'] . "'";
    $data = mysqli_query($dbc, $query);    
    $row = mysqli_fetch_array($data);
    $viewed = $row['username'];
    $viewed_id = $row['user_id'];   
    $query1 = "SELECT username FROM mismatch_user WHERE user_id = '" . $_SESSION['user_id'] . "'";
    $data1 = mysqli_query($dbc, $query1);
    $row = mysqli_fetch_array($data1);
    $viewer = $row['username'];
    $viewer_id = $_SESSION['user_id'];
    $user        = preg_replace('#[^a-z0-9]#i', '', $_POST['user']);
    $dbc         = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $sql         = "SELECT (user_id) FROM mismatch_user WHERE user_id='$user' AND activated='1' LIMIT 1";
    $query       = mysqli_query($dbc, $sql);
    $exist_count = mysqli_fetch_row($query);
    if ($exist_count[0] < 1) {
        mysqli_close($dbc);
        echo "$user does not exist.";
        exit();
    }
    
    //Add friends
    if($_POST['type'] == "friend"){
    $sql = "SELECT COUNT(id) FROM friends WHERE user1='$user' OR user2='$user' AND accepted='1'";
    $query = mysqli_query($dbc, $sql);
    $friend_count = mysqli_fetch_row($query);
    $sql = "SELECT COUNT(id) FROM blockedusers WHERE blocker='$user' AND blockee='$viewer_id' LIMIT 1";
    $query = mysqli_query($dbc, $sql);
    $blockcount1 = mysqli_fetch_row($query);
    $sql = "SELECT COUNT(id) FROM blockedusers WHERE blocker='$viewer_id' AND blockee='$user' LIMIT 1";
    $query = mysqli_query($dbc, $sql);
    $blockcount2 = mysqli_fetch_row($query);
    $sql = "SELECT COUNT(id) FROM friends WHERE user1='$viewer_id' AND user2='$user' AND accepted='1' LIMIT 1";
    $query = mysqli_query($dbc, $sql);
    $row_count1 = mysqli_fetch_row($query);
    $sql = "SELECT COUNT(id) FROM friends WHERE user1='$user' AND user2='$viewer_id' AND accepted='1' LIMIT 1";
    $query = mysqli_query($dbc, $sql);
    $row_count2 = mysqli_fetch_row($query);
    $sql = "SELECT COUNT(id) FROM friends WHERE user1='$viewer_id' AND user2='$user' AND accepted='0' LIMIT 1";
    $query = mysqli_query($dbc, $sql);
    $row_count3 = mysqli_fetch_row($query);
    $sql = "SELECT COUNT(id) FROM friends WHERE user1='$user' AND user2='$viewer_id' AND accepted='0' LIMIT 1";
    $query = mysqli_query($dbc, $sql);
    $row_count4 = mysqli_fetch_row($query);
      if($friend_count[0] > 99){
            mysqli_close($dbc);
          echo "$viewed currently has the maximum number of friends, and cannot accept more.";
          exit();
        } else if($blockcount1[0] > 0){
            mysqli_close($dbc);
          echo "$viewed has you blocked, we cannot proceed.";
          exit();
        } else if($blockcount2[0] > 0){
            mysqli_close($dbc);
          echo "You must first unblock $viewed in order to friend with them.";
          exit();
        } else if ($row_count1[0] > 0 || $row_count2[0] > 0) {
        mysqli_close($dbc);
          echo "You are already friends with $viewed.";
          exit();
      } else if ($row_count3[0] > 0) {
        mysqli_close($dbc);
          echo "You have a pending friend request already sent to $viewed.";
          exit();
      } else if ($row_count4[0] > 0) {
        mysqli_close($dbc);
          echo "$viewed has requested to friend with you first. Check your friend requests.";
          exit();
      } else {
          $sql = "INSERT INTO friends(user1, user2, datemade) VALUES('$viewer_id','$user',now())";
        $query = mysqli_query($dbc, $sql);
      mysqli_close($dbc);
          echo "friend_request_sent";
          exit();
    }
    //Unfriend 
  } else if($_POST['type'] == "unfriend"){
    
    $sql = "SELECT COUNT(id) FROM friends WHERE user1='$viewer_id' AND user2='$user' AND accepted='1' LIMIT 1";
    $query = mysqli_query($dbc, $sql);
    $row_count1 = mysqli_fetch_row($query);
    $sql = "SELECT COUNT(id) FROM friends WHERE user1='$user' AND user2='$viewer_id' AND accepted='1' LIMIT 1";
    $query = mysqli_query($dbc, $sql);
    $row_count2 = mysqli_fetch_row($query);
      if ($row_count1[0] > 0) {
          $sql = "DELETE FROM friends WHERE user1='$viewer_id' AND user2='$user' AND accepted='1' LIMIT 1";
      $query = mysqli_query($dbc, $sql);
      mysqli_close($dbc);
          echo "unfriend_ok";
          exit();
      } else if ($row_count2[0] > 0) {
      $sql = "DELETE FROM friends WHERE user1='$user' AND user2='$viewer_id' AND accepted='1' LIMIT 1";
      $query = mysqli_query($dbc, $sql);
      mysqli_close($dbc);
          echo "unfriend_ok";
          exit();
      } else {
      mysqli_close($dbc);
          echo "No friendship could be found between your account and $user, therefore we cannot unfriend you.";
          exit();
    }
  }
}
//Code for accepting friend requests
    if (isset($_POST['action']) && isset($_POST['reqid']) && isset($_POST['user1'])){
  $reqid = preg_replace('#[^0-9]#', '', $_POST['reqid']);
  $user = preg_replace('#[^a-z0-9]#i', '', $_POST['user1']);
  $viewer_id = ($_SESSION['user_id']);
  $sql = "SELECT COUNT(user_id) FROM mismatch_user WHERE user_id='$viewer_id' AND activated='1' LIMIT 1";
  $dbc         = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  $query = mysqli_query($dbc, $sql);
  $exist_count = mysqli_fetch_row($query);
  if($exist_count[0] < 1){
    mysqli_close($dbc);
    echo "$user does not exist.";
    exit();
  }
  if($_POST['action'] == "accept"){
    $sql = "SELECT COUNT(id) FROM friends WHERE user1='$viewer_id' AND user2='$user' AND accepted='1' LIMIT 1";

    $query = mysqli_query($dbc, $sql);
    $row_count1 = mysqli_fetch_row($query);
    $sql = "SELECT COUNT(id) FROM friends WHERE user1='$user' AND user2='$viewer_id' AND accepted='1' LIMIT 1";
    $query = mysqli_query($dbc, $sql);
    $row_count2 = mysqli_fetch_row($query);
      if ($row_count1[0] > 0 || $row_count2[0] > 0) {
        mysqli_close($dbc);
          echo "You are already friends with $viewed.";
          exit();
      } else {
      $sql = "UPDATE friends SET accepted='1' WHERE id='$reqid' AND user1='$user' AND user2='$viewer_id' LIMIT 1";
      $query = mysqli_query($dbc, $sql);
      mysqli_close($dbc);
          echo "accept_ok";
          exit();
    }
  } else if($_POST['action'] == "reject"){
    mysqli_query($dbc, "DELETE FROM friends WHERE id='$reqid' AND user1='$user' AND user2='$viewer_id' AND accepted='0' LIMIT 1");
    mysqli_close($dbc);
    echo "reject_ok";
    exit();
  }
}

?> 