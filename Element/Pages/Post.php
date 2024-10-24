<?php

$RootDir = $_SERVER["DOCUMENT_ROOT"];
require($RootDir . '/System/Scripts/Global/DataBase.php');
require($RootDir . '/System/Scripts/Global/Function.php');
require($RootDir . '/System/Scripts/Global/Elements.php');

$PostID = $_GET['PostID'];

if (isset($PostID)) {
    $Query = "SELECT p.*, p.UserID as UserID, a.Name as Name, a.Username as Username, a.Avatar as Avatar FROM `posts` p INNER JOIN `accounts` a ON p.UserID = a.ID WHERE `PostID` = :PostID";
    $Stmt = $PDO->prepare($Query);
    $Stmt->bindParam(':PostID', $PostID);
    $Stmt->execute();
    $Post = $Stmt->fetch(PDO::FETCH_ASSOC);

    if ($Post) {
        $PostID = $Post['PostID'];
        $UserIcons = GetUserIcons($Post['UserID']);
    
        if ($Account) {
            $Liked = UserLiked($Account['ID'], $PostID);
            $Disliked = UserDisliked($Account['ID'], $PostID);
        } else {
            $Liked = false;
            $Disliked = false;
        }
    
        if ($Post['Likes'] > 0) {
            $LikesCount = $Post['Likes'];
            if ($Liked == true) {
                $Liked = 'Liked';
            }
        } else {
            $LikesCount = 'Лайк';
        };
        if ($Post['Dislikes'] > 0) {
            $DislikesCount = $Post['Dislikes'];
            if ($Disliked == true) {
                $Disliked = 'Liked';
            }
        } else {
            $DislikesCount = 'Дизлайк';
        };
        if ($Post['Content']) {
            $Content = json_decode($Post['Content'], true);
        }
    
        if ($UserIcons) {
            $Icons = '<div class="UserIcons">'. $UserIcons .'</div>';
        } else {
            $Icons = null;
        }
    }
}

?>

<!DOCTYPE html>

<head>
    <?= $Head; ?>
    <meta property="og:image" content="/System/Images/Logo.svg">
    <meta property="og:title" content="Пост от <?= $Post['Name'] ?>">
    <meta property="og:description" content="<?= $Post['Text'] ?>">
    <title>Пост</title>
</head>

<body>
    <?= $StatusBar ?>
    <div class="Content Post-Page">
    <?php if ($Post): ?>
        <div class="UI-C_L Post-C_L">
            <div class="Post UI-Block UI-B_FIRST" data-postid="<?= $PostID ?>">
                <div class="TopBar">
                    <div class="Info">
                        <a href="/profile/<?= $Post['Username'] ?>"><?= GetAvatar($Post['Avatar'], $Post['Name']) ?></a>
                        <div>
                            <div class="Name"><?= $Post['Name'] ?><?= $Icons ?></div>
                            <div class="Date"><?= TimeAgo($Post['Date']) ?></div>
                        </div>
                    </div>
                    <?php if ($Account && $Post['UserID'] == $Account['ID'] OR $Account['Status'] == 'Admin'): ?>

                    <button class="GovernButton" clicked="0"><i class="icon-DotsV"></i></button>

                    <div class="GovernButtons"></div>

                    <?php endif; ?>
                </div>
                <div class="Text"><?= $Post['Text'] ?></div>
                <?php if ($Post['Content']): ?>
                <div class="Image" img-name="<?= $Content['Image']['Orig_Name'] ?>" img-size="<?= $Content['Image']['File_Size'] ?>">
                    <img class="IMG" src="<?= $FTP_Domain ?>/Content/Posts/Images/<?= $Content['Image']['File_Name'] ?>">
                    <div class="Blur"></div>
                    <img class="BlurIMG" src="<?= $FTP_Domain ?>/Content/Posts/Images/<?= $Content['Image']['File_Name'] ?>">
                </div>
                <?php endif; ?>
                <div class="InteractionButtons">
                    <button class="InteractionButton Like <?= $Liked ?>"><i class="icon-Like"></i><div class="Likes"><?= $LikesCount ?></div></button>
                    <button class="InteractionButton Dislike <?= $Disliked ?>"><i class="icon-Dislike"></i><div class="Dislikes"><?= $DislikesCount ?></div></button>
                    <?php if (!$Account): ?>
                    <a href="/auth"><button class="InteractionButton Connect"><i class="icon-Connect"></i>Присоединиться</button></a>
                    <?php endif; ?>
                    <button class="InteractionButton Share"><i class="icon-Share"></i>Поделиться</button>
                </div>
                <div class="ShareImposition"></div>
            </div>
        </div>
        <div class="UI-C_R Post-Comments">
            <?php if ($Account): ?>
            <div class="UI-Block UI-B_FIRST">
                <div class="UI-Title">Комментировать</div>
                <div class="Post-Add_comment">
                    <input id="COMMENT-INPUT" class="UI-Input" placeholder="Текст..." type="text">
                    <button id="COMMENT-SEND" class="Send"><i class="icon-Send"></i></button>
                </div>
            </div>
            <div class="UI-Block">
            <?php else: ?>
            <div class="UI-Block UI-B_FIRST">
            <?php endif; ?>
                <div class="UI-Title">Комментарии</div>
                <div class="Post-C"></div>
            </div>
        </div>
    <?php else: ?>
        <img class="UI-E_IMG" src="/System/Images/Error.png">
    <?php endif; ?>
    </div>
    <script src="/System/JavaScript/Packer.js"></script>
    <script src="/System/JavaScript/PostInteraction-12.js"></script>
    <script>
        $(document).ready(function() {
            LoadComments();
        });
    </script>
</body>