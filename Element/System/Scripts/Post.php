<?php

$RootDir = $_SERVER["DOCUMENT_ROOT"];
require($RootDir . '/System/Scripts/Global/DataBase.php');
require($RootDir . '/System/Scripts/Global/Function.php');

$Account = AccountConnect();
if (!$Account) {
    exit();
}

$Functions = array (
  'S_P'
);

$Data = $_POST;

if (isset($_GET['F'])) {
  if (in_array($_GET['F'], $Functions)) {
    $Function = $_GET['F'];
  }
}

if (isset($Function)) {
  if ($Function == 'S_P') {

    $Text = $Data['Text'];

    if ($Text == '') {
      if (!file_exists($_FILES['Image']['tmp_name'])) {
        $Errors[] = "Нельзя отправить пустой пост";
      }
    } else {
      if (CheckText($Text)) {
        $Errors[] = "Текст содержит запрещённые символы, или же состоит из пробелов";
      }
    }

    if ($Account['Status'] == 'Blocked') {
      $Errors[] = "Ваш аккаунт заблокирован.";
    }
  
    if (file_exists($_FILES['Image']['tmp_name'])) {
      $Size = $_FILES['Image']['size'];
      $Type = $_FILES['Image']['type'];

      if (($Type != "image/png") && ($Type != "image/jpg") && ($Type != "image/jpeg") && ($Type != "image/gif")) {
        $Errors[] = 'Это не изображение.';
      }

      if (($Size > 2 * 1024 * 1024)) {
        $Errors[] = 'Вы можете загружать изображения вес которых не привышает 2 MB.';
      }
    }

    if (CH_P_TIME($Account['ID'])) {
      $Errors[] = "Пост можно публиковать раз в три минуты.";
    }
  
    if (empty($Errors)) {

      $UserID = $Account['ID'];
      $Type = 'Text';
      $Text = HandleText($Text);
      $Date = date('Y-m-d H:i:s');

      if (file_exists($_FILES['Image']['tmp_name'])) {

        $Size = $_FILES['Image']['size'];
        $FileType = $_FILES['Image']['type'];
        $OrigName = $_FILES['Image']['name'];
        $Name = 'Image'.'_'.md5(microtime().$Account['Username']). '.' . substr($FileType, strlen("image/"));
        $FileHash = md5_file($_FILES['Image']['tmp_name']) ?? false;
        UploadFile($_FILES['Image']['tmp_name'], '/Content/Posts/Images/', $Name);

        if (isset($_POST['CensoringIMG'])) {
          $Censoring = true;
        }
        if (isset($_POST['ClearMetadataIMG'])) {
          $OrigName = false;
          ClearMetadataImage('/Content/Posts/Images/', $Name, $FileType);
        }

        $Content = array (
          'Image' => array (
            'Orig_Name' => $OrigName,
            'File_Name' => $Name,
            'File_Size' => $_FILES['Image']['size'],
            'Hash' => $FileHash,
            'Censoring' => $Censoring ?? false
          ),
        );
        $Type = 'Image';
        $Content = json_encode($Content);
      }
      
      $Query = "INSERT INTO `posts` (`UserID`, `Type`, `Text`, `Content`, `Date`) VALUES (:UserID, :Type, :Text, :Content, :Date)";
      $Stmt = $PDO->prepare($Query);
      $Stmt->execute([
        'UserID' => $UserID,
        'Text' => $Text,
        'Type' => $Type,
        'Content' => $Content ?? null,
        'Date' => $Date
      ]);
      
      ReCountPosts($UserID);
      
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
}

/* Функции */

function CH_P_TIME($UserID) {
  global $PDO;

  $Query = "SELECT * FROM `posts` WHERE `UserID` = :UserID ORDER BY `Date` DESC LIMIT 1";
  $Stmt = $PDO->prepare($Query);
  $Stmt->bindParam(':UserID', $UserID);
  $Stmt->execute();
  $Result = $Stmt->fetch(PDO::FETCH_ASSOC);

  if ($Result) {
    $TimeLimit = 180;

    $LP_Time = strtotime($Result['Date']);
    $CR_Time = time();

    $ElapsedTime = $CR_Time - $LP_Time;

    if ($ElapsedTime >= $TimeLimit) {
      return false;
    } else {
      return true;
    }
  } else {
    return false;
  }
}

function ReCountPosts ($UserID) {
  global $PDO;

  $Query = "SELECT COUNT(*) AS Count FROM `posts` WHERE `UserID` = :ID";
  $Stmt = $PDO->prepare($Query);
  $Stmt->bindParam(':ID', $UserID);
  $Stmt->execute();
  $Result = $Stmt->fetch(PDO::FETCH_ASSOC);
  $Result = $Result['Count'];

  $Query = "UPDATE `accounts` SET `Posts` = :Posts WHERE `ID` = :ID";
  $Stmt = $PDO->prepare($Query);
  $Stmt->bindParam(':ID', $UserID);
  $Stmt->bindParam(':Posts', $Result);
  $Stmt->execute();
}

?>