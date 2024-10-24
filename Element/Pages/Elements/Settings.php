<?php

if (empty($Verify)) {
    header('Location: /home');
    exit();
}

?>

<head>
    <link rel="stylesheet" type="text/css" href="/System/UI/ThemePrew.css">
    <title>Настройки</title>
</head>

<!-- Правая сторона -->

<div class="UI-C_R">
    <div class="UI-Block Profile-InfoBlock UI-B_FIRST">
        <div class="UI-Title" style="width: 100%;">Мой профиль</div>

        <div class="Profile-Cover">
            <?php if ($Account['Cover'] != 'None'): ?>
            <img src="<?= $FTP_Domain ?>/Content/Covers/<?= $Account['Cover'] ?>">
            <?php endif; ?>
        </div>

        <?= GetAvatar($Account['Avatar'], $Account['Name']) ?>

        <div class="Name"><div id="S-PROFILE_PREW_NAME"><?= $Account['Name'] ?></div><div class="UserIcons"><?= GetUserIcons($Account['ID']) ?></div></div>
        <div class="IconInfoContainer"><div class="Info"></div></div>
        <div class="Username">@<?= $Account['Username'] ?></div>
        <div class="Settings-PRFL_Email"><?= $Account['Email'] ?></div>

        <?php if ($Account['Description']): ?>
        <div class="Description">
            <div class="Title">описание</div>
            <div id="S-PROFILE_PREW_DEC" class="Text"><?= $Account['Description'] ?></div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Левая сторона -->

<div class="UI-C_L">
    <div class="UI-Block UI-B_FIRST">
        <div class="UI-Title" style="width: 100%;">Редактировать аккаунт</div>
        <div class="Settings-CP_Cover">
            <div class="Cover">
                <?php if ($Account['Cover'] != 'None'): ?>
                <img src="<?= $FTP_Domain ?>/Content/Covers/<?= $Account['Cover'] ?>">
                <?php endif; ?>
            </div>
            <div class="Settings-ChangeButtons">
                <form id="S-CP_COVER_FORM" enctype="multipart/form-data">
                    <input id="S-CP_UPLOAD_COVER" name="Cover" type="file">
                </form>
                <label for="S-CP_UPLOAD_COVER" class="Button">Загрузить новый</label>
                <button id="S-CP_DL_COVER" class="ButtonDL">Удалить</button>
            </div>
        </div>
        <div class="Settings-CP_Avatar">
            <?= GetAvatar($Account['Avatar'], $Account['Name']) ?>
            <div class="Settings-ChangeButtons">
                <form id="S-CP_UPLOAD_AVATAR_FORM" enctype="multipart/form-data">
                    <input id="S-CP_UPLOAD_AVATAR" name="Avatar" type="file">
                </form>
                <label for="S-CP_UPLOAD_AVATAR" class="Button">Загрузить новый</label>
                <button id="S-CP_DL_AVATAR" class="ButtonDL">Удалить</button>
            </div>
        </div>
        <div id="S-CP_NameContainer" class="Settings-CP_Input_container">
            <div class="Settings-CP_IC_Title">имя (псевдоним)</div>
            <input class="UI-Input" id="S-CP_Name" value="<?= $Account['Name'] ?>" type="text" maxlength="30" placeholder="Введите текст">
        </div>
        <div id="S-CP_DecContainer" class="Settings-CP_Input_container">
            <div class="Settings-CP_IC_Title">описание профиля</div>
            <textarea class="UI-Input" id="S-CP_Dec" type="text" maxlength="100" placeholder="Введите текст"><?= $Account['Description'] ?></textarea>
        </div>
    </div>
    <div class="UI-Block">
        <div class="UI-Title" style="width: 100%;">Выберите тему</div>
        <div class="Settings-Themes">
            <div class="Theme-Light ChangeTheme" themeid="Light">
                <div class="TH-Container">
                    <div class="TH-TopBar"></div>
                    <div class="TH-Posts">
                        <div class="TH-AddPost"><div class="TH-Button"></div></div>
                        <div class="TH-Post"></div>
                        <div class="TH-Post"></div>
                    </div>
                    <div class="TH-BottomBar"></div>
                </div>
                <div class="Info Selected">Светлая</div>
            </div>
            <?php if ($SubGold): ?>
            <div class="Theme-Gold ChangeTheme" themeid="Gold">
                <div class="TH-Container">
                    <div class="TH-TopBar"></div>
                    <div class="TH-Posts">
                        <div class="TH-AddPost"><div class="TH-Button"></div></div>
                        <div class="TH-Post"></div>
                        <div class="TH-Post"></div>
                    </div>
                    <div class="TH-BottomBar"></div>
                </div>
                <div class="Info">Золотая</div>
            </div>
            <?php endif; ?>
            <div class="Theme-Dark ChangeTheme" themeid="Dark">
                <div class="TH-Container">
                    <div class="TH-TopBar"></div>
                    <div class="TH-Posts">
                        <div class="TH-AddPost"><div class="TH-Button"></div></div>
                        <div class="TH-Post"></div>
                        <div class="TH-Post"></div>
                    </div>
                    <div class="TH-BottomBar"></div>
                </div>
                <div class="Info">Тёмная</div>
            </div>
        </div>
    </div>
</div>