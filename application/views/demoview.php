<?php if ($error !== null): ?>
<div class='error'>Error: <?=$error?></div>
<?php endif;?>
<div class='paste'>
  <form method='post' action='<?= $_SERVER['PHP_SELF'] ?>' id='input'>
    <table>
    <tr>
      <td><label for='submitter_'>Your name</label></td>
      <td><input id='submitter_' name='submitter' maxlength='20' type='text' 
        style='width:10em' value='<?= htmlentities($submitter) ?>'> </td>
    </tr>
    <tr>
      <td><label for='description_'>Description</label> (optional)</td>
      <td><input id='description_' name='description' maxlength='50' 
        type='text' style='width:20em' 
        value='<?= htmlentities($description) ?>'></td>
    </tr>
    <tr>
      <td><label for='lang_'>Language</label></td>
      <td><select id='lang_' name='lang'>
    <?php foreach(luminous::scanners() as $lang=>$codes) {
      $lang = htmlentities($lang);
      echo sprintf("<option value='%s'%s>%s</option>\n",
        $lang,
        ($lang === $language)? ' selected' : '',
        $lang);
      } ?>
    </select> </td>
    <tr><td></td><td> 
      (Note: 'PHP' requires &lt;? tags, 'PHP Snippet' does not)</td>
    </tr>
    <tr>
      <td><label for='theme_'>Theme</label></td>
      <td><select name='theme' id='theme_'>
      <?php foreach(Luminous::themes() as $t): ?>
      <option value='<?= $t;?>'<?= ($t === $theme)? ' selected' : '' ?>><?=
        preg_replace('/\\.css$/i', '', $t);?></option>
      <?php endforeach; ?>
      </select> </td>
    </tr>
    </table>
    
    Code input is limited to <?= $max ?> bytes (<?= $max/1024?>KiB) because this
    is inexpensive web hosting and it would be nice to keep it that way.
    <!-- set this to 0/max so the javascript can pick it up on-load //-->
    <div id='textlimit'>0/<?=$max?></div>
    <textarea rows=15 cols=75 name='code' id='code'><?=
      htmlentities(utf8_decode($code)) ?></textarea>
    <br/>

    <label for='save_'>Save</label> (your code will be visible to others*):
    <input type='checkbox' id='save_' name='save' <?= $save? 'checked' : '';?>>
    
    <br/>    
    <input type='submit'>
  </form>
</div>
<p> *if you accidentally submit some code which should have been kept private,
send an email to mark at asgaard co uk to request it to be removed from the
database. </p>
