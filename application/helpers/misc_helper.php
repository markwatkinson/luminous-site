<?php


/**
  * Returns a local (site) URL for the given news item's remote URL 
  */
function news_url($remote_url) {
  $trimmed = preg_replace('%(http://)[^/]*+/%i', '', $remote_url);
  return site_url('/news/' . $trimmed);
}

function format_feed($url, $n=5, $show_rss='/assets/img/rss.png') {
  $sp_dir = dirname(__FILE__) . '/../libraries/simplepie/';
  require_once($sp_dir . '/simplepie.class.php');
  
  $feed = new SimplePie();
  $feed->set_cache_location($sp_dir . '/cache');
  $feed->set_cache_duration(60*60*8);
  $feed->set_feed_url($url);
  $feed->init();
  $s = '<div class="news">';
  
  if ($show_rss) {
    $s .= <<<EOF
<a href='{$url}' class='rss' rel='external'>
  <img src='{$show_rss}' alt='RSS'>
  RSS
</a>
EOF;
  }
  
  $i = 0;
  foreach($feed->get_items() as $item) {
    if (++$i > $n) break;
    $desc = trim(strip_tags($item->get_description()));
    $words = preg_split('/(\S+)/', $desc, -1, PREG_SPLIT_DELIM_CAPTURE);
    // limits to n/2 words because every second element is whitespace
    $words = array_slice($words, 0, 250);
    $desc = implode('', $words);
    $date = $item->get_date('jS F Y');
    $title = (strip_tags($item->get_title()));
    $link = news_url($item->get_permalink());
    $s .= <<<EOF
<div class='header'>
  <span class='date'>{$date}</span> - <span class='title'><a href='{$link}' rel='external'>{$title}</a></span>
</div>
<div class='content'>{$desc} -- <a href='$link' rel='external'>[read more]</a></div>
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

function breadcrumb($pages, $prefix='') {  
  foreach($pages as $i=>$p) {
    list($name, $url) = $p;
    $name = str_replace('-', ' ', $name);
    $name = str_replace('_', ' ', $name);
    $name = ucwords($name);
    $pages_[] = ($i===count($p)-1)? $name : "<a href='$url'>$name</a>";
  }
  return '<div class="hierarchy">'
  . ($prefix? $prefix . ': ' : '') . join(' &gt; ', $pages_) . '</div>';
}


function pretty_date($time_or_date) {
  $d = 0;
  if (is_string($time_or_date)) $d = strtotime($time_or_date);
  elseif (is_int($time_or_date)) $d = $time_or_date;

  return date('jS F Y', $d);
  
}
