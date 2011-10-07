<?php


function format_feed($url, $n=5) {
  $sp_dir = dirname(__FILE__) . '/../libraries/simplepie/';
  require_once($sp_dir . '/simplepie.class.php');
  
  $feed = new SimplePie();
  $feed->set_cache_location($sp_dir . '/cache');
  $feed->set_cache_duration(60*60*8);
  $feed->set_feed_url($url);
  $feed->init();
  $s = '<div class="news">';
  $i = 0;
  foreach($feed->get_items() as $item) {
    if (++$i > $n) break;
    $desc = trim(strip_tags($item->get_description()));
    $words = preg_split('/(\S+)/', $desc, -1, PREG_SPLIT_DELIM_CAPTURE);
    // limits to n/2 words because every second element is whitespace
    $words = array_slice($words, 0, 80);
    $desc = implode('', $words);
    $date = $item->get_date('jS M Y');
    $title = (strip_tags($item->get_title()));
    $link = $item->get_permalink();
    $s .= <<<EOF
<div class='header'>
  <span class='date'>{$date}</span> - <span class='title'>{$title}</span>
</div>
<div class='content'>{$desc} -- <a href='$link'>[read more]</a></div>
EOF;
    }
  $s .= '</div>';
  return $s;
}



function button($text, $href, $class='', $image='', $alt='') {
  $button = <<<EOF
<a href='$href' class='button $class'>
    <span> %s $text </span>
</a>

EOF;
  $button = sprintf($button,
    $image? "<img src='$image' alt='$alt'>" : ''
  );
  return $button;
}

function title_case($text) {
  $text = str_replace('_', ' ', $text);
  $text = str_replace('-', ' ', $text);
  $text = ucwords($text);
  return $text;
}
