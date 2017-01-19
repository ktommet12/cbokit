window.onload = loadConversation('load-conversation.php?initial-load=1');

var messengerInterval = setInterval(reloadConversation, 5000);

function loadConversation(url){
  $(document).ready(function(){
   $.ajax({
       type: "GET",
       url: url,
       success: function(msg){
           $('#messages').html(msg);
           var messageDiv = $('#message-Section');
           var height = messageDiv[0].scrollHeight;
           messageDiv.scrollTop(height);
       },
       error: function(msg){
           console.log(msg);
       }
   }); 
});  
}
function reloadConversation(){
    loadConversation('load-conversation.php?reload=1');
}
function changeConversation(elem){
    var username = $(elem).val();
    console.log(username);
    
    $.ajax({
       type: 'GET',
       url: 'load-conversation.php',
       data: 'user_2_username=' + username + "&newConversation=1",
       success: function(msg){
           $('#messages').html(msg);
           $('#messages').scrollTop(500);
       }
    });
}

function postImMessage(){
    //gets the contents of the IM Message box.
    var message = $('#messageBox').val();
    //clears the contents of the IM message box
    $('#messageBox').val(function(){return "";});
    $.ajax({
        type: "GET",
        url: 'post-message.php?message=' + message,
        success: function(){
            //reloadConversation();
        }
    });
}