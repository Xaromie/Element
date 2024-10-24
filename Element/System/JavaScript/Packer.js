
function PACK_Post(PostID) {

    var Post = $('div[data-postid="' + PostID + '"]');
    var AvatarURL = Post.find('.Avatar img').attr('src');

    Name = Post.find('.TopBar .Name').clone();
    Name.find('.UserIcons').remove();
    var Name = Name.html();
    Username = Post.find('.Info a').attr('href');
    var Username = decodeURIComponent(Username).match(/\/profile\/([^/]+)/);
    var Text = Post.find('.Text').html();
    var LikesCount = Post.find('.Likes').html();
    var DislikesCount = Post.find('.Dislikes').html();
    var CommentsCount = Post.find('.Comments').html();

    if (AvatarURL) {
        ConvertToBase64(AvatarURL, function (Base64) {
            localStorage.setItem('AvatarB64', Base64);
        })
    }

    if (Post.find('.Image').length > 0) {
        ImageURL = Post.find('.IMG').attr('src');
        ConvertToBase64(ImageURL, function (Base64) {
            localStorage.setItem('ImageB64', Base64);
        })
    }

    setTimeout(function () {
        var AvatarB64 = localStorage.getItem('AvatarB64') || null;

        if (localStorage.getItem('ImageB64')) {
            var ImageB64 = localStorage.getItem('ImageB64') || null;
            var ImageName = Post.find('.Image').attr('img-name');
            var Size = Post.find('.Image').attr('img-size');
            var Content = {
                Type: 'Image',
                Image: ImageB64,
                Name: ImageName,
                Size: Size
            }
        }
    
        var EPACK = {
            E_VER: '1.1.3',
            E_TYPE: 'Post',
            Name: Name,
            Username: Username[1],
            Avatar: AvatarB64,
            PostID: PostID,
            Text: Text,
            Content: Content || null,
            LikesCount: LikesCount,
            DislikesCount: DislikesCount,
            CommentsCount: CommentsCount
        };
    
        EPACK_NAME = 'Пост ID' + PostID + '.epack';
        DownloadEPACK(EPACK, EPACK_NAME);
        localStorage.removeItem('AvatarB64');
        localStorage.removeItem('ImageB64');
    }, 1000)
}

function ConvertToBase64(URL, Return) {
    var XHR = new XMLHttpRequest();
    XHR.open('GET', URL);
    XHR.responseType = 'blob';
    XHR.onload = function() {
        if (XHR.status === 200) {
            var Image = XHR.response;
            var Render = new FileReader();
            Render.onload = function() {
                var Base64 = Render.result.split(',')[1];
                Return(Base64);
            };
            Render.readAsDataURL(Image);
        }
    };
    XHR.send();
}

function DownloadEPACK(EPACK, EPACK_NAME) {
    var Json = JSON.stringify(EPACK);
    var File = new Blob([Json], { type: 'application/json' });
    var URL = window.URL.createObjectURL(File);
    var DownloadLink = $('<a>').attr('id', 'TMP_E_DOWNLOAD').attr('href', URL).attr('download', EPACK_NAME);
    $('.Content').append(DownloadLink);
    DownloadLink[0].click();
    window.URL.revokeObjectURL(URL);
    $('#TMP_E_DOWNLOAD').remove();
}