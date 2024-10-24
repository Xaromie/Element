<?php

$RootDir = $_SERVER["DOCUMENT_ROOT"];
require $RootDir.'/System/Scripts/Global/DataBase.php';

$Type = $_GET['Type'] ?? 'Release';

$Types = array(
    'Release',
    'Beta',
);

if (!in_array($Type, $Types)) {
    die('Хуй там');
}

$Query = "SELECT * FROM `updates` WHERE `Type` = :Type ORDER BY `Version` DESC";
$Stmt = $PDO->prepare($Query);
$Stmt->execute(['Type' => $Type]);

$Updates = array();

while ($Update = $Stmt->fetch(PDO::FETCH_ASSOC)) {
    $Updates[] = $Update;
}

echo json_encode($Updates);

?>