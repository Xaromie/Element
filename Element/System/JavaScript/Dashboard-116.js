
/* Навигация */

$(document).ready( function () {
    
    var Page = decodeURIComponent(window.location.pathname);
    var Pages = ['/panel', '/panel/stat', '/panel/goldsub', '/home'];
    var PagesID = ['#STAT', '#STAT', '#SUB', '#EXIT'];
  
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
});

$('#AP-WEEDOUT_AVATARS').click( function () {
  $.ajax ({
      url: '/System/Scripts/WeedOutAvatars.php',
      type: 'GET',
      dataType: 'json',
      success: function (Data) {
        console.log(Data);
        InfoWindow(Data);
      }
  })
});
$('#AP-GN_KEY_BTN').click( function () {
    $.ajax ({
        url: '/System/Scripts/Interaction.php?F=AP_GN_KEY',
        type: 'GET',
        success: function (Data) {
            $('#AP-GS_KEYS').html(Data);
        }
    })
});
$('#AP-RC_SUB_USRS').click( function () {
    $.ajax ({
        url: '/System/Scripts/Interaction.php?F=AP_RC_SUB_USRS',
        type: 'GET',
        success: function (Data) {
            $('#AP-GS_USERS').html(Data);
        }
    })
});