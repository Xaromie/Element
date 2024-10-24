<?php

$RootDir = $_SERVER["DOCUMENT_ROOT"];
require($RootDir . '/System/Scripts/Global/DataBase.php');
require($RootDir . '/System/Scripts/Global/Function.php');
require($RootDir . '/System/Scripts/Global/Elements.php');

if (!$Account) {
    exit();
}

?>
<!DOCTYPE html>

<head>
    <?= $Head ?>
    <title>Мессенджер</title>
</head>

<body>
    <?= $StatusBar ?>
    <div class="Content">
        <div class="Messanger">
            <div class="Chats">
                <div class="Chats-Title">Чаты</div>
                <div class="Chats-List_scroll">
                    <div id="CHATS_LIST" class="Chats-List">
                        {Список чатов}
                    </div>
                </div>
            </div>
            <div class="Chat">
                <div class="Chat-Messanges">
                    <div class="Chat-Messanges_scroll">
                        <div id="CHAT_MESSAGES" class="Chat-Messanges_list"></div>
                    </div>
                </div>
                <div class="Chat-TopBar">
                    <i class="icon-ChatBack"></i>
                    <div id="CHAT_NAME" class="Chat-Name"><div class="UI-PRELOAD" style="width: 100px; height: 15px"></div></div>
                    <div id="CHAT_AVATAR" class="Avatar"><div class="UI-PRELOAD"></div></div>
                </div>
                <div class="Chat-TopWarning">
                    <div class="Text">Этот пользователь отправил вам сообщение, если его сообщения вам не приятны вы можете удалить чат.</div>
                    <div class="Buttons">
                        <button id="APPLY_CHAT" class="Apply">Принять</button>
                        <div class="UI-PUSTOTA_W"></div>
                        <button id="DELETE_CHAT" class="Close">Удалить чат</button>
                    </div>
                </div>
                <div class="Chat-DownBar">
                    <input class="Chat-Input UI-Input" id="MESSAGE_INPUT" type="text" placeholder="Введите сообщение..">
                    <button class="Chat-Send_button" id="SEND_MESSAGE">
                        <i class="icon-Send"></i>
                    </button>
                </div>
                <div class="Chat-Error">
                    <div class="Chat-Error_message">
                        Нет выбранного чата, выберите чат из списка или напишите кому-то.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {

            if (Device === 'Mobile') {
                $.ajax({
                    url: '/PagesMobile/Messager.html',
                    dataType: 'html',
                    success: function (HTML) {
                        $('head').append('<link rel="stylesheet" type="text/css" href="/System/UI/MobileStyle.css">');
                        $('body').html(HTML);
                    }
                })
            }

        })
    </script>
    <script src="/System/JavaScript/Messager-12.js"></script>
</body>