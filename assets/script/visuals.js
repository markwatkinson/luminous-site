(function($) {
  $(document).ready(function() {
    $('a:not(.button)').hover(
      function() {$(this).stop().animate({'opacity' : '0.7'});}, 
      function() {$(this).stop().animate({'opacity' : '1'});}
    );

    if (!$.browser.msie) {
      var default_='0.7', target='1', speed='fast';
      $('a.button img').css('opacity', default_);
      $('a.button').hover(
        function() {$('img', this).stop().animate({'opacity': target}, speed)},
        function() {$('img', this).stop().animate({'opacity': default_}), speed}
      );
    }
  });
})(jQuery);
