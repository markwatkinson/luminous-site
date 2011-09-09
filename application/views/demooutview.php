<?php if ($error !== null): ?>
<div class='error'>Error: <?=$error?></div>
<?php endif;?>

<?php if ($save): ?>
<div class='notification'><strong>Your code has been saved</strong></div>
<?php endif; ?>

<?php if (($d = trim($description))): ?>
<h1><?=htmlentities($d);?></h1>
<?php endif; ?>
<p>
<?= $size ?> bytes of <?= $scanner ?>
<?php if (strlen(trim($submitter))) {
  echo ' by ' . htmlentities(trim($submitter));
} ?>
</p>

<?php if ($ownership): ?>
Your browser session has ownership of this demo, so you may change the language
in case you it is wrong. This will only be allowed temporarily.
<form method='post' action='<?php echo site_url('/demo/show/' . $id) ?>'>
  <label for='lang_'>Language</label>
  <select id='lang_' name='lang'>
  <?php foreach(luminous::scanners() as $lang=>$codes) {
      $lang = htmlentities($lang);
      echo sprintf("<option value='%s'%s>%s</option>\n",
        $lang,
        ($lang === $scanner)? ' selected' : '',
        $lang);
    } ?>
  </select>
  <input type='hidden' name='change_language' value='true'>
  <input type='hidden' name='id' value='<?=htmlentities($id)?>'>
  <input type='submit' value='Switch'>
</form>
<?php endif;?>

<?php if ($themeable): ?>
<form method='post' action='<?php echo $_SERVER['PHP_SELF'] ?>'> 
<strong> Theme </strong>
<select name='theme' id='theme-selector'> 
  <?php foreach(Luminous::themes() as $t): ?>
    <option value='<?php echo $t;?>'<?php if ($t === $theme) echo ' selected'; ?>><?php echo preg_replace('/\\.css$/i', '', $t);?></option>
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

<p>
<?php endif; ?>

<?php echo $output; ?>
