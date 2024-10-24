<?php

$Data = $_POST;

if(isset($Data['SendButton'])) {
    $Result = md5($Data['Password']. "ZZZQuErT-s72hwsAdw334Axccvr");
    echo $Result;
}

?>

<form method="POST" action="/pass">
    <input name="Password" type="text" placeholder="Пароль">
    <button name="SendButton" type="submit">Отправить</button>
</form>