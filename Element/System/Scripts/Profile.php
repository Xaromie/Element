<?php

$RootDir = $_SERVER["DOCUMENT_ROOT"];
require($RootDir . '/System/Scripts/Global/DataBase.php');
require($RootDir . '/System/Scripts/Global/Function.php');

$Account = AccountConnect();

if (isset($_GET['Username'])) {
    $Username = $_GET['Username'];

    $Query = 'SELECT * FROM `accounts` WHERE `Username` = :Username';
    $Stmt = $PDO->prepare($Query);
    $Stmt->bindParam(':Username', $Username);
    $Stmt->execute();
    $Profile = $Stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($Profile)) {
        echo json_encode('Error');
        exit();
    }

    if ($Profile['Posts'] == '0') {
        $Query = "SELECT COUNT(*) AS Count FROM `posts` WHERE `UserID` = :ID";
        $Stmt = $PDO->prepare($Query);
        $Stmt->bindParam(':ID', $Profile['ID']);
        $Stmt->execute();
        $Result = $Stmt->fetch(PDO::FETCH_ASSOC);
        $Profile['Posts'] = $Result['Count'];

        $Query = "UPDATE `accounts` SET `Posts` = :Posts WHERE `ID` = :ID";
        $Stmt = $PDO->prepare($Query);
        $Stmt->bindParam(':ID', $Profile['ID']);
        $Stmt->bindParam(':Posts', $Profile['Posts']);
        $Stmt->execute();
    }

    if ($Account) {
        if ($Account['ID'] == $Profile['ID']) {
            $MyProfile = 'Yes';
        } else {
            $MyProfile = 'No';
        }
        $Subscribed = CheckSubscription($Account['ID'], $Profile['ID']);
    }
    
    $Icons = GetUserIcons($Profile['ID']);

    $CreateDate = new DateTime($Profile['CreateDate']);
    $CreateDate = $CreateDate->format('d.m.Y');

    $Profile = array (
        'ID' => $Profile['ID'],
        'Name' => $Profile['Name'],
        'Icons' => GetUserIcons($Profile['ID']),
        'Username' => $Profile['Username'],
        'Cover' => $Profile['Cover'],
        'Avatar' => $Profile['Avatar'],
        'Posts' => $Profile['Posts'],
        'Subscribers' => $Profile['Subscribers'],
        'Subscriptions' => $Profile['Subscriptions'],
        'CreateDate' => $CreateDate,
        'Description' => $Profile['Description'],
        'MyProfile' => $MyProfile ?? null,
        'MyStatus' => $Account['Status'] ?? null,
        'Subscribed' => $Subscribed ?? null,
    );

    echo json_encode($Profile);
}

?>