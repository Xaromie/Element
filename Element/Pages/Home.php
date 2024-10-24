<?php

$RootDir = $_SERVER["DOCUMENT_ROOT"];
include($RootDir . '/System/Scripts/Global/DataBase.php');
include($RootDir . '/System/Scripts/Global/Function.php');
include($RootDir . '/System/Scripts/Global/Elements.php');

if (!$Account) {
    exit(header('Location: /'));
}

$UriSegments = explode('/', $_SERVER['REQUEST_URI']);
$DesiredSegment = end($UriSegments);
$Page = urldecode($DesiredSegment);

$Verify = true;

?>
<!DOCTYPE html>

<head>
    <?= $Head ?>
</head>

<body>
    <?= $StatusBar ?>
    <?php if ($Account['Status'] == 'Admin') : ?>
        <div class="DebugInfo">
            <div class="DebugInfo-Title">Аккаунт</div>
            <?php print_r($Account); ?>
        </div>
    <?php endif; ?>

    <div class="Content">

        <div class="UI-L_NAV">
            <button class="PAGE-HOME UI-LN_Button UI-B_FIRST"><i class="icon-Nav_Home"></i>Главная</button></a>
            <a href="/chat" class="PAGE-CHAT UI-LN_Button"><i class="icon-Nav_Messager"></i>Месенджер</a>
            <?php if ($Account['Status'] == 'Admin'): ?>
            <a href="/panel" class="UI-LN_Button"><i class="icon-Nav_AdminPanel"></i>Панель</a>
            <?php endif; ?>
            <button class="PAGE-SETTINGS UI-LN_Button"><i class="icon-Nav_Settings"></i>Настройки</button></a>
            <button class="PAGE-GOLD UI-LN_Button"><div class="GoldText"><i class="icon-Nav_Sub"></i>Подписка</div></button>
            <button class="PAGE-EPACK UI-LN_Button"><i class="icon-Nav_EPACK"></i>Просмотр EPACK</button></a>
        </div>

        <?php if ($Page == 'home'): ?>
            <div class="Home-Page UI-PAGE_BODY">
                <div class="UI-PB_PRELOAD"></div>
                <?php include($RootDir . '/Pages/Elements/Posts.php'); ?>
            </div>
        <?php endif; ?>
        <?php if ($Page == 'settings'): ?>
            <div class="Settings-Page UI-PAGE_BODY">
                <div class="UI-PB_PRELOAD"></div>
                <?php include($RootDir . '/Pages/Elements/Settings.php'); ?>
            </div>
        <?php endif; ?>
        <?php if ($Page == 'goldsub'): ?>
            <div class="GoldSub-Page UI-PAGE_BODY">
                <div class="UI-PB_PRELOAD"></div>
                <?php include($RootDir . '/Pages/Elements/Gold.php'); ?>
            </div>
        <?php endif; ?>
        <?php if ($Page == 'epack'): ?>
            <div class="EPACK-Page UI-PAGE_BODY">
                <div class="UI-PB_PRELOAD"></div>
                <?php include($RootDir . '/Pages/Elements/EPACK.php'); ?>
            </div>
        <?php endif; ?>

    </div>
</body>

</html>