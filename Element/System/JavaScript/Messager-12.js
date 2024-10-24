$(document).ready( function () {

    // Получение профиля пользователя

    var Username = decodeURIComponent(window.location.pathname).match(/\/chat\/([^/]+)/);
    var clickCount = 0;

    if (Username) {
        var Username = Username[1];
        LoadUser(Username);
    }

    function LoadUser (Username) {
        $.ajax({
            url: '/System/Scripts/Messager.php?F=GET_USER',
            type: 'POST',
            data: { Username },
            dataType: 'json',
            success: function (User) {
                if (User !== 'Error') {
                    $('.Chat-Error').css({ animation: "CHAT-HIDE_ERROR 0.5s forwards" });
                    HandleUser(User);
                    LoadChat(User.ID);
                    setTimeout(function() {
                        CheckNewMessages(User.ID);
                    }, 2000);
                }
            }
        })
    }

    // Загрузка чатов

    LoadChats();

    function LoadChats () {
        $.ajax({
            url: '/System/Scripts/Messager.php?F=LOAD_CHATS',
            type: 'GET',
            dataType: 'json',
            success: function (Chats) {
                var Chats_HTML = '';

                for (var i = 0; i < Chats.length; i++) {
                    var Chat = Chats[i];
            
                    Chats_HTML += '<button class="Chats-User" username="' + Chat.Username + '">' +
                    '<div class="Avatar">' + GetAvatar(Chat.Avatar, Chat.Name) + '</div>' +
                    '<div class="Chats-NandLM">' +
                        '<div class="Chats-Name">' + Chat.Name + '</div>' +
                        '<div class="Chats-LastMessage">' + Chat.LastMessage + '</div>' +
                    '</div>' +
                    '</button>';
                }

                $('#CHATS_LIST').html(Chats_HTML);
            }
        })
    }

    // Выбор чата

    $('#CHATS_LIST').on('click', '.Chats-User', function () {
        var Username = $(this).attr('username');
        LoadUser(Username);

        if (Device === 'Mobile') {
            NavPanel_Hide();
            $('.Chat').css({ animation: 'CHAT-MOBILE_SHOW 0.3s forwards' })
        }
    });

    // Загрузка чата

    function LoadChat(UserID) {
        $.ajax({
            url: '/System/Scripts/Messager.php?F=LOAD_CHAT',
            type: 'POST',
            data: { UserID },
            dataType: 'json',
            success: function(Chat) {

                var Messages = Chat.Messages.reverse();
                var Messages_HTML = '';

                for (var i = 0; i < Messages.length; i++) {
                    var Message = Messages[i];

                    if (Message.From === UserID) {
                        Messages_HTML += '<div class="Chat-M_URS">' + Message.Text + '<div class="Time">' + Message.Time + '</div></div>';
                    } else {
                        Messages_HTML += '<div class="Chat-M_Me">' + Message.Text + '<div class="Time">' + Message.Time + '</div></div>';
                    }
                }

                $('#CHAT_MESSAGES').html(Messages_HTML);
                $('.Chat-Messanges_scroll').scrollTop(9999999999);

                if (Chat.Verify === 0) {
                    $('.Chat-TopWarning').css({ animation: "CHAT-SHOW_WARNING 1s forwards" });
                }
            },
        });

        // Отправка сообщения

        $('#SEND_MESSAGE').click( function () {
            SendMessage(UserID);
        });
        $('#MESSAGE_INPUT').keypress(function(Event) {
            if (Event.which === 13) { 
                SendMessage(UserID);
                Event.preventDefault();
            }
        });

        // Взаимодействие с чатом

        $('#APPLY_CHAT').click( function () {
            $('.Chat-TopWarning').css({ animation: "CHAT-HIDE_WARNING 1s forwards" });
            $.ajax({
                url: '/System/Scripts/Messager.php?F=VERIFY_CHAT_BUTTON',
                type: 'POST',
                data: { UserID },
            });
        })
        $('#DELETE_CHAT').click( function () {
            $.ajax({
                url: '/System/Scripts/Messager.php?F=DELETE_CHAT_BUTTON',
                type: 'POST',
                data: { UserID },
                dataType: 'json',
                success: function (Data) {
                    if (Data === 'Verify') {
                        $('.Chat-Error_message').html('Чат удалён.');
                        $('.Chat-TopWarning').css({ animation: "CHAT-HIDE_WARNING 1s forwards" });
                        $('.Chat-Error').css({ animation: "CHAT-SHOW_ERROR 0.5s forwards" });
                    }
                }
            });
        })
    }

    $(".icon-ChatBack").on("click", function () {
      if (Device === 'Mobile') {
        NavPanel_Show();
        $('.Chat').css({ animation: 'CHAT-MOBILE_HIDE 0.3s forwards' })
      } else {
        clickCount++;
  
        if (clickCount % 2 === 1) {
          $(".Chats").css({ animation: "0.8s forwards ChatsHide" });
          $(".Chat").css({ animation: "0.8s forwards ChatShow" });
        } else {
          $(".Chats").css({ animation: "1.5s forwards ChatsShow" });
          $(".Chat").css({ animation: "1.5s forwards ChatHide" });
        }
      }
    });

    // Другие функции ну не важно кАроче

    function SendMessage (UserID) {
        var Message = $('#MESSAGE_INPUT').val();

        if (Message !== '') {
            $.ajax({
                url: '/System/Scripts/Messager.php?F=SEND_MESSAGE',
                type: 'POST',
                data: { UserID, Message },
                dataType: 'json',
            });

            var RandomID = 'MSGID' + Math.floor(Math.random() * 100) + 1;
            var NewMessage = '<div class="Chat-M_Me ' + RandomID + '"><div class="MSG_Text"></div><div class="Time">сейчас</div></div>';
    
            $('#CHAT_MESSAGES').append(NewMessage);
            $('.' + RandomID + ' .MSG_Text').text(Message);
            $('.Chat-Messanges_scroll').scrollTop(999999999999);
            $('#MESSAGE_INPUT').val('');
        }
    }

    function CheckNewMessages (UserID) {
        $.ajax({
            url: '/System/Scripts/Messager.php?F=CHECK_NEW_MESSAGES',
            type: 'POST',
            data: { UserID },
            dataType: 'json',
            success: function(NewMessages) {
                var Messages = '';
    
                for (var i = 0; i < NewMessages.length; i++) {
                    var Message = NewMessages[i];
    
                    if (Message.From === UserID) {
                        Messages += '<div class="Chat-M_URS">' + Message.Text + '<div class="Time">' + Message.Time + '</div></div>';
                    } else {
                        Messages += '<div class="Chat-M_Me">' + Message.Text + '<div class="Time">' + Message.Time + '</div></div>';
                    }
                }

                $('#CHAT_MESSAGES').append(Messages);
                if (Messages !== '') {
                    $('.Chat-Messanges_scroll').scrollTop(999999999999);
                }
            },
            complete: function() {
                setTimeout(function() {
                    CheckNewMessages(UserID);
                }, 3000);
            }
        })
    }

    function HandleUser (User) {
        $('#CHAT_NAME').html(User.Name);
        if (User.Avatar === 'None') {
            $('#CHAT_AVATAR').html('<div class="NonAvatar">' + User.Name[0] + '</div>');
        } else {
            $('#CHAT_AVATAR').html('<img src="' + FilesServer + '/Content/Avatars/' + User.Avatar + '">');
        }
    }

});

