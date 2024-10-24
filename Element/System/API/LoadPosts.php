<?php

$RootDir = $_SERVER["DOCUMENT_ROOT"];
require($RootDir . '/System/Scripts/Global/DataBase.php');
require($RootDir . '/System/Scripts/Global/Function.php');

$Account = AccountConnect();

if (!$Account) {
    exit();
}
if (empty($_GET['F'])) {
    exit();
}

$Function = $_GET['F'];

if (isset($Function)) {

    if (isset($_POST['StartIndex'])) {
        $StartIndex = intval($_POST['StartIndex']);
    }else{
        $StartIndex = intval(0);
    }

    $Limit = intval(25);

    if ($Function == 'USER') {
        $UserID = $_POST['UserID'];
        $Query = "SELECT * FROM `posts` WHERE `UserID` = :UserID ORDER BY `Date` DESC LIMIT :StartIndex, :Limit";
        $Request = $PDO->prepare($Query);
        $Request->bindParam(':UserID', $UserID);
        $Request->bindParam(':StartIndex', $StartIndex, PDO::PARAM_INT);
        $Request->bindParam(':Limit', $Limit, PDO::PARAM_INT);
        $Request->execute();
        if ($Request) {
            $Posts = array();
    
            while ($Post = $Request->fetch(PDO::FETCH_ASSOC)) {
                $PostID = $Post['PostID'];
                $AuthorID = $Post['UserID'];
                $Date = TimeAgo($Post['Date']);
                $Text = $Post['Text'];
                $Content = $Post['Content'];
    
                if ($Post['Likes'] > 0) {
                    $Likes = $Post['Likes'];
                    $Liked = UserLiked($Account['ID'], $PostID);
                    if ($Liked == true) {
                        $Liked = 'Liked';
                    }
                } else {
                    $Likes = 'Лайк';
                    $Liked = null;
                }
                if ($Post['Dislikes'] > 0) {
                    $Dislikes = $Post['Dislikes'];
                    $Disliked = UserDisliked($Account['ID'], $PostID);
                    if ($Disliked == true) {
                        $Disliked = 'Liked';
                    }
                } else {
                    $Dislikes = 'Дизлайк';
                    $Disliked = null;
                }
                if ($Post['Comments'] > 0) {
                    $Comments = $Post['Comments'];
                } else {
                    $Comments = 'Обсудить';
                }
                if ($AuthorID == $Account['ID']) {
                    $MyPost = true;
                } else {
                    $MyPost = false;
                }
    
                $PostData = array(
                    'PostID' => $PostID,
                    'Text' => $Text,
                    'Content' => $Content,
                    'Date' => $Date,
                    'Likes' => $Likes,
                    'Dislikes' => $Dislikes,
                    'Liked' => $Liked,
                    'Disliked' => $Disliked,
                    'Comments' => $Comments,
                    'MyPost' => $MyPost
                );
    
                $Posts[] = $PostData;
            }
    
            echo json_encode($Posts);
        }
    }

    if ($Function == 'LATEST') {
        $Query = "SELECT p.*, p.UserID as UserID, a.Name as Name, a.Username as Username, a.Avatar as Avatar FROM `posts` p INNER JOIN `accounts` a ON p.UserID = a.ID ORDER BY `Date` DESC LIMIT :StartIndex, :Limit";
        $Request = $PDO->prepare($Query);
        $Request->bindParam(':StartIndex', $StartIndex, PDO::PARAM_INT);
        $Request->bindParam(':Limit', $Limit, PDO::PARAM_INT);
        $Request->execute();
        if ($Request) {
            $Posts = array();
    
            while ($Post = $Request->fetch(PDO::FETCH_ASSOC)) {
                $PostID = $Post['PostID'];
                $AuthorID = $Post['UserID'];
                $Avatar = GetAvatar($Post['Avatar'], $Post['Name']);
                $Name = $Post['Name'];
                $Username = $Post['Username'];
                $Date = TimeAgo($Post['Date']);
                $UserIcons = GetUserIcons($Post['UserID']);
                $Text = $Post['Text'];
                $Content = $Post['Content'];
    
                if ($Post['Likes'] > 0) {
                    $Likes = $Post['Likes'];
                    $Liked = UserLiked($Account['ID'], $PostID);
                    if ($Liked == true) {
                        $Liked = 'Liked';
                    }
                } else {
                    $Likes = 'Лайк';
                    $Liked = null;
                }
                if ($Post['Dislikes'] > 0) {
                    $Dislikes = $Post['Dislikes'];
                    $Disliked = UserDisliked($Account['ID'], $PostID);
                    if ($Disliked == true) {
                        $Disliked = 'Liked';
                    }
                } else {
                    $Dislikes = 'Дизлайк';
                    $Disliked = null;
                }
                if ($Post['Comments'] > 0) {
                    $Comments = $Post['Comments'];
                } else {
                    $Comments = 'Обсудить';
                }
                if ($AuthorID == $Account['ID']) {
                    $MyPost = true;
                } else {
                    $MyPost = false;
                }
    
                $PostData = array(
                    'PostID' => $PostID,
                    'AuthorID' => $AuthorID,
                    'Username' => $Username,
                    'Name' => $Name,
                    'Avatar' => $Avatar,
                    'UserIcons' => $UserIcons,
                    'Text' => $Text,
                    'Content' => $Content,
                    'Date' => $Date,
                    'Likes' => $Likes,
                    'Dislikes' => $Dislikes,
                    'Liked' => $Liked,
                    'Disliked' => $Disliked,
                    'Comments' => $Comments,
                    'MyPost' => $MyPost
                );
    
                $Posts[] = $PostData;
            }
    
            echo json_encode($Posts);
        }
    }

    if ($Function == 'SUBSCRIPTIONS') {
        $Query = "SELECT posts.*, accounts.Name AS Name, accounts.Username AS Username, accounts.Avatar AS Avatar
        FROM posts
        JOIN subscriptions ON posts.UserID = subscriptions.ToUser
        JOIN accounts ON posts.UserID = accounts.ID
        WHERE subscriptions.User = :AccountID ORDER BY `Date` DESC LIMIT :StartIndex, :Limit";
        $Request = $PDO->prepare($Query);
        $Request->bindParam(':AccountID', $Account['ID'], PDO::PARAM_INT);
        $Request->bindParam(':StartIndex', $StartIndex, PDO::PARAM_INT);
        $Request->bindParam(':Limit', $Limit, PDO::PARAM_INT);
        $Request->execute();

        if ($Request) {
            $Posts = array();
    
            while ($Post = $Request->fetch(PDO::FETCH_ASSOC)) {
                $PostID = $Post['PostID'];
                $AuthorID = $Post['UserID'];
                $Avatar = GetAvatar($Post['Avatar'], $Post['Name']);
                $Name = $Post['Name'];
                $Username = $Post['Username'];
                $Date = TimeAgo($Post['Date']);
                $UserIcons = GetUserIcons($Post['UserID']);
                $Text = $Post['Text'];
                $Content = $Post['Content'];
    
                if ($Post['Likes'] > 0) {
                    $Likes = $Post['Likes'];
                    $Liked = UserLiked($Account['ID'], $PostID);
                    if ($Liked == true) {
                        $Liked = 'Liked';
                    }
                } else {
                    $Likes = 'Лайк';
                    $Liked = null;
                }
                if ($Post['Dislikes'] > 0) {
                    $Dislikes = $Post['Dislikes'];
                    $Disliked = UserDisliked($Account['ID'], $PostID);
                    if ($Disliked == true) {
                        $Disliked = 'Liked';
                    }
                } else {
                    $Dislikes = 'Дизлайк';
                    $Disliked = null;
                }
                if ($Post['Comments'] > 0) {
                    $Comments = $Post['Comments'];
                } else {
                    $Comments = 'Обсудить';
                }
                if ($AuthorID == $Account['ID']) {
                    $MyPost = true;
                } else {
                    $MyPost = false;
                }
    
                $PostData = array(
                    'PostID' => $PostID,
                    'AuthorID' => $AuthorID,
                    'Username' => $Username,
                    'Name' => $Name,
                    'Avatar' => $Avatar,
                    'UserIcons' => $UserIcons,
                    'Text' => $Text,
                    'Content' => $Content,
                    'Date' => $Date,
                    'Likes' => $Likes,
                    'Dislikes' => $Dislikes,
                    'Liked' => $Liked,
                    'Disliked' => $Disliked,
                    'Comments' => $Comments,
                    'MyPost' => $MyPost
                );
    
                $Posts[] = $PostData;
            }
    
            echo json_encode($Posts);
        }
    }

}

?>