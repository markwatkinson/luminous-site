<?php foreach($items as $item): ?>
  <article class='news summary'>
    <header>
      <h1> 
        <a href='<?=htmlspecialchars(news_url($item->get_permalink())) ?>'><?= htmlspecialchars($item->get_title()) ?></a>
      </h1>
      <span class='date'> <?= $item->get_date() ?> </span>
    </header>
    <div>
      <?= $item->get_description(); ?>
    </div>
  </article>
<?php endforeach?>