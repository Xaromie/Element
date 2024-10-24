<?php

$RootDir = $_SERVER["DOCUMENT_ROOT"];
require($RootDir . '/System/Scripts/Global/Config.php');
require($RootDir . '/System/Scripts/ProfileIcons.php');

/* Авторизация */

function AccountConnect() {
    global $PDO;

    if (isset($_SESSION['Account'])) {
        $ID = $_SESSION['Account'];

        $Query = "SELECT * FROM `accounts` WHERE `ID` = :ID";
        $Stmt = $PDO->prepare($Query);
        $Stmt->bindParam(':ID', $ID);
        $Stmt->execute();
        $Account = $Stmt->fetch(PDO::FETCH_ASSOC);
        if ($Account) {
            return $Account;
        } else {
            return false;
        }
    } else {
        if (isset($_COOKIE['S_KEY'])) {
            $S_KEY = $_COOKIE['S_KEY'];
            $Query = "SELECT * FROM `accounts` WHERE `S_KEY` = :S_KEY";
            $Stmt = $PDO->prepare($Query);
            $Stmt->bindParam(':S_KEY', $S_KEY);
            $Stmt->execute();
            if ($Stmt) {
                $Account = $Stmt->fetch(PDO::FETCH_ASSOC);
                $_SESSION['Account'] = $Account['ID'];
                return $Account;
                $PDO = null;
                header('Location: /home');
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

function ClearText ($Text) {
    $Text = trim($Text);
    $Text = stripslashes($Text);
    $Text = strip_tags($Text);
    $Text = htmlspecialchars($Text);
    return $Text;
}

function CheckText ($Text) {

    if (preg_match('/\p{Arabic}/u', $Text)) {
        return true;
    }
    if (trim($Text) === '') {
        return true;
    }

    return false;
}

function HandleText ($Text) {
    $Text = htmlspecialchars($Text, ENT_QUOTES, 'UTF-8');
    $Text = preg_replace('/[\p{Mn}\p{Me}\p{Cf}\p{Lm}]+/u', '', $Text);
    return $Text;
}

function CheckUsername ($Username) {
    global $PDO;

    if (preg_match('/\s/', $Username)) {
        return 'Уникальное имя не должно содержать пробелов';
    }

    $Query = "SELECT * FROM `accounts` WHERE Username = ?";
    $Stmt = $PDO->prepare($Query);
    $Stmt->execute([$Username]);
    $Result = $Stmt->rowCount();

    if ($Result > 0) {
        return 'Уникальное имя занято';
    } else {
        return false;
    }
}

function CheckEmail ($Email) {
    global $PDO;

    $WhiteList = array(
        'gmail.com',
        'mail.ru',
        'vk.com',
        'proton.me',
        'protonmail.com',
        'yandex.ru',
        'icloud.com'
    );

    $Domain = substr(strrchr($Email, "@"), 1);

    if (!in_array($Domain, $WhiteList)) {
        return "Ошибка почты.";
    }

    if (preg_match('/\s/', $Email)) {
        return 'Почта не должна содержать пробелов';
    }

    $Query = "SELECT * FROM `accounts` WHERE Email = ?";
    $Stmt = $PDO->prepare($Query);
    $Stmt->execute([$Email]);
    $Result = $Stmt->rowCount();

    if ($Result > 0) {
        return 'Почта занята';
    } else {
        return false;
    }
}

function FindAccountID ($Username) {
    global $PDO;

    $Query = "SELECT * FROM `accounts` WHERE Username = :Username";
    $Stmt = $PDO->prepare($Query);
    $Stmt->bindParam(':Username', $Username);
    $Stmt->execute();
    $Result = $Stmt->fetch(PDO::FETCH_ASSOC);
    $Username = $Result['ID'];

    return $Username;
}

/* Посты */

function TimeAgo($Time) {
    $Seconds = time() - strtotime($Time);

    $Minutes = floor($Seconds / 60);
    $Hours = floor($Seconds / 3600);
    $Days = floor($Seconds / 86400);
    $Weeks = floor($Seconds / 604800);
    $Months = floor($Seconds / 2592000);
    $Years = floor($Seconds / 31536000);

    if ($Seconds < 60) {
        return "только что";
    } else if ($Minutes < 60) {
        return $Minutes . " минут назад";
    } else if ($Hours < 24) {
        return $Hours . " часов назад";
    } else if ($Days < 7) {
        return $Days . " дней назад";
    } else if ($Weeks < 4) {
        return $Weeks . " недель назад";
    } else if ($Months < 12) {
        return $Months . " месяцев назад";
    } else {
        return $Years . " лет назад";
    }
}

function UserLiked($UserID, $PostID) {
    global $PDO;

    $Query = "SELECT * FROM `post_likes` WHERE `PostID` = :PostID AND `UserID` = :UserID";
    $Stmt = $PDO->prepare($Query);
    $Stmt->bindParam(':PostID', $PostID);
    $Stmt->bindParam(':UserID', $UserID);
    $Stmt->execute();
    $Result = $Stmt->rowCount();

    if ($Result > 0) {
        return true;
    } else {
        return false;
    }
}

function UserDisliked($UserID, $PostID) {
    global $PDO;
    $Query = "SELECT * FROM `post_dislikes` WHERE `PostID` = :PostID AND `UserID` = :UserID";

    $Stmt = $PDO->prepare($Query);
    $Stmt->bindParam(':PostID', $PostID);
    $Stmt->bindParam(':UserID', $UserID);
    $Stmt->execute();
    $Result = $Stmt->rowCount();

    if ($Result > 0) {
        return true;
    } else {
        return false;
    }
}

function GetAvatar($Avatar, $Name) {
    global $FTP_Upload;
    global $FTP_Domain;

    $Letter = mb_substr($Name, 0, 1, "UTF-8");
    $NoneAvatar = '<div class="Avatar"><div class="NonAvatar">' . $Letter . '</div></div>';

    if($Avatar == 'None') {
        return $NoneAvatar;
    } else {
        if ($FTP_Upload) {
            $URL = $FTP_Domain.'/Content/Avatars/'.$Avatar;
        } else {
            $RootDir = $_SERVER["DOCUMENT_ROOT"];
            $URL = '/Content/Avatars/'.$Avatar;
            if (!file_exists($RootDir.$URL)) {
                return $NoneAvatar;
            }
        }
    }

    return
    '<div class="Avatar">
        <img src="'.$URL.'">
    </div>';
}

function GetUserIcons ($UserID) {
    global $PDO;

    $Icons = '';

    $Query = "SELECT * FROM `icons` WHERE `UserID` = :UserID";
    $Stmt = $PDO->prepare($Query);
    $Stmt->bindParam(':UserID', $UserID);
    $Stmt->execute();

    if ($Stmt) {
        while ($Icon = $Stmt->fetch(PDO::FETCH_ASSOC)) {
            $Icons .= HandleIcon($Icon['IconID']);
        }
    }

    $Query = "SELECT * FROM `subs_gold` WHERE `UserID` = :UserID AND `Status` = 'Active'";
    $Stmt = $PDO->prepare($Query);
    $Stmt->bindParam(':UserID', $UserID);
    $Stmt->execute();
    $Result = $Stmt->fetch(PDO::FETCH_ASSOC);

    if ($Result) {
        $Icons .= HandleIcon('GOLD');
    }

    if (isset($Icons)) {
        return $Icons;
    }
}

function HandleIcon ($Icon,) {
    global $ProfileIcons;

    if (array_key_exists($Icon, $ProfileIcons)) {
        return $ProfileIcons[$Icon][0];
    }
}

/* Подписка */

function GetKeys() {
    global $PDO;

    $Query = "SELECT * FROM `sub_keys` ORDER BY `ID` DESC";
    $Stmt = $PDO->prepare($Query);
    $Stmt->execute();

    while ($Key = $Stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($Key['Activated'] == 'Yes') {
            $Status = 'Dashboard-SK_A';
        } else {
            $Status = 'Dashboard-SK_NA';
        }
        echo '<div class="Dashboard-SUB_KEY '. $Status .'">'. $Key['Key'] .'</div>';
    }
}

function GetGold ($UserID, $Received) {
    global $PDO;

    $Date = date('Y-m-d H:i:s');

    $Query = "INSERT INTO `subs_gold` (`UserID`, `Received`, `Date`) VALUES (:UserID, :Received, :Date)";
    $Stmt = $PDO->prepare($Query);
    $Stmt->bindParam(':UserID', $UserID);
    $Stmt->bindParam(':Received', $Received);
    $Stmt->bindParam(':Date', $Date);
    $Stmt->execute();
}

function GetGoldUsers () {
    global $PDO;

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

function CheckGoldUser ($UserID) {
    global $PDO;
    
    $Query = "SELECT * FROM `subs_gold` WHERE `UserID` = :UserID AND `Status` = 'Active'";
    $Stmt = $PDO->prepare($Query);
    $Stmt->bindParam(':UserID', $UserID);
    $Stmt->execute();
    $Result = $Stmt->fetch(PDO::FETCH_ASSOC);

    if ($Result) {
        return true;
    } else {
        return false;
    }
}

function UploadFile ($Files, $Path, $Name) {
    global $FTP_Upload;
    global $FTP_Dir;
    global $FTP_Server;
    global $FTP_Username;
    global $FTP_Password;
    global $FTP_Port;

    if ($FTP_Upload) {
        $FTP = ftp_connect($FTP_Server, $FTP_Port);
        ftp_login($FTP, $FTP_Username, $FTP_Password);

        ftp_pasv($FTP, true);
        ftp_put($FTP, $FTP_Dir.$Path.$Name, $Files, FTP_BINARY);
        
        ftp_close($FTP);
    } else {
        $Dir = $_SERVER["DOCUMENT_ROOT"] . $Path;
        move_uploaded_file($Files, $Dir . $Name);
    }
}

function DeleteFile ($Path, $File) {
    global $FTP_Upload;
    global $FTP_Dir;
    global $FTP_Server;
    global $FTP_Username;
    global $FTP_Password;
    global $FTP_Port;

    if ($FTP_Upload) {
        $FTP = ftp_connect($FTP_Server, $FTP_Port);
        ftp_login($FTP, $FTP_Username, $FTP_Password);

        ftp_pasv($FTP, true);
        ftp_delete($FTP, $FTP_Dir.$Path.$File);
        
        ftp_close($FTP);
    } else {
        $Dir = $_SERVER["DOCUMENT_ROOT"] . $Path . $File;
        if (file_exists($Dir)) {
            unlink($Dir);
        }
    }
}

function ClearMetadataImage ($FilePath, $FileName, $FileType) {
    global $FTP_Upload;
    global $FTP_Dir;
    global $FTP_Server;
    global $FTP_Username;
    global $FTP_Password;
    global $FTP_Port;

    if ($FileType == 'image/gif' OR $FileType == 'image/png') {
        return false;
    }

    $TempFilePath = $_SERVER["DOCUMENT_ROOT"].'/!Temp/'.$FileName;

    if ($FTP_Upload == true) {
        $FTP = ftp_connect($FTP_Server, $FTP_Port);
        ftp_login($FTP, $FTP_Username, $FTP_Password);

        ftp_pasv($FTP, true);
        
        $TempFile = fopen($TempFilePath, 'w');
        ftp_fget($FTP, $TempFile, $FTP_Dir.$FilePath.$FileName, FTP_BINARY);
        fclose($TempFile);

        $Exif = exif_read_data($TempFilePath) ?? false;

        if ($Exif !== false) {
            $Image = imagecreatefromjpeg($TempFilePath);
            $TempImage = imagecreatetruecolor(imagesx($Image), imagesy($Image));
            imagecopy($TempImage, $Image, 0, 0, 0, 0, imagesx($Image), imagesy($Image));
            imagejpeg($TempImage, $TempFilePath);
            ftp_put($FTP, $FTP_Dir.$FilePath.$FileName, $TempFilePath, FTP_BINARY);
            imagedestroy($Image);
            imagedestroy($TempImage);
        }

        ftp_close($FTP);
    } else {
        $Exif = exif_read_data($_SERVER["DOCUMENT_ROOT"].$FilePath.$FileName);

        if ($Exif !== false) {
            $Image = imagecreatefromjpeg($_SERVER["DOCUMENT_ROOT"].$FilePath.$FileName);
            $TempImage = imagecreatetruecolor(imagesx($Image), imagesy($Image));
            imagecopy($TempImage, $Image, 0, 0, 0, 0, imagesx($Image), imagesy($Image));
            imagejpeg($TempImage, $_SERVER["DOCUMENT_ROOT"].$FilePath.$FileName);
            imagedestroy($Image);
            imagedestroy($TempImage);
        }
    }
}

// Работа с постами

function DeletePost ($PostID) {
    global $PDO;

    $Query = "SELECT * FROM `posts` WHERE `PostID` = :PostID";
    $Stmt = $PDO->prepare($Query);
    $Stmt->execute([
        'PostID' => $PostID
    ]);
    $Result = $Stmt->fetch(PDO::FETCH_ASSOC);

    if ($Result['Content']) {
        $PostContent = json_decode($Result['Content'], true);
        if ($PostContent['Image']) {
            DeleteFile('/Content/Posts/Images/', $PostContent['Image']['File_Name']);
        }
    }

    $Query = "DELETE FROM `posts` WHERE `PostID` = :PostID";
    $Stmt = $PDO->prepare($Query);
    $Stmt->execute(
        ['PostID' => $PostID]
    );
    $Query = "DELETE FROM `post_likes` WHERE `PostID` = :PostID";
    $Stmt = $PDO->prepare($Query);
    $Stmt->execute(
        ['PostID' => $PostID]
    );
    $Query = "DELETE FROM `post_dislikes` WHERE `PostID` = :PostID";
    $Stmt = $PDO->prepare($Query);
    $Stmt->execute(
        ['PostID' => $PostID]
    );
    $Query = "DELETE FROM `comments` WHERE `Post` = :PostID";
    $Stmt = $PDO->prepare($Query);
    $Stmt->execute(
        ['PostID' => $PostID]
    );
}

function GET_UDATA_FROM_PID ($PostID) {
    global $PDO;

    $Query = "SELECT * FROM `posts` WHERE `PostID` = :PostID";
    $Stmt = $PDO->prepare($Query);
    $Stmt->execute(
        ['PostID'=> $PostID]
    );
    $PostData = $Stmt->fetch(PDO::FETCH_ASSOC);

    $Query = "SELECT * FROM `accounts` WHERE `ID` = :ID";
    $Stmt = $PDO->prepare($Query);
    $Stmt->execute(
        ['ID' => $PostData['UserID']]
    );
    $AccountData = $Stmt->fetch(PDO::FETCH_ASSOC);

    if ($AccountData) {
        return $AccountData;
    } else {
        return false;
    }
}

function Answer($Type, $Content) {
    $Answer = array(
        'Type' => $Type,
        'Content' => $Content
    );
    echo json_encode($Answer);
}

function CheckValidUser($UserID) {
    global $PDO;

    $Query = "SELECT * FROM `accounts` WHERE `ID` = ?";
    $Stmt = $PDO->prepare($Query);
    $Stmt->execute([$UserID]);
    $Result = $Stmt->rowCount();

    if ($Result == 0) {
        return false;
    } else {
        return true;
    }
}

function CheckSubscription ($AccountID, $UserID) {
    global $PDO;

    $Query = "SELECT * FROM `subscriptions` WHERE `User` = :User AND `ToUser` = :ToUser";
    $Stmt = $PDO->prepare($Query);
    $Stmt->execute([
        'User' => $AccountID,
        'ToUser' => $UserID
    ]);
    $Result = $Stmt->rowCount();
    
    if ($Result > 0) {
        return true;
    } else {
        return false;
    }
}