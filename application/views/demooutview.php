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

<div class='demo-meta-bar'>
<a href='#' id='embed'>Embed</a>
<?php if ($demo->editable): ?>
<a href='<?=site_url('demo/edit/' . $demo->id)?>'>Edit</a>
<?endif?>
</div>

<!-- the embed notification, it's neater/easier to define this in HTML than JS -->
<div id='embed-notification' style='display:none'>
  Fetch as JSON:
  <input type='text' value='<?=site_url("/demo/embed/{$demo->id}/" . $this->session->userdata('theme'))?>'>
  Fetch as JSONP:
  <input type='text' value='&lt;script type="text/javascript" src="<?=site_url("/demo/embed/{$demo->id}/" . $this->session->userdata('theme'))?>?callback=CALLBACK"&gt;&lt;/script&gt;'>
  <div style='text-align:center; margin-top: 1em;'>
    <a href='<?=site_url('/demo/embed/')?>'>What does this mean?</a>
  </div>
</div>
  
<script>
$(document).ready(function() {
  // generate embed code
  $('#embed').click(function() {
    var embed = $('#embed-notification').clone().show();
    $.jKnotify(embed, {title: 'Embed Code', passive: false});
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
  $('.demo-meta-bar').append($a);
});
</script>
<?php endif; ?>
<p>
<?= $demo->highlighted; ?>




