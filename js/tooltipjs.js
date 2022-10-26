$(document).ready(function(){
  $('.tooltip').hover(function () {
  	var currentTitle = $(this).attr('title');
  	var displayClass = $(this).attr('displayclass');
  	$(this).attr('title', '');
  	$(this).append('<p class="' + displayClass + '">' + currentTitle + '</p>')
  },function () {
  	var currentTitle = $('p', this).html();
  	$(this).attr('title', currentTitle);
  	$('p', this).remove();
  });		
});