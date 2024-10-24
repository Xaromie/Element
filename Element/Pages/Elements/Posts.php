<?php

if (empty($Verify)) {
    header('Location: /home');
    exit();
}

?>

<head>
    <title>Главная</title>
</head>

<div class="UI-C_L">

    <!-- Публикация поста -->

    <div class="UI-Block AddPost UI-B_FIRST">
        <div class="UI-Title">Добавить пост</div>
        <form id="AP-FORM" enctype="multipart/form-data">
            <textarea id="AP-TEXT_INPUT" name="Text" class="UI-Input" maxlength="700" placeholder="Текст поста..."></textarea>
            <div class="Buttons">
                <button id="AP-SEND" class="Send" type="submit">Отправить</button>
                <input id="AP-FILE_INPUT" name="Image" type="file">
                <div class="AddFileButtons">
                    <label class="AddFile" for="AP-FILE_INPUT">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M18 3H6a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3V6a3 3 0 0 0-3-3zM6 5h12a1 1 0 0 1 1 1v8.36l-3.2-2.73a2.77 2.77 0 0 0-3.52 0L5 17.7V6a1 1 0 0 1 1-1zm12 14H6.56l7-5.84a.78.78 0 0 1 .93 0L19 17v1a1 1 0 0 1-1 1z"/><circle cx="8" cy="8.5" r="1.5"/></svg>
                        <div class="SelectFile">
                            <div id="AP-FILE_NAME"></div>
                        </div>
                    </label>
                    <div id="AP-CLOSE_FILE" class="Close">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m0 0h24v24h-24z" fill="#fff" opacity="0" transform="matrix(-1 0 0 -1 24 24)"/><path d="m13.41 12 4.3-4.29a1 1 0 1 0 -1.42-1.42l-4.29 4.3-4.29-4.3a1 1 0 0 0 -1.42 1.42l4.3 4.29-4.3 4.29a1 1 0 0 0 0 1.42 1 1 0 0 0 1.42 0l4.29-4.3 4.29 4.3a1 1 0 0 0 1.42 0 1 1 0 0 0 0-1.42z"/></svg>
                    </div>
                    <div id="AP-FILE_SETTINGS" class="FileSettings">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M7.429 1.525a6.593 6.593 0 011.142 0c.036.003.108.036.137.146l.289 1.105c.147.56.55.967.997 1.189.174.086.341.183.501.29.417.278.97.423 1.53.27l1.102-.303c.11-.03.175.016.195.046.219.31.41.641.573.989.014.031.022.11-.059.19l-.815.806c-.411.406-.562.957-.53 1.456a4.588 4.588 0 010 .582c-.032.499.119 1.05.53 1.456l.815.806c.08.08.073.159.059.19a6.494 6.494 0 01-.573.99c-.02.029-.086.074-.195.045l-1.103-.303c-.559-.153-1.112-.008-1.529.27-.16.107-.327.204-.5.29-.449.222-.851.628-.998 1.189l-.289 1.105c-.029.11-.101.143-.137.146a6.613 6.613 0 01-1.142 0c-.036-.003-.108-.037-.137-.146l-.289-1.105c-.147-.56-.55-.967-.997-1.189a4.502 4.502 0 01-.501-.29c-.417-.278-.97-.423-1.53-.27l-1.102.303c-.11.03-.175-.016-.195-.046a6.492 6.492 0 01-.573-.989c-.014-.031-.022-.11.059-.19l.815-.806c.411-.406.562-.957.53-1.456a4.587 4.587 0 010-.582c.032-.499-.119-1.05-.53-1.456l-.815-.806c-.08-.08-.073-.159-.059-.19a6.44 6.44 0 01.573-.99c.02-.029.086-.075.195-.045l1.103.303c.559.153 1.112.008 1.529-.27.16-.107.327-.204.5-.29.449-.222.851-.628.998-1.189l.289-1.105c.029-.11.101-.143.137-.146zM8 0c-.236 0-.47.01-.701.03-.743.065-1.29.615-1.458 1.261l-.29 1.106c-.017.066-.078.158-.211.224a5.994 5.994 0 00-.668.386c-.123.082-.233.09-.3.071L3.27 2.776c-.644-.177-1.392.02-1.82.63a7.977 7.977 0 00-.704 1.217c-.315.675-.111 1.422.363 1.891l.815.806c.05.048.098.147.088.294a6.084 6.084 0 000 .772c.01.147-.038.246-.088.294l-.815.806c-.474.469-.678 1.216-.363 1.891.2.428.436.835.704 1.218.428.609 1.176.806 1.82.63l1.103-.303c.066-.019.176-.011.299.071.213.143.436.272.668.386.133.066.194.158.212.224l.289 1.106c.169.646.715 1.196 1.458 1.26a8.094 8.094 0 001.402 0c.743-.064 1.29-.614 1.458-1.26l.29-1.106c.017-.066.078-.158.211-.224a5.98 5.98 0 00.668-.386c.123-.082.233-.09.3-.071l1.102.302c.644.177 1.392-.02 1.82-.63.268-.382.505-.789.704-1.217.315-.675.111-1.422-.364-1.891l-.814-.806c-.05-.048-.098-.147-.088-.294a6.1 6.1 0 000-.772c-.01-.147.039-.246.088-.294l.814-.806c.475-.469.679-1.216.364-1.891a7.992 7.992 0 00-.704-1.218c-.428-.609-1.176-.806-1.82-.63l-1.103.303c-.066.019-.176.011-.299-.071a5.991 5.991 0 00-.668-.386c-.133-.066-.194-.158-.212-.224L10.16 1.29C9.99.645 9.444.095 8.701.031A8.094 8.094 0 008 0zm1.5 8a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM11 8a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                </div>
            </div>
            <div id="AP-FS_SWITCHES" class="FileSettingsSwitchs">
                <div class="Item">
                    <input name="ClearMetadataIMG" id="AP-CI" type="checkbox" style="display: none;">
                    Очистить метаданные
                    <label for="AP-CI" class="UI-Switch"></label>
                </div>
                <div class="UI-PUSTOTA_H"></div>
                <div class="Item">
                    <input name="CensoringIMG" id="AP-CMI" type="checkbox" style="display: none;">
                    Деликатный контент
                    <label for="AP-CMI" class="UI-Switch"></label>
                </div>
            </div>
        </form>
    </div>

    <!-- Переключение вкладок -->

    <div class="UI-Tabs">
        <button id="POSTS-LATEST" class="Tab ActiveTab">Последние</button>
        <div class="UI-PUSTOTA_W"></div>
        <button id="POSTS-SUBSCRIPTIONS" class="Tab">Подписки</button>
    </div>

    <!-- Посты -->

    <div id="Posts"></div>
</div>
<div class="UI-C_R">
    <div class="UI-Block UI-B_FIRST">
        <div class="UI-Title">Обновление 1.2</div>
        <div class="UI-B_CONTENT">
            <div>Для Gold пользователей:</div>
            <div>• Вы можете сохранить любой пост в формате EPACK.</div>
            <div>Для всех:</div>
            <div>• Добавлен предпросмотр функций на странице подписки.</div>
            <div>• Добавлена анимация загрузки некоторых элементов.</div>
            <div>• Теперь можно подписываться на пользователей, и видеть такой контент, который вы хотите.</div>
            <div>• Теперь можно публиковать деликатный контент, а так же очистить метаданные при отправке файла.</div>
            <div>• Теперь список Gold пользователей есть на главной странице.</div>
            <div>• Теперь можно использовать эмодзи и HTML символы.</div>
            <div>• Теперь можно полноценно посмотреть изображение или же скачать его.</div>
            <div>• Теперь вы можете удалить свой пост.</div>
            <div>• Теперь при выборе темы с демонстрациями преимуществ подписки Gold, адаптируется видео.</div>
            <div>• Чат адаптирован под телефоны.</div>
            <div>• Улучшен, и оптимизирован интерфейс.</div>
            <div>• Исправление багов.</div>
            <div>• Изменены правила, подробнее на elm.lol/info/rules.</div>
        </div>
    </div>

    <?php if (CheckGoldUser($Account['ID']) == false): ?>
        <div class="UI-AD_N2-B">
            <div class="UI-AD_C_TOP">
                <div class="UI-AD_TITLE">Реклама</div>
            </div>
            <div class="UI-AD-T">Подпишитесь на телеграм канал автора сайта</div>
            <div class="UI-AD_C_BOTTOM">
                <a class="UI-AD_BTN" href="https://t.me/XaromieChannel">Перейти</a>
            </div>
            <img class="UI-AD_IMG" src="/System/Images/Ad/ADBG-1.png">
        </div>
    <?php endif; ?>

    <div class="UI-Block">
        <div class="UI-Title" style="width: 100%;">Gold пользователи</div>
        <div class="GoldSub-Users">
            <div class="UI-PRELOAD"></div>
            <div class="UI-PRELOAD"></div>
            <div class="UI-PRELOAD"></div>
        </div>
    </div>
</div>

<script src="/System/JavaScript/Packer.js"></script>
<script src="/System/JavaScript/PostInteraction-12.js"></script>
<script>

    Preload('Posts', '#Posts');

    $(document).ready(function () {

        var StartIndex = 25;

        localStorage.setItem('PostsType', 'LATEST');
        LoadPosts('LATEST');
        setTimeout(function () {
            GetGoldUsers('.GoldSub-Users');
        }, 200);

        $('#POSTS-LATEST').click( function () {
            var PostsType = localStorage.getItem('PostsType');
            if (PostsType !== 'LATEST') {
                Preload('Posts', '#Posts');
                localStorage.setItem('PostsType', 'LATEST');
                LoadPosts('LATEST');
            }
        })
        $('#POSTS-SUBSCRIPTIONS').click( function () {
            var PostsType = localStorage.getItem('PostsType');
            if (PostsType !== 'SUBSCRIPTIONS') {
                Preload('Posts', '#Posts');
                localStorage.setItem('PostsType', 'SUBSCRIPTIONS');
                LoadPosts('SUBSCRIPTIONS');
            }
        })

        $('.Content').on('click', '.UI-LM_BTN', function () {
            var PostsType = localStorage.getItem('PostsType');
            $.ajax({
                url: '/System/API/LoadPosts.php?F=' + PostsType,
                type: "POST",
                dataType: 'json',
                data: { StartIndex },
                success: function (Posts) {
                    $('.UI-LM_BTN').remove();
                    $('#Posts').append(HandlePosts(Posts));
                    StartIndex += 25;
                    if (Posts.length === 25) {
                        $('#Posts').append('<button class="UI-LM_BTN">Показать больше</button>');
                    }
                }
            })
        });
    });

    function LoadPosts(Type) {
        $.ajax({
            url: '/System/API/LoadPosts.php?F=' + Type,
            type: 'POST',
            dataType: 'json',
            success: function (Posts) {
                $('.UI-LM_BTN').remove();
                $('#Posts').html(HandlePosts(Posts));
                if (Posts.length === 25) {
                    $('#Posts').append('<button class="UI-LM_BTN">Показать больше</button>');
                }
            }
        });
    }
</script>