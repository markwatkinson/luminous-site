(function ($) {
  // interactive news on the index page
  
  $(document).ready(function () {
    var $contentArea = $('.news .col-1').eq(0),
      $selectors = $('.news .col-2 > article'),
      activeClass = 'active',
      animLength = 200,
      selectorClickFunc = function (ev) {
        var $this = $(this);
                
        $contentArea.find('article').fadeOut(animLength, function () {
          var $el = $this.clone(false)
            .attr('style', '')
            .hide();
          $(this).replaceWith($el);
          $el.fadeIn(animLength);
         });

        $selectors
          .filter('.' + activeClass)
          .removeClass(activeClass)

        $this.addClass(activeClass);
        ev.preventDefault();
        return true;
        
      };
    $selectors.click(selectorClickFunc);
    $selectors.addClass('js');
    $selectors.eq(0).click();
    
  });
}(jQuery));