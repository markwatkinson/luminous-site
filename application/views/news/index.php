<div class='page'>

<p class='intro'>
  This is all aggregated from my <a href='http://blog.asgaard.co.uk/' rel='external'>personal blog</a>. You can read and comment directly at the source under the <a href='http://blog.asgaard.co.uk/t/luminous/news' rel='external'>luminous/news category</a>.
<a href="http://blog.asgaard.co.uk/t/luminous/news?f=rss" class="rss" rel="external" style="opacity: 0.7; ">
  <img src="/assets/img/rss.png" alt="RSS">
  RSS
</a>
</p>
<?php foreach($items as $item): ?>
  <article class='news summary'>
    <header class='header'>
      <h1> 
        <a href='<?=htmlspecialchars(news_url($item->get_permalink())) ?>'><?= htmlspecialchars($item->get_title()) ?></a>
      </h1>
      <span class='date'> <?= $item->get_date() ?> </span>
    </header>
    <div>
      <?= $item->get_description(); ?>
      <a class='more' href='<?=htmlspecialchars(news_url($item->get_permalink())) ?>'>Read more</a>      
    </div>
  </article>
<?php endforeach?>

<?= $this->pagination->create_links();   ?>
</div>