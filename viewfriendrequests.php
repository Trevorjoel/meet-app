<?php
require_once('startsession.php');
require_once('connectvars.php');
    $friendsHTML           = '';
    $friends_view_all_link = '';
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $sql                   = "SELECT COUNT(id) FROM friends WHERE user1='$viewed_id' AND accepted='1' OR user2='$viewed_id' AND accepted='1'";
    $query                 = mysqli_query($dbc, $sql);
    $query_count           = mysqli_fetch_row($query);
    $friend_count          = $query_count[0];
    if ($friend_count < 1) {
        $friendsHTML = $viewed_id . " has no friends yet";

    } else {
        $max         = 18;
        $all_friends = array();
        $sql         = "SELECT user1 FROM friends WHERE user2='$viewed_id' AND accepted='1' ORDER BY RAND() LIMIT $max";
        $query       = mysqli_query($dbc, $sql);
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            array_push($all_friends, $row["user1"]);
        }
        $sql   = "SELECT user2 FROM friends WHERE user1='$viewed_id' AND accepted='1' ORDER BY RAND() LIMIT $max";
        $query = mysqli_query($dbc, $sql);
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            array_push($all_friends, $row["user2"]);
        }
        $friendArrayCount = count($all_friends);
        if ($friendArrayCount > $max) {
            array_splice($all_friends, $max);
        }
        if ($friend_count > $max) {
            $friends_view_all_link = '<a href="view_friends.php?u=' . $viewed_id . '">view all</a>';
        }
        $orLogic = '';
        foreach ($all_friends as $key => $user) {
            $orLogic .= "username='$user' OR ";
        }
        $orLogic = chop($orLogic, "OR ");
        $sql     = "SELECT username, picture FROM mismatch_user WHERE $orLogic";
        $query   = mysqli_query($dbc, $sql);
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            $friend_username = $row["username"];
            $friend_picture  = $row["picture"];
            if ($friend_picture != "") {
                $friend_pic = 'user/' . $friend_username . '/' . $friend_picture . '';
            } else {
                $friend_pic = 'images/nopic.jpg';
            }
            $friendsHTML .= '<a href="user.php?u=' . $friend_username . '"><img class="friendpics" src="' . $friend_pic . '" alt="' . $friend_username . '" title="' . $friend_username . '"></a>';
        }
    }
   // print_r($all_friends);
    ?>