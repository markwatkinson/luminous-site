(function($) {
  $(document).ready(function() {
    $('a').hover(
      function() {$(this).animate({'opacity' : '0.7'});}, 
      function() {$(this).animate({'opacity' : '1'});}
    );
  });
})(jQuery);
