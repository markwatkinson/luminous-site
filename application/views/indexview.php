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
<div class='news'>
  <?php $news_rss = 'http://blog.asgaard.co.uk/t/luminous/news?f=rss' ?>
  <a href="<?= $news_rss ?>" class="rss" rel="external">
    <img src="/assets/img/rss.png" alt="RSS">
    RSS
  </a>
  <section>
    <?php 
      $feed = $this->simplepieloader->feed($news_rss);
      $feed_limit = 10;
      foreach($feed->get_items() as $i=>$item):
        if ($i >= $feed_limit) break;
      ?>
      <article class='news-<?=$i?>'>
        <div class='header'>
          <span class='date'> <?= $item->get_date('jS F Y') ?> </span>
          -
          <span class='title'>
            <a href='<?= news_url($item->get_permalink()) ?>'><?= $item->get_title() ?></a>
          </span>
        </div>
        <div class='content'> 
          <?= $item->get_description() ?>
          <p>
            <a href='<?= news_url($item->get_permalink()) ?>'>Read more</a>
          </p>
        </div>
      </article>
    <?php endforeach ?>
  </section>
</div>

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
