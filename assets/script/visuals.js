(function($) {
  
  $(document).ready(function() {

    // theme switcher for Luminous. Display if there are any luminous elements
    // on the page.
    if ($('.luminous').length) {
      var fetched = false, themes = {};
      var theme_switcher = $('<a>')
        .attr('href', '#')
        .text('Switch theme?').click(function(ev) {
          var current = $('#luminous-theme').attr('href').replace(/.*\//, '');
          var show_switcher = function() {
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
          };
          if (fetched) {
            show_switcher();
          } else {
            $.getJSON(BASE_URL + 'index.php/ajax/luminous/theme/list', function(data){
              for(var i=0; i<data.length; i++) {
                var theme_name = data[i];
                // let's make it look a bit neater.
                theme_name = theme_name.charAt(0).toUpperCase() + theme_name.substr(1);
                theme_name = theme_name.replace(/\.css$/, '').replace(/_/, ' ');
                themes[data[i]] = theme_name;
              }
              fetched = true;
              show_switcher();
            });
          }
          ev.preventDefault();
          return true;
        }).css('float', 'left');
      $('.menu-bar').prepend(theme_switcher);
    }
/*
    // fade effect when mousing over <a> elements
    $('.page').css('display', 'none').fadeIn('fast');
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
*/


  });
})(jQuery);
