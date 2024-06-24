$(window).resize(function() {

  if ($(window).width() < 810 ) {
    emailLen = $('.email-string').text().length;
    nameLen = $('.name-string').text().length;
    if (emailLen>25){
    $('.email-line').css('flex-direction', 'column');
    }
    if (nameLen>25){
      $('.name-line').css('flex-direction', 'column');
      }
  }
 else {
  $('.email-line').css('flex-direction', 'row');
  $('.name-line').css('flex-direction', 'row');
 }

});

$( document ).ready(function() {
  if ($(window).width() < 810 ) {
    emailLen = $('.email-string').text().length;
    nameLen = $('.name-string').text().length;
    if (emailLen>25){
    $('.email-line').css('flex-direction', 'column');
    }
    if (nameLen>25){
      $('.name-line').css('flex-direction', 'column');
      }
  }
 else {
  $('.email-line').css('flex-direction', 'row');
  $('.name-line').css('flex-direction', 'row');
 }
 var navigated = $.cookie("navigated");
 if(navigated == 1 ){
  document.getElementById("scroll-target").scrollIntoView();
  $.cookie("navigated", "0", { path: '/', expires: 10 });
 }
 if(navigated == 2 ){
  document.getElementById("scroll-target2").scrollIntoView();
  $.cookie("navigated", "0", { path: '/', expires: 10 });
 }
 if(navigated == 3 ){
  $.cookie("navigated", "0", { path: '/', expires: 10 });
  document.getElementById("scroll-target3").scrollIntoView();
 }

});

function navigated(arg){
  $.cookie("navigated", arg, { path: '/', expires: 10 });

}