<?php
include_once("php_includes/check_login_status.php");
// Initialize any variables that the page might echo
$u = "";
$sex = "Male";
$userlevel = "";
$country = "";
$joindate = "";
$lastsession = "";
// Make sure the _GET username is set, and sanitize it
if(isset($_GET["u"])){
  $u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
} else {
    header("location: http://www.yoursite.com");
    exit(); 
}
// Select the member from the users table
$sql = "SELECT * FROM users WHERE username='$u' AND activated='1' LIMIT 1";
$user_query = mysqli_query($db_conx, $sql);
// Now make sure that user exists in the table
$numrows = mysqli_num_rows($user_query);
if($numrows < 1){
  echo "That user does not exist or is not yet activated, press back";
    exit(); 
}
// Check to see if the viewer is the account owner
$isOwner = "no";
if($u == $log_username && $user_ok == true){
  $isOwner = "yes";
}
// Fetch the user row from the query above
while ($row = mysqli_fetch_array($user_query, MYSQLI_ASSOC)) {
  $profile_id = $row["id"];
  $gender = $row["gender"];
  $country = $row["country"];
  $userlevel = $row["userlevel"];
  $signup = $row["signup"];
  $lastlogin = $row["lastlogin"];
  $joindate = strftime("%b %d, %Y", strtotime($signup));
  $lastsession = strftime("%b %d, %Y", strtotime($lastlogin));
  if($gender == "f"){
    $sex = "Female";
  }
}
?><?php
$isFriend = false;
$ownerBlockViewer = false;
$viewerBlockOwner = false;
if($u != $log_username && $user_ok == true){
  $friend_check = "SELECT id FROM friends WHERE user1='$log_username' AND user2='$u' AND accepted='1' OR user1='$u' AND user2='$log_username' AND accepted='1' LIMIT 1";
  if(mysqli_num_rows(mysqli_query($db_conx, $friend_check)) > 0){
        $isFriend = true;
    }
  $block_check1 = "SELECT id FROM blockedusers WHERE blocker='$u' AND blockee='$log_username' LIMIT 1";
  if(mysqli_num_rows(mysqli_query($db_conx, $block_check1)) > 0){
        $ownerBlockViewer = true;
    }
  $block_check2 = "SELECT id FROM blockedusers WHERE blocker='$log_username' AND blockee='$u' LIMIT 1";
  if(mysqli_num_rows(mysqli_query($db_conx, $block_check2)) > 0){
        $viewerBlockOwner = true;
    }
}
?><?php 
$friend_button = '<button disabled>Request As Friend</button>';
$block_button = '<button disabled>Block User</button>';
// LOGIC FOR FRIEND BUTTON
if($isFriend == true){
  $friend_button = '<button onclick="friendToggle(\'unfriend\',\''.$u.'\',\'friendBtn\')">Unfriend</button>';
} else if($user_ok == true && $u != $log_username && $ownerBlockViewer == false){
  $friend_button = '<button onclick="friendToggle(\'friend\',\''.$u.'\',\'friendBtn\')">Request As Friend</button>';
}
// LOGIC FOR BLOCK BUTTON
if($viewerBlockOwner == true){
  $block_button = '<button onclick="blockToggle(\'unblock\',\''.$u.'\',\'blockBtn\')">Unblock User</button>';
} else if($user_ok == true && $u != $log_username){
  $block_button = '<button onclick="blockToggle(\'block\',\''.$u.'\',\'blockBtn\')">Block User</button>';
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $u; ?></title>
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="style/style.css">
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script type="text/javascript">
function friendToggle(type,user,elem){
  var conf = confirm("Press OK to confirm the '"+type+"' action for user <?php echo $viewed; ?>.");
  if(conf != true){
    return false;
  }
  _(elem).innerHTML = 'please wait ...';
  var ajax = ajaxObj("POST", "php_parsers/friend_system.php");
  ajax.onreadystatechange = function() {
    if(ajaxReturn(ajax) == true) {
      if(ajax.responseText == "friend_request_sent"){
        _(elem).innerHTML = 'OK Friend Request Sent';
      } else if(ajax.responseText == "unfriend_ok"){
        _(elem).innerHTML = '<button onclick="friendToggle(\'friend\',\'<?php echo $u; ?>\',\'friendBtn\')">Request As Friend</button>';
      } else {
        alert(ajax.responseText);
        _(elem).innerHTML = 'Try again later';
      }
    }
  }
  ajax.send("type="+type+"&user="+user);
}
function blockToggle(type,blockee,elem){
  var conf = confirm("Press OK to confirm the '"+type+"' action on user <?php echo $u; ?>.");
  if(conf != true){
    return false;
  }
  var elem = document.getElementById(elem);
  elem.innerHTML = 'please wait ...';
  var ajax = ajaxObj("POST", "php_parsers/block_system.php");
  ajax.onreadystatechange = function() {
    if(ajaxReturn(ajax) == true) {
      if(ajax.responseText == "blocked_ok"){
        elem.innerHTML = '<button onclick="blockToggle(\'unblock\',\'<?php echo $u; ?>\',\'blockBtn\')">Unblock User</button>';
      } else if(ajax.responseText == "unblocked_ok"){
        elem.innerHTML = '<button onclick="blockToggle(\'block\',\'<?php echo $u; ?>\',\'blockBtn\')">Block User</button>';
      } else {
        alert(ajax.responseText);
        elem.innerHTML = 'Try again later';
      }
    }
  }
  ajax.send("type="+type+"&blockee="+blockee);
}
</script>
</head>
<body>
<?php include_once("template_pageTop.php"); ?>
<div id="pageMiddle">
  <h2><?php echo $u; ?></h2>
  <p>Is the viewer the page owner, logged in and verified? <b><?php echo $isOwner; ?></b></p>
  <p>Gender: <?php echo $sex; ?></p>
  <p>Country: <?php echo $country; ?></p>
  <p>User Level: <?php echo $userlevel; ?></p>
  <p>Join Date: <?php echo $joindate; ?></p>
  <p>Last Session: <?php echo $lastsession; ?></p>
  <hr />
  
<?php include_once("template_pageBottom.php"); ?>
</body>
</html>
