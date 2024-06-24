$( document ).ready(function() {
  $(".chat-container").scrollTop($(".chat-container")[0].scrollHeight);
})


$("form#data").submit(function(e){
  e.preventDefault();
  var formData = new FormData(this);

  $.ajax({
    url: "chatajax.php",
    type: 'POST',
    data: formData,
    async: false,
    success: function (data) {
          $('.chat-input').val('');
          $('.chat-container').append("<div class='message-container my-message-container'><div class='message-date my-message-date'>"+ data.date + "</div><div class='message-text my-message'>"+ data.text + "</div></div>");
          $(".chat-container").scrollTop($(".chat-container")[0].scrollHeight);

    },
    contentType: false,
    processData: false,
    dataType: "json",
    
});

})

function Refresh() {
  $.ajax({
    url: "refreshajax.php",
    async: false,
    error: function(xhr, status, error) {
      alert(xhr.responseText);
    },
    success: function (data) {
          for (let i = 0; i< data.length; i++) {
            $(".chat-container").append("<div class='message-container partner-message-container'><div class='message-date partner-message-date'>"+ data[i].date + "</div><div class='message-text partner-message'>"+ data[i].text + "</div></div>");
            $(".chat-container").scrollTop($(".chat-container")[0].scrollHeight);
          }

    },
    contentType: false,
    processData: false,
    dataType: "json",
    
});

}

setInterval(Refresh, 5000);

