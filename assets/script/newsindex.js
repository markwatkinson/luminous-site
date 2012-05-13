(function ($) {
  // interactive news on the index page
  // Basically, the first column shows the currently
  // selected story and the second column shows an archive list.
  // Clicking a story in the archive changes the first col's contents.
  // We implement this with a slider effect, so news stories slide
  // left/right as they are selected.

  $(document).ready(function () {
    var $contentArea = $('.news .col-1').eq(0),
      $selectors = $('.news .col-2 > article'),
      activeClass = 'active',
      animLength = 500,
      // slider container
      $scroller = $('<div class="scroller">'),
      $clicker = $('<span class="expander">&nbsp;</span>'),
      width = 0,
      origHeight = 0,
      selectorClickFunc = function (ev) {
        var $this = $(this);
        
        if (!$this.hasClass(activeClass) && ev.which === 1) {         
          $scroller.animate({'left': -1*$this.data('scrollTarget').position().left + 'px'},
            animLength);
          $contentArea.animate({'height': origHeight + $this.data('scrollTarget').outerHeight(true)},
            animLength/2);
          $selectors
            .filter('.' + activeClass)
            .removeClass(activeClass)
          
          $this.addClass(activeClass);
          ev.preventDefault();
          return true;
        }
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
      $clone.width( $contentArea.width() );
      
      width += $clone.outerWidth(true);
      $this.data('scrollTarget', $clone);
    });
    $scroller.width(width);
    $selectors.click(selectorClickFunc);    
    $selectors.eq(0).removeClass('active').trigger($.Event('click', {which: 1}));
    
  });
}(jQuery));