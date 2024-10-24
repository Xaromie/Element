<?php

$Query = "SELECT COUNT(*) AS Count FROM `subs_gold` WHERE `Status` = 'Active'";
$Result = mysqli_query($DataBase, $Query);
$Result = mysqli_fetch_assoc($Result);
$Statistics['SubActive'] = $Result['Count'];

$Query = "SELECT COUNT(*) AS Count FROM `subs_gold`";
$Result = mysqli_query($DataBase, $Query);
$Result = mysqli_fetch_assoc($Result);
$Statistics['SubActCount'] = $Result['Count'];

$Query = "SELECT COUNT(*) AS Count FROM `sub_keys`";
$Result = mysqli_query($DataBase, $Query);
$Result = mysqli_fetch_assoc($Result);
$Statistics['GeneratedCodes'] = $Result['Count'];

$Query = "SELECT COUNT(*) AS Count FROM `sub_keys` WHERE `Activated` = 'No'";
$Result = mysqli_query($DataBase, $Query);
$Result = mysqli_fetch_assoc($Result);
$Statistics['ActiveCodes'] = $Result['Count'];

?>

<div class="Dashboard-Blocks">
    <div class="Dashboard-Block UI-Block">
        <div class="Dashboard-B_Text">Пользователей с активной подпиской</div>
        <div class="Dashboard-B_Count">
            <?= $Statistics['SubActive'] ?>
        </div>
    </div>
    <div class="Dashboard-Block UI-Block">
        <div class="Dashboard-B_Text">Подписок активировано</div>
        <div class="Dashboard-B_Count">
            <?= $Statistics['SubActCount'] ?>
        </div>
    </div>
    <div class="Dashboard-Block UI-Block">
        <div class="Dashboard-B_Text">Сгенерировано кодов</div>
        <div class="Dashboard-B_Count">
            <?= $Statistics['GeneratedCodes'] ?>
        </div>
    </div>
    <div class="Dashboard-Block UI-Block">
        <div class="Dashboard-B_Text">Не активированных кодов</div>
        <div class="Dashboard-B_Count">
            <?= $Statistics['ActiveCodes'] ?>
        </div>
    </div>
</div>

<div class="UI-Block" style="height: 300px;margin: 4px;display: flex;">

    <div class="Dashboard-BL_CNT">
        <div class="UI-Title">Ключи для активации</div>
        <button id="AP-GN_KEY_BTN" class="Dashboard-SUB_BTN">Генерировать ключ</button>
        <div id="AP-GS_KEYS" class="Dashboard-SUB_LIST">
            <?php GetKeys(); ?>
        </div>
    </div>

    <div class="Dashboard-BL_CNT">
        <div class="UI-Title">Пользователи с подпиской</div>
        <button id="AP-RC_SUB_USRS" class="Dashboard-SUB_BTN">Пересчитать</button>
        <div id="AP-GS_USERS" class="Dashboard-SUB_LIST">
            <?php GetGoldUsers(); ?>
        </div>

    </div>

</div>