$("#Posts, .Post").on("click", ".Like", Like);
$("#Posts, .Post").on("click", ".Dislike", Dislike);
$("#Posts, .Post").on("click", ".Comment", Comment);

$('#COMMENT-SEND').click(PostComment);

function PostQuery (URL, PostID) {
  return new Promise(function (resolve) {
    $.ajax({
      url: '/System/API/' + URL,
      type: 'POST',
      data: { PostID },
      dataType: 'json',
      success: function (Data) {
        resolve(Data);
      },
    })
  })
}

function Like() {
  var PostID = $(this).closest(".Post").data("postid");
  var LikeButton = $(this);
  var DislikeButton = $(this).closest(".Post").find(".Dislike");

  $.ajax({
    type: 'POST',
    url: '/System/Scripts/Interaction.php?F=P_LIKE',
    data: { PostID },
    dataType: 'json',
    success: function (Data) {
      if ($(LikeButton).hasClass("Liked")) {
        LikeButton.toggleClass("Liked");
        DislikeButton.removeClass("Liked");
      } else {
        LikeButton.addClass("Liked");
        DislikeButton.removeClass("Liked");
      }

      ReCount(Data, LikeButton, DislikeButton);
    },
  });
}

function Dislike() {
  var PostID = $(this).closest(".Post").data("postid");
  var LikeButton = $(this).closest(".Post").find(".Like");
  var DislikeButton = $(this);

  $.ajax({
    type: 'POST',
    url: '/System/Scripts/Interaction.php?F=P_DISLIKE',
    data: { PostID },
    dataType: 'json',
    success: function (Data) {
      if ($(DislikeButton).hasClass("Liked")) {
        DislikeButton.toggleClass("Liked");
        LikeButton.removeClass("Liked");
      } else {
        DislikeButton.addClass("Liked");
        LikeButton.removeClass("Liked");
      }

      ReCount(Data, LikeButton, DislikeButton);
    },
  });
}

function ReCount(Data, LikeButton, DislikeButton) {
  var PostData = Data;

  var LikesElement = LikeButton.find(".Likes");
  var DislikesElement = DislikeButton.find(".Dislikes");
  
  LikesElement.text(PostData.Likes);
  DislikesElement.text(PostData.Dislikes);
}

/* Комментарии */

function Comment() {
  var PostID = $(this).closest(".Post").data("postid");
  window.location.href = "/post/" + PostID;
}

/* Комментарии - Отправка комментария */

function PostComment(CF) {
  var PostID = $('.Post').data("postid");
  var Text = $('#COMMENT-INPUT').val();
  CF.preventDefault();

  $.ajax({
    type: "POST",
    url: "/System/Scripts/Interaction.php?F=POST_C",
    data: { PostID, Text },
    success: function (Data) {
      if (Data.Type === "Verify") {
        $('#COMMENT-INPUT').val('');
        LoadComments();
      } else {
        InfoWindow(Data);
      }
    },
  });
}

function LoadComments() {
  var PostID = $(".Post").data("postid");

  $.ajax({
    url: "/System/Scripts/Interaction.php?F=LOAD_C",
    type: "POST",
    data: { PostID },
    success: function (Data) {
      $(".Post-C").html(Data);
    },
  });
}

$('#Posts, .Post').on('click', '.Share', function () {
  var PostID = $(this).closest('.Post').data('postid');
  var Share = $(this).closest('.Post').find('.ShareImposition');
  var InteractionButtons = $(this).closest('.Post').find('.InteractionButtons');

  var ShareHTML =
  '<div class="Interaction">' +
    '<button class="InteractionButton Back"><i class="icon-Back"></i>Назад</button>' +
    '<div class="URL"><input class="URLInput" type="Text" value="https://elm.lol/post/' + PostID + '" readonly></div>' +
    '<button class="CopyURL"><i class="icon-Copy"></i></button>' +
  '</div>';

  InteractionButtons.css('transform', 'scale(0.7)');
  Share.html(ShareHTML);
  Share.css({ animation: "0.4s forwards POST-SHOW_SHARE" });

  BackButton = Share.find('.Back');
  CopyButton = Share.find('.CopyURL');
  ShareInput = Share.find('.URLInput');

  CopyButton.click( function () {
    ShareInput.select();
    document.execCommand('copy');
  });

  BackButton.click( function () {
    Share.css({ animation: "0.4s forwards POST-HIDE_SHARE" });
    InteractionButtons.css('transform', 'scale(1)');
    setTimeout( function () {
      Share.html('');
    }, 400)
  });
});

$('#Posts, .Post').on('click', '.IMG, .Blur', function () {

  var Image = $(this).closest('.Image');
  var ImageURL = Image.find('.IMG').attr('src');
  var ImageName = Image.attr('img-name');
  var ImageSize = FileSize(Image.attr('img-size'));
  var ImageHTML =
  '<img class="IMG" src="' + ImageURL + '">' +
  '<div class="Blur"></div>' +
  '<img class="BlurIMG" src="' + ImageURL + '">' +
  '<button class="UI-IV_DisableFullscrean" id="PIMG-CloseFullscrean">Свернуть</button>';
  
  var ImageView = $('.UI-ImageView');
  var IV_Interaction = $('.UI-IV_Interaction');

  ImageView.html(ImageHTML);
  IV_Interaction.find('.Info').html(ImageName + ', ' + ImageSize);
  $('#PIMG-Dwonload').attr('href', ImageURL);
  $('#PIMG-Dwonload').attr('download', ImageName);

  var IV_DF_Button = ImageView.find('.UI-IV_DisableFullscrean');
  var IV_Blur = ImageView.find('.Blur');

  Blur_Show();
  if (Device === 'Mobile') {
    BottomNav_Hide();
  }
  ImageView.css({ animation: 'IMAGE_VIEW-SHOW 0.4s forwards' });
  IV_Interaction.css({ animation: 'IMAGE_VIEW-SHOW 0.5s forwards' });

  $('#PIMG-Close, .UI-Blur').on('click', function () {
    ImageView.css({ animation: 'IMAGE_VIEW-HIDE 0.5s forwards' });
    IV_Interaction.css({ animation: 'IMAGE_VIEW-HIDE 0.5s forwards' });
    Blur_Hide();
    if (Device === 'Mobile') {
      BottomNav_Show();
    }
  })

  $('#PIMG-Fullscrean').on('click', function () {
    ImageView.css({ animation: 'IMAGE_VIEW-FULLSCREAN 0.3s forwards' });
    IV_Interaction.css({ animation: 'IMAGE_VIEW-HIDE 0.5s forwards' });
    IV_DF_Button.css({ animation: 'IMAGE_VIEW-DF_BUTTON-SHOW 0.3s forwards' });
    IV_Blur.css({ animation: 'IMAGE_VIEW-DARK_BLUR 0.3s forwards' });
    NavPanel_Hide();
    if (Device === 'Mobile') {
      BottomNav_Hide();
    }
  })
  $('#PIMG-CloseFullscrean').on('click', function () {
    ImageView.css({ animation: 'IMAGE_VIEW-DISABLE_FULLSCREAN 0.3s forwards' });
    IV_Interaction.css({ animation: 'IMAGE_VIEW-SHOW 0.5s forwards' });
    IV_DF_Button.css({ animation: 'IMAGE_VIEW-DF_BUTTON-HIDE 0.3s forwards' });
    IV_Blur.css({ animation: 'IMAGE_VIEW-WHITE_BLUR 0.3s forwards' });
    NavPanel_Show();
    if (Device === 'Mobile') {
      BottomNav_Hide();
    }
  })
})

// Показать изображение
$('#Posts, .Post').on('click', '.ShowButton', function () {
  var Blur = $(this).closest('.Censoring');
  var CensoringInfo = $(this).closest('.Censoring .Info');
  var ImageBackdropBlur = $(this).closest('.Image').find('.Blur');
  ImageBackdropBlur.removeAttr('style');
  CensoringInfo.css({ animation: 'POST-CI_HIDE 1s forwards' });
  Blur.css({ animation: 'POST-SHOW_IMAGE 1s forwards' });
});

// Управление постами
$(document).ready( function () {

  $('#Posts, .Post').on('click', '.GovernButton', function () {

    if (MyStatus === 'Admin') {
      GOVERN_BUTTONS_HTML = 
      '<button class="Button MODERATE_DELETE_POST">Удалить пост</button>' +
      '<div class="UI-PUSTOTA_H"></div>' +
      '<button class="Button MODERATE_BLOCK_USER_FROM_POST">Заблокировать</button>';
    } else {
      GOVERN_BUTTONS_HTML = 
      '<button class="Button DELETE_POST">Удалить пост</button>';
    }

    if (GoldSub === true) {
      GOVERN_BUTTONS_HTML += 
      '<div class="UI-PUSTOTA_H"></div>' +
      '<button class="Button PACK_POST">Сохранить EPACK</button>';
    }

    G_POST_CONTINER =
    '<div class="Container">' + 
      GOVERN_BUTTONS_HTML +
    '</div>';
  
    var Buttons = $(this).closest(".Post").find(".GovernButtons");
  
    if ($(this).attr("clicked") === "0") {
      Buttons.html(G_POST_CONTINER);
      Buttons.css({ animation: "0.2s forwards SH_DOTS_M" });
      $(this).attr("clicked", "1");
    } else {
      Buttons.css({ animation: "0.2s forwards HD_DOTS_M" });
      $(this).attr("clicked", "0");
      setTimeout( function () {
        Buttons.html('');
      }, 100);
    }
  });

  $('#Posts, .Post').on('click', '.DELETE_POST', function () {

    var Post = $(this).closest(".Post");
    var PostID = $(this).closest(".Post").data("postid");

    var Type = 'Query';
    var Title = 'Точно удалить?';
    var Content = 'Удаление нельзя будет отменить.';
    QueryWindow(Type, Title, Content);

    $('.UI-Window').on('click', '#WIN-NEXT', function () {

      HideWindow();

      PostQuery('PostInteraction.php?F=DELETE_POST', PostID).then(function (Data) {
        if (Data.Type === 'Error') {
          InfoWindow(Data);
        } else {
          DELETE_POST_ANIM(Post);
        }
      })
    })
  });

  $('#Posts, .Post').on('click', '.MODERATE_DELETE_POST', function () {

    var Post = $(this).closest(".Post");
    var PostID = $(this).closest(".Post").data("postid");

    var Type = 'Query';
    var Title = 'Точно удалить?';
    var Content = 'Удаление нельзя будет отменить.';
    QueryWindow(Type, Title, Content);

    $('.UI-Window').on('click', '#WIN-NEXT', function () {

      HideWindow();

      PostQuery('Moderate.php?F=DELETE_POST', PostID).then(function (Data) {
        if (Data.Type === 'Error') {
          InfoWindow(Data);
        } else {
          DELETE_POST_ANIM(Post);
        }
      })
    })
  });

  $('#Posts, .Post').on('click', '.MODERATE_BLOCK_USER_FROM_POST', function () {
    
    var PostID = $(this).closest('.Post').data('postid');

    PostQuery('Moderate.php?F=BLOCK_USER_FROM_POST', PostID).then(function (Data) {
      InfoWindow(Data);
    })
  });

  $('#Posts, .Post').on('click', '.PACK_POST', function () {

    var PostID = $(this).closest('.Post').data('postid');

    PostQuery('CreateEPACK.php', PostID).then(function (Data) {
      if (Data.Type === 'Verify') {
        var DownloadLink = $('<a>').attr('id', 'TMP_E_DOWNLOAD').attr('href', '/Download/' + Data.Content).attr('download', Data.Content);
        $('.Content').append(DownloadLink);
        setTimeout(function() {
          DownloadLink[0].click();
        }, 100);
        $('#TMP_E_DOWNLOAD').remove();
      } else {
        InfoWindow(Data);
      }
    })
  });

});

// Разные функции, не обращайте внимания

function DELETE_POST_ANIM (Post) {
  PostH = Post.height();
  Post.css('height', PostH + 20);
  Post.css({ animation: "1s forwards POST-DELETE_VARIANT_1" });
  setTimeout( function () {
    Post.css('height', '0px');
    Post.css('padding', '0px');
    Post.css('margin', '0px');
  }, 1200)
}

// Действия с изображением