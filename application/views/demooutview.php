<?php if (($d = trim($demo->description))): ?>
<h1><?=htmlentities($d);?></h1>
<?php endif; ?>
<p>
<?= $demo->size ?> bytes of <?= $demo->scanner ?>
<?php if (strlen(trim($demo->submitter))) {
  echo ' by ' . htmlentities(trim($demo->submitter));
} ?>
</p>

<?php if ($demo->id !== null): // this tells us if we're at a permalink ?>
<noscript> <!-- this is already given by a widget, but only if JS is enabled //-->
  <form method='post' action='<?php echo $_SERVER['PHP_SELF'] ?>'>
  <strong> Theme </strong>
  <select name='theme' id='theme-selector'>
    <?php foreach(Luminous::themes() as $t): ?>
      <option value='<?= $t;?>'<?php if ($t === $theme) echo ' selected'; ?>>
        <?= preg_replace('/\\.css$/i', '', $t);?>
      </option>
    <?php endforeach; ?>
  </select>
  <input type='submit' value='Switch'>
  </form>
</noscript>

<div class='bar' style='text-align: center'>
<a href='#' id='embed'>Embed</a>
<?php if ($demo->editable): ?>
<a href='<?=site_url('demo/edit/' . $demo->id)?>' style='margin:0 2em'>Edit</a>
<?endif?>
</div>

<script>
$(document).ready(function() {
  // generate embed code
  $('#embed').click(function() {
    // TODO move this CSS into a stylesheet
    var jsonp = '<script src="<?=site_url("/demo/embed/{$demo->id}/" . $this->session->userdata('theme'))?>?callback=CALLBACK" type="text/javascript"><\/script>';
    var json = '<?=site_url("/demo/embed/{$demo->id}/" . $this->session->userdata('theme'))?>';
    var css = {'overflow': 'auto',
//       'font-weight' : 'bold',
      'margin' : '0 0'};
    var html = $('<div>');

    html.append($('<span>Embed using JSON</span>'));
    html.append($('<pre>').text(json).css(css));
    html.append($('<br>'));
    html.append($('<span>Embed using JSONP:</span>'));
    html.append($('<pre>').text(jsonp).css(css));
    
    html.append(
      $('<div style="text-align:center">').append(
        $('<a href="<?=site_url('/demo/embed/')?>">What does this mean?</a>').css('color', '#BFD9FF')
      )
    );
    $.jKnotify(html, {title: 'Embed Code', passive: false});
    return false;
  });
  
  // quick way to expand the Luminous widget with JS  
  if ($('.code_container').height() < parseInt($('.code_container').css('max-height')))
    return;
  var $a = $('<a href="#">Expand</a>').data('expanded', false).click(function(){
    var expanded = $(this).data('expanded');
    $('.code_container').css('max-height', expanded? '500px' : 'none');
    $(this).text(expanded? 'Expand' : 'Collapse');
    $(this).data('expanded', !expanded);
    return false;
  });
  $('.bar').append($a);
});
</script>
<?php endif; ?>
<p>
<?= $demo->highlighted; ?>




