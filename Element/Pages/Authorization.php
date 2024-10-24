<?php

$RootDir = $_SERVER["DOCUMENT_ROOT"];
require_once $RootDir.'/System/Scripts/Global/Config.php';

if (isset($_SESSION['Account'])) {
  header('Location: /home');
}

?>
<!DOCTYPE html>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="icon" href="/System/Images/Logo.svg" type="image/x-icon">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <link rel="stylesheet" type="text/css" href="/System/UI/Style-12.css">
  <link rel="stylesheet" type="text/css" href="/System/UI/AnimPack-12.css">
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <meta name="FilesServer" content="<?= $FTP_Domain ?>">
  <meta property="og:title" content="Element - социальная сеть будущего!">
  <meta property="og:description" content="Общайтесь, создавайте посты, комментируйте, или же делитесь ими, всё это бесплатно и без слежки!">
  <title>Авторизация</title>
</head>

<body>
  <div class="Content">
    <div class="Auth-Body UI-Block">
      <div class="Left">
        <div class="LogoAndTitle">
          <img class="Logo" src="/System/Images/Logo.svg">
          <div class="Title">Element - простая и конфиденциальная соц. сеть.</div>
          <div id="LAST_USERS" class="LastUsers">
            <a href="/profile/">
              <div class="User">
                <div class="Avatar"></div>
                <div class="Name"><div class="UI-PRELOAD" style="width: 40px; height: 15px;"></div></div>
              </div>
            </a>
            <a href="/profile/">
              <div class="User">
                <div class="Avatar"></div>
                <div class="Name"><div class="UI-PRELOAD"></div></div>
              </div>
            </a>
            <a href="/profile/">
              <div class="User">
                <div class="Avatar"></div>
                <div class="Name"><div class="UI-PRELOAD"></div></div>
              </div>
            </a>
            <a href="/profile/">
              <div class="User">
                <div class="Avatar"></div>
                <div class="Name"><div class="UI-PRELOAD"></div></div>
              </div>
            </a>
            <a href="/profile/">
              <div class="User">
                <div class="Avatar"></div>
                <div class="Name"><div class="UI-PRELOAD"></div></div>
              </div>
            </a>
          </div>
        </div>
        <div class="Wathermark">
          Создана Xaromie, с хостингом помог zovy.lol
        </div>
      </div>
      <div class="Right">
        <div class="Login">
          <div class="Form_Container-Text">Вход</div>
          <form class="Authorization-Form" id="LOGIN">
            <input id="LF_EMAIL" class="Authorization-Input UI-Input" type="text" placeholder="Почта">
            <input id="LF_PASSWORD" type="password" class="Authorization-Input UI-Input" placeholder="Пароль">

            <button class="Authorization-BTN_1">Войти</button>
          </form>
          <button class="Authorization-BTN_2" id="GO_REG">Создать аккаунт</button>
        </div>
        <div class="Reg">
          <div class="Form_Container-Text">Создание аккаунта</div>
          <form class="Authorization-Form" id="REG_FORM">

            <input name="Name" class="Authorization-Input UI-Input" type="text" placeholder="Имя (Псевдоним)">
            <input name="Username" class="Authorization-Input UI-Input" type="text" placeholder="@уникальныйник">
            <input name="Email" class="Authorization-Input UI-Input" type="text" placeholder="Почта">
            <input name="Password" class="Authorization-Input UI-Input" placeholder="Пароль">

            <div class="Authorization-Accept_R">
              <input name="Accept" type="checkbox" id="checkbox" style="display: none;">
              <label for="checkbox" class="UI-Switch"></label>
              <div style="margin-left: 10px;">Я принимаю <a href="/info" class="Authorization-Accept_R_BTN">правила</a></div>
            </div>

            <div class="Authorization-RC">
              <div class="g-recaptcha" data-sitekey="6Lci-dUmAAAAAN5ZjwsAgQkv1DftqMUgScCgCSBP"></div>
            </div>

            <button class="Authorization-BTN_1" type="submit">Создать аккаунт</button>
          </form>
          <button class="Authorization-BTN_2" id="GO_LOGIN">Войти</button>
        </div>
        <div class="Verify">
          <div class="Form_Container-Text">Проверка почты</div>
          <form class="Authorization-Form" id="REG_MAIL_FORM">

            <input name="Code" class="Authorization-Input UI-Input" type="text" placeholder="Код из письма">

            <button class="Authorization-BTN_1" type="submit">Проверить</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script src="/System/JavaScript/jQuery.js"></script>
  <script src="/System/JavaScript/Element-12.js"></script>
  <script src="/System/JavaScript/Authorization-12.js"></script>
</body>

</html>