$(document).ready(function() {
  $('#nav li').click(function() {
    location.href = $(event.target).attr('data-href');
  });
})