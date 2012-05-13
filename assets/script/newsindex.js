(function ($) {
  // interactive news on the index page
  // Basically, the first column shows the currently
  // selected story and the second column shows an archive list.
  // Clicking a story in the archive changes the first col's contents.
  // We implement this with a slider effect, so news stories slide
  // left/right as they are selected 
  // (we unload the actual effect to CSS3)

  $(document).ready(function () {
    var $contentArea = $('.news .col-1').eq(0),
      $selectors = $('.news .col-2 > article'),
      $active = $selectors.eq(0),
      $articles,
      activeClass = 'active',
      // slider container
      $scroller = $('<div class="scroller">'),
      $clicker = $('<span class="expander">&nbsp;</span>'),
      origHeight = 0,
      selectorClickFunc = function (ev) {
        var $this = $(this);
        
        if (ev.which === 1) {
          $active = $this.data('scrollTarget');
          $scroller.css('left', -1 * $active.position().left + 'px');
          $contentArea.css('height', origHeight + $active.outerHeight(true));
          $selectors
            .filter('.' + activeClass)
            .removeClass(activeClass)
          
          $this.addClass(activeClass);
          ev.preventDefault();
          return true;
        }
      },
      // since we're setting widths, we need to handle resizes.
      // performance seems okay to just do it directly on
      // the resize event.
      resize = function() {      
        var totalWidth = 0, width = $contentArea.width();
        $articles.each(function() {        
          var $this = $(this);
          $this.width(width);
          totalWidth += $this.outerWidth(true);
        });
        $scroller.width(totalWidth);
        $scroller.css('left', -1 * $active.position().left + 'px');
        $contentArea.css('height', $active.outerHeight(true) + origHeight + 'px');
      };
    
    $('body').addClass('js');
    
    // remove whatever's in the content area and repopulate it
    // with all the data (so we can slide them).
    $contentArea.find('article').remove();
    origHeight = $contentArea.height();
    $contentArea.append($scroller);
    

    $selectors.each(function() {
      var $this = $(this), $clone = $this.clone();
      $this.find('.header').append($clicker.clone());
      $scroller.append($clone);      
      $this.data('scrollTarget', $clone);
    });
    $articles = $scroller.children();
    
    $selectors.click(selectorClickFunc);    
    
    resize();
    $(window).resize(resize);
    $selectors.eq(0).trigger($.Event('click', {which: 1}));
    
  });
}(jQuery));