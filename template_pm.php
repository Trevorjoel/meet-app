<?php
require_once('startsession.php');
require_once('appvars.php');
require_once('connectvars.php');
require_once('php_functions.php');
require_once('header.php');
// Protect this script from direct url access
// You may further enhance this protection by checking for certain sessions and other means

// Initialize our ui
$pm_ui = "";
// If visitor to profile is a friend and is not the owner can send you a pm
// Build ui carry the profile id, vistor name, pm subject and comment to js

$isOwner = true;
$isFriend = "false";
$ownerBlockViewer = "false";
$viewerBlockOwner = "false";
$viewer_id = $_SESSION['user_id'];
if (isset($_GET['user_id'])) {
    $viewed_id = $_GET['user_id'];
} else {
    $viewed_id = $_SESSION['user_id'];
    $viewed = $_SESSION['username'];
}
  
$friend_check = "SELECT id FROM friends WHERE user1='$viewed_id' AND user2='$viewer_id' 
  OR  user1='$viewer_id' AND user2='$viewed_id' AND accepted='1' LIMIT 1";
    
if (mysqli_num_rows(mysqli_query($dbc, $friend_check)) > 0) {
    $isFriend = true;
}
   
    $block_check1 = "SELECT id FROM blockedusers WHERE blocker='$viewed' AND blockee='$viewer_id' LIMIT 1";
if (mysqli_num_rows(mysqli_query($dbc, $block_check1)) > 0) {
    $ownerBlockViewer = true;
}
    $block_check2 = "SELECT id FROM blockedusers WHERE blocker='$viewer_id' AND blockee='$viewed_id' LIMIT 1";
if (mysqli_num_rows(mysqli_query($dbc, $block_check2)) > 0) {
    $viewerBlockOwner = true;
}
    
if ($isFriend == 1) {
    $pm_ui .= '<div class="form-group box-width box-shade" style="">
	<h5>Message  '.$viewed.':</h5><br><input class="form-control" id="pmsubject" onkeyup="statusMax(this,30)" placeholder="Subject of pm..."><br />';
    $pm_ui .= '<textarea class="form-control" id="pmtext" onkeyup="statusMax(this,250)" placeholder="Send '.$viewed.' a private message"></textarea><br />';
    $pm_ui .= '<button class="form-control btn btn-primary btn-md" id="pmBtn" onclick="postPm(\''.$viewed_id.'\',\''.$viewer_id.'\',\'pmsubject\',\'pmtext\')">Send</button></div>';
} else {
    $pm_ui .= '<div class="form-group" style="background-color:#e3e4f6; width:350px;" ><h5>You must be friends and not blocking '.$viewed.' to send a message:</h5><br><input class="form-control" id="pmsubject" onkeyup="statusMax(this,30)" placeholder="Subject of pm..."><br />';
    $pm_ui .= '<textarea class="form-control" id="pmtext" onkeyup="statusMax(this,250)" placeholder="You must be friends and not blocking '.$viewed.' to send a message."></textarea><br>';
    $pm_ui .= '<button disabled class="form-control btn btn-primary btn-md" id="pmBtn" onclick="postPm(\''.$viewed_id.'\',\''.$viewer_id.'\',\'pmsubject\',\'pmtext\')">Send</button></div>';
}

?>
<script>
function postPm(tuser,fuser,subject,ta){
	var data = get_id(ta).value;
	var data2 = get_id(subject).value;
	if(data == "" || data2 == ""){
		alert("Fill all fields");
		return false;
	}
	get_id("pmBtn").disabled = true;
	var ajax = ajaxObj("POST", "php_parsers/pm_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "pm_sent"){
				alert("Message has been sent.");
				get_id("pmBtn").disabled = false;
				get_id(ta).value = "";
				get_id(subject).value = "";
			} else {
				alert(ajax.responseText);
			}
		}
	}
	ajax.send("action=new_pm&fuser="+fuser+"&tuser="+tuser+"&data="+data+"&data2="+data2);
}
function statusMax(field, maxlimit) {
	if (field.value.length > maxlimit){
		alert(maxlimit+" maximum character limit reached");
		field.value = field.value.substring(0, maxlimit);
	}
}

</script>
<?php
$debug = 1;
if ($debug == 1) {
    echo "Initiate conversation:";
    echo "Has replies: $hasreplies<br>";
    echo "Logged in username: $username<br>";
    echo "Logged in viewer_id: $viewer_id<br>";
    echo "receiver: $receiver <br>";
    echo "Sender: $sender <br>";
    echo "viewed_id: $viewed_id<br>";
    echo "sdelete: $sdelete<br>";
    echo "rdelete: $rdelete<br>";
//echo "pm id: $pmid<br>";
}
?>
<div id="statusui">
    <?php echo $pm_ui; ?>
</div>