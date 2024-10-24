<?php

$RootDir = $_SERVER["DOCUMENT_ROOT"];
require $RootDir . '/System/Scripts/Global/DataBase.php';
require $RootDir . '/System/Scripts/Global/Function.php';

$Account = AccountConnect();
$Function = $_GET['F'];

if (!$Account) {
    exit();
}

// Удалить пост

if ($Function == 'DELETE_POST') {

    $PostID = $_POST['PostID'];
    $User = GET_UDATA_FROM_PID($PostID);
    
    if ($User['ID'] != $Account['ID']) {
        $Errors[] = 'Это не ваш пост';
    }

    if (empty($Errors)) {
        DeletePost($PostID);
        $Type = 'Verify';
        $Content = 'Пост ID '. $PostID .' удалён';
    } else {
        $Type = 'Error';
        $Content = array_shift($Errors);
    }
}

// Отправка ответа

$Answer = array(
    'Type' => $Type,
    'Content' => $Content
);
echo json_encode($Answer);

?>