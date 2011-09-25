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
  <script type='text/javascript'>
  $(document).ready(function() {
    $('#theme-selector').change(function() {
      var $el = $('#luminous-theme');
      var href = $el.attr('href');
      href = href.replace(/[^\/]+\.css$/, $(this).val());
      $el.attr('href', href);
      $.post('<?=site_url('demo')?>', {theme: $(this).val()});
    });
  });
</script>

<div class='bar' style='text-align: center'> 
<a href='<?=site_url('demo/edit/' . $demo->id)?>' style='margin:0 2em'>Edit</a>
</div>

<script>
// quick way to expand the Luminous widget with JS
$(document).ready(function() {
  if ($('.code_container').height() < parseInt($('.code_container').css('max-height')))
    return;
  var $a = $('<a href="#">Expand</a>').data('expanded', false).click(function(){
    var expanded = $(this).data('expanded');
    $('.code_container').css('max-height', expanded? '500px' : 'none');
    $(this).text(expanded? 'Expand' : 'Collapse');
    $(this).data('expanded', !expanded);
  });
  $('.bar').append($a);
});
</script>
<?php endif; ?>
<p>
<?= $demo->highlighted; ?>




