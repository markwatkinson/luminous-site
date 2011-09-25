<?php if (isset($errors) and !empty($errors)): ?>
  <div class='error'><p>Errors were encounted:
  <ul>
    <?php foreach($errors as $e): ?>
    <li>  <?= $e ?> </li>
    <?php endforeach; ?>
  </ul>
  </div>
<?php endif;?>
<div class='paste'>
  <form method='post' action='<?= site_url('demo/paste') ?>' id='input'>
    <?php if ($demo->id !== null): ?>
      <input type='hidden' name='id' value='<?=$demo->id?>'>
    <?php endif; ?>
    <table>
    <tr>
      <td><label for='submitter_'>Your name</label></td>
      <td><input id='submitter_' name='submitter' maxlength='20' type='text' 
        style='width:10em' value='<?= htmlentities($demo->submitter) ?>'> </td>
    </tr>
    <tr>
      <td><label for='description_'>Description</label> (optional)</td>
      <td><input id='description_' name='description' maxlength='50' 
        type='text' style='width:20em' 
        value='<?= htmlentities($demo->description) ?>'></td>
    </tr>
    <tr>
      <td><label for='lang_'>Language</label></td>
      <td><select id='lang_' name='language'>
    <?php foreach(luminous::scanners() as $lang=>$codes) {
      $lang = htmlentities($lang);
      echo sprintf("<option value='%s'%s>%s</option>\n",
        $lang,
        ($lang === $demo->scanner)? ' selected' : '',
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
    
    Code input is limited to <?= $this->CODE_MAX ?> bytes (<?= $this->CODE_MAX/1024?>KiB) because this
    is inexpensive web hosting and it would be nice to keep it that way.
    <!-- set this to 0/max so the javascript can pick it up on-load //-->
    <div id='textlimit'>0/<?=$this->CODE_MAX?></div>
    <textarea rows=20 cols=90 name='raw' id='code'><?=
      htmlentities(utf8_decode($demo->raw)) ?></textarea>
    <br/>

    <label for='save_'>Save</label> (your code will be visible to others*):
    <!-- this just makes things easier //-->
    <input type='hidden' name='post' value='true'>
    
    <input type='checkbox' id='save_' name='save'
      <?= ($this->input->post('post') && !$this->input->post('save'))? '' : 'checked';?>>
    
    <br/>
    <input type='submit'>
  </form>
</div>
<p> *if you accidentally submit some code which should have been kept private,
send an email to mark at asgaard co uk to request it to be removed from the
database. </p>
