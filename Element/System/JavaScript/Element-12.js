
/* Глобально */

var FilesServer = $("meta[name='FilesServer']").attr('content');
var MyStatus = $("meta[name='MyStatus']").attr('content');
var metaGoldSub = $("meta[name='GoldSub']").attr('content') || null;
var GoldSub = JSON.parse(metaGoldSub) || null;
var Device = 'Desktop';

// console.log('FS = ' + FilesServer + ' MS = ' + MyStatus + ' GS = ' + GoldSub);

/* Главная */

/* Главная - Навигация */

$(document).ready( function () {

  if ($(window).width() <= 768) {
    Device = 'Mobile';
    $('head').append('<link rel="stylesheet" type="text/css" href="/System/UI/MobileStyle.css">');
  }

  $('body').on('click', '.UI-Logo', function () {
    window.location.href = "/";
  });

  setTimeout( function () {
    $('.UI-PB_PRELOAD').css({ animation: "PAGE_LOADED 1s forwards" });
  }, 100);
  
  var Page = window.location.pathname;
  var Pages = [

    '/home', 
    '/settings', 
    '/goldsub',
    '/epack',

    '/info', 
    '/info/advantages', 
    '/info/rules', 
    '/info/update'

  ];
  var PagesID = [

    '.PAGE-HOME', 
    '.PAGE-SETTINGS', 
    '.PAGE-GOLD',
    '.PAGE-EPACK',
  
    '.PAGE-INFP_PR',
    '.PAGE-INFP_PR', 
    '.PAGE-INFP_RL', 
    '.PAGE-INFP_UP'
  
  ];
  Themes = [ 
    { ID: 'Light', StyleFile: '' },
    { ID: 'Gold', StyleFile: 'GoldStyle-12.css' },
    { ID: 'Dark', StyleFile: 'DarkStyle-12.css' }
  ];

  for (let i = 0; i < Pages.length; i++) {
    $(PagesID[i]).click(function() {
      $('.UI-LN_Button').removeClass('UI-LNB_ACT');
      $(this).addClass('UI-LNB_ACT');
      setTimeout( function () {
        window.location.href = Pages[i];
      }, 400);
    });
    if (Page === Pages[i]) {
      $(PagesID[i]).addClass("UI-LNB_ACT");
    }
  }

  $('.UI-Tabs').on('click', '.Tab', function () {
    $('.UI-Tabs, .Tab').removeClass('ActiveTab');
    $(this).addClass('ActiveTab');
  });
  $('body').on('click', '#CLS_W', function () {
    HideWindow();
  });
});

/* Глобально */

$(document).ready(function () {

  // Меню профиля
  $(".UI-N_DIV .Avatar").click(function () {
    MyProfileButton = $(this);
    NavAd = $(".UI-AD_N1-B");

    if (MyProfileButton.attr("clicked") === "1") {
      MyProfileButton.attr("clicked", "0");
      $(".NavPanel-Container").css({ animation: "MP_NAV-HIDE 0.4s forwards" });
      NavAd.css({ animation: "HIDE_NAV_AD 0.4s forwards" });
      Blur_Hide();
    } else {
      MyProfileButton.attr("clicked", "1");
      $(".NavPanel-Container").css({ animation: "MP_NAV-SHOW 0.4s forwards" });
      NavAd.css({ animation: "SHOW_NAV_AD 0.4s forwards" });
      Blur_Show();
    }

    $(".UI-Blur").click(function () {
      MyProfileButton.attr("clicked", "0");
      $(".NavPanel-Container").css({ animation: "MP_NAV-HIDE 0.4s forwards" });
      NavAd.css({ animation: "HIDE_NAV_AD 0.4s forwards" });
      Blur_Hide();
    });
  });

  // Поиск
  $("#Search").on("input", function () {
    var SearchVal = $(this).val();
    if (SearchVal) {
      $(".Search-Result").css({ animation: "MP_NAV-SHOW 0.4s forwards" });
      Search(SearchVal);
    } else {
      $(".Search-Result").css({ animation: "MP_NAV-HIDE 0.4s forwards" });
    }
  });

  // Переключатель
  $('.UI-Switch').click( function () {
    if ($(this).attr('clicked') === '1') {
      $(this).removeClass('UI-Switch-On');
      $(this).attr('clicked', '0');
    } else {
      $(this).addClass('UI-Switch-On');
      $(this).attr('clicked', '1');
    }
  });
});

function Search(SearchVal) {
  $.ajax({
    url: "/System/Scripts/Interaction.php?F=SEARCH",
    type: "POST",
    data: { SearchVal },
    dataType: "json",
    success: function (Data) {
      HTML = '';

      for (var i = 0; i < Data.length; i++) {
        var User = Data[i];

        HTML +=
        '<a href="/profile/' + User.Username + '"><div class="Search-R_USR">'
         + User.Avatar +
        '<div>' +
        '<div class="Name">' + User.Name + '</div>' +
        '<div class="Posts">' + User.Posts + ' постов </div>' +
        '</div>' +
        '</div></a>';
      }

      if (HTML === '') {
        HTML += '<div class="Search-Error">Ой, такого пользователя нет.</div>';
      }

      $('.Search-R_USRS').html(HTML);
    },
  });
}

/* Отправка поста */

$(document).ready(function () {

  $('#AP-TEXT_INPUT').on('input', function() {
    const Input = $(this);
    Input.css('height', '80px');
    Input.css('height', Input.prop('scrollHeight') + 'px');
  });

  $("#AP-FILE_INPUT").change(function () {
    var Input = $(this)[0];
    var File = Input.files[0];
  
    if (File) {
      $('#AP-FILE_NAME').empty();
      $('#AP-FILE_NAME').append('<div class="FileName">' + File.name + '</div>');
      var FS_Margin = $('#AP-FILE_NAME').width() + 38;
      var CF_Margin = $('#AP-FILE_NAME').width() + 35;
      $('#AP-FILE_SETTINGS').css('margin-left', FS_Margin);
      $('#AP-CLOSE_FILE').css('margin-left', CF_Margin);
      $('#AP-FILE_SETTINGS').css('display', 'block');
      $('#AP-CLOSE_FILE').css('display', 'block');
      $('#AP-FILE_NAME').css('padding', '0px 25px 0px 30px')
    }
  })

  $('#AP-CLOSE_FILE').click( function () {
    $('#AP-FILE_INPUT').val('');
    $('#AP-FILE_NAME').empty();
    $('#AP-FILE_SETTINGS').css('display', 'none');
    $('#AP-CLOSE_FILE').css('display', 'none');
    $('#AP-FILE_NAME').css('padding', '0px')
  })

  $('#AP-FILE_SETTINGS').click( function () {
    if ($(this).attr('clicked') === '1') {
      $('#AP-FS_SWITCHES').css({ animation: 'HD_DOTS_M forwards 0.2s' });
      $(this).css({ animation: 'AP-FILE_SETTINGS-NOTACTIVE forwards 0.2s' });
      $(this).attr('clicked', '0');
    } else {
      $('#AP-FS_SWITCHES').css({ animation: 'SH_DOTS_M forwards 0.2s' });
      $(this).css({ animation: 'AP-FILE_SETTINGS-ACTIVE forwards 0.2s' });
      $(this).attr('clicked', '1');
    }
  })

  $("#AP-FORM").on("submit", function (Data) {
    Data.preventDefault();

    $.ajax({
      url: "/System/Scripts/Post.php?F=S_P",
      type: "POST",
      data: new FormData(this),
      processData: false,
      contentType: false,
      success: function (Data) {
        if (Data) {
          if (Data.Type === 'Verify') {
            $('#AP-TEXT_INPUT').css('height', '80px')
            $('#AP-FILE_INPUT').val('');
            $('#AP-FILE_NAME').empty();
            $('#AP-FILE_SETTINGS').css('display', 'none');
            $('#AP-CLOSE_FILE').css('display', 'none');
            $('#AP-FILE_NAME').css('padding', '0px')
            $('#AP-FS_SWITCHES').css({ animation: 'HD_DOTS_M forwards 0.2s' });
            LoadPosts('LATEST');
          } else {
            InfoWindow(Data);
          }
        }
      },
    });
  });
});

// Подписка Gold

$(document).ready( function () {

  $('#SUB-PAY').click(function () {
    var Data =  { Type: "Info", Content: "Напишите на телеграмм аккаунт @ElementGoldSub для покупки подписки." };
    InfoWindow(Data);
  });
  
  $("#SUB-ACT_KEY").click(function () {
    var Type = 'Input';
    var Title = 'Введите ключ';
    var Content = 'Ключ можно получить разными способами. Начиная от покупки, заканчивая просто подарком от кого-то.';
    QueryWindow(Type, Title, Content);
  
    $('.UI-Window').on('click', '#WIN-NEXT', function () {
      const Text = $('#WIN-INPUT').val();
      $.ajax({
        url: '/System/Scripts/Interaction.php?F=SUB_ACT',
        type: 'POST',
        data: { Text },
        dataType: 'json',
        success: function (Data) {
          InfoWindow(Data);
        },
      })
    });
  });
  
  $('.GoldSub-A_Block').click( function () {

    var ThemeLink = $('head').find('#THEME');
    var SelectTheme = '';

    if (ThemeLink.length > 0) {
      var StyleFilePath = ThemeLink.attr('href');
      var StyleFile = StyleFilePath.split('/').pop();
      var Theme = Themes.find(function(Theme) {
        return Theme.StyleFile === StyleFile;
      });
      if (Theme.ID === 'Dark') {
        var SelectTheme = '_Dark';
      }
    }

    var Title = $(this).find('.GoldSub-A_B_TITLE').html();
    var VideoFile = $(this).find('.GoldSub-A_B_TITLE').attr('target-video');
    Description = $(this).clone();
    Description.find('.GoldSub-A_B_TITLE').remove();
    var Description = Description.html();
    
    var InfoSubWindow = $('.GoldSub-VideoPrew');
    var InfoSubWindowVideo = $('.GoldSub-VideoPrew video');
    InfoSubWindowVideo.attr('src', '/System/Videos/' + VideoFile + SelectTheme + '.mp4');
    InfoSubWindow.find('.InfoTitle').html(Title);
    InfoSubWindow.find('.InfoDec').html(Description);
    InfoSubWindow.css({ animation: 'INFO_SUB-SHOW 0.4s forwards' });
    setTimeout( function() {
      InfoSubWindowVideo.css({ animation: 'INFO_SUB_VIDEO-SHOW 0.4s forwards' });
    }, 300);
    $('#CLOSE_VIDEO_PREW').click( function () {
      InfoSubWindow.css({ animation: 'INFO_SUB-HIDE 0.4s forwards' });
      InfoSubWindowVideo.css({ animation: 'INFO_SUB_VIDEO-HIDE 0.4s forwards' });
    });
  });
});

/* Настройки */

$(document).ready(function () {

  function Settings (Function, Data) {
    return new Promise(function (resolve) {
      $.ajax({
        url: '/System/Scripts/Settings.php?F=' + Function,
        type: 'POST',
        data: Data,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function (Data) {
          resolve(Data);
        },
      })
    })
  }

  /* Настройки - Редактор профиля */

  /* Удалить аватар */

  $('#S-CP_DL_AVATAR').click( function () {
    var Type = 'Query';
    var Title = 'Точно удалить?';
    var Content = 'Удаление нельзя будет отменить.';
    QueryWindow(Type, Title, Content);
  
    $('.UI-Window').on('click', '#WIN-NEXT', function () {
      Settings('CP_DL_AVATAR', null).then(function (Data) {
        $('.Avatar').html(Data.Content);
        HideWindow();
      })
    });
  });

  /* Загрузить аватар */
  
  $('#S-CP_UPLOAD_AVATAR').change(function () {
    var Form = $("#S-CP_UPLOAD_AVATAR_FORM")[0];
    var Data = new FormData(Form);
    Settings('CP_UPLOAD_AVATAR', Data).then(function (Data) {
      if (Data.Type === 'Verify') {
        $('.Avatar').html(Data.Content);
      } else {
        InfoWindow(Data);
      }
    })
  });

  /* Загрузить обложку */
  
  $('#S-CP_UPLOAD_COVER').change(function () {
    var Form = $("#S-CP_COVER_FORM")[0];
    var Data = new FormData(Form);
    Settings('CP_UPLOAD_COVER', Data).then(function (Data) {
      if (Data.Type === 'Verify') {
        var Image = '<img src="' + FilesServer + '/Content/Covers/' + Data.Content + '">';
        $('.Cover').html(Image);
        $('.Profile-Cover').html(Image);
      } else {
        InfoWindow(Data);
      }
    })
  });

  /* Удалить обложку */

  $('#S-CP_DL_COVER').click( function () {
    var Type = 'Query';
    var Title = 'Точно удалить?';
    var Content = 'Удаление нельзя будет отменить.';
    QueryWindow(Type, Title, Content);
  
    $('.UI-Window').on('click', '#WIN-NEXT', function () {
      Settings('CP_DL_COVER', null).then(function (Data) {
        Data = Data.Content;
        $('.Profile-Cover').html(Data);
        $('.Cover').html(Data);
        HideWindow();
      })
    });
  });


  /* Смена информации профиля */

  function SettingsInput (Input) {
    var InputVal = Input.val();
    var Container = Input.closest('.Settings-CP_Input_container');

    HTML = '<div class="Settings-Q_container">' +
    '<div class="Question">' +
      '<button class="Apply">Применить</button>' +
      '<button class="Back">Отменить</button>' +
    '</div>' +
    '</div>';

    if (InputVal) {
      QuestionContainer = Container.find('.Settings-Q_container')
      if (QuestionContainer.length < 1) {
        Container.append(HTML);
      }
      Question = Container.find('.Question');
      Input.css('z-index', '3');
      Question.css({ animation: "0.8s forwards SETTINGS-SHOW_INPUT" });
    } else {
      Question.css({ animation: "0.8s forwards SETTINGS-HIDE_INPUT" });
      setTimeout ( function () {
        Container.find('.Settings-Q_container').remove();
        Input.css('z-index', '1');
      }, 500);
    }
  }

  function CloseSettingsInput() {
    Button = $(this);
    Container = Button.closest('.Settings-CP_Input_container');
    Question = Container.find('.Question')

    Question.css({ animation: "0.8s forwards SETTINGS-HIDE_INPUT" });
    setTimeout ( function () {
      Container.find('.Settings-Q_container').remove();
      Container.find('.UI-Input').css('z-index', '1');
    }, 500);
  }

  $('#S-CP_Name').on('input', function() {
    SettingsInput($(this));
    $('.Question .Apply').click(CloseSettingsInput);
    $('.Question .Back').click(CloseSettingsInput);
  });

  $('#S-CP_NameContainer').on('click', '.Apply', function() {
    Value = $('#S-CP_Name').val();
    Data = new FormData;
    Data.append('Name', Value);
    Settings('CHANGE_NAME', Data).then(function (Data) {
      if (Data.Type === 'Verify') {
        $('#S-PROFILE_PREW_NAME').text(Value);
      } else {
        InfoWindow(Data);
      }
    });
  });

  $('#S-CP_Dec').on('input', function() {
    SettingsInput($(this));
    $('.Question .Apply').click(CloseSettingsInput);
    $('.Question .Back').click(CloseSettingsInput);
  });

  $('#S-CP_DecContainer').on('click', '.Apply', function() {
    Value = $('#S-CP_Dec').val();
    Data = new FormData;
    Data.append('Description', Value);
    Settings('CHANGE_DEC', Data).then(function (Data) {
      if (Data.Type === 'Verify') {
        $('#S-PROFILE_PREW_DEC').text(Value);
      } else {
        InfoWindow(Data);
      }
    });
  });
  
  /* Настройки - Смена темы */

  SelectTheme();

  function SelectTheme() {
    var Link = $('head').find('#THEME');

    if (Link.length > 0) {
      var StyleFilePath = Link.attr('href');
      var StyleFile = StyleFilePath.split('/').pop();
      var Theme = Themes.find(function(Theme) {
        return Theme.StyleFile === StyleFile;
      });
      $('.Info').removeClass('Selected');
      $('.Theme-' + Theme.ID).find('.Info').addClass('Selected');
    }
  }
  
  $('.ChangeTheme').click( function () {
    ThemeButton = $(this);
    ThemeID = $(this).attr('themeid');

    var Theme = Themes.find(function(Theme) {
      return Theme.ID === ThemeID;
    });
    
    if (Theme) {
      Data = new FormData;
      Data.append('Theme', ThemeID);
      Settings('CHANGE_THEME', Data);

      if (Theme.ID === 'Light') {
        $('head').find('#THEME').remove();
      } else {
        $('head').find('#THEME').remove();
        $('head').append('<link id="THEME" rel="stylesheet" type="text/css" href="/System/UI/' + Theme.StyleFile + '">');
      }

      $('.Info').removeClass('Selected');
      ThemeButton.find('.Info').addClass('Selected');
    }
  });

  $('.Settings-PRFL_Email').mouseenter(function() {
    $(this).addClass('hover');
  });
  $('.Settings-PRFL_Email').mouseleave(function() {
    $(this).removeClass('hover');
  });

});

// Слой размытия

function Blur_Show () {
  if ($('.UI-Blur').length < 1) {
    $('.Content').append('<div class="UI-Blur"></div>');
    $('.UI-TopBar').css({ animation: 'NAV_PANEL-DISABLE_BLUR 0.1s forwards' });
    $('.UI-Blur').css({ animation: 'BLUR-SHOW 0.2s forwards' });
  }
}
function Blur_Hide () {
  $('.UI-Blur').on('animationend webkitAnimationEnd oanimationend MSAnimationEnd', function() {
    $(this).remove();
  });
  $('.UI-TopBar').css({ animation: 'NAV_PANEL-ENABLE_BLUR 0.1s forwards' });
  $('.UI-Blur').css({ animation: 'BLUR-HIDE 0.2s forwards' });
}

// Панель навигации

function NavPanel_Show () {
  $('.UI-TopBar').css({ animation: 'NAV_PANEL-SHOW 0.3s forwards' });
}
function NavPanel_Hide () {
  $('.UI-TopBar').css({ animation: 'NAV_PANEL-HIDE 0.3s forwards' });
}

function BottomNav_Show () {
  $('.UI-L_NAV').css({ animation: 'BOTTOM_NAV-SHOW 0.3s forwards' });
}
function BottomNav_Hide () {
  $('.UI-L_NAV').css({ animation: 'BOTTOM_NAV-HIDE 0.3s forwards' });
}

// Вывод информационного окна

function QueryWindow(Type, Title, Content) {

  HTML = '';

  HTML += '<div class="UI-Window_BG"></div>' +
  '<div class="UI-Window">' +
   '<div class="UI-Window_content">' +
   '<div class="UI-Window_title">' + Title + '</div>' +
   '<div class="UI-Window_text">' + Content + '</div>';

    if (Type === 'Input') {
      HTML += '<input id="WIN-INPUT" class="UI-Window_input UI-Input" type="text" placeholder="Введите текст">';
    }

   HTML += '</div><div class="UI-Window_BTNS">' +

   '<button id="WIN-NEXT" class="UI-Window_button">Далее</button>' +
   '<div class="UI-Window_BW"></div>' +
   '<button id="CLS_W" class="UI-Window_BTN_NOACT UI-Window_button">Отменить</button>' +

   '</div>' +
  '</div>';

  $("body").append(HTML);

  $(".UI-Window_BG").css({ animation: "0.6s forwards WINDOW-SHOW_BG" });
  $(".UI-Window").css({ animation: "0.4s forwards WINDOW-SHOW" });
}

function InfoWindow(Data) {
  if (Data.Type === 'Error') {
    Title = 'Ошибка';
  }
  if (Data.Type === 'Info') {
    Title = 'Информация';
  }
  if (Data.Type === 'Verify') {
    Title = 'Успешно';
  }
  if (Data.Content !== false) {
    Content = Data.Content;
  }
  if (Content) {
    HTML = '<div class="UI-Window_BG"></div>' +
            '<div class="UI-Window">' +
             '<div class="UI-Window_content">' +
              '<div class="UI-Window_title">' + Title + '</div>' +
              '<div class="UI-Window_text">' + Content + '</div>' +
             '</div>' +
             '<div class="UI-Window_BTNS">' +
             '<button class="UI-Window_button" id="CLS_W">Хорошо</button>' +
             '</div>' +
            '</div>';
    
    $("body").append(HTML);

    $(".UI-Window_BG").css({ animation: "0.6s forwards WINDOW-SHOW_BG" });
    $(".UI-Window").css({ animation: "0.4s forwards WINDOW-SHOW" });
  }
}

function HideWindow() {
  $(".UI-Window_BG").css({ animation: "0.6s forwards WINDOW-HIDE_BG" });
  $(".UI-Window").css({ animation: "0.4s forwards WINDOW-HIDE" });
  setTimeout(function () {
    $(".UI-Window_BG").remove();
    $(".UI-Window").remove();
  }, 520);
}

/* Обработка постов */

function HandlePosts(Data) {
  var HTML = '';
  for (var i = 0; i < Data.length; i++) {
    var Post = Data[i];
    var ImageHTML = '';
    var HTML_USR_I = '';
    var GOVERN_BUTTON_HTML = '';

    if (Post.UserIcons) {
      HTML_USR_I = '<div class="UserIcons">' + Post.UserIcons + '</div>'
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
        ImageHTML +=
        '<div class="Censoring">' + 
        '<div class="Info">' + 
        '<div class="Text">Это изображение помечено как «Деликатный контент», чтобы просмотреть его, нажмите на кнопку</div><button class="ShowButton">Показать</button></div>' +
        '</div>';
      }

      ImageHTML += '<img class="IMG" src="' + FilesServer + '/Content/Posts/Images/' + ImageName + '">';

      if (Content.Image.Censoring === true) {
        ImageHTML += '<div class="Blur" style="backdrop-filter: blur(0px);"></div>';
      } else {
        ImageHTML += '<div class="Blur"></div>';
      }

      ImageHTML +=
      '<img class="BlurIMG" src="' + FilesServer + '/Content/Posts/Images/' + ImageName + '">' +
      '</div>';
    }

    if (Post.MyPost === true || MyStatus === 'Admin') {
      GOVERN_BUTTON_HTML +=
      '<button class="GovernButton" clicked="0"><i class="icon-DotsV"></i></button>' +
      '<div class="GovernButtons"></div>';
    }

    HTML +=
      '<div class="Post UI-Block" data-postid="' + Post.PostID + '">' +
      '<div class="TopBar">' +
      '<div class="Info">' +
      '<a href="/profile/' + Post.Username + '">' + Post.Avatar + '</a>' +
      '<div class="N_A_D">' +
      '<a href="/profile/' + Post.Username + '"><div class="Name">' + Post.Name + HTML_USR_I + '</div></a>' +
      '<div class="Date">' + Post.Date + '</div>' +
      '</div>' +
      '</div>' + GOVERN_BUTTON_HTML +
      '</div>' + 
      '<div class="Text">' + Post.Text + '</div>' +
      ImageHTML +
      '<div class="InteractionButtons">' +
      '<button class="InteractionButton Like ' + Post.Liked + '"><i class="icon-Like"></i><div class="Likes">' + Post.Likes + '</div></button>' +
      '<button class="InteractionButton Dislike ' + Post.Disliked + '"><i class="icon-Dislike"></i><div class="Dislikes">' + Post.Dislikes + '</div></button>' +
      '<button class="InteractionButton Comment"><i class="icon-Comment"></i><div class="Comments">' + Post.Comments + '</div></button>' +
      '<button class="InteractionButton Share"><i class="icon-Share"></i>Поделиться</button>' +
      '</div>' +
      '<div class="ShareImposition"></div>' +
      '</div>';
    }

    return HTML;
}

function FileSize(Bytes) {
  if (Bytes < 1024) {
    return Bytes + ' B';
  } else if (Bytes < 1048576) {
    return (Bytes / 1024).toFixed(2) + ' KB';
  } else if (Bytes < 1073741824) {
    return (Bytes / 1048576).toFixed(2) + ' MB';
  } else {
    return (Bytes / 1073741824).toFixed(2) + ' GB';
  }
}

/* Пользователи с подпиской */

function GetGoldUsers(Element) {
  $.ajax({
    url: '/System/Scripts/Interaction.php?F=GOLD_LIST',
    type: 'GET',
    dataType: 'json',
    success: function (Data) {
      HTML = '';

      for (var i = 0; i < Data.length; i++) {
        var User = Data[i];

        HTML +=
        '<a href="/profile/' + User.Username + '"><div class="GoldSub-User">'
         + User.Avatar +
        '<div>' +
        '<div class="Name">' + User.Name + '</div>' +
        '<div class="Posts">' + User.Posts + ' постов </div>' +
        '</div>' +
        '<div class="GoldStar"><svg viewBox="0 0 12.7 12.7" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg"><defs><linearGradient id="b"><stop offset="0" stop-color="#fab31e"/><stop offset="1" stop-color="#ffd479"/></linearGradient><linearGradient id="a"><stop offset="0" stop-color="#fab31e"/><stop offset=".744" stop-color="#ffd479"/></linearGradient><linearGradient xlink:href="#a" id="c" x1=".292" y1="6.362" x2="12.498" y2="6.362" gradientUnits="userSpaceOnUse" gradientTransform="matrix(.93282 0 0 .93282 .344 .4)"/><linearGradient xlink:href="#b" id="d" x1=".292" y1="6.362" x2="12.498" y2="6.362" gradientUnits="userSpaceOnUse" gradientTransform="matrix(.93282 0 0 .93282 .344 .4)"/></defs><path d="M7.296.694C7.106.646 5.043 4.02 4.898 4.078 4.752 4.137.927 3.16.824 3.327.72 3.494 3.29 6.497 3.3 6.65c.01.155-2.105 3.496-1.98 3.645.125.15 3.781-1.37 3.93-1.333.15.037 2.677 3.086 2.857 3.012.18-.073-.135-4.017-.055-4.148.08-.132 3.76-1.593 3.746-1.788-.014-.195-3.86-1.114-3.96-1.233-.1-.119-.353-4.065-.542-4.112z" fill="url(#c)" stroke="url(#d)" stroke-width="1.148" stroke-linejoin="round" paint-order="stroke fill markers"/></svg>' +
        '</div></div></a>';
      }

      $(Element).html(HTML);
    },
  });
}

function GetAvatar (Avatar, Name) {
  if (Avatar === 'None') {
    return '<div class="NonAvatar">' + Name[0] + '</div>';
  } else {
    return '<img src="' + FilesServer + '/Content/Avatars/' + Avatar + '">'
  }
}

function GetUpdates (Type) {
  $.ajax({
    url: '/System/Scripts/GetUpdates.php?Type=' + Type,
    type: 'GET',
    dataType: 'json',
    success: function (Updates) {

      HTML = '';

      for (var i = 0; i < Updates.length; i++) {

        var Update = Updates[i];

        if (Update.Type === 'Beta') {
          Type = 'Бета';
        } else {
          Type = 'Обновление';
        }

        HTML += '<div class="Info-UPT_B">' +
        '<div class="Info-UPT_B_T">' + Type + ' ' + Update.Version  + '</div>' +
        '<div class="Info-UPT_B_C">' +

        Update.Content +

        '</div>' +
        '</div>';

      }

      $('#UPDATES_HISTORY').html(HTML);

    }
  })
}

function Preload (Name, Element) {
  $.ajax({
    url: '/System/Preloads/' + Name + '.html',
    dataType: 'html',
    success: function (HTML) {
      $(Element).html(HTML);
    }
  })
}