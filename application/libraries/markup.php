<?php
set_time_limit(TIME_LIMIT);
require_once(dirname(__FILE__) . '/markuplite.class.php');
require_once(dirname(__FILE__) . '/simplepie/simplepie.class.php');

class Markup {

  public $linker = null;
  private $obj = null;
  
  function linker($url) {
    if (preg_match('%^.*://|^www\d*\.%i', $url)) return false;
    return array('uri' => site_url($url));
  }

  function feed_cb($matches) {
    $feed = new SimplePie();
    $feed->set_cache_location(dirname(__FILE__) . '/simplepie/cache/');
    $url = $matches[1];
    $count = isset($matches[2])? max((int)$matches[2], 0) : 5;
    $feed->set_feed_url($url);
    $feed->set_cache_duration(60*60*8);
    $feed->init();
    $s = '<div class="news">';
    $i = 0;
    foreach($feed->get_items() as $item) {
      if (++$i > $count) break;
      $desc = trim(strip_tags($item->get_description()));
      $words = preg_split('/(\S+)/', $desc, -1, PREG_SPLIT_DELIM_CAPTURE);
      // limits to n/2 words because every second element is whitespace
      $words = array_slice($words, 0, 80);
      $desc = implode('', $words);
      $date = $item->get_date('j/m/y');
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
    return $this->obj->hide($s);
  }
  function feed($str, &$obj) {
    $this->obj = $obj;
    return preg_replace_callback("/\\\\feed\s*([^\s]*)(\s+\d+)?/",
      array('Markup', 'feed_cb'), $str);
  }
  
  public function __construct() {
    $CI =& get_instance(); 
    $CI->load->helper('luminous');
    $this->linker = array($this, 'linker');
  }
  
  function format($str) {
    $m = new MarkupLite();
    $m->linkifier_cb = $this->linker;
    $m->highlight_cb = create_function('$code,$lang', 
      'return luminous::highlight($lang, $code);');
    $m->AddHandler('feed', array($this, 'feed'), 0);
    
    return $m->Format($str);
  }
}
