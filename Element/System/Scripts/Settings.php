<?php

$RootDir = $_SERVER["DOCUMENT_ROOT"];
require($RootDir . '/System/Scripts/Global/DataBase.php');
require($RootDir . '/System/Scripts/Global/Function.php');

$Account = AccountConnect();
    
if (!$Account) {
    exit();
}

$Function = $_GET['F'];

if (isset($Function)) {

    /* Редактирование профиля */

    /* Редактирование профиля - Загрузить аватар */

    if ($Function == 'CP_UPLOAD_AVATAR') {
        if (isset($_FILES['Avatar'])) {
    
            $Size = $_FILES['Avatar']['size'];
            $Type = $_FILES['Avatar']['type'];
        
            if (($Type != "image/png") && ($Type != "image/jpg") && ($Type != "image/jpeg") && ($Type != "image/gif")) {
                $Errors[] = 'Это не изображение';
            }
        
            if (($Size > 2 * 1024 * 1024)) {
                $Errors[] = 'Аватарка весит более двух мегабайт';
            }
        
            if (empty($Errors)) {
                if($Account['Avatar'] != 'None') {
                    DeleteFile('/Content/Avatars/', $Account['Avatar']);
                    $Query = "UPDATE `accounts` SET `Avatar` = 'None' WHERE `ID` = :ID";
                    $Stmt = $PDO->prepare($Query);
                    $Stmt->bindParam(':ID', $Account['ID']);
                    $Stmt->execute();
                }
                
                $Name = 'Avatar'.'_'. date('Y-m-d H_i_s'). '_' .md5(microtime().$Account['Username']). '.' . substr($Type, strlen("image/"));
                UploadFile($_FILES['Avatar']['tmp_name'], '/Content/Avatars/', $Name);

                $Query = "UPDATE `accounts` SET `Avatar` = :Name WHERE `ID` = :ID";
                $Stmt = $PDO->prepare($Query);
                $Stmt->bindParam(':ID', $Account['ID']);
                $Stmt->bindParam(':Name', $Name);
                $Stmt->execute();
                $PDO = null;

                $Type = 'Verify';
                $Content = '<img src="'.$FTP_Domain.'/Content/Avatars/'.$Name.'">';
            } else {
                $Type = 'Error';
                $Content = array_shift($Errors);
            }

            $Answer = array(
                'Type' => $Type,
                'Content' => $Content
            );
    
            header('Content-Type: application/json');
            echo json_encode($Answer);
        }
    }

    /* Редактирование профиля - Удалить аватар */

    if ($Function == 'CP_DL_AVATAR') {
        if($Account['Avatar'] != 'None') {
            DeleteFile('/Content/Avatars/', $Account['Avatar']);
            $Query = "UPDATE `accounts` SET `Avatar` = 'None' WHERE `ID` = ?";
            $Stmt = $PDO->prepare($Query);
            $Stmt->execute([$Account['ID']]);
        }

        $Letter = mb_substr($Account['Name'], 0, 1, "UTF-8");
        $NoneAvatar = '<div class="NonAvatar">' . $Letter . '</div>';

        $Answer = array(
            'Type' => 'Verify',
            'Content' => $NoneAvatar
        );

        header('Content-Type: application/json');
        echo json_encode($Answer);
    }

    /* Редактирование профиля - Загрузить обложку */

    if ($Function == 'CP_UPLOAD_COVER') {
        if (isset($_FILES['Cover'])) {
    
            $Size = $_FILES['Cover']['size'];
            $Type = $_FILES['Cover']['type'];
        
            if (($Type != "image/png") && ($Type != "image/jpg") && ($Type != "image/jpeg") && ($Type != "image/gif")) {
                $Errors[] = 'Это не изображение';
            }
        
            if (($Size > 2 * 1024 * 1024)) {
                $Errors[] = 'Обложка весит более двух мегабайт';
            }
        
            if (empty($Errors)) {
                if($Account['Cover'] != 'None') {
                    DeleteFile('/Content/Covers/', $Account['Cover']);
                    $Query = "UPDATE `accounts` SET `Cover` = 'None' WHERE `ID` = :ID";
                    $Stmt = $PDO->prepare($Query);
                    $Stmt->bindParam(':ID', $Account['ID']);
                    $Stmt->execute();
                }
                
                $Name = 'Cover'.'_'. date('Y-m-d H_i_s'). '_' .md5(microtime().$Account['Username']). '.' . substr($Type, strlen("image/"));
                UploadFile($_FILES['Cover']['tmp_name'], '/Content/Covers/', $Name);

                $Query = "UPDATE `accounts` SET `Cover` = :Name WHERE `ID` = :ID";
                $Stmt = $PDO->prepare($Query);
                $Stmt->bindParam(':ID', $Account['ID']);
                $Stmt->bindParam(':Name', $Name);
                $Stmt->execute();
                $PDO = null;

                $Type = 'Verify';
                $Content = $Name;
            } else {
                $Type = 'Error';
                $Content = array_shift($Errors);
            }

            $Answer = array(
                'Type' => $Type,
                'Content' => $Content
            );
    
            header('Content-Type: application/json');
            echo json_encode($Answer);
        }
    }

    // Редактирование профиля - Удалить обложку

    if ($Function == 'CP_DL_COVER') {
        if($Account['Cover'] != 'None') {
            DeleteFile('/Content/Covers/', $Account['Cover']);
            $Query = "UPDATE `accounts` SET `Cover` = 'None' WHERE `ID` = :ID";
            $Stmt = $PDO->prepare($Query);
            $Stmt->execute([
                'ID' => $Account['ID']
            ]);
        }

        $Answer = array(
            'Type' => 'Verify',
            'Content' => ''
        );

        header('Content-Type: application/json');
        echo json_encode($Answer);
    }

    /* Редактирование профиля - Смена имени */

    if ($Function == 'CHANGE_NAME') {

        $Name = $_POST['Name'];

        if (CheckText($Name)) {
            $Errors[] = 'Имя содержит запрещённые символы, или же состоит из пробелов';
        }

        if((mb_strlen($Name) > 30)) {
            $Errors[] = 'Имя может быть не длинее 30 символов';
        } 

        if (empty($Errors)) {
            $Name = HandleText($Name);
            
            $Query = "UPDATE `accounts` SET `Name` = :Name WHERE `ID` = :ID";
            $Stmt = $PDO->prepare($Query);
            $Stmt->bindParam(':ID', $Account['ID']);
            $Stmt->bindParam(':Name', $Name);
            $Stmt->execute();
            $Type = 'Verify';
            $Content = 'None';
        } else {
            $Type = 'Error';
            $Content = array_shift($Errors);
        }

        $Answer = array(
            'Type' => $Type,
            'Content' => $Content
        );

        header('Content-Type: application/json');
        echo json_encode($Answer);
    }

    /* Редактирование профиля - Смена описания */

    if ($Function == 'CHANGE_DEC') {

        $Description = $_POST['Description'];

        if (CheckText($Description)) {
            $Errors[] = 'Описание содержит запрещённые символы, или же состоит из пробелов';
        }

        if((mb_strlen($Description) > 100)) {
            $Errors[] = 'Описание может быть не длинее 100 символов';
        } 

        if (empty($Errors)) {
            $Description = HandleText($Description);

            $Query = "UPDATE `accounts` SET `Description` = :Description WHERE `ID` = :ID";
            $Stmt = $PDO->prepare($Query);
            $Stmt->bindParam(':ID', $Account['ID']);
            $Stmt->bindParam(':Description', $Description);
            $Stmt->execute();
            $Type = 'Verify';
            $Content = 'None';
        } else {
            $Type = 'Error';
            $Content = array_shift($Errors);
        }

        $Answer = array(
            'Type' => $Type,
            'Content' => $Content
        );

        header('Content-Type: application/json');
        echo json_encode($Answer);
    }

    /* Смена темы */

    if ($Function == 'CHANGE_THEME') {
        $Themes = array(
            'Light', 'Gold', 'Dark'
        );

        if (in_array($_POST['Theme'], $Themes)) {
            $Theme = $_POST['Theme'];

            $Query = "UPDATE `accounts` SET `Theme` = :Theme WHERE `ID` = :ID";
            $Stmt = $PDO->prepare($Query);
            $Stmt->bindParam(':ID', $Account['ID']);
            $Stmt->bindParam(':Theme', $Theme);
            $Stmt->execute();
        }

    }
}

?>