<?php

$RootDir = $_SERVER["DOCUMENT_ROOT"];
require $RootDir . '/System/Scripts/Global/DataBase.php';
require $RootDir . '/System/Scripts/Global/Function.php';

$PostID = $_POST['PostID'] ?? null;

if (empty($PostID)) {
    exit();
}

$Account = AccountConnect();

if (!$Account) {
    exit();
}

$SubGold = CheckGoldUser($Account['ID']);

if ($SubGold == false) {
    $Errors[] = 'У вас нет активной подписки Gold';
}

if (empty($Errors)) {
    $Query = "SELECT p.*, p.UserID as UserID, a.Name as Name, a.Username as Username, a.Avatar as Avatar FROM `posts` p INNER JOIN `accounts` a ON p.UserID = a.ID WHERE `PostID` = ?";
    $Stmt = $PDO->prepare($Query);
    $Stmt->execute([$PostID]);
    $Post = $Stmt->fetch(PDO::FETCH_ASSOC);

    if ($FTP_Upload) {

    } else {

    }

    $EPACK = array(
        'E_VER' => '1.2',
        'E_TYPE' => 'Post',
        'Name' => $Post['Name'],
        'Username' => $Post['Username'],
        'Avatar' => EPACK_GetAvatar($Post['Avatar']),
        'PostID' => $Post['PostID'],
        'Date' => $Post['Date'],
        'Text' => $Post['Text'] ?? null,
        'Content' => EPACK_GetPostContent($Post['Type'], $Post['Content']) ?? null,
        'LikesCount' => $Post['Likes'],
        'DislikesCount' => $Post['Dislikes'],
        'CommentsCount' => $Post['Comments'],
    );

    $EPACK_Name = 'Пост ID'.$Post['PostID'].'.epack';

    file_put_contents($RootDir.'/Download/'.$EPACK_Name, json_encode($EPACK));
    Answer('Verify', $EPACK_Name);
} else {
    Answer('Error', array_shift($Errors));
}

// Функции

function EPACK_GetAvatar ($Avatar) {
    if ($Avatar == 'None') {
        return false;
    } else {
        return FileToB64('/Content/Avatars/', $Avatar);
    }
}

function EPACK_GetPostContent ($Type, $Content) {
    if ($Type == 'Image') {
        if (!empty($Content)) {
            $Content = json_decode($Content, true);
            $Content = array (
                'Type' => 'Image',
                'Image' => FileToB64('/Content/Posts/Images/', $Content['Image']['File_Name']),
                'Name' => $Content['Image']['Orig_Name'],
                'Size' => $Content['Image']['File_Size'],
                'Censoring' => $Content['Image']['Censoring'] ?? false
            );

            return $Content;
        } else {
            return false;
        }
    }
}

function FileToB64 ($FilePath, $FileName) {
    global $FTP_Upload;
    global $FTP_Dir;
    global $FTP_Server;
    global $FTP_Username;
    global $FTP_Password;
    global $FTP_Port;

    $TempFilePath = $_SERVER["DOCUMENT_ROOT"].'/!Temp/'.$FileName;

    if (!file_exists($TempFilePath)) {
        if ($FTP_Upload) {
            $FTP = ftp_connect($FTP_Server, $FTP_Port);
            ftp_login($FTP, $FTP_Username, $FTP_Password);
    
            ftp_pasv($FTP, true);
            
            $TempFile = fopen($TempFilePath, 'w');
            ftp_fget($FTP, $TempFile, $FTP_Dir.$FilePath.$FileName, FTP_BINARY);
            fclose($TempFile);
            ftp_close($FTP);
        } else {
            copy($_SERVER["DOCUMENT_ROOT"].$FilePath.$FileName, $TempFilePath);
        }
    }

    $FileContent = file_get_contents($TempFilePath);
    return base64_encode($FileContent);
}

?>