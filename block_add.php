<?php
//START Code for ADD FRIENDS/BLOCK USERS
require_once('startsession.php');
require_once('appvars.php');
require_once('connectvars.php');
if (!isset($_GET['user_id']) || ($_GET['user_id'] )  == ($_SESSION['user_id']) ){

 }else{
$isOwner = "";
    $isFriend = "false";
$ownerBlockViewer = "false";
$viewerBlockOwner = "false";
$viewer_id = $_SESSION['user_id'];
if (isset ($_GET['user_id'])) {
  $viewed_id = $_GET['user_id'];
}
  
$friend_check = "SELECT id FROM friends WHERE user1='$viewed_id' AND user2='$viewer_id' OR  user1='$viewer_id' AND user2='$viewed_id' 
AND accepted='1' LIMIT 1";
    
   if(mysqli_num_rows(mysqli_query($dbc, $friend_check)) > 0){
        $isFriend = true;
    }
   
    $block_check1 = "SELECT id FROM blockedusers WHERE blocker='$viewed' AND blockee='$viewer_id' LIMIT 1";
  if(mysqli_num_rows(mysqli_query($dbc, $block_check1)) > 0){
        $ownerBlockViewer = true;
    }
    $block_check2 = "SELECT id FROM blockedusers WHERE blocker='$viewer_id' AND blockee='$viewed_id' LIMIT 1";
  if(mysqli_num_rows(mysqli_query($dbc, $block_check2)) > 0){
        $viewerBlockOwner = true;
    }
    
    
 $friend_button = '<button disabled>Request As Friend</button>';
 
// Code for FRIEND BUTTON
$isOwner = ($_GET['user_id']) == ($_SESSION['user_id']) ;
  
  if($isFriend == 1){
  $friend_button = '<button onclick="friendToggle(\'unfriend\',\''.$viewed_id.'\',\'friendBtn\')">Unfriend</button>';
} else{ 
  $friend_button = '<button onclick="friendToggle(\'friend\',\''.$viewed_id.'\',\'friendBtn\')">Request As Friend</button>';
  
}
// code for BLOCK BUTTON
if($viewerBlockOwner == 1){
  $block_button = '<button onclick="blockToggle(\'unblock\',\''.$viewed_id.'\',\'blockBtn\')">Unblock User</button>';
} else if ($viewed_id != $viewer_id){
  $block_button = '<button onclick="blockToggle(\'block\',\''.$viewed_id.'\',\'blockBtn\')">Block User</button>';
}
    
   

//END ADD FRIENDS/BLOCK USERS




?>
<span id="friendBtn"><?php echo $friend_button; ?></span>
  <span id="blockBtn"><?php echo $block_button; ?></span></p>
<!--START js for ADD FRIENDS/BLOCK USERS function -->
<script type="text/javascript">
function friendToggle(type,user,elem){
  var conf = confirm("Press OK to confirm the '"+type+"' action for user <?php echo $viewed; ?>.");
  if(conf != true){
    return false;
  }
  get_id(elem).innerHTML = 'please wait ...';
  var ajax = ajaxObj("POST", "php_parsers/friend_system.php");
  ajax.onreadystatechange = function() {
    if(ajaxReturn(ajax) == true) {
      if(ajax.responseText == "Friend request sent"){
        get_id(elem).innerHTML = 'OK Friend Request Sent';
      } else if(ajax.responseText == "unfriend ok"){
        get_id(elem).innerHTML = '<button onclick="friendToggle(\'friend\',\'<?php echo $viewed_id; ?>\',\'friendBtn\')">Request As Friend</button>';
      } else {
        alert(ajax.responseText);
        get_id(elem).innerHTML = '<button disabled>Request As Friend</button>';
      }
    }
  }
  ajax.send("type="+type+"&user="+user);
}
 
function blockToggle(type,blockee,elem){
  var conf = confirm("Press OK to confirm the '"+type+"' action on user <?php echo $viewed; ?>.");
  if(conf != true){
    return false;
  }
  var elem = document.getElementById(elem);
  elem.innerHTML = 'please wait ...';
  var ajax = ajaxObj("POST", "php_parsers/block_system.php");
  ajax.onreadystatechange = function() {
    if(ajaxReturn(ajax) == true) {
      if(ajax.responseText == "blocked_ok"){
        elem.innerHTML = '<button onclick="blockToggle(\'unblock\',\'<?php echo $viewer; ?>\',\'blockBtn\')">Unblock User</button>';
      } else if(ajax.responseText == "unblocked_ok"){
        elem.innerHTML = '<button onclick="blockToggle(\'block\',\'<?php echo $viewer; ?>\',\'blockBtn\')">Block User</button>';
      } else {
        alert(ajax.responseText);
        elem.innerHTML = 'Try again later';
      }
    }
  }
  ajax.send("type="+type+"&blockee="+blockee);
}
</script>
<!--END js for ADD FRIENDS/BLOCK USERS function -->
<?php
}
?>