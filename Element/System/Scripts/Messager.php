<?php

$RootDir = $_SERVER["DOCUMENT_ROOT"];
require($RootDir . '/System/Scripts/Global/DataBase.php');
require($RootDir . '/System/Scripts/Global/Function.php');

$Account = AccountConnect();

if (!$Account) {
    exit();
}

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

$Function = $_GET['F'];

if ($Function == 'GET_USER') {
    $Username = $_POST['Username'] ?? null;

    if ($Username) {
        $Query = "SELECT * FROM `accounts` WHERE `Username` = :Username";
        $Stmt = $PDO->prepare($Query);
        $Stmt->bindParam(':Username', $Username);
        $Stmt->execute();
        $User = $Stmt->fetch(PDO::FETCH_ASSOC);

        if ($User) {
            $User = array (
                'ID' => $User['ID'],
                'Username' => $User['Username'],
                'Name' => $User['Name'],
                'Avatar' => $User['Avatar']
            );

            echo json_encode($User);
        } else {
            echo json_encode('Error');
        }
    } else {
        echo json_encode('Error');
    }
}

if ($Function == 'LOAD_CHATS') {
    $Query = "SELECT * FROM `chats` WHERE (`UserID_1` = :Account) OR (`UserID_2` = :Account) ORDER BY `LastMessage_Date` DESC";
    $Stmt = $PDO->prepare($Query);
    $Stmt->bindParam(':Account', $Account['ID']);
    $Stmt->execute();

    if ($Stmt) {

        $Chats = array();

        while ($Chat = $Stmt->fetch(PDO::FETCH_ASSOC)) {

            if ($Chat['UserID_1'] == $Account['ID']) {
                $ID = $Chat['UserID_2'];
            } else {
                $ID = $Chat['UserID_1'];
            }

            $Query = "SELECT * FROM `accounts` WHERE ID = $ID";
            $Result = mysqli_query($DataBase, $Query);
            $User = mysqli_fetch_assoc($Result);

            $User = array (
                'Username' => $User['Username'],
                'Name' => $User['Name'],
                'Avatar' => $User['Avatar'],
                'LastMessage' => $Chat['LastMessage'] ?? 'пусто',
                'Verify' => $Chat['Verify']
            );

            $Chats[] = $User;
        }

        echo json_encode($Chats);
    }
}

if ($Function == 'LOAD_CHAT') {

    $User = $_POST['UserID'];

    $Query = "SELECT * FROM `chats` WHERE (`UserID_1` = :Account AND `UserID_2` = :User) OR (`UserID_1` = :User AND `UserID_2` = :Account) LIMIT 25";
    $Stmt = $PDO->prepare($Query);
    $Stmt->bindParam(':Account', $Account['ID']);
    $Stmt->bindParam(':User', $User);
    $Stmt->execute();
    $Chat = $Stmt->fetch(PDO::FETCH_ASSOC);

    if ($Chat) {

        $UID_1 = $Chat['UserID_1'];
        $UID_2 = $Chat['UserID_2'];
        $ChatVerify = $Chat['Verify'];

        if (empty($Chat['LastMessage'])) {
            $Query = "SELECT * FROM `chat_message` WHERE (`From` = :Account AND `For` = :User) OR (`For` = :Account AND `From` = :User) ORDER BY `Date` DESC LIMIT 1";
            $Stmt = $PDO->prepare($Query);
            $Stmt->bindParam(':Account', $Account['ID']);
            $Stmt->bindParam(':User', $User);
            $Stmt->execute();
            $Messages = $Stmt->fetch(PDO::FETCH_ASSOC);

            $Query = "UPDATE `chats` SET `LastMessage` = :Message WHERE `UserID_1` = :UserID_1 AND `UserID_2` = :UserID_2";
            $Stmt = $PDO->prepare($Query);
            $Stmt->bindParam(':UserID_1', $UID_1);
            $Stmt->bindParam(':UserID_2', $UID_2);
            $Stmt->bindParam(':Message', $Messages['Message']);
            $Stmt->execute();
        }

        if (empty($Chat['LastMessage_Date'])) {
            $Query = "SELECT * FROM `chat_message` WHERE (`From` = :Account AND `For` = :User) OR (`For` = :Account AND `From` = :User) ORDER BY `Date` DESC LIMIT 1";
            $Stmt = $PDO->prepare($Query);
            $Stmt->bindParam(':Account', $Account['ID']);
            $Stmt->bindParam(':User', $User);
            $Stmt->execute();
            $Messages = $Stmt->fetch(PDO::FETCH_ASSOC);

            $Query = "UPDATE `chats` SET `LastMessage_Date` = :Date WHERE `UserID_1` = :UserID_1 AND `UserID_2` = :UserID_2";
            $Stmt = $PDO->prepare($Query);
            $Stmt->bindParam(':UserID_1', $UID_1);
            $Stmt->bindParam(':UserID_2', $UID_2);
            $Stmt->bindParam(':Date', $Messages['Date']);
            $Stmt->execute();
        }

        $Query = "SELECT * FROM `chat_message` WHERE (`From` = :Account AND `For` = :User) OR (`From` = :User AND `For` = :Account) ORDER BY `Date` DESC";
        $Stmt = $PDO->prepare($Query);
        $Stmt->bindParam(':Account', $Account['ID']);
        $Stmt->bindParam(':User', $User);
        $Stmt->execute();
    
        if ($Stmt) {
    
            $Messages = array();
    
            while ($Message = $Stmt->fetch(PDO::FETCH_ASSOC)) {
    
                $Time = new DateTime($Message['Date']);
                $Time = $Time->format('H:i');
    
                $Message = array(
                    'For' => $Message['For'],
                    'From' => $Message['From'],
                    'Text' => $Message['Message'],
                    'Time' => $Time
                );
    
                $Messages[] = $Message;
            }

            $Chat = array (
                'Verify' => $ChatVerify,
                'Messages' => $Messages
            );
    
            echo json_encode($Chat);

            $Query = "UPDATE `chat_message` SET `Viewed` = 1 WHERE `For` = :Account AND `From` = :User AND `Viewed` = 0";
            $Stmt = $PDO->prepare($Query);
            $Stmt->bindParam(':Account', $Account['ID']);
            $Stmt->bindParam(':User', $User);
            $Stmt->execute();
        }
    }
}

if ($Function == 'SEND_MESSAGE') {

    $Message = $_POST['Message'];
    $For = $_POST['UserID'];
    $From = $Account['ID'];

    if (CheckText($Message)) {
        exit();
    }
    if (!CheckValidUser($For)) {
        exit();
    }

    if (empty($Errors)) {
        CheckChat($From, $For);
        $Date = date('Y-m-d H:i:s');

        $Message = HandleText($Message);

        $Query = "INSERT INTO `chat_message` (`From`, `For`, `Message`, `Date`) VALUES (:From, :For, :Message, :Date)";
        $Stmt = $PDO->prepare($Query);
        $Stmt->bindParam(':From', $From);
        $Stmt->bindParam(':For', $For);
        $Stmt->bindParam(':Message', $Message);
        $Stmt->bindParam(':Date', $Date);
        $Stmt->execute();

        $Query = "UPDATE `chats` SET `LastMessage` = :Message, `LastMessage_Date` = :Date WHERE (`UserID_1` = :UserID_1 AND `UserID_2` = :UserID_2) OR (`UserID_1` = :UserID_2 AND `UserID_2` = :UserID_1)";
        $Stmt = $PDO->prepare($Query);
        $Stmt->bindParam(':UserID_1', $From);
        $Stmt->bindParam(':UserID_2', $For);
        $Stmt->bindParam(':Message', $Message);
        $Stmt->bindParam(':Date', $Date);
        $Stmt->execute();
    }
}

if ($Function == 'VERIFY_CHAT_BUTTON') {
    $User = $_POST['UserID'] ?? null;

    if (isset($User)) {
        $Query = "UPDATE `chats` SET `Verify` = :Verify WHERE (`UserID_1` = :UserID_1 AND `UserID_2` = :UserID_2) OR (`UserID_1` = :UserID_2 AND `UserID_2` = :UserID_1)";
        $Stmt = $PDO->prepare($Query);
        $Stmt->execute([
            'UserID_1' => $User, 
            'UserID_2' => $Account['ID'], 
            'Verify' => '1'
        ]);
    }
}

if ($Function == 'DELETE_CHAT_BUTTON') {
    $User = $_POST['UserID'] ?? null;

    if (isset($User)) {
        $Query = "SELECT * FROM `chats` WHERE (`UserID_1` = :UserID_1 AND `UserID_2` = :UserID_2) OR (`UserID_1` = :UserID_2 AND `UserID_2` = :UserID_1)";
        $Stmt = $PDO->prepare($Query);
        $Stmt->execute([
            'UserID_1' => $User, 
            'UserID_2' => $Account['ID'],
        ]);
        $Chat = $Stmt->fetch();

        if ($Chat && $Chat['Verify'] == 0) {

            $Query = "DELETE FROM `chats` WHERE (`UserID_1` = :UserID_1 AND `UserID_2` = :UserID_2) OR (`UserID_1` = :UserID_2 AND `UserID_2` = :UserID_1)";
            $Stmt = $PDO->prepare($Query);
            $Stmt->execute([
                'UserID_1' => $User, 
                'UserID_2' => $Account['ID'],
            ]);
    
            $Query = "DELETE FROM `chat_message` WHERE (`From` = :UserID_1 AND `For` = :UserID_2) OR (`From` = :UserID_2 AND `For` = :UserID_1)";
            $Stmt = $PDO->prepare($Query);
            $Stmt->execute([
                'UserID_1' => $User, 
                'UserID_2' => $Account['ID'],
            ]);

            echo json_encode('Verify');

        } else {
            echo json_encode('Error');
        }
    }
}

if ($Function == 'CHECK_NEW_MESSAGES') {
    $User = $_POST['UserID'];

    if ($User) {
        $Query = "SELECT * FROM `chat_message` WHERE `For` = :Account AND `From` = :User AND `Viewed` = 0";
        $Stmt = $PDO->prepare($Query);
        $Stmt->bindParam(':Account', $Account['ID']);
        $Stmt->bindParam(':User', $User);
        $Stmt->execute();

        if ($Stmt) {
    
            $Messages = array();
    
            while ($Message = $Stmt->fetch(PDO::FETCH_ASSOC)) {
    
                $Time = new DateTime($Message['Date']);
                $Time = $Time->format('H:i');
    
                $Message = array(
                    'For' => $Message['For'],
                    'From' => $Message['From'],
                    'Text' => $Message['Message'],
                    'Time' => $Time
                );
    
                $Messages[] = $Message;
            }
    
            echo json_encode($Messages);

            $Query = "UPDATE `chat_message` SET `Viewed` = 1 WHERE `For` = :Account AND `From` = :User AND `Viewed` = 0";
            $Stmt = $PDO->prepare($Query);
            $Stmt->bindParam(':Account', $Account['ID']);
            $Stmt->bindParam(':User', $User);
            $Stmt->execute();
        }
    }
}

/* Функции */

function CheckChat($From, $For) {
    global $PDO;

    $Query = "SELECT * FROM `chats` WHERE (UserID_1 = :From AND UserID_2 = :For) OR (UserID_1 = :For AND UserID_2 = :From)";
    $Stmt = $PDO->prepare($Query);
    $Stmt->bindParam(':From', $From);
    $Stmt->bindParam(':For', $For);
    $Stmt->execute();
    $Result = $Stmt->rowCount();

    if($From != $For) {
        if ($Result == 0) {
            $Query = "INSERT INTO `chats` (`UserID_1`, `UserID_2`) VALUES (:UserID_1, :UserID_2)";
            $Stmt = $PDO->prepare($Query);
            $Stmt->bindParam(':UserID_1', $From);
            $Stmt->bindParam(':UserID_2', $For);
            $Stmt->execute();
        }
    }
}

