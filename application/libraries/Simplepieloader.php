<?php

class Simplepieloader {

  private $path;
  private $cache = 'cache/';
  private $fn = 'simplepie.class.php';
  
  public function Simplepieloader() {
    $this->path = dirname(__FILE__) . '/simplepie/';
    require_once($this->path . $this->fn);
  }
  public function feed($url) {
    $feed = new SimplePie();
    $feed->set_cache_location($this->path . $this->cache);
    $feed->set_cache_duration(60*60*8);
    $feed->set_feed_url($url);
    $feed->init();  
    return $feed;
  }
}