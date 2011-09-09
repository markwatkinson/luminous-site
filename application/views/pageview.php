<?php
if (isset($page_hierarchy) && count($page_hierarchy) > 1) {
  $hierarchy_texts = array();
  foreach($page_hierarchy as $i=>$p) {
    list($name, $url) = $p;
    $name = ucwords($name);
    if ($i < count($page_hierarchy)-1) {
      $name = character_limiter($name, 15);
      $hierarchy_texts[] = "<a href='" . site_url($url) . "'>$name</a>";
    } else {
      $hierarchy_texts[] = $name;
    }
  } ?>
  <div class='hierarchy'> <?=$this->pages->active_name()?>: <?= join($hierarchy_texts, ' &gt; ') ?> </div>
  <?php
}
?>
<?php echo $content; ?>

