<?php

$RootDir = $_SERVER["DOCUMENT_ROOT"];
require($RootDir . '/System/Scripts/Global/DataBase.php');
require($RootDir . '/System/Scripts/Global/Function.php');

$FTP = ftp_connect($FTP_Server, $FTP_Port);
ftp_login($FTP, $FTP_Username, $FTP_Password);
//ftp_pasv($FTP, true);

$Files = ftp_nlist($FTP, '/Content/Avatars/');
$Count = 0;

if (!$FTP OR !$Files) {
    Answer('Error', 'Ошибка при работе с FTP');
    die();
}

foreach ($Files as $File) {
    $FileName = basename($File);
    $Query = "SELECT * FROM `accounts` WHERE `Avatar` = ?";
    $Stmt = $PDO->prepare($Query);
    $Stmt->execute([$FileName]);
    $Result = $Stmt->rowCount();

    if ($Result == 0) {
        $Count++;
        // ftp_delete($FTP, $File);
    }
}

Answer('Verify', 'Удалено '. $Count .' объектов');

?>