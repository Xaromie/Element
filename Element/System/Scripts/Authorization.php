<?php

$RootDir = $_SERVER["DOCUMENT_ROOT"];
require($RootDir . '/System/Scripts/Global/DataBase.php');
require($RootDir . '/System/Scripts/Global/Function.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

header('Content-Type: application/json');

$Data = $_POST;
$Function = $_GET['F'];

if (isset($Function)) {

    if ($Function == 'REG') {

        $Username = str_replace('@', '', $Data['Username']);
        $Email = $Data['Email'];

        if ($Data['Name'] == '') {
            $Errors[] = 'Введите имя';
        }
        if ($Username == '') {
            $Errors[] = 'Введите уникальное имя';
        }
        if ($Email == '') {
            $Errors[] = 'Введите почту';
        }
        if ($Data['Password'] == '') {
            $Errors[] = 'Введите пароль';
        }
        if (CheckText($Username)) {
            $Errors[] = 'Уникальное имя содержит запрещённые символы';
        }
        if (CheckText($Data['Name'])) {
            $Errors[] = 'Имя содержит запрещённые символы';
        }
        if (CheckText($Email)) {
            $Errors[] = 'Почта содержит запрещённые символы';
        }
        if (preg_match('/[^a-zA-Zа-яА-Я0-9._]/u', $Username)) {
            $Errors[] = 'Уникальное имя содержит запрещённые символы';
        }
        if (CheckUsername($Username)) {
            $Errors[] = CheckUsername($Username);
        }
        if (CheckEmail($Email)) {
            $Errors[] = CheckEmail($Email);
        }
        if (!$Data['g-recaptcha-response']) {
            $Errors[] = 'Капча не пройдена.';
        }
        if (!isset($Data['Accept'])) {
            $Errors[] = 'Вы должны принять правила.';
        }

        $C_URL = 'https://www.google.com/recaptcha/api/siteverify';
        $SC_Key = '6Lci-dUmAAAAAPfgaLVDccvwJgvodOzEVltGg4ef';
        $Query = $C_URL . '?secret=' . $SC_Key . '&response=' . $Data['g-recaptcha-response'] ?? null . '&remoteip=' . $_SERVER['REMOTE_ADDR'];
        $C_Data = json_decode(file_get_contents($Query));

        if ($C_Data->success == false) {
            $Errors[] = 'Капча не пройдена.';
        }

        if (empty($Errors)) {
            $Username = HandleText($Username);
            $Name = HandleText($Data['Name']);
            $Email = HandleText($Data['Email']);
            $Password = md5($Data['Password'] . "ZZZQuErT-s72hwsAdw334Axccvr");

            if ($Email_Checker) {
                $Query = "SELECT * FROM `verify_email` WHERE `Email` = ?";
                $Stmt = $PDO->prepare($Query);
                $Stmt->execute([$Email]);
                $Result = $Stmt->fetch(PDO::FETCH_ASSOC);

                if ($Result) {
                    $Query = "DELETE FROM `verify_email` WHERE `Email` = ?";
                    $Stmt = $PDO->prepare($Query);
                    $Stmt->execute([$Email]);
                }

                $Code = sprintf("%06d", rand(0, 9999));

                $Query = "INSERT INTO `verify_email` (`Name`, `Username`, `Email`, `Password`, `Code`) VALUES (:Name, :Username, :Email, :Password, :Code)";
                $Stmt = $PDO->prepare($Query);
                $Stmt->execute([
                    'Name' => $Name,
                    'Username' => $Username,
                    'Email' => $Email,
                    'Password' => $Password,
                    'Code' => $Code
                ]);

                SendVerifyEmail($Email, $Code);

                $Type = 'Verify';
                $Content = 'CodeIsTrue';
            } else {
                $S_KEY = md5(microtime() . $Username . $Password . "AQ_10=1-OW_XD3wdder987654ey");
                $CreateDate = date('Y-m-d H:i:s');

                $Query = "INSERT INTO `accounts` (`Name`, `Username`, `Email`, `Password`, `S_KEY`, `CreateDate`) VALUES (:Name, :Username, :Email, :Password, :S_KEY, :CreateDate)";
                $Stmt = $PDO->prepare($Query);
                $Stmt->execute([
                    'Name' => $Name,
                    'Username' => $Username,
                    'Email' => $Email,
                    'Password' => $Password,
                    'S_KEY' => $S_KEY,
                    'CreateDate' => $CreateDate
                ]);

                $AccountID = FindAccountID($Username);
                $_SESSION['Account'] = $AccountID;
                setcookie('S_KEY', $S_KEY, time() + 360 * 24 * 360, "/");

                $Type = 'Verify';
                $Content = 'CodeIsFalse';
            }

        } else {
            $Type = 'Error';
            $Content = array_shift($Errors);
        }
    }

    if ($Function == 'REG_V_M') {

        if (isset($Data['Code'])) {

            $Code = $Data['Code'];

            $Query = "SELECT * FROM `verify_email` WHERE `Code` = ?";
            $Stmt = $PDO->prepare($Query);
            $Stmt->execute([$Code]);
            $Result = $Stmt->fetch(PDO::FETCH_ASSOC);

            if ($Result) {
                $Username = HandleText($Result['Username']);
                $Name = HandleText($Result['Name']);
                $Email = HandleText($Result['Email']);
                $Password = $Result['Password'];
                $S_KEY = md5(microtime() . $Username . $Password . "AQ_10=1-OW_XD3wdder987654ey");
                $CreateDate = date('Y-m-d H:i:s');

                $Query = "SELECT * FROM `accounts` WHERE `Username` = ?";
                $Stmt = $PDO->prepare($Query);
                $Stmt->execute([$Username]);
                $Result = $Stmt->fetch(PDO::FETCH_ASSOC);

                if (!$Result) {
                    $Query = "INSERT INTO `accounts` (`Name`, `Username`, `Email`, `Password`, `S_KEY`, `CreateDate`) VALUES (:Name, :Username, :Email, :Password, :S_KEY, :CreateDate)";
                    $Stmt = $PDO->prepare($Query);
                    $Stmt->execute([
                        'Name' => $Name,
                        'Username' => $Username,
                        'Email' => $Email,
                        'Password' => $Password,
                        'S_KEY' => $S_KEY,
                        'CreateDate' => $CreateDate
                    ]);

                    $Query = "DELETE FROM `verify_email` WHERE `Code` = ?";
                    $Stmt = $PDO->prepare($Query);
                    $Stmt->execute([$Code]);

                    $Account_ID = FindAccountID($Username);

                    $_SESSION['Account'] = $Account_ID;
                    setcookie('S_KEY', $S_KEY, time() + 360 * 24 * 360, "/");

                    $Type = 'Verify';
                    $Content = 'None';
                }
            } else {
                $Type = 'Error';
                $Content = 'Код недействителен';
            }
        } else {
            $Type = 'Error';
            $Content = 'Введите код';
        }
    }

    if ($Function == 'LOGIN') {

        $Email = $Data['Email'] ?? null;
        $Password = $Data['Password'] ?? null;

        if (!$Email) {
            $Errors[] = 'Введите почту';
        }

        if (!$Password) {
            $Errors[] = 'Введите пароль';
        } else {
            $Password = md5($Data['Password'] . "ZZZQuErT-s72hwsAdw334Axccvr");
        }

        if (empty($Errors)) {
            $Query = "SELECT * FROM `accounts` WHERE `Email` = ?";
            $Stmt = $PDO->prepare($Query);
            $Stmt->execute([$Email]);
            $Account = $Stmt->fetch(PDO::FETCH_ASSOC);

            if ($Account) {
                if ($Password != $Account['Password']) {
                    $Errors[] = 'Пароль не верный.';
                } else {
                    $S_KEY = md5(microtime() . $Email . $Password . "AQ_10=1-OW_XD3wdder987654ey");

                    $Query = "UPDATE `accounts` SET `S_KEY` = :S_KEY WHERE `ID` = :ID";
                    $Stmt = $PDO->prepare($Query);
                    $Stmt->execute([
                        'ID' => $Account['ID'],
                        'S_KEY' => $S_KEY
                    ]);

                    $_SESSION['Account'] = $Account['ID'];
                    setcookie('S_KEY', $S_KEY, time() + 360 * 24 * 360, "/");

                    $Type = 'Verify';
                    $Content = 'None';
                }
            } else {
                $Errors[] = 'Такого аккаунта не существует';
                $Type = 'Error';
                $Content = array_shift($Errors);
            }
        } else {
            $Type = 'Error';
            $Content = array_shift($Errors);
        }
    }

    $Answer = array(
        'Type' => $Type,
        'Content' => $Content
    );

    echo json_encode($Answer);
}

// Функции

function SendVerifyEmail($Email, $Code) {

    global $RootDir;
    $Mail = new PHPMailer(true);

    // Настройка SMTP
    $Mail->isSMTP();
    $Mail->Host = 'altnodes.top';
    $Mail->SMTPAuth = true;
    $Mail->Username = 'element@altnodes.top';
    $Mail->Password = 'Vqyj11$95';
    $Mail->SMTPSecure = 'ssl';
    $Mail->Port = 465;

    $Mail->addAddress($Email);

    // Отправка письма
    $Mail->setFrom('element@altnodes.top', 'Element Verify');
    $Mail->CharSet = 'UTF-8';
    $Mail->IsHTML(true);
    $HTML = file_get_contents($RootDir . '/Pages/VerifyMessage.php');
    $HTML = str_replace('{Code}', $Code, $HTML);
    $Mail->Subject = 'Код активации';
    $Mail->Body = $HTML;

    $Mail->send();
}

?>