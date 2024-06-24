
function deleteImg(param) {
  $.ajax({
    type:'POST',
    url:'delete.php',
    async:false,
    dataType: "json",
    data: {img:param},
    success:function(data){ 
        if(data.result == 1){
          $('#'+data.message).remove();
        }
      }
  });
}


function deleteAd() {
  $.ajax({
    type:'POST',
    url:'deleteAd.php',
    async:false,
    data: {start:1},
    dataType: "json",
    error: function(xhr, status, error) {
      alert(xhr.responseText);
    },
    success:function(data){ 
      window.location.href = "deleteSuccess.php";
    }

  });
}



