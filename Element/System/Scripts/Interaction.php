<?php

$RootDir = $_SERVER["DOCUMENT_ROOT"];
require($RootDir . '/System/Scripts/Global/DataBase.php');
require($RootDir . '/System/Scripts/Global/Function.php');

$Account = AccountConnect();

if (empty($_GET['F'])) {
    exit();
}

$Function = $_GET['F'];

if (isset($Function)) {

    /* Поиск */

    if ($Function == 'SEARCH') {
        if (!$Account) {
            exit();
        }

        $SearchQuery = $_POST['SearchVal'];

        $Query = "SELECT * FROM `accounts` WHERE (`Name` LIKE :Query) OR (`Username` LIKE :Query) LIMIT 25";
        $Stmt = $PDO->prepare($Query);
        $Stmt->bindValue(':Query', '%' . $SearchQuery . '%', PDO::PARAM_STR);
        $Stmt->execute();

        if ($Stmt) {
            $Users = array();

            while ($User = $Stmt->fetch(PDO::FETCH_ASSOC)) {

                $UserData = array(
                    'Username' => $User['Username'],
                    'Name' => $User['Name'],
                    'Avatar' => GetAvatar($User['Avatar'], $User['Name']),
                    'Posts' => $User['Posts']
                );
        
                $Users[] = $UserData;
            }

            header('Content-Type: application/json');
            echo json_encode($Users);
        }
    }

    /* Активация подписки */

    if ($Function == 'SUB_ACT') {
        if (!$Account) {
            exit();
        }
        
        $Key = $_POST['Text'];

        $Query = "SELECT * FROM `sub_keys` WHERE `Key` = :Key AND `Activated` = 'No'";
        $Stmt = $PDO->prepare($Query);
        $Stmt->bindParam(':Key', $Key);
        $Stmt->execute();
        $Result = $Stmt->fetch(PDO::FETCH_ASSOC);

        if($Result) {
            $Query = "SELECT * FROM `subs_gold` WHERE `UserID` = :UserID AND `Status` = 'Active' ORDER BY `Date` DESC LIMIT 1";
            $Stmt = $PDO->prepare($Query);
            $Stmt->bindParam(':UserID', $Account['ID']);
            $Stmt->execute();
            $Result = $Stmt->fetch(PDO::FETCH_ASSOC);

            if ($Result) {
                $Type = 'Error';
                $Content = 'У вас уже есть подписка.';
            } else {
                $Query = "UPDATE `sub_keys` SET `Activated` = 'Yes' WHERE `Key` = :Key";
                $Stmt = $PDO->prepare($Query);
                $Stmt->bindParam(':Key', $Key);
                $Stmt->execute();
    
                GetGold($Account['ID'], 'Code');
    
                $Type = 'Verify';
                $Content = 'Код активирован.';
            }
        } else {
            $Type = 'Error';
            $Content = 'Такого кода не существует или он уже активирован.';
        }

        
        $Answer = array(
            'Type' => $Type,
            'Content' => $Content
        );

        header('Content-Type: application/json');
        echo json_encode($Answer);
    }

    /* Особый список */

    if ($Function == 'GOLD_LIST') {
        $Query = "SELECT sg.*, a.Name as Name, a.Username as Username, a.Avatar as Avatar, a.Posts as Posts FROM `subs_gold` sg  INNER JOIN `accounts` a ON sg.UserID = a.ID WHERE sg.Status = 'Active' ORDER BY `Date` DESC";
        $Stmt = $PDO->prepare($Query);
        $Stmt->execute();
    
        if ($Stmt) {
            $Users = array();

            while ($User = $Stmt->fetch(PDO::FETCH_ASSOC)) {

                $UserData = array(
                    'Username' => $User['Username'],
                    'Name' => $User['Name'],
                    'Avatar' => GetAvatar($User['Avatar'], $User['Name']),
                    'Posts' => $User['Posts']
                );
        
                $Users[] = $UserData;
            }

            header('Content-Type: application/json');
            echo json_encode($Users);
        }
    }

    /* Панель управления */

    /* Панель управления - Генератор ключей */

    if ($Function == 'AP_GN_KEY' && $Account['Status'] == 'Admin') {
        if (!$Account) {
            exit();
        }

        $Key = sprintf("%02dX86-%05d-W%05d", rand(1, 99), rand(1, 99999), rand(1, 99999));

        $Query = "INSERT INTO `sub_keys` (`Key`) VALUES ('$Key')";
        $Result = mysqli_query($DataBase, $Query);

        echo GetKeys();
    }

    /* Панель управления - Пересчитать пользователей с подпиской */

    if ($Function == 'AP_RC_SUB_USRS' && $Account['Status'] == 'Admin') {
        if (!$Account) {
            exit();
        }

        $Query = "SELECT * FROM `subs_gold` WHERE `Status` = 'Active'";
        $Stmt = $PDO->prepare($Query);
        $Stmt->execute();

        while ($User = $Stmt->fetch(PDO::FETCH_ASSOC)) {
            $ThisTame = new DateTime();
            $ActivateSubDate = new DateTime($User['Date']);

            $Interval = $ThisTame->diff($ActivateSubDate);
            $DaysPassed = $Interval->days;
            
            if ($DaysPassed >= 30) {
                $Query = "UPDATE `subs_gold` SET `Status` = 'Inactive' WHERE `UserID` = :UserID";
                $Stmt = $PDO->prepare($Query);
                $Stmt->bindParam(':UserID', $User['UserID']);
                $Stmt->execute();
            }
        }

        $Query = "SELECT sg.*, a.Name as Name, a.Username as Username, a.Avatar as Avatar, a.Posts as Posts FROM `subs_gold` sg  INNER JOIN `accounts` a ON sg.UserID = a.ID WHERE sg.Status = 'Active' ORDER BY `Date` DESC";
        $Stmt = $PDO->prepare($Query);
        $Stmt->execute();
    
        while ($User = $Stmt->fetch(PDO::FETCH_ASSOC)) {
            echo 
            '<a href="/profile/'. $User['Username'] .'">
            <div class="Dashboard-SUB_USR">'
            . GetAvatar($User['Avatar'], $User['Name']) .
                '<div>
                    <div class="Name">'. $User['Name'] .'</div>
                    <div class="Posts">'. $User['Posts'] .' постов</div>
                </div>
            </div>
            </a>';
        }
    }

    // Удалить обложку

    if ($Function == 'DELETE_COVER') {
        if (!$Account) {
            exit();
        }
        if (!$_POST['UserID']) {
            exit();
        }

        $UserID = $_POST['UserID'];

        if ($Account['Status'] != 'Admin') {
            $Errors[] = 'Вы не являетесь модератором';
        }

        if (empty($Errors)) {
            $User = G_USR_FRM_ID($UserID);

            DeleteFile('/Content/Covers/', $User['Cover']);

            $Query = "UPDATE `accounts` SET `Cover` = 'None' WHERE `ID` = :ID";
            $Stmt = $PDO->prepare($Query);
            $Stmt->execute([
                'ID' => $User['ID']
            ]);

            $Type = 'Verify';
            $Content = 'Обложка пользователя ' .$User['Username']. ' удалена.';
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

    /* Удалить аватарку */

    if ($Function == 'DELETE_AVATAR') {
        if (!$Account) {
            exit();
        }
        if (!$_POST['UserID']) {
            exit();
        }

        $UserID = $_POST['UserID'];

        if ($Account['Status'] != 'Admin') {
            $Errors[] = 'Вы не являетесь модератором';
        }

        if (empty($Errors)) {
            $User = G_USR_FRM_ID($UserID);
            $Avatar = 'None';

            DeleteFile('/Content/Avatars/', $User['Avatar']);

            $Query = "UPDATE `accounts` SET `Avatar` = :Avatar WHERE `ID` = :ID";
            $Stmt = $PDO->prepare($Query);
            $Stmt->bindParam(':ID', $User['ID']);
            $Stmt->bindParam(':Avatar', $Avatar);
            $Stmt->execute();

            $Type = 'Verify';
            $Content = 'Аватар пользователя ' .$User['Username']. ' удалён.';
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

    /* Заблокировать пользователя */

    if ($Function == 'BLOCK_USER') {
        if (!$Account) {
            exit();
        }
        if (!$_POST['UserID']) {
            exit();
        }

        $UserID = $_POST['UserID'];
        $User = G_USR_FRM_ID($UserID);

        if ($User['Status'] == 'Admin') {
            $Errors[] = 'Нельзя заблокировать модератора.';
        }

        if ($Account['Status'] != 'Admin') {
            $Errors[] = 'Вы не являетесь модератором';
        }

        if (empty($Errors)) {
            $Username = $User['Username'];

            $Query = "UPDATE `accounts` SET `Status` = 'Blocked' WHERE `ID` = '$UserID'";
            mysqli_query($DataBase, $Query);

            $Type = 'Verify';
            $Content = 'Пользователь '. $Username .' заблокирован';
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

    /* Лайк */

    if ($Function == 'P_LIKE') {
        if (!$Account) {
            exit();
        }

        $UserID = $Account['ID'];
        $PostID = $_POST['PostID'];

        if (ExistsPost($PostID)) {
            
            P_LIKE($UserID, $PostID);

            $Query = "SELECT * FROM `posts` WHERE `PostID` = :PostID";
            $Stmt = $PDO->prepare($Query);
            $Stmt->execute([
                'PostID' => $PostID
            ]);
            $Post = $Stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($Post['Likes'] > 0) {
                $Likes = $Post['Likes'];
            } else {
                $Likes = 'Лайк';
            }
    
            if ($Post['Dislikes'] > 0) {
                $Dislikes = $Post['Dislikes'];
            } else {
                $Dislikes = 'Дизлайк';
            }
    
            $PostData = array (
                'Likes' => $Likes,
                'Dislikes' => $Dislikes
            );
    
            header('Content-Type: application/json');
            echo json_encode($PostData);
        }
    }

    /* Дизлайк */

    if ($Function == 'P_DISLIKE') {
        if (!$Account) {
            exit();
        }

        $UserID = $Account['ID'];
        $PostID = $_POST['PostID'];

        if (ExistsPost($PostID)) {

            P_DISLIKE($UserID, $PostID);

            $Query = "SELECT * FROM `posts` WHERE `PostID` = :PostID";
            $Stmt = $PDO->prepare($Query);
            $Stmt->execute([
                'PostID' => $PostID
            ]);
            $Post = $Stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($Post['Likes'] > 0) {
                $Likes = $Post['Likes'];
            } else {
                $Likes = 'Лайк';
            }
    
            if ($Post['Dislikes'] > 0) {
                $Dislikes = $Post['Dislikes'];
            } else {
                $Dislikes = 'Дизлайк';
            }
    
            $PostData = array (
                'Likes' => $Likes,
                'Dislikes' => $Dislikes
            );
    
            header('Content-Type: application/json');
            echo json_encode($PostData);
        }
    }

    if ($Function == 'POST_C') {
        if (!$Account) {
            exit();
        }

        $PostID = $_POST['PostID'] ?? null;
        $Text = $_POST['Text'];

        if($Text == '') {
            $Errors[] = "Введите текст";
        }
        if (!ExistsPost($PostID)) {
            $Errors[] = 'Такого поста не существует';
        }
        if(mb_strlen($Text) > 300) {
            $Errors[] = "Максимальное количество символов в тексте 300.";
        }
        if(CheckText($Text)) {
            $Errors[] = "Текст содержит запрещённые символы, или же состоит из пробелов";
        }
        if (CH_С_TIME($Account['ID'])) {
            $Errors[] = "Комментарии можно публиковать раз в минуту.";
        }

        if (empty($Errors)) {
            $Text = HandleText($Text);
            $Date = date('Y-m-d H:i:s');

            $Query ="INSERT INTO `comments` (`User`, `Post`, `Text`, `Date`) VALUES (:UserID, :PostID, :Text, :Date)";
            $Stmt = $PDO->prepare($Query);
            $Stmt->execute([
                'UserID' => $Account['ID'],
                'PostID' => $PostID,
                'Text' => $Text,
                'Date' => $Date
            ]);

            RELOAD_P_C($PostID);
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

    if ($Function == 'LOAD_C') {
        if (empty($_POST['PostID'])) {
            exit();
        }
        
        $PostID = $_POST['PostID'];

        $Query = "SELECT * FROM `posts` WHERE `PostID` = :PostID";
        $Stmt = $PDO->prepare($Query);
        $Stmt->bindParam(':PostID', $PostID);
        $Stmt->execute();
        $Post = $Stmt->fetch(PDO::FETCH_ASSOC);

        if ($Post && $Post['Comments'] > 0) {
            $Query = "SELECT c.*, a.Name as Name, a.Username as Username, a.Avatar as Avatar FROM `comments` c INNER JOIN `accounts` a ON c.User = a.ID WHERE `Post` = :Post ORDER BY `Date` DESC";
            $Stmt = $PDO->prepare($Query);
            $Stmt->bindParam(':Post', $PostID);
            $Stmt->execute();

            if ($Stmt) {
                while ($Comment = $Stmt->fetch(PDO::FETCH_ASSOC)) {
                echo 
                '<div class="Comment">
                    <div class="TopBar">
                        <a href="/profile/'. $Comment['Username'] .'">'. GetAvatar($Comment['Avatar'], $Comment['Name']) .'<a>
                        <div>
                            <div class="Name">'. $Comment['Name'] .'</div>
                            <div class="Date">'. TimeAgo($Comment['Date']) .'</div>
                        </div>
                    </div>
                    <div class="Text">'. $Comment['Text'] .'</div>
                </div>';
                }
            }
        } else {
            echo '<div class="Error">Пока что никто не оставил комментарий..</div>';
        }
    }
}

/* Функции */

function G_USR_FRM_ID($UserID) {
    global $PDO;

    $Query = "SELECT * FROM `accounts` WHERE `ID` = :UserID";
    $Stmt = $PDO->prepare($Query);
    $Stmt->bindParam(':UserID', $UserID);
    $Stmt->execute();
    $Result = $Stmt->fetch(PDO::FETCH_ASSOC);

    return $Result;
}

function P_LIKE ($UserID, $PostID) {
    global $PDO;
    $Date = date('Y-m-d H:i:s');

    if (!ExistsPost($PostID)) {
        exit();
    }

    if (UserLiked($UserID, $PostID)) {
        $Query = "DELETE FROM `post_likes` WHERE `PostID` = :PostID AND `UserID` = :UserID";
        $Stmt = $PDO->prepare($Query);
        $Stmt->bindParam(':PostID', $PostID);
        $Stmt->bindParam(':UserID', $UserID);
        $Stmt->execute();
        RELOAD_P_L($PostID);
    } else {
        if (UserDisliked($UserID, $PostID)) {
            $Query = "DELETE FROM `post_dislikes` WHERE `PostID` = :PostID AND `UserID` = :UserID";
            $Stmt = $PDO->prepare($Query);
            $Stmt->bindParam(':PostID', $PostID);
            $Stmt->bindParam(':UserID', $UserID);
            $Stmt->execute();
            $Query = "INSERT INTO `post_likes` (`PostID`, `UserID`, `Date`) VALUES (:PostID, :UserID, :Date)";
            $Stmt = $PDO->prepare($Query);
            $Stmt->bindParam(':PostID', $PostID);
            $Stmt->bindParam(':UserID', $UserID);
            $Stmt->bindParam(':Date', $Date);
            $Stmt->execute();
            RELOAD_P_L($PostID);
            RELOAD_P_D($PostID);
        } else {
            $Query = "INSERT INTO `post_likes` (`PostID`, `UserID`, `Date`) VALUES (:PostID, :UserID, :Date)";
            $Stmt = $PDO->prepare($Query);
            $Stmt->bindParam(':PostID', $PostID);
            $Stmt->bindParam(':UserID', $UserID);
            $Stmt->bindParam(':Date', $Date);
            $Stmt->execute();
            RELOAD_P_L($PostID);
        }
    }
}

function P_DISLIKE ($UserID, $PostID) {
    global $PDO;
    $Date = date('Y-m-d H:i:s');

    if (!ExistsPost($PostID)) {
        exit();
    }

    if (UserDisliked($UserID, $PostID)) {
        $Query = "DELETE FROM `post_dislikes` WHERE `PostID` = :PostID AND `UserID` = :UserID";
        $Stmt = $PDO->prepare($Query);
        $Stmt->bindParam(':PostID', $PostID);
        $Stmt->bindParam(':UserID', $UserID);
        $Stmt->execute();
        RELOAD_P_D($PostID);
    } else {
        if (UserLiked($UserID, $PostID)) {
            $Query = "DELETE FROM `post_likes` WHERE `PostID` = :PostID AND `UserID` = :UserID";
            $Stmt = $PDO->prepare($Query);
            $Stmt->bindParam(':PostID', $PostID);
            $Stmt->bindParam(':UserID', $UserID);
            $Stmt->execute();
            $Query = "INSERT INTO `post_dislikes` (`PostID`, `UserID`, `Date`) VALUES (:PostID, :UserID, :Date)";
            $Stmt = $PDO->prepare($Query);
            $Stmt->bindParam(':PostID', $PostID);
            $Stmt->bindParam(':UserID', $UserID);
            $Stmt->bindParam(':Date', $Date);
            $Stmt->execute();
            RELOAD_P_L($PostID);
            RELOAD_P_D($PostID);
        } else {
            $Query = "INSERT INTO `post_dislikes` (`PostID`, `UserID`, `Date`) VALUES (:PostID, :UserID, :Date)";
            $Stmt = $PDO->prepare($Query);
            $Stmt->bindParam(':PostID', $PostID);
            $Stmt->bindParam(':UserID', $UserID);
            $Stmt->bindParam(':Date', $Date);
            $Stmt->execute();
            RELOAD_P_D($PostID);
        }
    }
}

function RELOAD_P_L($PostID) {
    global $PDO;

    $Query = "UPDATE posts SET Likes = (SELECT COUNT(*) AS LikesCount FROM post_likes WHERE PostID = :PostID) WHERE PostID = :PostID";
    $Stmt = $PDO->prepare($Query);
    $Stmt->bindParam(':PostID', $PostID);
    $Stmt->execute();
}

function RELOAD_P_D($PostID) {
    global $PDO;

    $Query = "UPDATE posts SET Dislikes = (SELECT COUNT(*) AS DislikesCount FROM post_dislikes WHERE PostID = :PostID) WHERE PostID = :PostID";
    $Stmt = $PDO->prepare($Query);
    $Stmt->bindParam(':PostID', $PostID);
    $Stmt->execute();
}

function RELOAD_P_C($PostID) {
    global $PDO;

    $Query = "SELECT COUNT(*) AS Comments FROM `comments` WHERE `Post` = :PostID";
    $Stmt = $PDO->prepare($Query);
    $Stmt->execute([
        'PostID' => $PostID,
    ]);
    if ($Stmt) {
        $Row = $Stmt->fetch(PDO::FETCH_ASSOC);
        $Comments = $Row['Comments'];
        $Query = "UPDATE `posts` SET `Comments` = :Comments WHERE `PostID` = :PostID";
        $Stmt = $PDO->prepare($Query);
        $Stmt->execute([
            'PostID' => $PostID,
            'Comments' => $Comments
        ]);
    }
}

function CH_С_TIME($UserID) {
    global $PDO;
  
    $Query = "SELECT * FROM `comments` WHERE `User` = :UserID ORDER BY `Date` DESC LIMIT 1";
    $Stmt = $PDO->prepare($Query);
    $Stmt->bindParam(':UserID', $UserID);
    $Stmt->execute();
    $Result = $Stmt->fetch(PDO::FETCH_ASSOC);
  
    if ($Result) {
      $TimeLimit = 60;
  
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

function ExistsPost($PostID) {
    global $PDO;

    $Query = "SELECT * FROM `posts` WHERE `PostID` = :PostID";
    $Stmt = $PDO->prepare($Query);
    $Stmt->execute([
        'PostID' => $PostID
    ]);
    $Result = $Stmt->rowCount();

    if ($Result > 0) {
        return true;
    } else {
        return false;
    }
}

?>