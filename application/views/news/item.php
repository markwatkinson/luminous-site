<div class='page'>
  <div class='hierarchy'>
    <a href='<?=site_url('news')?>'>News</a>
    &gt;
    <?= htmlspecialchars($item->get_title()) ?>
  </div>
  <article class='news-item'>
    <header>
      <h1> <?= htmlspecialchars($item->get_title()) ?> </h1>
      <span class='date'> <?= $item->get_date() ?> </span>
    </header>
    <div>
      <?= $item->get_content(); ?>
    </div>
    
  </article>
</div>