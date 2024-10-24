<?php

if (empty($Verify)) {
    header('Location: /home');
    exit();
}

?>

<head>
    <title>Просмотр EPACK</title>
</head>

<!-- Левая сторона -->

<div class="UI-C_L">
    <div class="UI-Block UI-B_FIRST">
        <div class="UI-Title" style="width: 100%;">Загрузка файла</div>
        <div class="EPACK-FileInput">
            <input type="file" id="EPACK_Input">
            <label for="EPACK_Input">Выбрать файл</label>
            <div class="Text">он должен быть в формате ".epack"</div>
        </div>
    </div>
    <div id="EPACK_RESULT">
        <div class="EPACK-NonFile">Файл не выбран</div>
    </div>
</div>

<!-- Правая сторона -->

<div class="UI-C_R">
    <?php if (CheckGoldUser($Account['ID']) == false) : ?>
        <div class="UI-AD_N2-B UI-B_FIRST">
            <div class="UI-AD_C_TOP">
                <div class="UI-AD_TITLE">Реклама</div>
            </div>
            <div class="UI-AD-T">Подпишитесь на телеграм канал автора сайта</div>
            <div class="UI-AD_C_BOTTOM">
                <a class="UI-AD_BTN" href="https://t.me/XaromieChannel">Перейти</a>
            </div>
            <img class="UI-AD_IMG" src="/System/Images/Ad/ADBG-1.png">
        </div>
    <?php endif; ?>
    <div id="EP-GS_USERS" class="UI-Block">
        <div class="UI-Title" style="width: 100%;">Gold пользователи</div>
        <div class="GoldSub-Users"></div>
    </div>
</div>

<script>
    setTimeout( function() {
        GetGoldUsers('.GoldSub-Users');
    }, 200);
    if (GoldSub === true) {
        $('#EP-GS_USERS').addClass('UI-B_FIRST');
    }
</script>
<script src="/System/JavaScript/Unpacker-114.js"></script>
