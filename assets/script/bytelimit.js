$(document).ready(function() {

  $byte_counter = $('#textlimit');
  if ($byte_counter.length) {
    var limit = parseInt($byte_counter.html().replace(/.*\//, ''));
    var changefunc = function() {
      var chars = $(this).val().length;
      $byte_counter.text('' + chars + '/' + limit + ' chars (' + Math.floor(chars/limit * 100) + '%)')
        .css('background-color', (chars <= limit)? '#A7C7F7' : '#FF8080');
      };
    $('#code').change(changefunc).keyup(changefunc).trigger('change');
    $('#input').submit(function() {
      
      var n = 0;
      if ( (n = $('#code').val().length - limit) > 0) {
        $.jKnotify('Your code is ' + n + ' characters too long!');
        return false;
      }
      return true;
    });
  }
});