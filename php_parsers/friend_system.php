<?php
require_once('startsession.php');
if (isset($_POST['type']) && isset($_POST['user_id'])){
  $user = preg_replace('#[^a-z0-9]#i', '', $_POST['user_id']);
  $sql = "SELECT COUNT(id) FROM mismatch_user WHERE username='$user' AND activated='1' LIMIT 1";
  $query = mysqli_query($dbc, $sql);
  $exist_count = mysqli_fetch_row($query);
  if($exist_count[0] < 1){
    mysqli_close($dbc);
    echo "$user does not exist.";
    exit();
  }
  if($_POST['type'] == "friend"){
    $sql = "SELECT COUNT(id) FROM friends WHERE user1='$user' AND accepted='1' OR user2='$user' AND accepted='1'";
    $query = mysqli_query($dbc, $sql);
    $friend_count = mysqli_fetch_row($query);
    $sql = "SELECT COUNT(id) FROM blockedusers WHERE blocker='$user' AND blockee='$viewer' LIMIT 1";
    $query = mysqli_query($dbc, $sql);
    $blockcount1 = mysqli_fetch_row($query);
    $sql = "SELECT COUNT(id) FROM blockedusers WHERE blocker='$viewer' AND blockee='$user' LIMIT 1";
    $query = mysqli_query($dbc, $sql);
    $blockcount2 = mysqli_fetch_row($query);
    $sql = "SELECT COUNT(id) FROM friends WHviewer' AND user2='$user' AND accepted='1' LIMIT 1";
    $query = mysqli_query($dbc, $sql);
    $row_count1 = mysqli_fetch_row($query);
    $sql = "SELECT COUNT(id) FROM friends WHERE user1='$user' AND user2='$viewer' AND accepted='1' LIMIT 1";
    $query = mysqli_query($dbc, $sql);
    $row_count2 = mysqli_fetch_row($query);
    $sql = "SELECT COUNT(id) FROM friends WHERE user1='$viewer' AND user2='$user' AND accepted='0' LIMIT 1";
    $query = mysqli_query($dbc, $sql);
    $row_count3 = mysqli_fetch_row($query);
    $sql = "SELECT COUNT(id) FROM friends WHERE user1='$user' AND user2='$viewer' AND accepted='0' LIMIT 1";
    $query = mysqli_query($dbc, $sql);
    $row_count4 = mysqli_fetch_row($query);
      if($friend_count[0] > 99){
            mysqli_close($dbc);
          echo "$user currently has the maximum number of friends, and cannot accept more.";
          exit();
        } else if($blockcount1[0] > 0){
            mysqli_close($dbc);
          echo "$user has you blocked, we cannot proceed.";
          exit();
        } else if($blockcount2[0] > 0){
            mysqli_close($dbc);
          echo "You must first unblock $user in order to friend with them.";
          exit();
        } else if ($row_count1[0] > 0 || $row_count2[0] > 0) {
        mysqli_close($dbc);
          echo "You are already friends with $user.";
          exit();
      } else if ($row_count3[0] > 0) {
        mysqli_close($dbc);
          echo "You have a pending friend request already sent to $user.";
          exit();
      } else if ($row_count4[0] > 0) {
        mysqli_close($dbc);
          echo "$user has requested to friend with you first. Check your friend requests.";
          exit();
      } else {
          $sql = "INSERT INTO friends(user1, user2, datemade) VALUES('$viewer','$user',now())";
        $query = mysqli_query($dbc, $sql);
      mysqli_close($dbc);
          echo "friend_request_sent";
          exit();
    }
  } else if($_POST['type'] == "unfriend"){
    $sql = "SELECT COUNT(id) FROM friends WHERE user1='$viewer' AND user2='$user' AND accepted='1' LIMIT 1";
    $query = mysqli_query($dbc, $sql);
    $row_count1 = mysqli_fetch_row($query);
    $sql = "SELECT COUNT(id) FROM friends WHERE user1='$user' AND user2='$viewer' AND accepted='1' LIMIT 1";
    $query = mysqli_query($dbc, $sql);
    $row_count2 = mysqli_fetch_row($query);
      if ($row_count1[0] > 0) {
          $sql = "DELETE FROM friends WHERE user1='$viewer' AND user2='$user' AND accepted='1' LIMIT 1";
      $query = mysqli_query($dbc, $sql);
      mysqli_close($dbc);
          echo "unfriend_ok";
          exit();
      } else if ($row_count2[0] > 0) {
      $sql = "DELETE FROM friends WHERE user1='$user' AND user2='$viewer' AND accepted='1' LIMIT 1";
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
?>