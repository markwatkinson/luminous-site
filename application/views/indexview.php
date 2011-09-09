<h1>Luminous - A PHP Syntax Highlighter</h1>

<div class='about'>
Luminous is a <strong>syntax highlighter</strong> written in <strong>PHP</strong>.<br/>
Its main focus is on accuracy and quality of code highlighting, and supports modern features like CSS colour themes. Luminous can highlight around 30 source code languages, and even includes a built-in cache so you don't have to worry about your server load.
</div>
<p>
<?php

if (!empty($releases)) {
  $newest = $releases[0];
  $date = $newest['release_date'];
  $version = $newest['release_number'];
  $url = site_url("download/get/luminous-v$version.zip");
  ?>

<div class='download-badge'>
  <a href='<?=$url?>'>
  <img src='<?=assets_url('img/download.png')?>' alt='download'>
    Download Luminous
    <br>
    v<?= $version ?> (zip)
  </a>
</div>
<?php
}
?>

<h2>News</h2>
<?= format_feed('http://blog.luminous.asgaard.co.uk/feed/', 2); ?>

<h2>Example/Demo</h2>
<?= luminous::highlight('php', 
"<?php require_once('luminous/luminous.php'); ?>
<!DOCTYPE html>
<html>
  <head>
    <?= luminous::head_html(); // outputs stylesheet includes ?>
  </head>
  <body>
    <?= luminous::highlight('php', 'echo \"hello world\";'); ?>
  </body>
</html>"); ?>
