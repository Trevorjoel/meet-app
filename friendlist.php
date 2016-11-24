<?php
require_once('startsession.php');

//START make FRIENDS LIST

if (!isset($_GET['user_id']) || ($_SESSION['user_id'] == $_GET['user_id'])) {
    $viewed_id = $_SESSION['user_id'];
    $viewed = $_SESSION['username'];
    }
    if (!isset($_GET['user_id']) || ($_SESSION['user_id'] == $_GET['user_id'])) {
        print "<hr /><br><h4>Your friends:</h4>";
    }else{
print "<hr /><br><h4>$viewed's friends:</h4>"; 
}

$friendsHTML = '';
$friends_view_all_link = '';
$sql = "SELECT COUNT(id) FROM friends WHERE user1='$viewed_id' AND accepted='1' OR user2='$viewed_id' AND accepted='1'";
$query = mysqli_query($dbc, $sql);
$query_count = mysqli_fetch_row($query);
$friend_count = $query_count[0];
if($friend_count < 1){
    $friendsHTML = $viewed." has no friends yet";
} else {
    $max = 18;
    $all_friends = array();
    $sql = "SELECT user1 FROM friends WHERE user2='$viewed_id' AND accepted='1' ORDER BY RAND() LIMIT $max";

    $query = mysqli_query($dbc, $sql);
    
    
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        array_push($all_friends, $row["user1"]);
    }
    $sql = "SELECT user2 FROM friends WHERE user1='$viewed_id' AND accepted='1' ORDER BY RAND() LIMIT $max";
    $query = mysqli_query($dbc, $sql);
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        array_push($all_friends, $row["user2"]);

    }

    $friendArrayCount = count($all_friends);
    if($friendArrayCount > $max){
        array_splice($all_friends, $max);
    }
    if($friend_count > $max){
        $friends_view_all_link = '<a href="view_friends.php?u='.$viewed_id.'">view all</a>';

    }

    $orLogic = '';

    foreach($all_friends as $key => $user){
            $orLogic .= "user_id='$user' OR ";
            
    }

    $orLogic = chop($orLogic, "OR ");

    $sql = "SELECT user_id, username, picture FROM mismatch_user WHERE $orLogic";

    $query = mysqli_query($dbc, $sql);
    
    while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        $friend_id = $row["user_id"];
        $friend_username = $row["username"];
        $friend_avatar = $row["picture"];
        if($friend_avatar != ""){
            $friendsHTML .= 
        '<a href="viewprofile.php?user_id='.$friend_id.'"><img class="friendpics img-circle" src="' . MM_UPLOADPATH . $row['picture'] . '" class="img-circle" alt="Profile Picture" alt="'.$friend_username.'" title="'.$friend_username.'"></a>';


        } else {
            $friendsHTML .= 
        '<a href="viewprofile.php?user_id='.$friend_id.'"><img class="friendpics" src="images/nopic.jpg" class="friendpics img-circle" alt="Profile Picture" alt="'.$friend_username.'" title="'.$friend_username.'"></a>';

        }
        
        
    }

}


echo "$friendsHTML<hr>";
//END code for LIST FRIENDS
?>