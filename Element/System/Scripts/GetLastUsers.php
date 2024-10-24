<?php

$RootDir = $_SERVER["DOCUMENT_ROOT"];
require_once $RootDir.'/System/Scripts/Global/DataBase.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

$Query = "SELECT * FROM `accounts` WHERE `avatar` != 'None' ORDER BY `CreateDate` DESC LIMIT 5";
$Stmt = $PDO->prepare($Query);
$Stmt->execute();

$Users = array();

while ($User = $Stmt->fetch(PDO::FETCH_ASSOC)) {
    $User = array (
        'Name' => $User['Name'],
        'Username' => $User['Username'],
        'Avatar' => $User['Avatar']
    );

    $Users[] = $User;
}

echo json_encode($Users);

?>