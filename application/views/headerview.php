<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name='description' content='Luminous is a PHP syntax highlighter. It focusses on modern features like CSS and providing high quality highlighting for your website or blog'>
  <title> Luminous PHP Syntax Highlighter - <?= isset($title)? $title : htmlentities($this->pages->active_name()); ?></title>
  <script>
  var BASE_URL = '<?=base_url()?>';
  </script>
  <?= $this->scripts->html(); ?>
  <?php if ($this->input->get('luminous_debug')): ?>
    <script type='text/javascript' src='<?=assets_url('/luminous/tests/lineheight.js')?>'></script>
  <?php endif; ?>
  <link rel='stylesheet' href='<?=assets_url('/luminous/style/luminous.css')?>'>
  <link rel='stylesheet' id='luminous-theme' href='<?=
    assets_url('/luminous/style/' .  $this->session->userdata('theme'))?>'>
  <script type='text/javascript' src='<?=assets_url('/luminous/client/luminous.js')?>'></script>

  <!-- Wordpress RSS feed //-->
    <link rel="alternate" type="application/rss+xml" title="RSS"
      href="http://blog.luminous.asgaard.co.uk/feed/">
   
<?php if (strpos(base_url(), 'localhost') === false): ?>
  <!-- Google analytics -->
  <script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-22484472-1']);
    _gaq.push(['_trackPageview']);

    (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
  </script>
<?php endif;?>

  <!-- IE9 breaks with the inline block/float/border radius
  combo for some reason which makes our buttons look ugly sometimes //-->
  <!--[if IE]>
  <style type="text/css">
    .button { border-radius: 0; }
  </style>
  <![endif]-->
</head>
<body>
  <div class='menu-bar'>
    <div class='menu'>
<!-- funny identation here prevents space characters between the A tags //-->
    <?php foreach($this->pages->pages as $p): ?>
<a class='<?=$p->active? 'page-active' : ''?> menu' href='<?= site_url($p->url); ?>'>
<?= htmlentities($p->name); ?>
</a><?php endforeach; ?>
    </div>
  </div>
  <div class='content'>

    <div class='page'>
    <!--[if lt IE 8]>
      <div class='browser-warning' style='margin-top:65px'>
        You appear to be using a very old browser, which may not be capable of
        rendering everything on this site correctly.
        To fix this try using a free browser like
        <a href='http://www.mozilla.com/'>Firefox</a>, or
        <a href='http://www.google.com/chrome'>Google Chrome</a>
      </div>
    <![endif]-->    
    <!-- end header view  //-->
