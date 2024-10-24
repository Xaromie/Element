<?php

$RootDir = $_SERVER["DOCUMENT_ROOT"];
require($RootDir . '/System/Scripts/Global/DataBase.php');

if(isset($_SESSION['Account'])) {
    unset($_SESSION['Account']);
    setcookie('S_KEY', $S_KEY, time() - 3600 * 24 * 360, "/");
    header('Location: /');
}

?>