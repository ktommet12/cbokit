window.onload = setViewHeight, firstRunPostLoad();

$(document).ready(function(){
    //hides the new post error box because it is not needed on page load
    $("#add-new-post-error").hide();
    $("#register-error").hide();
    //changes the display of characters that are left, max is 1000, will count down as the users types in the message box or when they copy/paste something
    $('#feed-post').on('input', function(){
      var numChars = 1000 - ($('#feed-post').val().length);
      $('#characters').html(numChars);
    });
    $('#post-message-btn').click(function(){
        var postTitle = $('#post-title').val();
        var postBody = $('#feed-post').val();
        var hashTag = $('#hash_tags').val();
        
        //if the user makes an attempt to post a message without selecting a hash-tag
        if(hashTag == "Select" || postTitle == "" || postBody == ""){
            $('#add-new-post-error').text("One or more required fields was left empty");
            $('#add-new-post-error').show();
        }else{
            //otherwise the post is loading into the database
            $.ajax({
                type: 'GET',
                url: 'add-new-post.php',
                data: 'title=' + postTitle + '&message=' + postBody + '&hash_tag=' + hashTag,
                success: function(msg){
                    $('#post-title').val(function(){return "";});
                    $('#feed-post').val(function(){return "";});
                    $("#add-new-post-error").hide();
                    updateFeed();
                }
            });
        }
    });//post-message-btn
});//document-ready
function updateUserList(){
    $.ajax({
       type: 'GET',
       url: 'get-logged-in-users.php',
       success: function(msg){
           console.log('Update User List');
           $('#users-inner').html(msg);
       }
    });
}
var userInterval = setInterval(updateUserList, 5000);
//when the feed page inititally loads this function will be called
function firstRunPostLoad(){
    $(document).ready(function(){
        $.ajax({
           type: 'GET',
           url: 'load-posts.php?pageNum=1',
           success: function(msg){
              $('#all-posts').html(msg);
           },
           error: function(msg){
               console.log(msg);
           }
        });
    });   
}
//checks the fields of the register page to make sure that the fields are at least filled out.
function checkRegister(){
    var firstname       = $('#firstName').val();
    var lastName        = $('#lastName').val();
    var userSelector;
    var idNum           = $('#studentId').val();
    var email           = $('#email').val();
    var username        = $('#username').val();
    var password        = $('#password').val();
    
    if(firstname == "" || lastName == ""  || idNum == "" || email == "" || username == "" || password == ""){
        return false;
    }
    else return true;
}
//this will reload the post section either every minute or when the user makes a post or when they delete a post
function updateFeed(){
    $(document).ready(function(){
        console.log('Update Feed Function called');
        $.ajax({
           type: 'GET',
           url: 'load-posts.php?pageNum=1',
           success: function(msg){
              $('#all-posts').html(msg);
           },
           error: function(msg){
               console.log(msg);
           }
        });
    });
}
function deletePost(elem){
    //getting the parent element of the post to be deleted
    var parentElement = $(elem).parent();
    
    //getting the post title for database lookup
    var postTitle = parentElement[0].children[2].innerText;
    
    //deleting post from database by title
    $.ajax({
       type: "GET",
       url: "delete-post.php?post-title="+ postTitle,
       success: function(msg){
           console.log("post successfully deleted");
           updateFeed();
       },
       error: function(msg){
           console.log(msg);
       }
    });
    
}
//reloads the feed every minute
var interval = setInterval(updateFeed, 60000);

//returns the height of entity which can be anything from the window to a element like a div
function getViewPortHeight(entity){
    var clientViewHeight = $(entity).height();
    return clientViewHeight;
}
function setViewHeight(){
    var navHeight = getViewPortHeight($('#bs-example-navbar-collapse-1'));
    var footerHeight = getViewPortHeight($('#footer'));
    var windowHeight = getViewPortHeight(window);
    //calculating the height of the window - (nav + footer) so that the whole feed section fits in the current window size
    var feedHeight = windowHeight - (navHeight + footerHeight);
    var messenger_userList_Height = Math.floor(feedHeight / 2);

    //setting the height of the feed/user-list/messenger section to be the height of the current window size
    //document.getElementsByClassName('container-fluid')[0].style.height = feedHeight + "px";
    document.getElementsByClassName('discussion-posts')[0].style.height = feedHeight + "px";
    document.getElementById('messenger').style.height = (messenger_userList_Height-34) + "px";
    document.getElementById('user-list').style.height = messenger_userList_Height + "px";
}