<?php

$RootDir = $_SERVER["DOCUMENT_ROOT"];
require($RootDir . '/System/Scripts/Global/DataBase.php');
require($RootDir . '/System/Scripts/Global/Function.php');
require($RootDir . '/System/Scripts/Global/Elements.php');

?>

<!DOCTYPE html>

<head>
    <?= $Head ?>
    <title>Профиль</title>
</head>

<body>
    <?= $StatusBar ?>
    <div class="Content Profile-Page">
        <div class="UI-C_L">

            <div class="UI-Block Profile-InfoBlock UI-B_FIRST">

                <div id="PROFILE_COVER" class="Profile-Cover"></div>

                <div id="PROFILE_AVATAR" class="Avatar"></div>

                <div id="PROFILE_NAME" class="Name">
                    <div class="UI-PRELOAD" style="width: 100px; height: 15px;"></div>
                </div>
                <div class="IconInfoContainer">
                    <div class="Info"></div>
                </div>
                <div id="PROFILE_USERNAME" class="Username">
                    <div class="UI-PRELOAD" style="width: 120px; height: 15px;"></div>
                </div>

                <div id="PROFILE_BUTTONS" style="position: relative;">
                    <div id="PROFILE_GOVERN_BUTTONS" class="GovernButtons">
                        <button id="PROFILE_DL_COVER" class="Button">Удалить обложку</button>
                        <div class="UI-PUSTOTA_H"></div>
                        <button id="PROFILE_DL_AVATAR" class="Button">Удалить аватарку</button>
                        <div class="UI-PUSTOTA_H"></div>
                        <button id="PROFILE_BLOCK" class="Button">Запретить посты</button>
                    </div>
                </div>

                <div class="Info">
                    <div class="Container">
                        <div id="PROFILE_SUBSCRIBERS" class="Value">
                            <div class="UI-PRELOAD" style="width: 40px; height: 15px;"></div>
                        </div>
                        <div class="Title">подписчиков</div>
                    </div>
                    <div class="UI-PUSTOTA_W"></div>
                    <div class="Container">
                        <div id="PROFILE_SUBSCRIPTIONS" class="Value">
                            <div class="UI-PRELOAD" style="width: 40px; height: 15px;"></div>
                        </div>
                        <div class="Title">подписок</div>
                    </div>
                    <div class="UI-PUSTOTA_W"></div>
                    <div class="Container">
                        <div id="PROFILE_POSTS" class="Value">
                            <div class="UI-PRELOAD" style="width: 40px; height: 15px;"></div>
                        </div>
                        <div class="Title">постов</div>
                    </div>
                </div>
                <div id="PROFILE_DESCRIPTION" style="width: 100%;"></div>
            </div>
        </div>

        <div class="UI-C_R Profile-Posts">
            <div class="UI-Tabs UI-B_FIRST">
                <button id="PROFILE-PARTITION_POSTS" class="Tab ActiveTab">Посты</button>
                <div class="UI-PUSTOTA_W"></div>
                <button id="PROFILE-PARTITION_INFO" class="Tab">Доп. информация</button>
            </div>
            <div id="Posts"></div>
            <div id="ProfileInfo" hidden>
                <div class="UI-Block">
                    <div id="PROFILE_REG_DATE"></div>
                </div>
            </div>
        </div>
    </div>
    <script src="/System/JavaScript/Profile-115.js"></script>
    <script src="/System/JavaScript/Packer.js"></script>
    <script src="/System/JavaScript/PostInteraction-12.js"></script>
</body>

</html>