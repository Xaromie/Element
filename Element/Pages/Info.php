<?php

$RootDir = $_SERVER["DOCUMENT_ROOT"];
require($RootDir . '/System/Scripts/Global/DataBase.php');
require($RootDir . '/System/Scripts/Global/Function.php');
require($RootDir . '/System/Scripts/Global/Elements.php');

$Pages = array(
    'advantages' => 'Advantages.php',
    'rules' => 'Rules.php',
    'update' => 'Updates.php'
);

if (isset($_GET['Page'])) {
    $Page = $_GET['Page'];
    if (array_key_exists($Page, $Pages)) {
        $SelectPage = $Pages[$Page];
    }
}
if (empty($SelectPage)) {
    header('Location: /info/advantages');
}

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
                <div>Информация</div>
            </div>
        </div>
    </div>
    <div class="Content">
        <div class="UI-L_NAV">
            <button class="PAGE-INFP_PR UI-LN_Button UI-B_FIRST"><i class="icon-Nav_Info"></i>Преимущества</button>
            <button class="PAGE-INFP_RL UI-LN_Button"><i class="icon-Nav_Info"></i>Правила</button>
            <button class="PAGE-INFP_UP UI-LN_Button"><i class="icon-Nav_Info"></i>Обновления</button>
        </div>
        <div class="UI-PAGE_BODY">
            <div class="UI-PAGE_SCROLL">
                <?php require_once 'InfoPages/'.$SelectPage ?>
            </div>
        </div>
    </div>
</body>

</html>