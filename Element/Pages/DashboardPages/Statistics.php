<?php

$Query = "SELECT COUNT(*) AS Count FROM `accounts`";
$Result = mysqli_query($DataBase, $Query);
$Result = mysqli_fetch_assoc($Result);
$Statistics['Users'] = $Result['Count'];
$Query = "SELECT COUNT(*) AS Count FROM `accounts` WHERE `Status` = 'Blocked'";
$Result = mysqli_query($DataBase, $Query);
$Result = mysqli_fetch_assoc($Result);
$Statistics['Blocked_users'] = $Result['Count'];
$Query = "SELECT COUNT(*) AS Count FROM `posts`";
$Result = mysqli_query($DataBase, $Query);
$Result = mysqli_fetch_assoc($Result);
$Statistics['Posts'] = $Result['Count'];
$Query = "SELECT COUNT(*) AS Count FROM `post_likes`";
$Result = mysqli_query($DataBase, $Query);
$Result = mysqli_fetch_assoc($Result);
$Statistics['Likes'] = $Result['Count'];
$Query = "SELECT COUNT(*) AS Count FROM `post_dislikes`";
$Result = mysqli_query($DataBase, $Query);
$Result = mysqli_fetch_assoc($Result);
$Statistics['Dislikes'] = $Result['Count'];
$Query = "SELECT COUNT(*) AS Count FROM `comments`";
$Result = mysqli_query($DataBase, $Query);
$Result = mysqli_fetch_assoc($Result);
$Statistics['Comments'] = $Result['Count'];

?>

<div class="Dashboard-Blocks">
    <div class="Dashboard-Block UI-Block">
        <div class="Dashboard-B_Text">Зарегистрировано пользователей</div>
        <div class="Dashboard-B_Count">
            <?= $Statistics['Users'] ?>
        </div>
    </div>
    <div class="Dashboard-Block UI-Block">
        <div class="Dashboard-B_Text">Заблокировано пользователей</div>
        <div class="Dashboard-B_Count">
            <?= $Statistics['Blocked_users'] ?>
        </div>
    </div>
    <div class="Dashboard-Block UI-Block">
        <div class="Dashboard-B_Text">Опубликовано постов</div>
        <div class="Dashboard-B_Count">
            <?= $Statistics['Posts'] ?>
        </div>
    </div>
    <div class="Dashboard-Block UI-Block">
        <div class="Dashboard-B_Text">Оставлено комментариев</div>
        <div class="Dashboard-B_Count">
            <?= $Statistics['Comments'] ?>
        </div>
    </div>
    <div class="Dashboard-Block UI-Block">
        <div class="Dashboard-B_Text">Поставлено лайков</div>
        <div class="Dashboard-B_Count">
            <?= $Statistics['Likes'] ?>
        </div>
    </div>
    <div class="Dashboard-Block UI-Block">
        <div class="Dashboard-B_Text">Поставлено дизлайков</div>
        <div class="Dashboard-B_Count">
            <?= $Statistics['Dislikes'] ?>
        </div>
    </div>
</div>

<div class="UI-Block" style="margin: 4px;display: flex;flex-direction: column;">
    <div class="UI-Title" style="width: 100%;">Разные функции</div>
    <div class="Dashboard-F_buttons">
        <button id="AP-WEEDOUT_AVATARS" class="Button">Отсеять аватары</button>
        <div class="UI-PUSTOTA_W"></div>
        <button class="Button">Очистить незарегистрированные акки (10)</button>
    </div>
</div>