<div id="notesBox"><h2>Notifications</h2></div>
<div align:left;>
<?php
require_once('startsession.php');
require_once('appvars.php');
require_once('connectvars.php');
require_once('php_functions.php');
require_once('header.php');

        
echo "This is file pm_inbox.php<br />";
// Initialize any variables that the page might echo
$viewer_id = $_SESSION['user_id'];
$mail = "";
// Make sure the _GET username is set, and sanitize it
if (isset($_SESSION["user_id"])) {
    $viewed_id = preg_replace('#[^a-z0-9]#i', '', $_SESSION['user_id']);
} else {
}
// Select the member from the users table
$sql = "SELECT * FROM mismatch_user WHERE user_id='$viewed_id' AND activated='1' LIMIT 1";
$user_query = mysqli_query($dbc, $sql);

// Now make sure that user exists in the table
$numrows = mysqli_num_rows($user_query);
if ($numrows < 1) {
    echo "That user does not exist or is not yet activated, press back";
    exit();
}

// Check to see if the viewer is the account owner
$isOwner = "no";
if ($viewed_id == $_SESSION['user_id']) {
    $isOwner = "yes";
}
if ($isOwner != "yes") {
    header("location: index.php");

    exit();
}
// Get list of parent pm's not deleted
$sql = "SELECT * FROM pm WHERE 
(receiver='$viewed_id' AND parent='x' AND rdelete='0' AND hasreplies='0') 
OR
(receiver='$viewed_id' AND parent='x' AND rdelete='0' AND hasreplies='1') 
OR 
(sender='$viewed_id' AND sdelete='0' AND parent='x' AND hasreplies='1') 
OR
(sender='$viewer_id' AND parent='x' AND rdelete='0' AND hasreplies='0')
ORDER BY senttime ASC";
$query = mysqli_query($dbc, $sql);
$statusnumrows = mysqli_num_rows($query);
print_r($statusnumrows);

echo '<div id="div1"></div>';

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
        $sdelete = "";
        $rdelete = "";

        if ($sender == $_SESSION['user_id']) {
            $sender = $row["receiver"];
        }

        $query1 = "SELECT user_id, username, first_name, last_name, gender, birthdate, city, state, picture FROM mismatch_user WHERE user_id = '" . $sender . "'";
        $data1 = mysqli_query($dbc, $query1);
        $row1 = mysqli_fetch_array($data1);
        $frm = $row1['username'];
        $frm_id = $row1['user_id'];
        $profile_pic = $row1['picture'];
  
    
        // Start to build our list of parent pm's
        $mail .= '<div id="'.$wrap.'" class="pm_wrap form-group">';
        $mail .= '<div class="pm_header form-control"><a href="viewprofile.php?user_id='.$frm_id.'"><img class="friendpics img-circle" src="' . MM_UPLOADPATH . $profile_pic . '" class="img-circle" alt="Profile Picture" alt="'.$frm.'" title="'.$frm.'"></a><br /><b>Subject: </b>'.$subject.'<br /><br />';
        // Add button for mark as read
        $mail .= '<button class="btn btn-primary btn-sm" onclick="markRead('.$pmid.','.$sender.')">Mark As Read</button>';

        $mail .= '<button id="hide'. $frm_id .'" class="btn  btn-sm" style="float:right">Hide</button>
<button  id="show'. $frm_id.'" class="btn btn-primary btn-sm" style="float:right">Show</button>';
        // Add Delete button
        $mail .= '<button class="btn btn-warning btn-sm" id="'.$btid2.'" onclick="deletePm('.$pmid.',\''.$wrap.'\','.$sender.')">Delete</button></div>'; ?> 
<!-- JQ for the show/hide messages -->
        <script type="text/javascript">
$(document).ready(function(){
    $("#hide<?php echo $frm_id; ?>").click(function(){
        $("#<?php echo $pmid2; ?>").hide('slow');
    });
    $("#show<?php echo $frm_id ?>").click(function(){
        $("#<?php echo $pmid2; ?>").show('slow');
    });
});
</script>
<!-- END JQ for the show hide messages -->

<?php


        $mail .= '<div style="display:none;" class="msg_bod" id="'.$pmid2.'">';//start expanding area
        $mail .= '<div class="pm_post">From: '.$frm.' - '.$time.'<br />'.$message.'</div>';
        
        // Gather up any replies to the parent pm's
        $pm_replies = "";
        $query_replies = mysqli_query($dbc, "SELECT sender, message, senttime, deletetime FROM pm WHERE parent='$pmid' ORDER BY senttime ASC");
        $replynumrows = mysqli_num_rows($query_replies);
        if ($replynumrows > 0) {
            while ($row2 = mysqli_fetch_array($query_replies, MYSQLI_ASSOC)) {
                $rsender = $row2["sender"];
                $reply = $row2["message"];
                $time2 = $row2["senttime"];
                $deletetime = $row2["deletetime"];
                $mail .= '<div class ="pm_post ">Your reply: on '.$time2.'....<br />'.$reply.'<br /></div>';
            }
        }
//Message debug unit
$debug = 1;
        if ($debug == 1) {
            echo "Has replies: $hasreplies<br>";
            
            echo "Logged in username: $username<br>";
            echo "Logged in viewer_id: $viewer_id<br>";
            echo "receiver: $receiver <br>";
            echo "Sender: $sender <br>";
            echo "viewed_id: $viewed_id<br>";
            echo "sdelete: $sdelete<br>";
            echo "rdelete: $rdelete<br>";
            echo "pm id: $pmid<br>";
            echo "sender: $sender<br>";
            echo "rt: $rt<br>";
            echo "rb: $rb<br>";
            echo "frm: $frm<br>";
            echo "Delete time: $deletetime<br>";
            
            
//echo "pm_: $pm_<br>";
        }
        // Each parent and child is now listed
        $mail .= '</div>';
        // Add reply textbox
        $mail .= '<textarea class="form-control" id="'.$rt.'" width="100" placeholder="Reply..."></textarea><br />';
        // Add reply button
        
         $mail .= '<button class="form-control btn btn-primary btn-md" id="'.$rb.'" onclick="replyToPm('.$pmid.','.$viewed_id.',\''.$rt.'\',\''.$rb.'\','.$sender.')">Reply</button>';
        $mail .= '</div>';
        echo "PM inbox:<br />";
    }
}
?>
<?php echo $mail; ?>
