<?php

$Account = AccountConnect();
$MyID = $Account['ID'] ?? null;
$Theme = null;
$AccountStatus = $Account['Status'] ?? null;
$GoldSubStatus = 'false';

if ($Account) {
    $SubGold = CheckGoldUser($Account['ID']);

    if ($SubGold) {
        $GoldSubStatus = 'true';
    }

    if ($Account['Theme'] == 'Gold' && $SubGold) {
        $Theme = '<link id="THEME" rel="stylesheet" type="text/css" href="/System/UI/GoldStyle-12.css">';
    }
    if ($Account['Theme'] == 'Dark') {
        $Theme = '<link id="THEME" rel="stylesheet" type="text/css" href="/System/UI/DarkStyle-12.css">';
    }

    $NP_Buttons = array(
        'Profile' => '<a href="/profile/' .$Account['Username']. '"><button class="NavPanel-Btn">Мой профиль</button></a><div style="height: 5px;"></div>',
        'Exit' => '<a href="/выход"><button class="NavPanel-Btn">Выйти</button></a>'
    );

    $NavPanel =
        '<div class="NavPanel-Container"><div class="NavPanel">' .
        $NP_Buttons['Profile'].
        $NP_Buttons['Exit'].
        '</div></div>';

    if (!$SubGold) {
        $NavPanel .= '<div class="UI-AD_N1-B">
        <div class="UI-AD_C_TOP">
            <div class="UI-AD_TITLE">Реклама</div>
        </div>
        <div class="UI-AD_C_BOTTOM">
            <a class="UI-AD_BTN" href="https://altnodes.top/">Перейти</a>
        </div>
        <img class="UI-AD_IMG" src="/System/Images/Ad/ADBG-2.png">';
    }
    $NavPanel .= '</div>        
    <div class="UI-ImageView"></div>
    <div class="UI-IV_Interaction">
        <div class="Info">{Имя, Размер}</div>
        <div class="Buttons">
            <a id="PIMG-Dwonload">Скачать</a>
            <div class="UI-PUSTOTA_W"></div>
            <button id="PIMG-Fullscrean">Развернуть</button>
            <div class="UI-PUSTOTA_W"></div>
            <button id="PIMG-Close">Закрыть</button>
        </div>
    </div>';

    $StatusBar = 
        '<div class="UI-TopBar">
        <div class="UI-N_DIV">
        <div class="UI-Logo"></div>
            <input id="Search" class="Search UI-Input" placeholder="Поиск" type="text">
            '. GetAvatar($Account['Avatar'], $Account['Name']) .'
        </div>
        </div>
        <div class="Search-Result">
        <div class="Search-R_USRS">
        </div>
        </div>'
         .$NavPanel;
} else {
    $StatusBar = '
    <div class="UI-TopBar">
    <div class="UI-N_DIV">
        <div class="UI-N_L_AND_N">
            <img class="UI-Logo" src="/System/Images/Logo.svg">
            <div>Element</div>
        </div>
    </div>
   </div>';
}

$Head = '
<!-- Настройки -->
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<link rel="icon" href="/System/Images/Logo.svg" type="image/x-icon">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<!-- Интерфейс -->
<link rel="stylesheet" type="text/css" href="/System/UI/Style-12.css">
<link rel="stylesheet" type="text/css" href="/System/UI/IconPack-116.css">
<link rel="stylesheet" type="text/css" href="/System/UI/AnimPack-12.css">
<!-- Переменные -->
<meta name="MyID" content="'. $MyID .'">
<meta name="FilesServer" content="'. $FTP_Domain .'">
<meta name="MyStatus" content="'. $AccountStatus .'">
<meta name="GoldSub" content="'. $GoldSubStatus .'">
<!-- Скрипты -->
<script src="/System/JavaScript/jQuery.js"></script>
<script src="/System/JavaScript/Element-12.js"></script>'
.$Theme;