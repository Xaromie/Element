$(document).ready( function () {

    $('#EPACK_Input').change( function () {
        var Input = $(this)[0].files[0];
        if (Input) {
            var Render = new FileReader;
            Render.onload = function(Event) {
                var FileContent = Event.target.result;
                var Data = JSON.parse(FileContent);
                HandlePost(Data);
            }
            Render.readAsText(Input);
        }
    });
});

function HandlePost(PostData) {

    var Post = PostData;
    var PostHTML = '';
    var ImageHTML = '';

    if (Post.Content && Post.Content.Type === 'Image') {
        var Image = 'data:image/jpeg;base64,' + Post.Content.Image;
        ImageHTML = '<div class="Image" img-name="' + Post.Content.Name + '" img-size="' + Post.Content.Size + '">' +
        '<img class="IMG" src="' + Image + '">' +
        '<div class="Blur"></div>' +
        '<img class="BlurIMG" src="' + Image + '">' +
        '</div>';
    }
  
    PostHTML +=
        '<div class="EPACK-Post UI-Block"">' +
        '<div class="TopBar">' +
        '<a href="/profile/' + Post.Username + '"><div class="Avatar"><img src="data:image/jpeg;base64,' + Post.Avatar + '"></div></a>' +
        '<div class="N_A_D">' +
        '<div class="Name"></div>' +
        '</div>' +
        '</div>' + 
        '<div class="Text"></div>' + ImageHTML +
        '<div class="Interaction">' +
        '<div class="InteractionCount"><i class="icon-Like"></i><div class="Likes"></div></div>' +
        '<div class="InteractionCount"><i class="icon-Dislike"></i><div class="Dislikes"></div></div>' +
        '</div>' +
        '</div>';
    
    $('#EPACK_RESULT').html(PostHTML);
    
    var EPACK_RESULT = $('#EPACK_RESULT');
    EPACK_RESULT.find('.Name').text(Post.Name);
    EPACK_RESULT.find('.Text').text(Post.Text);
    EPACK_RESULT.find('.Likes').text(Post.LikesCount);
    EPACK_RESULT.find('.Dislikes').text(Post.DislikesCount);
}