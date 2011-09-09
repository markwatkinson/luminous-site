(function($) {
  $(document).ready(function() {
    $('a').hover(
      function() {$(this).stop().animate({'opacity' : '0.7'});}, 
      function() {$(this).stop().animate({'opacity' : '1'});}
    );
  });
})(jQuery);
