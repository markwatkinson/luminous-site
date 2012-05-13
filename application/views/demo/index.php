<!-- Demo list view -->
<h1> Demos </h1>
<div class='center'>
<form  method='post' action='<?= $_SERVER['PHP_SELF'];?>'>
  <strong>Filter to</strong>:
  <select name='language'>
    <option value='all'>All</option>
    <?php foreach(Luminous::scanners() as $lang=>$code): ?>
    <option value='<?= luminous_encode_language($lang); ?>'
      <?= ($lang == $language)? 'selected' : ''; ?>
      ><?= htmlentities($lang); ?>
    </option>
    <?php endforeach;?>
  </select>
  <input type='submit' value='Go'>
  </form>
</div>
<p>
<?php if (!empty($demos)): ?>
  <table class='demos'>
  <tr class='header'>
  <?php
    $cols = array(
      'description'=>array('readable' => 'Description', 'sort' => 'up'),
      'submitter' => array('readable' => 'By', 'sort'=>'up'),
      'scanner' => array('readable' => 'Language', 'sort' => 'up'),
      'size' => array('readable' => 'Size', 'sort' => 'up'),
      'time' => array('readable' => 'Date', 'sort' => 'down')
      );
    $url_pattern = "demo/browse/{$language}/%s/%s/%d";
    foreach($cols as $name=>$data):?>
    <td>
      <a href='<?=
      site_url(
        sprintf($url_pattern,
                $name,    // sort field
                ($sort_by === $name)? ($sort_dir === 'up')? 'down' : 'up'
                  : $data['sort'], // sort direction
                ($sort_by === $name)? $page : 0 // page/offset
                  ))
    ?>'><?= $data['readable'] ?> </a>
   </td>
   <?php endforeach; ?>

  </tr>
  <?php foreach($demos as $d): ?>
  <tr> 
    <td> 
    <a href='<?= site_url();?>/demo/show/<?= $d['id']; ?>'><?php
      echo 
        strlen(trim($d['description']))
          ?  wordwrap(trim(htmlentities($d['description'])), 50, "<br/>", true)
          : '[No description]'; ?></a>
    </td>
    <td> <?= strlen(trim($d['submitter']))? htmlentities(trim($d['submitter'])) : '-' ?> </td>
    <td> <?= htmlentities($d['scanner']); ?> </td>
    <td> <?= $d['size']; ?> bytes </td>
    <td> <?= date('j M Y H:i:s', $d['time']); ?> </td>
  </tr>
  <?php endforeach; ?>
  </table>
<?php else: ?>
No demos for <?= htmlentities($language) ?>, be the first to
<a href='<?= site_url('/demo/paste/' . htmlentities(luminous_encode_language($language))); ?>'>
create one</a>!
<?php endif; ?>

<div class='paginator'><?= $this->pagination->create_links(); ?></div>