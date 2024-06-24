
$("form#data").submit(function(e){
  e.preventDefault();
  var formData = new FormData(this);

  $.ajax({
    url: "editajax.php",
    type: 'POST',
    data: formData,
    async: false,
    error: function(xhr, status, error) {
      alert(xhr.responseText);
    },
    success: function (data) {
        if(data.result == 1){
          window.location.href = "ad page.php?ad_id=" + data.ad_id;

        }
        else {
          $('.ajaxerrors').css('display', 'block');
          $('.ajaxerrors').empty(); 
          for (let i = 0; i< data.errors.length; i++) {
            $('#errors').append("<p class='error'>"+ data.errors[i] + "</p>");
          }
        }
    },
    contentType: false,
    processData: false,
    dataType: "json",
});



})