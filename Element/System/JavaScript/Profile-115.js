$(document).ready(function () {

    Preload('Posts', '#Posts');
    var Username = decodeURIComponent(window.location.pathname).match(/\/profile\/([^/]+)/);

    if (Username) {

        $.ajax({
            url: '/System/Scripts/Profile.php?Username=' + Username[1],
            type: 'GET',
            dataType: 'json',
            success: function (Profile) {
                if (Profile === 'Error') {
                    $('.Content').html('<img class="UI-E_IMG" src="/System/Images/Error.png">');
                } else {
                    HandleProfile(Profile);
                }
            }
        });

        // Обработка профиля
        function HandleProfile (Profile) {
            var UserID = Profile.ID;
            var DESCRIPTION_HTML = '';
            var BUTTONS_HTML = '';

            $('.Profile-InfoBlock').attr("userid", UserID);

            if (Profile.Cover !== 'None') {
                $('#PROFILE_COVER').html('<img src="' + FilesServer + '/Content/Covers/' + Profile.Cover + '">');
            }

            if (Profile.Description !== null) {
                DESCRIPTION_HTML = '<div class="Description">'
                    + '<div class="Title">описание</div>'
                    + '<div class="Text">' + Profile.Description + '</div>'
                    + '</div>';
            }

            if (Profile.MyProfile === 'No') {
                BUTTONS_HTML += '<div class="ButtonsContainer">' +
                    '<div class="Buttons">' + 
                    '<button id="PROFILE-SUB_BUTTON" class="SubButton Button" sub="0">Подписаться</button>' +
                    '<div class="UI-PUSTOTA_W"></div>' +
                    '<a href="/chat/' + Profile.Username + '"><button class="Button"><i class="icon-Nav_Messager"></i></button></a>';
                if (Profile.MyStatus === 'Admin') {
                    BUTTONS_HTML += '<div class="UI-PUSTOTA_W"></div>' +
                        '<button id="PROFILE_GOVERN" clicked="0" class="Button"><i class="icon-DotsH"></i></button>' + 
                        '</div>';
                } else {
                    BUTTONS_HTML += '</div>';
                }
            }

            var Avatar = GetAvatar(Profile.Avatar, Profile.Name);

            $('head').append('<meta name="UserID" content="' + UserID + '">')
            $('#PROFILE_AVATAR').html(Avatar);
            $('#PROFILE_NAME').html(Profile.Name);
            $('#PROFILE_USERNAME').html('@' + Profile.Username);
            $('#PROFILE_SUBSCRIBERS').html(Profile.Subscribers);
            $('#PROFILE_SUBSCRIPTIONS').html(Profile.Subscriptions);
            $('#PROFILE_POSTS').html(Profile.Posts);
            $('#PROFILE_DESCRIPTION').html(DESCRIPTION_HTML);
            $('#PROFILE_BUTTONS').append(BUTTONS_HTML);

            $('#PROFILE_REG_DATE').text('Дата регистрации: ' + Profile.CreateDate)

            if (Profile.Icons !== null) {
                $('#PROFILE_NAME').append('<div class="UserIcons">' + Profile.Icons + '</div>');
            }
            if (Profile.Subscribed === true) {
                var SubButton = $('#PROFILE-SUB_BUTTON');
                SubButton.html('Отписаться');
                SubButton.attr('sub', '1');
                SubButton.removeClass('SubButton');
            }
            if (Profile.Posts > 0) {
                LoadPosts(Profile);
            }
        }

        /* Действия с профилем */

        // Подписка
        $('.Profile-InfoBlock').on('click', '#PROFILE-SUB_BUTTON', function () {

            var UserID = $("meta[name='UserID']").attr('content');
            var SubButton = $(this);
            var SubStatus = JSON.parse($(this).attr('sub'));
            var SubsCount = parseInt($('#PROFILE_SUBSCRIBERS').text());

            $.ajax({
                url: '/System/API/Subscriptions.php?F=SUB_TO_USER',
                type: 'POST',
                data: { UserID },
                dataType: 'json',
            });

            if (SubStatus === 0) {
                SubsCount++
                SubButton.html('Отписаться');
                SubButton.attr('sub', '1');
                SubButton.removeClass('SubButton');
            } else {
                SubsCount--
                SubButton.text('Подписаться');
                SubButton.attr('sub', '0');
                SubButton.addClass('SubButton');
            }

            $('#PROFILE_SUBSCRIBERS').text(SubsCount)
        });

        // Просмотр значков
        $('.Profile-InfoBlock').on('click', '.Icon', function () {
            IconID = $(this).attr("iid");
            Icons = $('.Profile-InfoBlock .Icon');
            Icon = $(this);

            if (Icon.attr("clicked") === "0") {
                Icons.removeAttr("style");
                Icons.attr("clicked", "0");
                Icon.attr("clicked", "1");
                Icon.css({ animation: "0.2s forwards PROFILE_ICON_INFO" });
                $('.IconInfoContainer .Info').css({ animation: "0.3s forwards SH_DOTS_M" });
            } else {
                Icons.removeAttr("style");
                Icons.attr("clicked", "0");
                Icon.attr("clicked", "0");
                Icon.css({ animation: "0.2s forwards HD_PROFILE_ICON_INFO" });
                $('.IconInfoContainer .Info').css({ animation: "0.3s forwards HD_DOTS_M" });
            }

            $.ajax({
                url: '/System/Scripts/ProfileIcons.php?F=IINFO',
                type: 'POST',
                data: { IconID },
                dataType: 'json',
                success: function (Data) {
                    $('.IconInfoContainer .Info').html(Data.Info)
                },
            })
        });

        $('.Profile-InfoBlock').on('click', '#PROFILE_GOVERN', function () {
            var UserID = $('.Profile-InfoBlock').attr("userid");
            var Buttons = $('#PROFILE_GOVERN_BUTTONS');
          
            if ($(this).attr("clicked") === "0") {
              Buttons.css({ animation: "0.3s forwards SH_DOTS_M" });
              $(this).attr("clicked", "1");
            } else {
              Buttons.css({ animation: "0.3s forwards HD_DOTS_M" });
              $(this).attr("clicked", "0");
            }
          
            $('#PROFILE_BLOCK').click(function () {
              $.ajax({
                url: "/System/Scripts/Interaction.php?F=BLOCK_USER",
                type: "POST",
                data: { UserID },
                dataType: "json",
                success: function (Data) {
                  InfoWindow(Data);
                },
              });
            });
            $('#PROFILE_DL_AVATAR').click(function () {
              $.ajax({
                url: "/System/Scripts/Interaction.php?F=DELETE_AVATAR",
                type: "POST",
                data: { UserID },
                dataType: "json",
                success: function (Data) {
                  InfoWindow(Data);
                },
              });
            });
            $('#PROFILE_DL_COVER').click(function () {
                $.ajax({
                  url: "/System/Scripts/Interaction.php?F=DELETE_COVER",
                  type: "POST",
                  data: { UserID },
                  dataType: "json",
                  success: function (Data) {
                    InfoWindow(Data);
                  },
                });
            });
        });

        // Переключение разделов
        $('#PROFILE-PARTITION_INFO').click( function () {
            $('#Posts').hide();
            $('.UI-LM_BTN').hide();
            $('#ProfileInfo').show();
        })
        $('#PROFILE-PARTITION_POSTS').click( function () {
            $('#Posts').show();
            $('.UI-LM_BTN').show();
            $('#ProfileInfo').hide();
        })

        /* Вывод постов */

        // Загрузка постов
        function LoadPosts(Profile) {
            var UserID = $('.Profile-InfoBlock').attr("userid");
            var StartIndex = 25;

            $.ajax({
                url: '/System/API/LoadPosts.php?F=USER',
                type: 'POST',
                dataType: 'json',
                data: { UserID },
                success: function(Data) {
                    $('.UI-LM_BTN').remove();
                    $('#Posts').html(HandleProfilePosts(Profile, Data));
                    if (Profile.Posts > 25) {
                        $('#Posts').append('<button class="UI-LM_BTN">Показать больше</button>');
                    }
                }
            });

            $('.Content').on('click', '.UI-LM_BTN', function() {

                $('.UI-LM_BTN').remove();

                $.ajax({
                    url: "/System/API/LoadPosts.php?F=USER",
                    type: "POST",
                    dataType: 'json',
                    data: { UserID, StartIndex },
                    success: function(Posts) {
                        $('#Posts').append(HandleProfilePosts(Profile, Posts));
                        StartIndex += 25;
                        if (Posts.length === 25) {
                            $('#Posts').append('<button class="UI-LM_BTN">Показать больше</button>');
                        }
                    }
                })
            });
        }

        // Обработка постов
        function HandleProfilePosts(Profile, Data) {
            var HTML = '';
            for (var i = 0; i < Data.length; i++) {
              var Post = Data[i];
              var ImageHTML = '';
              var HTML_GVE_P = '';
              var HTML_USR_I = '';
          
              if (Profile.Icons) {
                HTML_USR_I = '<div class="UserIcons">' + Profile.Icons + '</div>'
              }
          
              if (Profile.MyStatus === 'Admin') {
                HTML_GVE_P = '<button class="GovernButton" clicked="0"><i class="icon-DotsV"></i></button>' +
                '<div class="GovernButtons">' +
                '<div class="Container">' +
                '<button class="Button P-G_BTN_DL">Удалить пост</button>' +
                '<div class="UI-PUSTOTA_H"></div>' +
                '<button class="Button P-G_BTN_BL">Заблокировать</button>' +
                '</div></div>';
              }
          
              if (Post.Content) {
                var Content = JSON.parse(Post.Content);
                var ImageOrigName = Content.Image.Orig_Name;
                var ImageName = Content.Image.File_Name;
                var ImageSize = Content.Image.File_Size;
          
                if (ImageOrigName === false) {
                  ImageOrigName = ImageName;
                }
          
                ImageHTML = '<div class="Image" img-name="' + ImageOrigName + '" img-size="' + ImageSize + '">';
                
                if (Content.Image.Censoring === true) {
                  ImageHTML += '<div class="Censoring">' + 
                  '<div class="Info">' + 
                  '<div class="Text">Это изображение помечено как «Деликатный контент», чтобы просмотреть его, нажмите на кнопку</div><button class="ShowButton">Показать</button></div>' +
                  '</div>';
                }
          
                ImageHTML += '<img class="IMG" src="' + FilesServer + '/Content/Posts/Images/' + ImageName + '">' +
                '<div class="Blur"></div>' +
                '<img class="BlurIMG" src="' + FilesServer + '/Content/Posts/Images/' + ImageName + '">' +
                '</div>';
              }
          
              HTML +=
                '<div class="Post UI-Block" data-postid="' + Post.PostID + '">' +
                '<div class="TopBar">' +
                '<div class="Info">' +
                '<div class="Avatar">' + GetAvatar(Profile.Avatar, Profile.Name) + '</div>' +
                '<div class="N_A_D">' +
                '<div class="Name">' + Profile.Name + HTML_USR_I + '</div>' +
                '<div class="Date">' + Post.Date + '</div>' +
                '</div>' +
                '</div>' + HTML_GVE_P +
                '</div>' +
                '<div class="Text">' + Post.Text + '</div>' + ImageHTML +
                '<div class="InteractionButtons">' +
                '<button class="InteractionButton Like ' + Post.Liked + '"><i class="icon-Like"></i><div class="Likes">' + Post.Likes + '</div></button>' +
                '<button class="InteractionButton Dislike ' + Post.Disliked + '"><i class="icon-Dislike"></i><div class="Dislikes">' + Post.Dislikes + '</div></button>' +
                '<button class="InteractionButton Comment"><i class="icon-Comment"></i><div>' + Post.Comments + '</div></button>' +
                '<button class="InteractionButton Share"><i class="icon-Share"></i>Поделиться</button>' +
                '</div>' +
                '<div class="ShareImposition"></div>' +
                '</div>';
              }
          
              return HTML;
        }
    }
});