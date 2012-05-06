<?php

if (!empty($releases)) {
  $newest = $releases[0];
  $date = $newest['release_date'];
  $version = $newest['release_number'];
  $url = site_url("download/get/luminous-v$version.zip");
  ?>
<h1>Luminous - A PHP Syntax Highlighter</h1>
<div class='latest-release'>Latest release: <?=pretty_date($date)?> - v<?=$version?></div>

<div class='about'>
<p>Luminous is a <strong>PHP syntax highlighter</strong>.
<p>
Its main focus is on accuracy and quality of code highlighting, and modern features like CSS colour themes. Luminous is a generic highlighter which can highlight around 30 source code languages, and includes a built-in cache so you don't have to worry about your server load. If you want professional, high quality PHP syntax highlighting for your website or blog, give it a try.

<p>
You can try it out here as an online syntax highlighter, and download if it you want to deploy it on your own site.
</div>
<p>

<p>

<div class='index-button-holder'>

  <?= button('Download', $url, 'index-button', assets_url('img/download.png'),
    'Download version ' . $version); ?>
  <?= button('Get Started', site_url('/docs/show/index'), 'index-button alt',
    assets_url('img/system-help32.png'), 'Get Started') ?>
</div>
<script>
// IE doesn't render the '&#8658' right arrow properly so I am adding it
// here with a check
// thanks jQuery for deprecating $.browser
if (navigator.appName != 'Microsoft Internet Explorer') {
  $(document).ready(function() {
    $('.button span').each(function() {
      $(this).html( $(this).html() + '&#8658;' );
    });
  });
}
</script>
<?php
}
?>

<h2><a href='<?=site_url('news')?>'>News</a></h2>
<?= format_feed('http://blog.asgaard.co.uk/t/luminous/news?f=rss', 4); ?>

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
