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


    // theme switcher for Luminous. Display if there are any luminous elements
    // on the page.
    if ($('.luminous').length) {
      var theme_switcher = $('<a>').text('Switch theme?').click(function() {
        // First get the list of themes (w e could cache this)
        $.getJSON(BASE_URL + 'index.php/ajax/luminous/theme/list', function(data){
          var themes = {};
          for(var i=0; i<data.length; i++) {
            var theme_name = data[i];
            // let's make it look a bit neater.
            theme_name = theme_name.charAt(0).toUpperCase() + theme_name.substr(1);
            theme_name = theme_name.replace(/\.css$/, '').replace(/_/, ' ');
            themes[data[i]] = theme_name;
          }
          // secondly, get the active theme so we know which one should be the
          // default selection
          $.getJSON(BASE_URL + 'index.php/ajax/luminous/theme/current', function(current) {
            // TODO: can I make this submit on click or do I have to change
            // jKnotifyUi first?
            $.jKnotifyUi().jK.title('Change Theme')
                        .jK.selectInput('Switch to', 'theme', themes, current, true)
                        .jK.closable(true)
                        .jK.callback(function(args) {
                          var t = args.theme;
                          if (typeof themes[t] !== 'undefined') {
                            $.get(BASE_URL + 'index.php/ajax/luminous/theme/set/' + t);
                            $('#luminous-theme').attr('href', BASE_URL +
                              '/assets/luminous/style/' + t);
                          }
                          return false;
                        })
                        .jK.finish();
          });
        });
        return false;
      }).css('float', 'right');
      
      $('.page').prepend(theme_switcher);
    }
  });
})(jQuery);
