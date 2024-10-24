$(document).ready(function () {

  // Крутилка вертелка

  $("#GO_REG").click(function () {
    $('.Login').css({ animation: "AUTH-HIDE_LOGIN 0.3s forwards" });
    $('.Reg').css({ animation: "AUTH-SHOW_REG 0.3s forwards" });
  });

  $("#GO_LOGIN").click(function () {
    $('.Login').css({ animation: "AUTH-SHOW_LOGIN 0.3s forwards" });
    $('.Reg').css({ animation: "AUTH-HIDE_REG 0.3s forwards" });
  });

  // Получаем ласт юзеров

  GetLastUsers();

  function GetLastUsers() {
    $.ajax({
      url: '/System/Scripts/GetLastUsers.php',
      type: 'GET',
      dataType: "json",
      success: function (Data) {
        HTML = "";

        for (var i = 0; i < Data.length; i++) {
          var User = Data[i];

          HTML += '<a href="/profile/' + User.Username + '">' +
          '<div class="User">' +
            '<div class="Avatar">' + GetAvatar(User.Avatar, User.Name) + '</div>' +
            '<div class="Name">' + User.Name + '</div>' +
          '</div>' +
          '</a>';
        }

        $('#LAST_USERS').html(HTML);
      },
    });
  }

  // Авторизация - Вход в аккаунт

  $("#LOGIN").on("submit", function (e) {
    e.preventDefault();

    Email = $('#LF_EMAIL').val();
    Password = $('#LF_PASSWORD').val();

    var LoginForm = new FormData;
    LoginForm.append('Email', Email);
    LoginForm.append('Password', Password);

    console.log(LoginForm);

    $.ajax({
      url: '/System/Scripts/Authorization.php?F=LOGIN',
      type: 'POST',
      data: LoginForm,
      processData: false,
      contentType: false,
      success: function (Data) {
        if (Data) {
          if (Data.Type === "Verify") {
            window.location.href = "/home";
          } else {
            InfoWindow(Data);
          }
        }
      },
    });
  });

  /* Авторизация - Регистрация аккаунта */

  $("#REG_FORM").on("submit", function (e) {
    e.preventDefault();
    var Form = $(this);

    console.log(Form);

    $.ajax({
      url: "/System/Scripts/Authorization.php?F=REG",
      type: "POST",
      data: Form.serialize(),
      success: function (Data) {
        if (Data) {
          if (Data.Type === 'Verify') {
            if (Data.Content !== 'CodeIsFalse') {
              $('.Verify').css({ animation: "AUTH-SHOW_REG 0.3s forwards" });
              $('.Reg').css({ animation: "AUTH-HIDE_LOGIN 0.3s forwards" });
            } else {
              window.location.href = "/home";
            }
          } else {
            InfoWindow(Data);
          }
        }
      },
    });
  })

  $("#REG_MAIL_FORM").on("submit", function (e) {
    e.preventDefault();
    var Form = $(this);

    $.ajax({
      url: "/System/Scripts/Authorization.php?F=REG_V_M",
      type: "POST",
      data: Form.serialize(),
      success: function (Data) {
        if (Data) {
          if (Data.Type === "Verify") {
            window.location.href = "/home";
          } else {
            InfoWindow(Data);
          }
        }
      },
    });
  })
});
