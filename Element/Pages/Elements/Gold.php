<?php

if (empty($Verify)) {
    header('Location: /home');
    exit();
}

?>

<head>
    <title>Подписка Gold</title>
</head>

<div class="UI-C_L">
    <div class="GoldSub-Info">
        <div class="GoldSub-Info_scroll">
            <div class="UI-Block UI-B_FIRST">
                <div class="UI-Title">Подписка Gold</div>
                <img class="GoldSub-Logo" src="/System/Images/SubscriptionLogo.svg">
                <?php if ($SubGold == true): ?>
                    <div class="GoldSub-Price">Приобретено</div>
                <?php else: ?>
                    <div class="GoldSub-Price">1 месяц / 39 рублей</div>
                <?php endif; ?>
            </div>
            <div class="UI-Block">
                <div class="UI-Title">Преимущества</div>
                <div class="GoldSub-Advantages">
                    <div class="GoldSub-A_Block">
                        <div class="GoldSub-A_B_TITLE" target-video="GoldSub_EPACK">Загрузка EPACK</div>
                        Продвинутый формат сохранения информации, вы сможете скачать любой пост в этом формате и этот
                        файл
                        будет не зависим от Element'a, и будет доступен к просмотру всегда.
                    </div>
                    <div class="UI-PUSTOTA_H"></div>
                    <div class="GoldSub-A_Block">
                        <div class="GoldSub-A_B_TITLE" target-video="GoldSub_Icon">Уникальный значок</div>
                        У вас в профиле будет уникальный значок, он так же будет виден на посте.
                    </div>
                    <div class="UI-PUSTOTA_H"></div>
                    <div class="GoldSub-A_Block">
                        <div class="GoldSub-A_B_TITLE" target-video="GoldSub_Ad">Удаление рекламы</div>
                        Вся реклама которая есть будет скрыта для вас.
                    </div>
                    <div class="UI-PUSTOTA_H"></div>
                    <div class="GoldSub-A_Block">
                        <div class="GoldSub-A_B_TITLE" target-video="GoldSub_Theme">Уникальная тема</div>
                        У вас будет дополнительная золотая тема.
                    </div>
                    <div class="UI-PUSTOTA_H"></div>
                    <div class="GoldSub-A_Block">
                        <div class="GoldSub-A_B_TITLE" target-video="GoldSub_List">Особый список</div>
                        Ваш аккаунт будет добавлен в особый список на главной странице.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="GoldSub-Info_action">
        <?php if ($SubGold == false): ?>
            <div class="GoldSub-Buttons">
                <button id="SUB-PAY" class="Pay">Купить</button>
                <button id="SUB-ACT_KEY" class="Activate">Активировать</button>
            </div>
        <?php endif; ?>
        <div class="GoldSub-VideoPrew">
            <video autoplay muted loop></video>
            <div class="Info">
                <div class="InfoTitle">Загрузка EPACK</div>
                <div class="InfoDec">Продвинутый формат сохранения информации, вы сможете скачать любой пост в этом формате и этот файл будет не зависим от Element'a, и будет доступен к просмотру всегда.</div>
                <button id="CLOSE_VIDEO_PREW" class="Close">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<div class="UI-C_R">
    <div class="UI-Block UI-B_FIRST">
        <div class="UI-Title" style="width: 100%;">Уже купили</div>
        <div class="GoldSub-Users">
            <div class="UI-PRELOAD"></div>
            <div class="UI-PRELOAD"></div>
            <div class="UI-PRELOAD"></div>
        </div>
    </div>
</div>

<script>
    setTimeout( function() {
        GetGoldUsers('.GoldSub-Users');
    }, 200);
</script>