<?php

$RootDir = $_SERVER["DOCUMENT_ROOT"];
require($RootDir . '/System/Scripts/Global/DataBase.php');
require($RootDir . '/System/Scripts/Global/Function.php');
require($RootDir . '/System/Scripts/Global/Elements.php');

if (!$Account) {
    exit();
}
if ($Account['Status'] != 'Admin') {
    exit();
}

$Pages = array(
    'stat' => 'Statistics.php',
    'goldsub' => 'GoldSub.php',
);
if (isset($_GET['Page'])) {
    $Page = $_GET['Page'];
    if (array_key_exists($Page, $Pages)) {
        $SelectPage = $Pages[$Page];
    }
}
if (empty($SelectPage)) {
    header('Location: /panel/stat');
}

$Statistics = array(
    'Users' => '0',
    'Blocked_users' => '0',
    'Posts' => '0',
    'Comments' => '0',
    'Likes' => '0',
    'Dislikes' => '0',
    'SubActive' => '0',
    'SubBought' => '0',
    'SubActCode' => '0'
);

?>
<!DOCTYPE html>

<head>
    <?= $Head ?>
    <title>Панель упрвления</title>
</head>

<body>
    <div class="UI-TopBar">
        <div class="UI-N_DIV">
            <div class="UI-N_L_AND_N">
                <div class="UI-Logo"></div>
                <div>Панель управления</div>
            </div>
        </div>
    </div>
    <div class="Content">
        <div class="UI-L_NAV">
            <button id="STAT" class="UI-LN_Button UI-B_FIRST"><i class="icon-Nav_AdminPanel"></i>Главное</button>
            <button id="SUB" class="UI-LN_Button"><i class="icon-Nav_Sub"></i>Подписка</button>
            <button id="EXIT" class="UI-LN_Button"><i class="icon-Exit"></i>Выход</button>
        </div>
        <div class="UI-PAGE_BODY" style="padding: 0px 6px">
            <div class="UI-PAGE_SCROLL">
                <?php require_once 'DashboardPages/'.$SelectPage ?>
            </div>
        </div>
    </div>
    <script src="/System/JavaScript/jQuery.js"></script>
    <script src="/System/JavaScript/Element-12.js"></script>
    <script src="/System/JavaScript/Dashboard-116.js"></script>
</body>

</html>