<?php
require_once('startsession.php');
$page_title = 'Social';
require_once('header.php');
  require_once('appvars.php');
  require_once('connectvars.php');
  require_once('php_functions.php'); 
// Make sure the user is logged in before going any further.
  if (!isset($_SESSION['user_id'])) {
    exit();
    }
   /* if  (isset($_GET['user_id']) || ($_SESSION['user_id'] == $_GET['user_id'])) {
*/
    	$viewer_id = $_SESSION['user_id'];
    	$viewer = $_SESSION['username'];
    	
 ?>
  <!--BEGIN Js code for accepting and rejecting friends-->
<script type="text/javascript">
function friendReqHandler(action,reqid,user1,elem){
	var conf = confirm("Press OK to '"+action+"' this friend request.");
	if(conf != true){
		return false;
	}
	get_id(elem).innerHTML = "processing ...";
	var ajax = ajaxObj("POST", "php_parsers/friend_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "accept_ok"){
				get_id(elem).innerHTML = "<b>Request Accepted!</b><br />Your are now friends";
			} else if(ajax.responseText == "reject_ok"){
				get_id(elem).innerHTML = "<b>Request Rejected</b><br />You chose to reject friendship with this user";
			} else {
				get_id(elem).innerHTML = ajax.responseText;
			}
		}
	}
	ajax.send("action="+action+"&reqid="+reqid+"&user1="+user1);
}
</script>
<!--END Js code for accepting and rejecting friends -->

<?php
//BEGIN FRIEND REQUESTS 
$friend_requests = "";
$sql = "SELECT * FROM friends WHERE user2='$viewer_id' AND accepted='0' ORDER BY datemade ASC";
$query = mysqli_query($dbc, $sql);

$numrows = mysqli_num_rows($query);
if($numrows < 1){

	$friend_requests = 'No friend requests';
} else {
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$reqID = $row["id"];
		$user1 = $row["user1"];
		$datemade = $row["datemade"];
		$datemade = strftime("%B %d", strtotime($datemade));
		$thumbquery = mysqli_query($dbc, "SELECT picture FROM mismatch_user WHERE user_id='$user1' LIMIT 1");		
    if (!$thumbquery) {
    printf("Error: %s\n", mysqli_error($dbc));
    exit();
}
		
		$thumbrow = mysqli_fetch_row($thumbquery);
		$user1avatar = $thumbrow[0];
		$user1pic = '<img src="images/'.  $thumbrow[0].'" alt="'.$user1.'" class="user_pic">';
		if($user1avatar == NULL){
			$user1pic = '<img src="images/nopic.jpg" alt="'.$user1.'" class="user_pic">';
		}
		$friend_requests .= '<div id="friendreq_'.$reqID.'" class="friendrequests">';
		$friend_requests .= '<a href="user.php?u='.$user1.'">'.$user1pic.'</a>';
		$friend_requests .= '<div class="user_info" id="user_info_'.$reqID.'">'.$datemade.' <a href="user.php?u='.$user1.'">'.$user1.'</a> requests friendship<br /><br />';
		$friend_requests .= '<button onclick="friendReqHandler(\'accept\',\''.$reqID.'\',\''.$user1.'\',\'user_info_'.$reqID.'\')">accept</button> or ';
		$friend_requests .= '<button onclick="friendReqHandler(\'reject\',\''.$reqID.'\',\''.$user1.'\',\'user_info_'.$reqID.'\')">reject</button>';
		$friend_requests .= '</div>';
		$friend_requests .= '</div>';
	}
}

?>

  <div id="friendReqBox"><h2>Friend Requests</h2><?php echo $friend_requests; ?></div>
  <div style="clear:left;"></div>
  <?php 

  ?>
  <!-- END friend requests -->
</div>

    
